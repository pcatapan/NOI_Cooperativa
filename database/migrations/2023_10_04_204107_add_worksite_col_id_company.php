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
            $table->unsignedBigInteger('id_company')->nullable()->after('id_responsable');

            $table->foreign('id_company')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('worksites', function (Blueprint $table) {
            $table->dropForeign(['id_company']);
            $table->dropColumn(['id_company']);
        });
    }
};
