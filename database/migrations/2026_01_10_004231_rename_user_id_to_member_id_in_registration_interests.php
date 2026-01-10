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
        // Check if the column needs to be renamed (it might already be member_id on fresh installs)
        if (Schema::hasColumn('registration_interests', 'user_id')) {
            Schema::table('registration_interests', function (Blueprint $table) {
                // Drop the old foreign key constraint if it exists
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue
                }

                // Rename the column
                $table->renameColumn('user_id', 'member_id');
            });

            // Add new foreign key constraint in a separate statement
            Schema::table('registration_interests', function (Blueprint $table) {
                $table->foreign('member_id')->references('id')->on('members')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only run if member_id exists
        if (Schema::hasColumn('registration_interests', 'member_id')) {
            Schema::table('registration_interests', function (Blueprint $table) {
                // Drop the foreign key constraint
                try {
                    $table->dropForeign(['member_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue
                }

                // Rename back to user_id
                $table->renameColumn('member_id', 'user_id');
            });

            // Restore old foreign key constraint
            Schema::table('registration_interests', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            });
        }
    }
};
