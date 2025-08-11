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
                // Chat Header Section
                Section::make()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('')
                                    ->formatStateUsing(function ($record) {
                                        $avatar = strtoupper(substr($record->name, 0, 1));
                                        return "
                                        <div class='flex items-center space-x-3'>
                                            <div class='w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg relative'>
                                                {$avatar}
                                                <div class='absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white'></div>
                                            </div>
                                            <div>
                                                <h3 class='font-semibold text-lg text-gray-900 dark:text-white'>{$record->name}</h3>
                                                <p class='text-sm text-gray-500 dark:text-gray-400'>{$record->email}</p>
                                            </div>
                                        </div>";
                                    })
                                    ->html()
                                    ->columnSpan(2),
                                    
                                TextEntry::make('created_at')
                                    ->label('')
                                    ->formatStateUsing(function ($record) {
                                        $time = $record->created_at->format('g:i A • M d');
                                        $status = ucfirst(str_replace('_', ' ', $record->status));
                                        $statusColor = match($record->status) {
                                            'new' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                            'read' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                                            'in_progress' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            'responded' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'archived' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
                                        };
                                        return "
                                        <div class='text-right'>
                                            <p class='text-xs text-gray-500 dark:text-gray-400 font-medium'>{$time}</p>
                                            <span class='inline-block px-2 py-1 text-xs font-medium rounded-full mt-1 {$statusColor}'>{$status}</span>
                                        </div>";
                                    })
                                    ->html()
                                    ->columnSpan(1),
                            ])
                    ])
                    ->headerActions([])
                    ->extraAttributes(['class' => 'border-b border-gray-200 dark:border-gray-700 pb-4 mb-6']),

                // Message Thread Section
                Section::make()
                    ->schema([
                        // Subject Bubble
                        TextEntry::make('subject')
                            ->label('')
                            ->formatStateUsing(function ($record) {
                                return "
                                <div class='flex justify-start mb-4'>
                                    <div class='bg-gray-100 dark:bg-gray-800 rounded-2xl px-4 py-2 max-w-md'>
                                        <p class='text-sm font-medium text-gray-700 dark:text-gray-300'>📧 {$record->subject}</p>
                                    </div>
                                </div>";
                            })
                            ->html(),

                        // Main Message Bubble
                        TextEntry::make('message')
                            ->label('')
                            ->formatStateUsing(function ($record) {
                                $message = nl2br(htmlspecialchars($record->message));
                                $phone = $record->phone ? "
                                <div class='mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center text-sm text-gray-600 dark:text-gray-400'>
                                    📞 <span class='ml-2'>{$record->phone}</span>
                                </div>" : '';
                                
                                return "
                                <div class='flex justify-start'>
                                    <div class='bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl rounded-tl-sm px-4 py-3 max-w-2xl shadow-sm'>
                                        <div class='text-gray-800 dark:text-gray-200 text-base leading-relaxed'>{$message}</div>
                                        {$phone}
                                    </div>
                                </div>";
                            })
                            ->html(),
                    ])
                    ->headerActions([])
                    ->extraAttributes(['class' => 'space-y-3']),
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
