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
        Schema::create('maison_travaux_temp', function (Blueprint $table) {
            $table->id();
            $table->string('type_maison')->nullable();
            $table->string('description')->nullable();
            $table->decimal('surface', 10, 2)->nullable();
            $table->string('code_travaux')->nullable();
            $table->string('type_travaux')->nullable();
            $table->string('unite')->nullable();
            $table->decimal('prix_unitaire', 10, 2)->nullable();
            $table->decimal('quantite', 10, 2)->nullable();
            $table->decimal('duree_travaux', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maison_travaux_temp');
    }
};
