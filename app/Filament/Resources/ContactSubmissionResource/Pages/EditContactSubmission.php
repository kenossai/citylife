<?php

namespace App\Filament\Resources\ContactSubmissionResource\Pages;

use App\Filament\Resources\ContactSubmissionResource;
use App\Mail\ContactFormReply;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class EditContactSubmission extends EditRecord
{
    protected static string $resource = ContactSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sendReply')
                ->label('Send Reply')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Send Reply Email')
                ->modalDescription('This will send your reply to the sender and mark the message as responded.')
                ->form([
                    \Filament\Forms\Components\RichEditor::make('reply_content')
                        ->label('Reply Message')
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
                        ]),
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
                            'admin_notes' => ($this->record->admin_notes ? $this->record->admin_notes . "\n\n" : '') .
                                "REPLY SENT (" . now()->format('M d, Y \a\t g:i A') . "):\n" . strip_tags($data['reply_content'])
                        ]);

                        // Show success notification
                        Notification::make()
                            ->title('Reply sent successfully!')
                            ->body('Your reply has been sent to ' . $this->record->email)
                            ->success()
                            ->send();

                        // Redirect back to the inbox
                        return redirect()->route('filament.admin.resources.contact-submissions.index');

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

            Actions\Action::make('markAsRead')
                ->label('Mark as Read')
                ->icon('heroicon-o-envelope-open')
                ->color('gray')
                ->action(function () {
                    $this->record->update(['status' => 'read']);

                    Notification::make()
                        ->title('Message marked as read')
                        ->success()
                        ->send();
                })
                ->visible(fn () => $this->record->status === 'new'),

            Actions\Action::make('archive')
                ->label('Archive')
                ->icon('heroicon-o-archive-box')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'archived']);

                    Notification::make()
                        ->title('Message archived')
                        ->success()
                        ->send();

                    return redirect()->route('filament.admin.resources.contact-submissions.index');
                }),

            Actions\DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Mark as read when viewing/editing
        if ($this->record->status === 'new') {
            $data['status'] = 'read';
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
