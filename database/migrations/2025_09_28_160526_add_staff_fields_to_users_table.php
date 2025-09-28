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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone')->nullable()->after('email');
            $table->string('job_title')->nullable();
            $table->string('department')->nullable();
            $table->date('hire_date')->nullable();
            $table->enum('employment_status', ['active', 'inactive', 'suspended', 'terminated'])->default('active');
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable(); // Profile picture
            $table->json('preferences')->nullable(); // User preferences
            $table->datetime('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('force_password_change')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'phone', 'job_title', 'department',
                'hire_date', 'employment_status', 'bio', 'avatar', 'preferences',
                'last_login_at', 'last_login_ip', 'is_active', 'force_password_change'
            ]);
        });
    }
};
