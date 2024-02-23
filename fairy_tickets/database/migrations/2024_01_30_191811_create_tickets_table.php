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
            $table->unsignedBigInteger('ticket_type_id');

            // atributos iniciales BD
            $table->string('name')->nullable();
            $table->string('dni', 9)->nullable(); // 8 Numeros y 1 letra
            $table->string('phone_number')->nullable();
            $table->boolean('verified')->default(false);

            // timestamps
            $table->timestamps();

            // definicion constraint FK
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('ticket_type_id')->references('id')->on('ticket_types')->onDelete('cascade');
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
