<?php

namespace App\Filament\Resources\SocialMediaPostResource\Pages;

use App\Filament\Resources\SocialMediaPostResource;
use App\Services\SocialMediaService;
use App\Models\Event;
use App\Models\News;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateSocialMediaPost extends CreateRecord
{
    protected static string $resource = SocialMediaPostResource::class;

    protected array $platforms = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Handle multiple platforms - create separate records for each platform
        if (isset($data['platforms']) && is_array($data['platforms'])) {
            $this->platforms = $data['platforms'];
            unset($data['platforms']);

            // Set the first platform as the main record
            $data['platform'] = $this->platforms[0];
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $service = new SocialMediaService();
        $record = $this->record;

        // Create additional records for other platforms
        if (isset($this->platforms) && count($this->platforms) > 1) {
            $otherPlatforms = array_slice($this->platforms, 1);

            foreach ($otherPlatforms as $platform) {
                $record->replicate()->fill([
                    'platform' => $platform,
                ])->save();
            }
        }

        // If status is 'published', post immediately to all platforms
        if ($record->status === 'published') {
            $this->publishImmediately($service, $record);
        }
    }

    protected function publishImmediately(SocialMediaService $service, $record): void
    {
        $platforms = isset($this->platforms) ? $this->platforms : [$record->platform];

        try {
            if ($record->content_type === 'event') {
                $event = Event::find($record->content_id);
                if ($event) {
                    $results = $service->postEvent($event, $platforms);
                    $this->showResults($results);
                }
            } elseif ($record->content_type === 'news') {
                $news = News::find($record->content_id);
                if ($news) {
                    $results = $service->postAnnouncement($news, $platforms);
                    $this->showResults($results);
                }
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Publishing failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function showResults(array $results): void
    {
        $successful = array_filter($results, fn($r) => $r['success']);
        $failed = array_filter($results, fn($r) => !$r['success']);

        if (count($successful) > 0) {
            Notification::make()
                ->title('Posted successfully to ' . implode(', ', array_keys($successful)))
                ->success()
                ->send();
        }

        if (count($failed) > 0) {
            $errors = array_map(fn($r) => $r['error'], $failed);
            Notification::make()
                ->title('Failed to post to ' . implode(', ', array_keys($failed)))
                ->body(implode('; ', $errors))
                ->danger()
                ->send();
        }
    }
}
