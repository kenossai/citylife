<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // email_verified_at already exists from previous migration
            // Only add new columns
            $table->string('email_verification_token')->nullable()->after('email_verified_at');
            $table->timestamp('approved_at')->nullable()->after('email_verification_token');
            $table->foreignId('approved_by')->nullable()->constrained('users')->after('approved_at');
            $table->text('approval_notes')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'email_verification_token',
                'approved_at',
                'approved_by',
                'approval_notes'
            ]);
        });
    }
};
