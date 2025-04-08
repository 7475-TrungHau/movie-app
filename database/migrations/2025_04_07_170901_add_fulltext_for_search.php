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

            $table->fullText(['name'], 'ft_movies_name');
            $table->fullText(['origin_name'], 'ft_movies_origin_name');
            $table->fullText(['genres'], 'ft_movies_genres');
            $table->fullText(['description', 'actor', 'director'], 'ft_movies_others');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropIndex('ft_movies_name');
            $table->dropIndex('ft_movies_origin_name');
            $table->dropIndex('ft_movies_genres');
            $table->dropIndex('ft_movies_others');
        });
    }
};
