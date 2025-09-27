<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gdpr_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // data_access, data_export, consent_change, etc.
            $table->string('data_type')->nullable(); // What type of data was accessed/modified
            $table->text('description');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('performed_by'); // Admin user who performed action
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'action']);
            $table->index('created_at');
            $table->index('performed_by');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gdpr_audit_logs');
    }
};
