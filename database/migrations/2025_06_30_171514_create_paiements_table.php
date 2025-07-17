<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->string('methode')->nullable();
            $table->string('transaction_id')->nullable();
            $table->decimal('montant', 10, 2);
            $table->enum('statut', ['succes', 'echec']);
            $table->timestamps(); // Crée date_paiement (created_at)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};