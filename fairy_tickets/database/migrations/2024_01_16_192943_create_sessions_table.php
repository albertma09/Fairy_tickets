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
            // Determinamos el charset y el collation
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Id
            $table->id();

            //definicion de constraint BD
            $table->unsignedBigInteger('event_id');

            //atributos iniciales BD
            $table->date('date');
            $table->time('hour');
            $table->integer('session_capacity');

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
