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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 5)->unique(); // ISO 2-letter country code
            $table->string('flag')->nullable();
            $table->string('capital')->nullable();
            $table->string('region')->nullable();
            $table->bigInteger('population')->default(0);
            $table->double('gdp')->default(0);
            $table->double('inflation')->default(0);
            $table->string('currency_code', 10)->nullable();
            $table->string('currency_name')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
