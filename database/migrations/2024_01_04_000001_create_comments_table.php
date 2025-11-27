<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('story_id')->constrained('stories')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->uuid('parent_id')->nullable();
            $table->text('body');
            $table->string('path', 255)->nullable();
            $table->unsignedInteger('depth')->default(0);
            $table->unsignedInteger('replies_count')->default(0);
            $table->unsignedInteger('likes_count')->default(0);
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Simple indexes only
            $table->index('story_id');
            $table->index('parent_id');
            $table->index('user_id');
            $table->index('created_at');
            
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('comments')
                  ->onDelete('cascade');
        });

        // Add prefix index for path column separately
        DB::statement('ALTER TABLE comments ADD INDEX comments_path_index (path(191))');
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};