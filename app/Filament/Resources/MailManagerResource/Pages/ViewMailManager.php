<?php

namespace App\Filament\Resources\MailManagerResource\Pages;

use App\Filament\Resources\MailManagerResource;
use App\Mail\ContactFormReply;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Actions\Action;

use Filament\Infolists\Components\ViewEntry;

class ViewMailManager extends ViewRecord
{
    protected static string $resource = MailManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reply')
                ->label('Reply')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('primary')
                ->modalHeading('Reply to ' . $this->record->name)
                ->modalDescription('Send a reply to this message')
                ->form([
                    \Filament\Forms\Components\RichEditor::make('reply_content')
                        ->label('Your Reply')
                        ->required()
                        ->placeholder('Type your reply here...')
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'link',
                            'bulletList',
                            'orderedList',
                            'redo',
                            'undo',
                        ])
                        ->columnSpanFull(),
                ])
                ->action(function (array $data) {
                    try {
                        // Send reply email
                        Mail::to($this->record->email)
                            ->send(new ContactFormReply(
                                $this->record,
                                $data['reply_content'],
                                Auth::user()
                            ));

                        // Update the record
                        $this->record->update([
                            'status' => 'responded',
                            'responded_at' => now(),
                            'responded_by' => Auth::id(),
                        ]);

                        // Show success notification
                        Notification::make()
                            ->title('Reply sent successfully!')
                            ->body('Your reply has been sent to ' . $this->record->email)
                            ->success()
                            ->send();

                        // Redirect back to the inbox
                        return redirect(MailManagerResource::getUrl('index'));

                    } catch (\Exception $e) {
                        // Show error notification
                        Notification::make()
                            ->title('Failed to send reply')
                            ->body('There was an error sending the reply: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->visible(fn () => $this->record->status !== 'responded'),

            Actions\Action::make('archive')
                ->label('Archive')
                ->icon('heroicon-o-archive-box')
                ->color('gray')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'archived']);

                    Notification::make()
                        ->title('Message archived')
                        ->success()
                        ->send();

                    return redirect(MailManagerResource::getUrl('index'));
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        ViewEntry::make('mail_interface')
                            ->view('filament.mail-view')
                            ->viewData(['record' => $this->record]),
                    ])
                    ->headerActions([]),
            ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Mark as read when viewing
        if ($this->record->status === 'new') {
            $this->record->update(['status' => 'read']);
        }

        return $data;
    }
}
