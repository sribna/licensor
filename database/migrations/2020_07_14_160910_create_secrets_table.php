<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateSecretsTable
 */
class CreateSecretsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('secrets', function (Blueprint $table) {
            $table->string('id')->unique()->primary();
            $table->boolean('status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('secrets');
    }
}
