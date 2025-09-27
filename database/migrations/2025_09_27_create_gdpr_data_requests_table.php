<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gdpr_data_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->enum('request_type', ['export', 'deletion', 'rectification', 'portability']);
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])->default('pending');
            $table->text('request_details')->nullable();
            $table->json('requested_data_types')->nullable(); // Which data types to include
            $table->timestamp('requested_at');
            $table->timestamp('completed_at')->nullable();
            $table->text('completion_notes')->nullable();
            $table->string('processed_by')->nullable(); // Admin who processed the request
            $table->json('exported_files')->nullable(); // File paths for exported data
            $table->timestamps();

            $table->index(['member_id', 'request_type']);
            $table->index('status');
            $table->index('requested_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gdpr_data_requests');
    }
};
