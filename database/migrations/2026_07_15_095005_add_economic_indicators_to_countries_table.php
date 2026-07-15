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
        Schema::table('countries', function (Blueprint $table) {
            $table->double('gdp_growth')->default(0);
            $table->double('unemployment_rate')->default(0);
            $table->double('exports')->default(0);
            $table->double('imports')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn(['gdp_growth', 'unemployment_rate', 'exports', 'imports']);
        });
    }
};
