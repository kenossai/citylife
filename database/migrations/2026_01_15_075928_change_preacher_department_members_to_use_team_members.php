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
        Schema::table('preacher_department_members', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['member_id']);
            
            // Rename the column from member_id to team_member_id
            $table->renameColumn('member_id', 'team_member_id');
        });

        // Add the new foreign key constraint in a separate call (required for some DB versions)
        Schema::table('preacher_department_members', function (Blueprint $table) {
            $table->foreign('team_member_id')
                ->references('id')
                ->on('team_members')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preacher_department_members', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['team_member_id']);
            
            // Rename the column back
            $table->renameColumn('team_member_id', 'member_id');
        });

        // Add the old foreign key constraint back
        Schema::table('preacher_department_members', function (Blueprint $table) {
            $table->foreign('member_id')
                ->references('id')
                ->on('members')
                ->onDelete('cascade');
        });
    }
};
