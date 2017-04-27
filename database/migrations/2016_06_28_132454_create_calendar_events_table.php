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
        Schema::create('eventplanner_calendarevents', function (Blueprint $table) {
            $table->increments('id');            
            $table->dateTime('start_date')->comment('The start datetime of the event');
            $table->dateTime('end_date')->comment('The end datetime of the event');            
            $table->string('type', 30)->comment('The user-provided type of event (birthday, wedding, etc.)');
            $table->string('name')->comment('The name of the event');
            $table->string('host')->nullable()->comment('Event host (could be an individual’s name or an organization)');
            $table->text('description')->nullable()->comment('A description for the event');
            $table->text('guest_list')->nullable()->comment('A JSON-encoded list of guests');
            $table->string('location')->comment('The event location');
            $table->text('guest_message')->nullable()->comment('A message for the guests');
            
            /*
             * A users can have many calendar events
             */
            $table->integer('user_id')->unsigned()->comment('The ID of the user that owns this event');
            $table->foreign('user_id')->references('id')->on('eventplanner_users')->onDelete('cascade')->onUpdate('cascade');
            
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
        Schema::drop('eventplanner_calendarevents');
    }
}
