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
        Schema::create('paiement_temp', function (Blueprint $table) {
            $table->id();
            $table->string('ref_devis')->nullable();
            $table->string('ref_paiement')->nullable();
            $table->date('date_paiement')->nullable();
            $table->decimal('montant', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiement_temp');
    }
};
