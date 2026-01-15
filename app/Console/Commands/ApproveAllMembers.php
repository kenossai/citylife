<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

class ApproveAllMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:approve-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Approve and verify all existing members to prevent lockout';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Approving all existing members...');

        $updated = DB::table('members')
            ->where(function ($query) {
                $query->whereNull('email_verified_at')
                      ->orWhereNull('approved_at');
            })
            ->update([
                'email_verified_at' => now(),
                'approved_at' => now(),
            ]);

        $this->info("✓ {$updated} members verified and approved");

        // Show statistics
        $total = Member::count();
        $verified = Member::whereNotNull('email_verified_at')->count();
        $approved = Member::whereNotNull('approved_at')->count();
        $active = Member::where('is_active', true)->count();

        $this->newLine();
        $this->table(
            ['Status', 'Count'],
            [
                ['Total Members', $total],
                ['Email Verified', $verified],
                ['Admin Approved', $approved],
                ['Active', $active],
            ]
        );

        $this->newLine();
        $this->info('All existing members can now login!');

        return Command::SUCCESS;
    }
}
