<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\crud\WorkType;
use App\Models\crud\Unit;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_work_type', function (Blueprint $table) {
            $table->id('id_detail_work_type');
            $table->foreignIdFor(WorkType::class)->nullable();
            $table->string('code')->nullable();
            $table->string('designation')->nullable();
            $table->foreignIdFor(Unit::class)->nullable();
            $table->decimal('unit_price', 8, 2)->nullable();
            $table->decimal('quantity', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_work_type');
    }
};
