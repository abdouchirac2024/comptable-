<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('numero_commande')->unique();
            $table->enum('statut', ['en_attente', 'payee', 'expediee', 'annulee'])->default('en_attente');
            $table->decimal('total_commande', 10, 2);
            $table->text('adresse_livraison_snapshot');
            $table->text('adresse_facturation_snapshot');
            $table->timestamps(); // Crée date_commande (created_at)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};