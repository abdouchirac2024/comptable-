<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Auteur
            $table->string('titre');
            $table->string('titre_en')->nullable();
            $table->text('contenu');
            $table->text('contenu_en')->nullable();
            $table->string('meta_titre')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('slug')->unique();
            $table->timestamp('date_publication')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_blogs');
    }
};
