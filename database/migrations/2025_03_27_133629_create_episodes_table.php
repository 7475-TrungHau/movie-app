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
        Schema::create('episodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('video_url', 255);
            $table->dateTime('release_date')->nullable();
            $table->integer('episode_number')->unsigned(); // so tap
            $table->string('thumbnail_url', 1000)->nullable();
            $table->string('slug', 512)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
