<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Public profile links use a short code (T-1001) instead of the user id,
 * so the URL does not reveal how many accounts exist.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tutor_code', 16)->nullable()->unique()->after('role');
        });

        DB::table('users')->where('role', 'tutor')->orderBy('id')->get(['id'])
            ->each(fn ($u) => DB::table('users')->where('id', $u->id)->update(['tutor_code' => 'T-'.(1000 + $u->id)]));
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['tutor_code']);
            $table->dropColumn('tutor_code');
        });
    }
};
