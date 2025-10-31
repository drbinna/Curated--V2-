<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('story_categories', function (Blueprint $table) {
            $table->uuid('story_id');
            $table->uuid('category_id');
            $table->timestamps();

            $table->primary(['story_id', 'category_id']);
            $table->foreign('story_id')->references('id')->on('stories')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_categories');
    }
};



