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
        Schema::table('presences', function (Blueprint $table) {
            $table->renameColumn('hours_worked', 'minutes_worked');
            $table->renameColumn('hours_extraordinary', 'minutes_extraordinary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->renameColumn('minutes_worked', 'hours_worked');
            $table->renameColumn('minutes_extraordinary', 'hours_extraordinary');
        });
    }
};
