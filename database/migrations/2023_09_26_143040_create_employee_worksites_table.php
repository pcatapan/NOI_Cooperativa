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
        Schema::create('employee_worksites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_employee');
            $table->unsignedBigInteger('id_worksite');
            $table->timestamps();

            $table->foreign('id_employee')->references('id')->on('employees');
            $table->foreign('id_worksite')->references('id')->on('worksites');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_worksites');
    }
};
