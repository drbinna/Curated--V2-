<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publication_follows', function (Blueprint $table) {
            $table->uuid('publication_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->primary(['publication_id', 'user_id']);
            $table->foreign('publication_id')->references('id')->on('publications')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publication_follows');
    }
};






