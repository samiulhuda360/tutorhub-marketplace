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
        Schema::table('areas', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('areas')->onDelete('cascade');
            $table->text('seo_content')->nullable()->after('slug');
            $table->string('meta_title')->nullable()->after('seo_content');
            $table->text('meta_description')->nullable()->after('meta_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'seo_content', 'meta_title', 'meta_description']);
        });
    }
};
