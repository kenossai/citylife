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
            $table->boolean('spouse_is_member')->default(false)->after('marital_status');
            $table->foreignId('spouse_member_id')->nullable()->constrained('members')->onDelete('set null')->after('spouse_is_member');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['spouse_member_id']);
            $table->dropColumn(['spouse_is_member', 'spouse_member_id']);
        });
    }
};
