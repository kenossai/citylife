<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\ContactSubmission;
use App\Models\VolunteerApplication;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class EventAnalyticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Event & Engagement Analytics';
    protected static ?int $sort = 3;
    protected static bool $isLazy = true;

    protected function getData(): array
    {
        return Cache::remember('widget.event_analytics', now()->addMinutes(15), function () {
            return $this->buildChartData();
        });
    }

    private function buildChartData(): array
    {
        $since = now()->subMonths(5)->startOfMonth();

        // 3 queries instead of 18
        $eventRows = Event::selectRaw("DATE_FORMAT(start_date, '%Y-%m') as month, COUNT(*) as count")
            ->where('is_published', true)
            ->where('start_date', '>=', $since)
            ->groupBy('month')
            ->pluck('count', 'month');

        $contactRows = ContactSubmission::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->where('created_at', '>=', $since)
            ->groupBy('month')
            ->pluck('count', 'month');

        $volunteerRows = VolunteerApplication::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->where('created_at', '>=', $since)
            ->groupBy('month')
            ->pluck('count', 'month');

        $eventData = collect();
        $contactData = collect();
        $volunteerData = collect();

        for ($i = 5; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $eventData->push($eventRows->get($key, 0));
            $contactData->push($contactRows->get($key, 0));
            $volunteerData->push($volunteerRows->get($key, 0));
        }

        return [
            'datasets' => [
                [
                    'label' => 'Events Published',
                    'data' => $eventData->toArray(),
                    'borderColor' => '#8B5CF6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.2)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Contact Messages',
                    'data' => $contactData->toArray(),
                    'borderColor' => '#06B6D4',
                    'backgroundColor' => 'rgba(6, 182, 212, 0.2)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Volunteer Applications',
                    'data' => $volunteerData->toArray(),
                    'borderColor' => '#F59E0B',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                    'tension' => 0.3,
                ],
            ],
            'labels' => collect(range(5, 0))->map(function ($i) {
                return now()->subMonths($i)->format('M Y');
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'aspectRatio' => 1.2,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
        ];
    }
}
