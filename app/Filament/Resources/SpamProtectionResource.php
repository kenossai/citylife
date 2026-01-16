<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpamProtectionResource\Pages;
use App\Models\BlockedIp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class SpamProtectionResource extends Resource
{
    protected static ?string $model = BlockedIp::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Spam Protection';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 99;

    public static function getNavigationBadge(): ?string
    {
        $count = BlockedIp::active()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ip_address')
                    ->label('IP Address')
                    ->placeholder('e.g., 123.456.789.012')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->rule('ip')
                    ->maxLength(45),

                Forms\Components\Textarea::make('reason')
                    ->label('Reason for Blocking')
                    ->placeholder('Why is this IP being blocked?')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->helperText('Inactive IPs are not blocked but kept for records')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-globe-alt'),

                Tables\Columns\TextColumn::make('reason')
                    ->label('Reason')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->reason)
                    ->searchable(),

                Tables\Columns\IconColumn::make('auto_blocked')
                    ->label('Auto')
                    ->boolean()
                    ->tooltip(fn ($record) => $record->auto_blocked ? 'Auto-blocked from spam submission' : 'Manually blocked'),

                Tables\Columns\TextColumn::make('spam_count')
                    ->label('Attempts')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state >= 10 => 'danger',
                        $state >= 5 => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('last_attempt_at')
                    ->label('Last Attempt')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->since()
                    ->tooltip(fn ($record) => $record->last_attempt_at?->format('F j, Y g:i:s A')),

                Tables\Columns\TextColumn::make('blockedBy.name')
                    ->label('Blocked By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->tooltip(fn ($record) => $record->is_active ? 'Active - Blocking' : 'Inactive'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Blocked On')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('auto_blocked')
                    ->label('Block Type')
                    ->trueLabel('Auto-blocked')
                    ->falseLabel('Manually blocked')
                    ->native(false),

                Tables\Filters\Filter::make('high_spam_count')
                    ->label('High Spam Count (10+)')
                    ->query(fn (Builder $query) => $query->where('spam_count', '>=', 10)),

                Tables\Filters\Filter::make('recent')
                    ->label('Blocked in Last 7 Days')
                    ->query(fn (Builder $query) => $query->where('created_at', '>=', now()->subDays(7))),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('toggle_status')
                    ->label(fn ($record) => $record->is_active ? 'Unblock' : 'Reblock')
                    ->icon(fn ($record) => $record->is_active ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed')
                    ->color(fn ($record) => $record->is_active ? 'warning' : 'success')
                    ->requiresConfirmation()
                    ->action(function (BlockedIp $record) {
                        if ($record->is_active) {
                            $record->unblock();
                            Notification::make()
                                ->title('IP Unblocked')
                                ->success()
                                ->body("IP {$record->ip_address} is now unblocked and can access the site.")
                                ->send();
                        } else {
                            $record->reblock();
                            Notification::make()
                                ->title('IP Reblocked')
                                ->success()
                                ->body("IP {$record->ip_address} is now blocked again.")
                                ->send();
                        }
                    }),

                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Block Selected')
                        ->icon('heroicon-o-lock-closed')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->reblock();
                            Notification::make()
                                ->title('IPs Blocked')
                                ->success()
                                ->body(count($records) . ' IP(s) have been blocked.')
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Unblock Selected')
                        ->icon('heroicon-o-lock-open')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->unblock();
                            Notification::make()
                                ->title('IPs Unblocked')
                                ->success()
                                ->body(count($records) . ' IP(s) have been unblocked.')
                                ->send();
                        }),

                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSpamProtection::route('/'),
        ];
    }
}
