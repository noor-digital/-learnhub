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
    Schema::create('quiz_results', function (Blueprint $table) {
        $table->id();
        $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->unsignedTinyInteger('score');       // score en %
        $table->boolean('passed')->default(false);
        $table->unsignedTinyInteger('attempts')->default(1);
        $table->timestamp('completed_at')->useCurrent();
        $table->timestamps();
        // Un résultat par user par quiz (on met à jour si nouvelle tentative)
        $table->unique(['quiz_id', 'user_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_results');
    }
};
