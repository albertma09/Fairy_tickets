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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            // definicion de atributo fk
            $table->unsignedBigInteger('purchase_id');

            // atributos iniciales BD
            $table->string('name');
            $table->string('dni', 9); // 8 Numeros y 1 letra
            $table->string('phone_number');

            // timestamps
            $table->timestamps();

            // definicion constraint FK
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
