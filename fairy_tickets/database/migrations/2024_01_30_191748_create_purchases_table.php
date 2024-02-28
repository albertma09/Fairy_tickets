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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            // definicion de atributo fk
            $table->unsignedBigInteger('session_id');

            // atributos iniciales BD
            $table->string('name');
            $table->string('dni', 9); // 8 Numeros y 1 letra
            $table->string('phone_number');
            $table->string('email');

            // timestamps
            $table->timestamps();

            //definición constrain FK
            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
