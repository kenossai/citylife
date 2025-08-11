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
                Section::make()
                    ->schema([
                        // Subject line - clean and prominent
                        TextEntry::make('subject')
                            ->label('')
                            ->size('xl')
                            ->weight('bold')
                            ->color('primary')
                            ->formatStateUsing(function ($state) {
                                return '<h1 class="text-3xl font-bold text-gray-900 mb-6">' . e($state) . '</h1>';
                            })
                            ->html(),

                        // Sender info and timestamp in a clean header
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('sender_info')
                                    ->label('')
                                    ->html()
                                    ->formatStateUsing(function ($record) {
                                        $avatar = '<div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold mr-3 shadow-sm">' . 
                                                 strtoupper(substr($record->name, 0, 1)) . '</div>';
                                        $senderInfo = '<div>' .
                                                    '<div class="font-semibold text-gray-900 text-lg">' . e($record->name) . '</div>' .
                                                    '<div class="text-sm text-gray-600">' . e($record->email) . '</div>' .
                                                    '</div>';
                                        return '<div class="flex items-center py-2">' . $avatar . $senderInfo . '</div>';
                                    }),
                                    
                                TextEntry::make('created_at')
                                    ->label('')
                                    ->formatStateUsing(function ($state) {
                                        if (!$state) return '';
                                        if (is_string($state)) return $state;
                                        $formatted = $state->format('M d, Y \a\t g:i A');
                                        return '<div class="text-right py-2">' .
                                               '<div class="text-sm text-gray-600 font-medium">' . $formatted . '</div>' .
                                               '</div>';
                                    })
                                    ->html()
                                    ->alignEnd(),
                            ]),

                        // Horizontal divider
                        TextEntry::make('divider')
                            ->label('')
                            ->formatStateUsing(fn () => '<hr class="my-6 border-gray-200">')
                            ->html(),

                        // Message content with better styling
                        TextEntry::make('message')
                            ->label('')
                            ->html()
                            ->formatStateUsing(function ($state) {
                                return '<div class="prose prose-gray max-w-none">' .
                                       '<div class="text-gray-800 text-base leading-7 whitespace-pre-wrap font-normal">' . 
                                       nl2br(e($state)) . 
                                       '</div>' .
                                       '</div>';
                            }),

                        // Contact info if available
                        TextEntry::make('contact_info')
                            ->label('')
                            ->html()
                            ->formatStateUsing(function ($record) {
                                if (!$record->phone) return '';
                                return '<div class="mt-6 pt-4 border-t border-gray-200">' .
                                       '<div class="flex items-center text-sm text-gray-600">' .
                                       '<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">' .
                                       '<path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>' .
                                       '</svg>' .
                                       '<span class="font-medium">Phone:</span> ' . e($record->phone) .
                                       '</div>' .
                                       '</div>';
                            })
                            ->visible(fn ($record) => !empty($record->phone)),

                        // Status info
                        TextEntry::make('status_info')
                            ->label('')
                            ->html()
                            ->formatStateUsing(function ($record) {
                                $statusColors = [
                                    'new' => 'bg-red-100 text-red-800',
                                    'read' => 'bg-gray-100 text-gray-800',
                                    'in_progress' => 'bg-yellow-100 text-yellow-800',
                                    'responded' => 'bg-green-100 text-green-800',
                                    'archived' => 'bg-blue-100 text-blue-800',
                                ];
                                
                                $statusLabels = [
                                    'new' => 'New',
                                    'read' => 'Read',
                                    'in_progress' => 'In Progress',
                                    'responded' => 'Responded',
                                    'archived' => 'Archived',
                                ];
                                
                                $colorClass = $statusColors[$record->status] ?? 'bg-gray-100 text-gray-800';
                                $label = $statusLabels[$record->status] ?? ucfirst($record->status);
                                
                                return '<div class="mt-6 pt-4 border-t border-gray-200">' .
                                       '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $colorClass . '">' .
                                       $label .
                                       '</span>' .
                                       '</div>';
                            }),
                    ])
                    ->columnSpan('full'),
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
