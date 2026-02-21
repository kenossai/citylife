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
        Schema::create('bible_school_otp_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('code', 30);          // e.g. BS847291
            $table->unsignedSmallInteger('year'); // bible school year scope
            $table->unsignedBigInteger('bible_school_speaker_id')->nullable()->index();
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['email', 'code']);
            $table->index(['code', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_school_otp_tokens');
    }
};
