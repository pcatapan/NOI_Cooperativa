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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('number_serial')->nullable()->default(null);
            $table->string('fiscal_code');
            $table->string('inps_number')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->string('city')->nullable()->default(null);
            $table->string('zip_code')->nullable()->default(null);
            $table->string('province')->nullable()->default(null);
            $table->string('phone')->nullable()->default(null);
            $table->string('notes')->nullable()->default(null);
            $table->date('date_of_hiring')->nullable()->default(null);
            $table->date('date_of_resignation')->nullable()->default(null);
            $table->string('job')->nullable()->default(null);
            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
