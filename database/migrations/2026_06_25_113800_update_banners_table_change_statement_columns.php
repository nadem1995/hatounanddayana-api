<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->renameColumn('statement', 'statement_en');
            $table->text('statement_ar');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->renameColumn('statement_en', 'statement');
            $table->dropColumn('statement_ar');
        });
    }
};
