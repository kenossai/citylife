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
            // Make team_member_id nullable to allow both member types
            $table->unsignedBigInteger('team_member_id')->nullable()->change();

            // Add member_id column back as nullable
            $table->unsignedBigInteger('member_id')->nullable()->after('preacher_department_id');

            // Add foreign key for member_id
            $table->foreign('member_id')
                ->references('id')
                ->on('members')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preacher_department_members', function (Blueprint $table) {
            // Drop the member_id foreign key and column
            $table->dropForeign(['member_id']);
            $table->dropColumn('member_id');

            // Make team_member_id required again
            $table->unsignedBigInteger('team_member_id')->nullable(false)->change();
        });
    }
};
