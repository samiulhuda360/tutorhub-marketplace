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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tutor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tuition_offer_id')->nullable()->constrained('tuition_offers')->onDelete('set null');
            $table->text('message'); // Invitation message from parent
            $table->enum('status', [
                'pending',
                'accepted',
                'rejected',
                'with_admin',
                'contacted',
                'negotiating',
                'commission_paid',
                'completed'
            ])->default('pending');
            $table->text('tutor_response_message')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('sent_to_admin_at')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index(['parent_id', 'status']);
            $table->index(['tutor_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
