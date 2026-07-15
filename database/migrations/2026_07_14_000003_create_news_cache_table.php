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
        Schema::create('news_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('source')->nullable();
            $table->text('url')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->enum('sentiment', ['Positive', 'Neutral', 'Negative'])->default('Neutral');
            $table->double('sentiment_score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_cache');
    }
};
