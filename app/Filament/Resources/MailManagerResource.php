<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MailManagerResource\Pages;
use App\Filament\Resources\MailManagerResource\RelationManagers;
use App\Models\ContactSubmission;
use App\Models\BlockedIp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormReply;
use Illuminate\Support\Str;

class MailManagerResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Mail Manager';

    protected static ?string $modelLabel = 'Message';

    protected static ?string $pluralModelLabel = 'Messages';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Message Details')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('From Name')
                                    ->disabled(),
                                Forms\Components\TextInput::make('email')
                                    ->label('From Email')
                                    ->disabled(),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->disabled(),
                                Forms\Components\TextInput::make('subject')
                                    ->label('Subject')
                                    ->disabled(),
                            ]),
                        Forms\Components\TextInput::make('created_at')
                            ->label('Received At')
                            ->disabled()
                            ->formatStateUsing(function ($state) {
                                if (!$state) return '';
                                if (is_string($state)) return $state;
                                return $state->format('M d, Y \a\t g:i A');
                            }),
                    ]),

                Forms\Components\Section::make('Message Content')
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->label('Original Message')
                            ->disabled()
                            ->rows(8)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Message Status')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('status')
                                    ->label('Status')
                                    ->disabled()
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'new' => 'New (Unread)',
                                        'read' => 'Read',
                                        'in_progress' => 'In Progress',
                                        'responded' => 'Responded',
                                        'archived' => 'Archived',
                                        default => ucfirst($state),
                                    }),
                                Forms\Components\TextInput::make('responded_at')
                                    ->label('Response Date')
                                    ->disabled()
                                    ->formatStateUsing(function ($state) {
                                        if (!$state) return 'Not responded';
                                        if (is_string($state)) return $state;
                                        return $state->format('M d, Y \a\t g:i A');
                                    })
                                    ->visible(fn ($record) => $record && $record->responded_at),
                                Forms\Components\TextInput::make('respondedBy.name')
                                    ->label('Responded By')
                                    ->disabled()
                                    ->visible(fn ($record) => $record && $record->responded_by),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('status')
                    ->label('')
                    ->icon(fn (string $state): string => match ($state) {
                        'new' => 'heroicon-s-envelope',
                        'read' => 'heroicon-o-envelope-open',
                        'in_progress' => 'heroicon-s-clock',
                        'responded' => 'heroicon-s-check-circle',
                        'archived' => 'heroicon-s-archive-box',
                        default => 'heroicon-o-envelope',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'primary',
                        'read' => 'gray',
                        'in_progress' => 'warning',
                        'responded' => 'success',
                        'archived' => 'gray',
                        default => 'gray',
                    })
                    ->size('sm'),

                Tables\Columns\TextColumn::make('name')
                    ->label('From')
                    ->searchable()
                    ->sortable()
                    ->weight(fn ($record) => $record->status === 'new' ? FontWeight::Bold : FontWeight::Medium)
                    ->formatStateUsing(fn ($record) => $record->name . ' ' . $record->email . ' '),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->weight(fn ($record) => $record->status === 'new' ? FontWeight::Bold : FontWeight::Medium)
                    ->description(fn ($record) => Str::limit($record->message, 80)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime('M d, H:i')
                    ->sortable()
                    ->since()
                    ->tooltip(function ($record) {
                        if (!$record->created_at) return '';
                        if (is_string($record->created_at)) return $record->created_at;
                        return $record->created_at->format('F j, Y \a\t g:i A');
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'new',
                        'gray' => 'read',
                        'warning' => 'in_progress',
                        'success' => 'responded',
                        'secondary' => 'archived',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => 'Unread',
                        'read' => 'Read',
                        'in_progress' => 'In Progress',
                        'responded' => 'Replied',
                        'archived' => 'Archived',
                        default => ucfirst($state),
                    }),

                Tables\Columns\IconColumn::make('is_spam')
                    ->label('Spam')
                    ->boolean()
                    ->trueIcon('heroicon-s-exclamation-triangle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->tooltip(fn ($record) => $record->is_spam ? $record->spam_reason : 'Legitimate'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'in_progress' => 'In Progress',
                        'responded' => 'Responded',
                        'archived' => 'Archived',
                    ]),
                SelectFilter::make('subject')
                    ->options([
                        'General Inquiry' => 'General Inquiry',
                        'Prayer Request' => 'Prayer Request',
                        'Volunteer Opportunities' => 'Volunteer Opportunities',
                        'Event Information' => 'Event Information',
                        'Pastoral Care' => 'Pastoral Care',
                        'Membership' => 'Membership',
                        'Donations' => 'Donations',
                        'Technical Support' => 'Technical Support',
                    ]),
                Tables\Filters\TernaryFilter::make('is_spam')
                    ->label('Spam Status')
                    ->placeholder('All messages')
                    ->trueLabel('Spam only')
                    ->falseLabel('Legitimate only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('View & Reply'),
                Tables\Actions\Action::make('markAsRead')
                    ->label('Mark as Read')
                    ->icon('heroicon-o-envelope-open')
                    ->color('gray')
                    ->visible(fn (ContactSubmission $record) => $record->status === 'new')
                    ->action(function (ContactSubmission $record) {
                        $record->update(['status' => 'read']);
                    })
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('markAsSpam')
                    ->label('Mark as Spam')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->visible(fn (ContactSubmission $record) => !$record->is_spam)
                    ->form([
                        Forms\Components\Toggle::make('block_ip')
                            ->label('Block this IP address')
                            ->helperText('Automatically block this IP from future submissions')
                            ->default(true),
                    ])
                    ->action(function (ContactSubmission $record, array $data) {
                        $record->update([
                            'is_spam' => true,
                            'spam_reason' => 'Manually marked as spam by admin',
                            'status' => 'archived',
                        ]);

                        // Auto-block IP if checkbox is checked
                        if ($data['block_ip'] ?? true) {
                            BlockedIp::blockIp(
                                $record->ip_address,
                                "Auto-blocked from spam submission: {$record->subject}",
                                auth()->id(),
                                true
                            );

                            \Filament\Notifications\Notification::make()
                                ->title('IP Blocked')
                                ->success()
                                ->body("IP {$record->ip_address} has been blocked.")
                                ->send();
                        }
                    })
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('markAsLegitimate')
                    ->label('Not Spam')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (ContactSubmission $record) => $record->is_spam)
                    ->action(function (ContactSubmission $record) {
                        $record->update([
                            'is_spam' => false,
                            'spam_reason' => null,
                            'status' => 'new',
                        ]);
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markAsRead')
                        ->label('Mark as Read')
                        ->icon('heroicon-o-envelope-open')
                        ->color('gray')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => 'read']);
                            });
                        }),
                    Tables\Actions\BulkAction::make('archive')
                        ->label('Archive')
                        ->icon('heroicon-o-archive-box')
                        ->color('warning')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => 'archived']);
                            });
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('markAsSpam')
                        ->label('Mark as Spam')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('danger')
                        ->form([
                            Forms\Components\Toggle::make('block_ips')
                                ->label('Block all IP addresses')
                                ->helperText('Automatically block all IPs from these submissions')
                                ->default(true),
                        ])
                        ->action(function ($records, array $data) {
                            $blockedCount = 0;
                            
                            $records->each(function ($record) use ($data, &$blockedCount) {
                                $record->update([
                                    'is_spam' => true,
                                    'spam_reason' => 'Bulk marked as spam by admin',
                                    'status' => 'archived',
                                ]);

                                // Auto-block IP if checkbox is checked
                                if ($data['block_ips'] ?? true) {
                                    if (!BlockedIp::isBlocked($record->ip_address)) {
                                        BlockedIp::blockIp(
                                            $record->ip_address,
                                            "Auto-blocked from bulk spam marking: {$record->subject}",
                                            auth()->id(),
                                            true
                                        );
                                        $blockedCount++;
                                    }
                                }
                            });

                            if ($blockedCount > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->title('IPs Blocked')
                                    ->success()
                                    ->body("{$blockedCount} IP address(es) have been blocked.")
                                    ->send();
                            }
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Delete Spam')
                        ->requiresConfirmation()
                        ->modalHeading('Delete selected messages')
                        ->modalDescription('Are you sure you want to permanently delete these messages?'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailManager::route('/'),
            'view' => Pages\ViewMailManager::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'new')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'new')->count() > 0 ? 'warning' : 'primary';
    }
}
