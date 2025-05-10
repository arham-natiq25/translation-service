<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add additional indexes for performance
        Schema::table('translations', function (Blueprint $table) {
            // Index for faster joins
            $table->index('language_id');
            $table->index('translation_key_id');
        });

        Schema::table('translation_tag', function (Blueprint $table) {
            // Indexes for faster tag lookups
            $table->index('tag_id');
            $table->index('translation_key_id');
        });
    }

    public function down(): void
    {
        Schema::table('translations', function (Blueprint $table) {
            $table->dropIndex(['language_id']);
            $table->dropIndex(['translation_key_id']);
        });

        Schema::table('translation_tag', function (Blueprint $table) {
            $table->dropIndex(['tag_id']);
            $table->dropIndex(['translation_key_id']);
        });
    }
};
