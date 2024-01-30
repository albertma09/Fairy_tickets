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
        Schema::create('sessions', function (Blueprint $table) {
            // Id
            $table->id();

            //definicion de constraint BD
            $table->unsignedBigInteger('event_id');

            //atributos iniciales BD
            $table->date('date');
            $table->time('hour');
            $table->integer('session_capacity');
            $table->dateTime('online_sale_closure');
            $table->boolean('nominal_tickets');

            // Timestamps
            $table->timestamps();


            //definicion de foreign key
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
