<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\ContactSubmission;
use App\Models\VolunteerApplication;
use Filament\Widgets\ChartWidget;

class EventAnalyticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Event & Engagement Analytics';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    
    protected function getData(): array
    {
        // Get event data for the last 6 months
        $eventData = collect();
        $contactData = collect();
        $volunteerData = collect();
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            $eventCount = Event::whereBetween('start_date', [$startOfMonth, $endOfMonth])
                ->where('is_published', true)
                ->count();
                
            $contactCount = ContactSubmission::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
                
            $volunteerCount = VolunteerApplication::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
            
            $eventData->push($eventCount);
            $contactData->push($contactCount);
            $volunteerData->push($volunteerCount);
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
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
        ];
    }
}
