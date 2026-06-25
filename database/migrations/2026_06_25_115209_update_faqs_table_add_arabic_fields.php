<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('f_a_q_s', function (Blueprint $table) {
            $table->renameColumn('question', 'question_en');
            $table->renameColumn('answer', 'answer_en');

            $table->text('question_ar')->after('question_en');
            $table->text('answer_ar')->after('answer_en');
        });
    }

    public function down(): void
    {
        Schema::table('f_a_q_s', function (Blueprint $table) {
            $table->renameColumn('question_en', 'question');
            $table->renameColumn('answer_en', 'answer');

            $table->dropColumn([
                'question_ar',
                'answer_ar',
            ]);
        });
    }
};
