<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('prenom');
            $table->string('nom');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // Le "mot_de_passe_hash"
            $table->text('adresse_livraison')->nullable();
            $table->text('adresse_facturation')->nullable();
            $table->enum('role', ['client', 'admin'])->default('client');
            $table->string('refresh_token', 100)->nullable(); // Ajouté pour le refresh token
            $table->rememberToken();
            $table->timestamps(); // Crée date_creation et date_modification
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
