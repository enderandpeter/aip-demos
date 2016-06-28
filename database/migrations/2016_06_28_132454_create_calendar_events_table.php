<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->increments('id');            
            $table->date('start_date')->comment('The start date of the event');
            $table->date('end_date')->comment('The end date of the event');
            $table->time('start_time')->nullable()->comment('The start time of the event, if set');
            $table->time('end_time')->nullable()->comment('The end time of the event, if set');
            $table->string('type', 30)->comment('The user-provided type of event (birthday, wedding, etc.)');
            $table->string('title')->comment('The name of the event');
            $table->text('description')->comment('A description for the event');
            $table->text('guest_list')->comment('A JSON-encoded list of guests, which may be empty');
            $table->string('location')->comment('A list of guests, if provided');
            $table->text('guest_message')->comment('A message for the guests, which may be empty');
            
            /*
             * A users can have many calendar events
             */
            $table->integer('user_id')->unsigned()->comment('The ID of the user that owns this event');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('calendar_events');
    }
}
