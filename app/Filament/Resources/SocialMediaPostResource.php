<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialMediaPostResource\Pages;
use App\Models\SocialMediaPost;
use App\Models\Event;
use App\Models\News;
use App\Services\SocialMediaService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class SocialMediaPostResource extends Resource
{
    protected static ?string $model = SocialMediaPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static ?string $navigationLabel = 'Social Media Posts';

    protected static ?string $modelLabel = 'Social Media Post';

    protected static ?string $navigationGroup = 'Communications';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Content Information')
                    ->schema([
                        Forms\Components\Select::make('content_type')
                            ->label('Content Type')
                            ->options([
                                'event' => 'Event',
                                'news' => 'News/Announcement',
                            ])
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('content_id')
                            ->label('Select Content')
                            ->options(function (Forms\Get $get) {
                                $contentType = $get('content_type');
                                if ($contentType === 'event') {
                                    return Event::published()
                                        ->orderBy('start_date', 'desc')
                                        ->limit(50)
                                        ->pluck('title', 'id');
                                } elseif ($contentType === 'news') {
                                    return News::published()
                                        ->orderBy('published_at', 'desc')
                                        ->limit(50)
                                        ->pluck('title', 'id');
                                }
                                return [];
                            })
                            ->searchable()
                            ->required()
                            ->visible(fn (Forms\Get $get): bool => filled($get('content_type'))),
                    ])->columns(2),

                Forms\Components\Section::make('Platform Settings')
                    ->schema([
                        Forms\Components\CheckboxList::make('platforms')
                            ->label('Select Platforms')
                            ->options([
                                'facebook' => 'Facebook',
                                'twitter' => 'Twitter/X',
                                'instagram' => 'Instagram',
                                'linkedin' => 'LinkedIn',
                            ])
                            ->descriptions([
                                'facebook' => 'Post to Facebook page',
                                'twitter' => 'Post to Twitter/X account',
                                'instagram' => 'Post to Instagram (requires image)',
                                'linkedin' => 'Post to LinkedIn company page',
                            ])
                            ->required()
                            ->columns(2),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'scheduled' => 'Scheduled',
                                'published' => 'Published Now',
                            ])
                            ->default('published')
                            ->required()
                            ->live(),

                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->label('Schedule For')
                            ->visible(fn (Forms\Get $get): bool => $get('status') === 'scheduled')
                            ->required(fn (Forms\Get $get): bool => $get('status') === 'scheduled')
                            ->minDate(now())
                            ->native(false),
                    ])->columns(1),

                Forms\Components\Section::make('Preview')
                    ->schema([
                        Forms\Components\Placeholder::make('content_preview')
                            ->label('Post Preview')
                            ->content(function (Forms\Get $get) {
                                $contentType = $get('content_type');
                                $contentId = $get('content_id');

                                if (!$contentType || !$contentId) {
                                    return 'Select content to see preview...';
                                }

                                $service = new SocialMediaService();

                                if ($contentType === 'event') {
                                    $event = Event::find($contentId);
                                    if ($event) {
                                        $content = $service->formatEventContent($event);
                                        return new \Illuminate\Support\HtmlString('<div class="p-4 bg-gray-700 rounded-lg"><pre class="whitespace-pre-wrap text-sm">' . htmlspecialchars($content['text']) . '</pre></div>');
                                    }
                                } elseif ($contentType === 'news') {
                                    $news = News::find($contentId);
                                    if ($news) {
                                        $content = $service->formatAnnouncementContent($news);
                                        return new \Illuminate\Support\HtmlString('<div class="p-4 bg-gray-700 rounded-lg"><pre class="whitespace-pre-wrap text-sm">' . htmlspecialchars($content['text']) . '</pre></div>');
                                    }
                                }

                                return 'Content not found...';
                            })
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Forms\Get $get): bool => filled($get('content_type')) && filled($get('content_id'))),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('platform')
                    ->colors([
                        'primary' => 'facebook',
                        'info' => 'twitter',
                        'warning' => 'instagram',
                        'success' => 'linkedin',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('content_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'event' => 'success',
                        'news' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('content_title')
                    ->label('Content')
                    ->getStateUsing(function (SocialMediaPost $record): string {
                        $content = $record->getContentModel();
                        return $content ? \Illuminate\Support\Str::limit($content->title, 50) : 'N/A';
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function ($query) use ($search) {
                            $query->whereHas('content', function ($q) use ($search) {
                                $q->where('title', 'like', "%{$search}%");
                            });
                        });
                    })
                    ->limit(50),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'scheduled',
                        'success' => 'published',
                        'danger' => 'failed',
                    ]),

                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Scheduled')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('platform')
                    ->options([
                        'facebook' => 'Facebook',
                        'twitter' => 'Twitter/X',
                        'instagram' => 'Instagram',
                        'linkedin' => 'LinkedIn',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'published' => 'Published',
                        'failed' => 'Failed',
                    ]),

                Tables\Filters\SelectFilter::make('content_type')
                    ->label('Content Type')
                    ->options([
                        'event' => 'Events',
                        'news' => 'News/Announcements',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('view_post')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (SocialMediaPost $record): ?string => $record->platform_url)
                    ->openUrlInNewTab()
                    ->visible(fn (SocialMediaPost $record): bool => $record->isSuccessful()),

                Tables\Actions\Action::make('retry')
                    ->label('Retry')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->action(function (SocialMediaPost $record) {
                        $service = new SocialMediaService();

                        if ($record->content_type === 'event') {
                            $event = Event::find($record->content_id);
                            if ($event) {
                                $result = $service->postEvent($event, [$record->platform]);

                                if ($result[$record->platform]['success']) {
                                    Notification::make()
                                        ->title('Post retried successfully!')
                                        ->success()
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->title('Retry failed')
                                        ->body($result[$record->platform]['error'])
                                        ->danger()
                                        ->send();
                                }
                            }
                        } elseif ($record->content_type === 'news') {
                            $news = News::find($record->content_id);
                            if ($news) {
                                $result = $service->postAnnouncement($news, [$record->platform]);

                                if ($result[$record->platform]['success']) {
                                    Notification::make()
                                        ->title('Post retried successfully!')
                                        ->success()
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->title('Retry failed')
                                        ->body($result[$record->platform]['error'])
                                        ->danger()
                                        ->send();
                                }
                            }
                        }
                    })
                    ->requiresConfirmation()
                    ->visible(fn (SocialMediaPost $record): bool => $record->canRetry()),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('retry_failed')
                        ->label('Retry Failed Posts')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->action(function ($records) {
                            $service = new SocialMediaService();
                            $successCount = 0;

                            foreach ($records as $record) {
                                if ($record->canRetry()) {
                                    if ($record->content_type === 'event') {
                                        $event = Event::find($record->content_id);
                                        if ($event) {
                                            $result = $service->postEvent($event, [$record->platform]);
                                            if ($result[$record->platform]['success']) {
                                                $successCount++;
                                            }
                                        }
                                    } elseif ($record->content_type === 'news') {
                                        $news = News::find($record->content_id);
                                        if ($news) {
                                            $result = $service->postAnnouncement($news, [$record->platform]);
                                            if ($result[$record->platform]['success']) {
                                                $successCount++;
                                            }
                                        }
                                    }
                                }
                            }

                            Notification::make()
                                ->title("Retried {$successCount} posts successfully!")
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSocialMediaPosts::route('/'),
            'create' => Pages\CreateSocialMediaPost::route('/create'),
            'edit' => Pages\EditSocialMediaPost::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $failedCount = static::getModel()::where('status', 'failed')->count();
        $scheduledCount = static::getModel()::where('status', 'scheduled')->count();

        $totalCount = $failedCount + $scheduledCount;

        return $totalCount > 0 ? (string) $totalCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $failedCount = static::getModel()::where('status', 'failed')->count();

        return $failedCount > 0 ? 'danger' : 'warning';
    }
}
