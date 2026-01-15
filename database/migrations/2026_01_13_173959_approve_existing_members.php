<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mark all existing members as email verified and approved
        // This ensures existing members don't get locked out
        DB::table('members')
            ->whereNull('email_verified_at')
            ->update([
                'email_verified_at' => now(),
                'approved_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't reverse this migration - we don't want to unapprove members
    }
};
