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
    Schema::create('lessons', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_id')->constrained()->onDelete('cascade');
        $table->string('title');
        $table->string('video_url')->nullable();       // URL YouTube ou fichier
        $table->unsignedSmallInteger('duration')->default(0); // en minutes
        $table->unsignedSmallInteger('order')->default(0);    // ordre d'affichage
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('lessons');
}
};