<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('story_id')->constrained('stories')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'story_id']);
            $table->index('user_id');
            $table->index('story_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};





