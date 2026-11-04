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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            $table->string('title');                          // Titre du cours
        $table->text('description')->nullable();          // Description (facultative)
        $table->string('thumbnail')->nullable();          // Image de couverture (facultative)
        $table->decimal('price', 8, 2)->default(0);       // Prix (ex: 29.99)
        $table->boolean('is_published')->default(false);  // Publié ou brouillon ?

        // Clés étrangères — relient courses à users et categories
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('category_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
