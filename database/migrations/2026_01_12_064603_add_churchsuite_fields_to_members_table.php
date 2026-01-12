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
            $table->string('churchsuite_id')->nullable()->after('is_active');
            $table->timestamp('churchsuite_synced_at')->nullable()->after('churchsuite_id');
            $table->enum('churchsuite_sync_status', ['pending', 'synced', 'failed'])->nullable()->after('churchsuite_synced_at');
            $table->text('churchsuite_sync_error')->nullable()->after('churchsuite_sync_status');

            // Add index for faster queries
            $table->index('churchsuite_id');
            $table->index('churchsuite_sync_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropIndex(['churchsuite_id']);
            $table->dropIndex(['churchsuite_sync_status']);
            $table->dropColumn([
                'churchsuite_id',
                'churchsuite_synced_at',
                'churchsuite_sync_status',
                'churchsuite_sync_error',
            ]);
        });
    }
};
