<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimelineTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timeline_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('state')->nullable();
            $table->boolean('finished')->default(false);
            $table->timestamps();
        });

        Schema::create('timeline_participation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('history_id')->unsigned();
            $table->bigInteger('eventable_id')->unsigned()->index();
            $table->string('eventable_type');
            $table->timestamps();

            $table->unique(['history_id', 'eventable_id', 'eventable_type'], 'participation_index');

            $table->foreign('history_id')
                ->references('id')->on('timeline_histories')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('timeline_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('history_id')->unsigned();
            $table->bigInteger('participation_id')->unsigned();
            $table->string('title');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('history_id')
                ->references('id')->on('timeline_histories')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('participation_id')
                ->references('id')->on('timeline_participation')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timeline_events');
        Schema::dropIfExists('timeline_participation');
        Schema::dropIfExists('timeline_histories');
    }
}
