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
        Schema::table('movies', function (Blueprint $table) {
            $table->text('name')->change();
            $table->text('description')->nullable()->change();
            $table->text('genres')->nullable()->change();
            $table->text('actor')->nullable()->change();
            $table->text('director')->nullable()->change();

            $table->fullText(['name', 'description', 'genres', 'actor', 'director']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            //
        });
    }
};
