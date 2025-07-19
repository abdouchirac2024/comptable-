<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade');
            $table->string('nom');
            $table->string('nom_en')->nullable();
            $table->text('description_courte')->nullable();
            $table->text('description_courte_en')->nullable();
            $table->longText('description_longue')->nullable();
            $table->longText('description_longue_en')->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('est_en_vedette')->default(false);
            $table->decimal('prix', 10, 2);
            $table->string('reference_sku')->unique()->nullable();
            $table->string('slug')->unique();
            $table->timestamps(); // date_creation
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
