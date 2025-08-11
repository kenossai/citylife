<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MailManagerResource\Pages;
use App\Filament\Resources\MailManagerResource\RelationManagers;
use App\Models\ContactSubmission;
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

    protected static ?string $navigationLabel = 'Mail Inbox';

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
                            ->formatStateUsing(fn ($state) => $state ? $state->format('M d, Y \a\t g:i A') : ''),
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
                                    ->formatStateUsing(fn ($state) => $state ? $state->format('M d, Y \a\t g:i A') : 'Not responded')
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
                    ->formatStateUsing(fn ($record) => $record->name . ' <' . $record->email . '>'),

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
                    ->tooltip(fn ($record) => $record->created_at->format('F j, Y \a\t g:i A')),

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
            'index' => Pages\ListContactSubmissions::route('/'),
            'view' => Pages\ViewContactSubmission::route('/{record}'),
        ];
    }
}
