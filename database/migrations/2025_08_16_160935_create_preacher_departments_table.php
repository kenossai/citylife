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
        Schema::create('preacher_departments', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Sunday Service, Youth, Bible Study, etc.
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('head_of_department')->nullable();
            $table->string('head_image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preacher_departments');
    }
};
