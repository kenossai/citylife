<?php

namespace App\Filament\Resources\VerifiedMembersResource\Pages;

use App\Filament\Resources\VerifiedMembersResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ViewVerifiedMember extends ViewRecord
{
    protected static string $resource = VerifiedMembersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => !$this->record->approved_at)
                ->action(function () {
                    $this->record->update([
                        'approved_at' => now(),
                        'approved_by' => Auth::id(),
                    ]);

                    try {
                        $this->record->notify(new \App\Notifications\MemberApproved());
                    } catch (\Exception $e) {
                        Log::error('Failed to send member approval notification', [
                            'member_id' => $this->record->id,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    Notification::make()
                        ->title('Member Approved')
                        ->success()
                        ->send();

                    return redirect()->route('filament.admin.resources.verified-members.index');
                }),
            Actions\Action::make('edit_full')
                ->label('Edit in Members')
                ->icon('heroicon-o-pencil-square')
                ->url(fn () => route('filament.admin.resources.members.edit', $this->record)),
        ];
    }
}
