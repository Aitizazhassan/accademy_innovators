<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {

        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->softDeletes(); // for deleted_at column
            $table->string('status')->nullable();
            $table->timestamps(); // for created_at and updated_at columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('country');

    }


};
