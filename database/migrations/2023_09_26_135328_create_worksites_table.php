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
        Schema::create('worksites', function (Blueprint $table) {
            $table->id();
            $table->longText('description');
            $table->unsignedBigInteger('cod');
            $table->string('address')->nullable()->default(null);
            $table->string('city')->nullable()->default(null);
            $table->string('zip_code')->nullable()->default(null);
            $table->string('province')->nullable()->default(null);
            $table->unsignedBigInteger('id_responsable');
            $table->integer('total_hours')->nullable()->default(null);
            $table->longText('notes')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('id_responsable')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worksites');
    }
};
