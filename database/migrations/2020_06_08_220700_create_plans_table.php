<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreatePlansTable
 */
class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('text')->nullable();
            $table->unsignedInteger('lifetime');
            $table->unsignedInteger('price');
            $table->boolean('status')->default(false);
            $table->tinyInteger('weight')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
