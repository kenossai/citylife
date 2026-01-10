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
        Schema::table('registration_interests', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['user_id']);

            // Rename the column
            $table->renameColumn('user_id', 'member_id');

            // Add new foreign key constraint to members table
            $table->foreign('member_id')->references('id')->on('members')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_interests', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['member_id']);

            // Rename back to user_id
            $table->renameColumn('member_id', 'user_id');

            // Restore old foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }
};
