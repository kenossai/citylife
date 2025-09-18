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
        Schema::table('givings', function (Blueprint $table) {
            // Drop old columns that are no longer needed
            $table->dropColumn([
                'title',
                'description',
                'content',
                'payment_methods',
                'bank_name',
                'account_name',
                'account_number',
                'sort_code',
                'online_giving_url',
                'instructions',
                'suggested_amount',
                'featured_image',
                'is_active',
                'is_featured',
                'sort_order'
            ]);

            // Add new columns for giving records
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->string('donor_phone')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->date('given_date');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('gift_aid_eligible')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('givings', function (Blueprint $table) {
            // Remove new columns
            $table->dropForeign(['member_id']);
            $table->dropColumn([
                'member_id',
                'donor_name',
                'donor_email',
                'donor_phone',
                'amount',
                'payment_method',
                'given_date',
                'reference_number',
                'notes',
                'is_anonymous',
                'gift_aid_eligible'
            ]);

            // Restore old columns
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('payment_methods');
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('sort_code')->nullable();
            $table->string('online_giving_url')->nullable();
            $table->text('instructions')->nullable();
            $table->decimal('suggested_amount', 8, 2)->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
        });
    }
};
