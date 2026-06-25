<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {

            // change message → message_en
            $table->renameColumn('message', 'message_en');

            // remove source column
            $table->dropColumn('source');

            // add Arabic message
            $table->text('message_ar');
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {

            // rollback
            $table->renameColumn('message_en', 'message');

            $table->string('source')->nullable();

            $table->dropColumn('message_ar');
        });
    }
};
