<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_c_qs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('board_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('chapter_id')->nullable();
            $table->unsignedBigInteger('topic_id')->nullable();
            $table->longText('statement');
            $table->longText('optionA');
            $table->longText('optionB');
            $table->longText('optionC');
            $table->longText('optionD');
            $table->string('solution_link_english')->nullable();
            $table->string('solution_link_urdu')->nullable();
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
