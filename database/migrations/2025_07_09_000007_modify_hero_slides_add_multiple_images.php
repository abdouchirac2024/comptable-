<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->json('images')->nullable()->after('background_image'); // Images multiples
            $table->string('image_alt_text')->nullable()->after('images'); // Texte alternatif pour l'image principale
        });
    }

    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropColumn(['images', 'image_alt_text']);
        });
    }
}; 