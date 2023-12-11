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
        Schema::create('worksite_holiday', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worksite_id')->constrained();
            $table->foreignId('holiday_id')->constrained();

            $table->foreign('worksite_id')->references('id')->on('worksites');
            $table->foreign('holiday_id')->references('id')->on('holidays');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worksite_holiday');
    }
};
