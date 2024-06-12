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
        Schema::create('estimate', function (Blueprint $table) {
            $table->id('id_estimate');
            $table->string('client_phone_number')->nullable();
            $table->date('start_date')->nullable();
            $table->integer('state')->nullable();
            $table->string('house_type_designation')->nullable();
            $table->decimal('duration', 8, 2)->nullable();
            $table->string('house_description')->nullable();
            $table->string('finish_type_designation')->nullable();
            $table->decimal('percent_increase', 8, 2)->nullable();
            $table->decimal('sum_amount_work', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimate');
    }
};
