<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('story_views', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('story_id')->constrained('stories')->onDelete('cascade');
            $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('story_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_views');
    }
};


