<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('name', 'name_en');
            $table->string('name_ar')->after('name_en');
            $table->dropColumn('description');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('name_en', 'name');
            $table->text('description')->nullable();
            $table->dropColumn('name_ar');
        });
    }
};
