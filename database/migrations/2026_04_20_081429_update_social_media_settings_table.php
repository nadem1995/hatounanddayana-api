<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('social_media_settings', function (Blueprint $table) {
            // Rename column
            $table->renameColumn('links', 'url');

            // Add new column
            $table->string('name')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('social_media_settings', function (Blueprint $table) {
            // Reverse changes
            $table->renameColumn('url', 'links');
            $table->dropColumn('name');
        });
    }
};
