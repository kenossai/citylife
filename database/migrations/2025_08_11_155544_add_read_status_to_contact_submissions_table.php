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
        Schema::table('contact_submissions', function (Blueprint $table) {
            // Update the status enum to include 'read'
            $table->enum('status', ['new', 'read', 'in_progress', 'responded', 'archived'])->default('new')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            // Revert back to original enum values
            $table->enum('status', ['new', 'in_progress', 'responded', 'archived'])->default('new')->change();
        });
    }
};
