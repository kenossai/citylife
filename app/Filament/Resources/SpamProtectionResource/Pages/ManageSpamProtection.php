<?php

namespace App\Filament\Resources\SpamProtectionResource\Pages;

use App\Filament\Resources\SpamProtectionResource;
use App\Models\BlockedIp;
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
            Actions\Action::make('import_config')
                ->label('Import from Config')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->requiresConfirmation()
                ->modalDescription('This will import IPs from config/spam-protection.php into the database. Existing IPs will not be duplicated.')
                ->action(function () {
                    $configIps = config('spam-protection.blocked_ips', []);
                    $imported = 0;

                    foreach ($configIps as $ip) {
                        if (!BlockedIp::where('ip_address', $ip)->exists()) {
                            BlockedIp::blockIp(
                                $ip,
                                'Imported from config file',
                                auth()->id(),
                                false
                            );
                            $imported++;
                        }
                    }

                    Notification::make()
                        ->title('Import Complete')
                        ->success()
                        ->body("Imported {$imported} IP(s) from config file.")
                        ->send();
                }),

            Actions\Action::make('view_stats')
                ->label('Statistics')
                ->icon('heroicon-o-chart-bar')
                ->color('success')
                ->modalContent(fn () => view('filament.pages.spam-stats', [
                    'totalBlocked' => BlockedIp::count(),
                    'activeBlocked' => BlockedIp::active()->count(),
                    'totalAttempts' => BlockedIp::sum('spam_count'),
                    'autoBlocked' => BlockedIp::where('auto_blocked', true)->count(),
                    'recentBlocks' => BlockedIp::where('created_at', '>=', now()->subDays(7))->count(),
                    'topOffenders' => BlockedIp::orderBy('spam_count', 'desc')->limit(5)->get(),
                ]))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close'),

            Actions\CreateAction::make()
                ->label('Block New IP')
                ->icon('heroicon-o-plus')
                ->mutateFormDataUsing(function (array $data) {
                    $data['blocked_by'] = auth()->id();
                    $data['spam_count'] = 0;
                    $data['auto_blocked'] = false;
                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('IP Blocked')
                        ->body('The IP address has been successfully blocked.')
                ),
        ];
    }

    public function getTitle(): string
    {
        return 'Spam Protection';
    }

    public function getHeading(): string
    {
        return 'Spam Protection Management';
    }

    public function getSubheading(): ?string
    {
        $active = BlockedIp::active()->count();
        $total = BlockedIp::count();
        return "{$active} active blocked IPs out of {$total} total";
    }
}
