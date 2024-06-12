<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\crud\HouseType;
use App\Models\crud\WorkType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_house_type', function (Blueprint $table) {
            $table->id('id_work_house_type');
            $table->foreignIdFor(HouseType::class);
            $table->foreignIdFor(WorkType::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_house_type');
    }
};
