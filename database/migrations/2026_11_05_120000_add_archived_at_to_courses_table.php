<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add an explicit archive marker without changing existing course data.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable()->after('is_published')->index();
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['archived_at']);
            $table->dropColumn('archived_at');
        });
    }
};
