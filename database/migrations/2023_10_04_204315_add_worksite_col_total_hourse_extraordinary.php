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
        Schema::table('worksites', function (Blueprint $table) {
            $table->unsignedBigInteger('total_hours_extraordinary')->nullable()->after('total_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('worksites', function (Blueprint $table) {
            $table->dropColumn(['total_hours_extraordinary']);
        });
    }
};
