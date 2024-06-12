<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\gestion_devis\Estimate;
use App\Models\crud\WorkType;
use App\Models\crud\Unit;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estimate_detail', function (Blueprint $table) {
            $table->id('id_estimate_detail');
            $table->foreignIdFor(Estimate::class)->nullable();
            $table->foreignIdFor(WorkType::class)->nullable();
            $table->string('code')->nullable();
            $table->string('work_detail_designation')->nullable();
            $table->foreignIdFor(Unit::class)->nullable();
            $table->decimal('unit_price', 8, 2)->nullable();
            $table->decimal('quantity', 8, 2)->nullable();
            $table->decimal('amount', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimate_detail');
    }
};
