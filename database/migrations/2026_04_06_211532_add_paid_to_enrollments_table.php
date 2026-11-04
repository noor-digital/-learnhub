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
    Schema::table('enrollments', function (Blueprint $table) {
        // paid = a payé, free = cours gratuit, pending = en attente
        $table->enum('payment_status', ['free', 'paid', 'pending'])
              ->default('free')
              ->after('enrolled_at');
    });
}

public function down(): void
{
    Schema::table('enrollments', function (Blueprint $table) {
        $table->dropColumn('payment_status');
    });
}
};
