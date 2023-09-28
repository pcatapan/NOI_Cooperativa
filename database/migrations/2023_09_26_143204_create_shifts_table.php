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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_employee');
            $table->unsignedBigInteger('id_worksite');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->boolean('validated')->default(false);
            $table->timestamps();

            $table->foreign('id_employee')->references('id')->on('users');
            $table->foreign('id_worksite')->references('id')->on('worksites');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
