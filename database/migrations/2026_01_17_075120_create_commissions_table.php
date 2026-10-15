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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->string('match_type'); // 'application' or 'invitation'
            $table->unsignedBigInteger('match_id'); // ID of application or invitation
            $table->foreignId('tutor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Commission amount in BDT
            $table->enum('payment_method', ['cash', 'bkash', 'nagad', 'bank_transfer', 'other'])->default('cash');
            $table->string('receipt_number')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index(['match_type', 'match_id']);
            $table->index(['tutor_id']);
            $table->index(['parent_id']);
            $table->index(['paid_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
