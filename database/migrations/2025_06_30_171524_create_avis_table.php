<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('titre')->nullable();
            $table->string('titre_en')->nullable();
            $table->text('commentaire');
            $table->text('commentaire_en')->nullable();
            $table->boolean('est_approuve')->default(false);
            $table->tinyInteger('note')->unsigned(); // Pour une note de 1 à 5
            $table->timestamps(); // date_creation
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
