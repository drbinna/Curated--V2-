<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if column exists before dropping
        if (Schema::hasColumn('stories', 'publication_id')) {
            // Get the foreign key constraint name
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'stories' 
                AND COLUMN_NAME = 'publication_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            // Drop foreign key if it exists
            if (!empty($foreignKeys)) {
                $constraintName = $foreignKeys[0]->CONSTRAINT_NAME;
                DB::statement("ALTER TABLE stories DROP FOREIGN KEY `{$constraintName}`");
            }

            // Drop the column
            Schema::table('stories', function (Blueprint $table) {
                $table->dropColumn('publication_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            if (!Schema::hasColumn('stories', 'publication_id')) {
                $table->uuid('publication_id')->nullable()->after('user_id');
            }
        });
    }
};

