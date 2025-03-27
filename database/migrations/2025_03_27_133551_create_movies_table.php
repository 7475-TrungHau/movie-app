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
        Schema::create('movies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug', 512)->unique();
            $table->string('name', 512);
            $table->text('genres')->nullable();
            $table->text('description')->nullable();
            $table->text('actor')->nullable();
            $table->text('director')->nullable();
            $table->integer('year')->nullable();
            $table->string('poster_url', 1000)->nullable();
            $table->string('trailer_url', 1000)->nullable()->comment('ULR trailer neu la series, neu la movie thi URL movie');
            $table->enum('type', ['movie', 'series']);
            $table->string('thumbnail_url', 1000)->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
