<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFeaturePlanTable
 */
class CreateFeaturePlanTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('feature_plan', function (Blueprint $table) {

            $table->id('id');
            $table->string('feature_id');
            $table->unsignedBigInteger('plan_id');

            $table->foreign('plan_id')
                ->references('id')
                ->on('plans')
                ->onDelete('cascade');

            $table->foreign('feature_id')
                ->references('id')
                ->on('features')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('feature_plan');
    }
}
