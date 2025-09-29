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
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();

            // Backup details
            $table->string('name'); // Backup file name
            $table->string('type'); // database, files, full
            $table->enum('status', ['pending', 'running', 'completed', 'failed', 'partial'])->default('pending');
            $table->string('file_path')->nullable(); // Path to backup file
            $table->bigInteger('file_size')->nullable(); // Size in bytes
            $table->string('compression')->nullable(); // gzip, zip, none

            // Timing
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('duration_seconds')->nullable(); // Duration in seconds

            // Metadata
            $table->json('database_tables')->nullable(); // List of backed up tables
            $table->json('file_directories')->nullable(); // List of backed up directories
            $table->integer('records_count')->nullable(); // Total records backed up
            $table->bigInteger('total_size')->nullable(); // Total size of backed up data

            // Integrity
            $table->string('checksum')->nullable(); // File checksum for integrity
            $table->string('encryption')->nullable(); // Encryption method if used

            // Recovery
            $table->boolean('is_restorable')->default(true);
            $table->timestamp('expires_at')->nullable(); // Auto-cleanup date
            $table->text('restore_notes')->nullable();

            // Error handling
            $table->text('error_message')->nullable();
            $table->json('error_details')->nullable();
            $table->integer('retry_count')->default(0);

            // User tracking
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('trigger_type')->default('manual'); // manual, scheduled, automatic

            // Additional metadata
            $table->json('config')->nullable(); // Backup configuration used
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['status', 'created_at']);
            $table->index(['type', 'created_at']);
            $table->index(['created_by', 'created_at']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_logs');
    }
};
