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
        Schema::create('youth_campings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->year('year');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('location');
            $table->integer('max_participants')->nullable();
            $table->decimal('cost_per_person', 8, 2)->default(0);
            $table->datetime('registration_opens_at')->nullable();
            $table->datetime('registration_closes_at')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('requirements')->nullable();
            $table->json('what_to_bring')->nullable();
            $table->json('activities')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_registration_open')->default(false);
            $table->timestamps();

            $table->index(['year', 'is_published']);
            $table->index(['start_date']);
            $table->index(['registration_opens_at'], 'youth_campings_reg_opens_idx');
            $table->index(['registration_closes_at'], 'youth_campings_reg_closes_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youth_campings');
    }
};
