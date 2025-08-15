<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Member;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add a default password for existing members who don't have one
        $members = Member::whereNull('password')->get();
        
        foreach ($members as $member) {
            // Set a default password that can be changed later
            // For demo purposes, we'll use 'password123' but in production
            // you'd want to generate secure temporary passwords and notify users
            $member->update([
                'password' => Hash::make('password123')
            ]);
        }
        
        // Log the update
        if ($members->count() > 0) {
            Log::info("Updated {$members->count()} members with default passwords");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // In a real scenario, you wouldn't want to remove passwords
        // This is just for development rollback
        Member::whereNotNull('password')->update(['password' => null]);
    }
};
