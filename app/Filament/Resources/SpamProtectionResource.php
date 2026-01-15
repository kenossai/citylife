<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpamProtectionResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\File;

class SpamProtectionResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Spam Protection';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 99;

    public static function getNavigationBadge(): ?string
    {
        $config = config('spam-protection.blocked_ips', []);
        $count = count($config);
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function table(Table $table): Table
    {
        $blockedIPs = config('spam-protection.blocked_ips', []);

        // Convert to collection for table display
        $data = collect($blockedIPs)->map(function ($ip, $index) {
            return [
                'id' => $index,
                'ip' => $ip,
                'added_at' => 'From config',
            ];
        });

        return $table
            ->query(
                // Create a fake query builder for static data
                \Illuminate\Database\Eloquent\Builder::getQuery()->whereRaw('1=0')
            )
            ->columns([
                Tables\Columns\TextColumn::make('ip')
                    ->label('IP Address')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('added_at')
                    ->label('Status'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('remove')
                    ->label('Remove')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        // This is a simplified version - in production you'd want to update the config file
                        Notification::make()
                            ->title('To remove this IP')
                            ->warning()
                            ->body('Please edit config/spam-protection.php and remove the IP from the blocked_ips array.')
                            ->persistent()
                            ->send();
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSpamProtection::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
