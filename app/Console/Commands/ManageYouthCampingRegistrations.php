<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\YouthCamping;

class ManageYouthCampingRegistrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youth-camping:manage-registrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically open and close youth camping registrations based on configured dates';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🏕️ Managing Youth Camping Registrations...');

        $campings = YouthCamping::published()
            ->where(function ($query) {
                // Campings that should have registration opened
                $query->where(function ($subQuery) {
                    $subQuery->where('is_registration_open', false)
                        ->where('registration_opens_at', '<=', now())
                        ->whereNotNull('registration_opens_at');
                })
                // OR campings that should have registration closed
                ->orWhere(function ($subQuery) {
                    $subQuery->where('is_registration_open', true)
                        ->where('registration_closes_at', '<=', now())
                        ->whereNotNull('registration_closes_at');
                });
            })
            ->get();

        if ($campings->isEmpty()) {
            $this->info('📅 No camping registrations need status updates at this time.');
            return self::SUCCESS;
        }

        $opened = 0;
        $closed = 0;

        foreach ($campings as $camping) {
            $now = now();

            // Check if registration should be opened
            if (!$camping->is_registration_open &&
                $camping->registration_opens_at &&
                $now->gte($camping->registration_opens_at)) {

                $camping->update(['is_registration_open' => true]);
                $this->info("✅ Opened registration for: {$camping->name} ({$camping->year})");
                $opened++;
            }

            // Check if registration should be closed
            if ($camping->is_registration_open &&
                $camping->registration_closes_at &&
                $now->gt($camping->registration_closes_at)) {

                $camping->update(['is_registration_open' => false]);
                $this->info("🔒 Closed registration for: {$camping->name} ({$camping->year})");
                $closed++;
            }
        }

        $this->info("📊 Summary: {$opened} registrations opened, {$closed} registrations closed");

        // Show upcoming registration dates
        $this->showUpcomingRegistrations();

        return self::SUCCESS;
    }

    /**
     * Show upcoming registration dates
     */
    protected function showUpcomingRegistrations(): void
    {
        $this->info('');
        $this->info('📅 Upcoming Registration Events:');

        $upcomingOpening = YouthCamping::published()
            ->where('is_registration_open', false)
            ->where('registration_opens_at', '>', now())
            ->where('registration_opens_at', '<=', now()->addWeeks(2))
            ->orderBy('registration_opens_at')
            ->get();

        $upcomingClosing = YouthCamping::published()
            ->where('is_registration_open', true)
            ->where('registration_closes_at', '>', now())
            ->where('registration_closes_at', '<=', now()->addWeeks(2))
            ->orderBy('registration_closes_at')
            ->get();

        if ($upcomingOpening->isNotEmpty()) {
            $this->info('⏰ Registrations Opening Soon:');
            foreach ($upcomingOpening as $camping) {
                $this->line("   • {$camping->name} ({$camping->year}) - opens {$camping->registration_opens_at->format('M j, Y \a\t g:i A')}");
            }
        }

        if ($upcomingClosing->isNotEmpty()) {
            $this->info('⏰ Registrations Closing Soon:');
            foreach ($upcomingClosing as $camping) {
                $this->line("   • {$camping->name} ({$camping->year}) - closes {$camping->registration_closes_at->format('M j, Y \a\t g:i A')}");
            }
        }

        if ($upcomingOpening->isEmpty() && $upcomingClosing->isEmpty()) {
            $this->line('   No registration events in the next 2 weeks.');
        }
    }
}
