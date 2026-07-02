<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Rename existing columns
            $table->renameColumn('name', 'name_ar');
            $table->renameColumn('description', 'description_ar');

            // Add English columns
            $table->string('name_en')->after('name_ar');
            $table->text('description_en')->nullable()->after('description_ar');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            // Rename existing column
            $table->renameColumn('color_name', 'color_name_ar');

            // Add English column
            $table->string('color_name_en')->after('color_name_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'description_en']);

            $table->renameColumn('name_ar', 'name');
            $table->renameColumn('description_ar', 'description');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('color_name_en');

            $table->renameColumn('color_name_ar', 'color_name');
        });
    }
};
