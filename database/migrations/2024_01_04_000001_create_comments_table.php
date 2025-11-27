<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('story_id')->constrained('stories')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->uuid('parent_id')->nullable(); // NULL = top-level comment
            $table->text('body');
            $table->string('path', 1000)->nullable(); // Materialized path for efficient queries
            $table->unsignedInteger('depth')->default(0); // Nesting level
            $table->unsignedInteger('replies_count')->default(0); // Denormalized counter
            $table->unsignedInteger('likes_count')->default(0);
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Keep structure intact when deleted

            // Indexes for common queries
            $table->index(['story_id', 'created_at']);
            $table->index(['story_id', 'path']);
            $table->index('parent_id');
            $table->index('user_id');
            
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('comments')
                  ->onDelete('cascade');
        });

        // Optional: Comment likes table
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('comment_id')->constrained('comments')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['comment_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_likes');
        Schema::dropIfExists('comments');
    }
};