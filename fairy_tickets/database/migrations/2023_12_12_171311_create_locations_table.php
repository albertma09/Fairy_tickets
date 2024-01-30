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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();

            //atributos iniciales BD
            $table->string('name');
            $table->integer('capacity');
            $table->string('province');
            $table->string('city');
            $table->string('street');
            $table->string('number');
            $table->string('cp');

            // restricciones
            $table->unique(['name', 'street', 'number', 'cp']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
