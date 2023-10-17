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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_employee');
            $table->unsignedBigInteger('id_worksite');
            $table->date('date');
            $table->time('time_entry');
            $table->time('time_exit');
            $table->time('time_entry_extraordinary')->nullable();
            $table->time('time_exit_extraordinary')->nullable();
            $table->decimal('hours_worked', 5, 2);
            $table->decimal('hours_extraordinary', 5, 2)->nullable();
            $table->longText('motivation_extraordinary')->nullable();
            $table->boolean('absent')->default(false);
            $table->enum('type_absent', ['holidays', 'permit', 'illness', 'maternity', 'paternity', 'injury', 'other'])->nullable();
            $table->longText('note')->nullable();

            $table->foreign('id_employee')->references('id')->on('employees');
            $table->foreign('id_worksite')->references('id')->on('worksites');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
