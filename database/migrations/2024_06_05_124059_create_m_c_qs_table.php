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
        Schema::create('m_c_qs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('board_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('chapter_id');
            $table->unsignedBigInteger('topic_id');
            $table->longText('statement');
            $table->longText('optionA');
            $table->longText('optionB');
            $table->longText('optionC');
            $table->longText('optionD');
            $table->string('solution_link_english');
            $table->string('solution_link_urdu');
            $table->timestamps();

            // Optionally add foreign key constraints
            // $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
            // $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            // $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
            // $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_c_qs');
    }
};
