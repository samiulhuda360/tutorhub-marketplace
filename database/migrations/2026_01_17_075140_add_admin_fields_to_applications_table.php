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
        Schema::table('applications', function (Blueprint $table) {
            $table->timestamp('sent_to_admin_at')->nullable()->after('status');
            $table->timestamp('admin_contacted_at')->nullable()->after('sent_to_admin_at');
            $table->timestamp('parent_contact_shared_at')->nullable()->after('admin_contacted_at');
            $table->foreignId('commission_id')->nullable()->constrained('commissions')->onDelete('set null')->after('parent_contact_shared_at');
            $table->text('admin_notes')->nullable()->after('commission_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['commission_id']);
            $table->dropColumn([
                'sent_to_admin_at',
                'admin_contacted_at',
                'parent_contact_shared_at',
                'commission_id',
                'admin_notes'
            ]);
        });
    }
};
