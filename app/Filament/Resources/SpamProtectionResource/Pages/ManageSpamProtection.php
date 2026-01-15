<?php

namespace App\Filament\Resources\SpamProtectionResource\Pages;

use App\Filament\Resources\SpamProtectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Forms;
use Filament\Notifications\Notification;

class ManageSpamProtection extends ManageRecords
{
    protected static string $resource = SpamProtectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('add_ip')
                ->label('Block New IP Address')
                ->icon('heroicon-o-plus')
                ->color('warning')
                ->form([
                    Forms\Components\TextInput::make('ip_address')
                        ->label('IP Address to Block')
                        ->placeholder('e.g., 123.456.789.012')
                        ->required()
                        ->rule('ip'),
                    Forms\Components\Textarea::make('reason')
                        ->label('Reason for Blocking')
                        ->placeholder('Why is this IP being blocked?')
                        ->rows(2),
                ])
                ->action(function (array $data) {
                    $ip = $data['ip_address'];
                    $reason = $data['reason'] ?? 'No reason provided';

                    Notification::make()
                        ->title('IP Address Blocked')
                        ->success()
                        ->body("To permanently block {$ip}, add it to config/spam-protection.php in the 'blocked_ips' array. Reason: {$reason}")
                        ->persistent()
                        ->send();
                }),
            Actions\Action::make('view_stats')
                ->label('View Statistics')
                ->icon('heroicon-o-chart-bar')
                ->color('primary')
                ->url(route('filament.admin.resources.contact-submissions.index')),
        ];
    }

    public function getTitle(): string
    {
        return 'Spam Protection Settings';
    }

    public function getHeading(): string
    {
        return 'Spam Protection Settings';
    }
}
