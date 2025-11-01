<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('publication_id')->constrained('publications')->onDelete('cascade');
            $table->string('title');
            $table->text('excerpt');
            $table->string('image_url')->nullable();
            $table->string('substack_post_url');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at');
            $table->string('status')->default('active'); // active, expired, archived
            $table->integer('view_count')->default(0);
            $table->integer('click_count')->default(0);
            $table->integer('save_count')->default(0);
            $table->integer('share_count')->default(0);
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};





