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
        Schema::create('events', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();

            //definicion de constraint BD
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('location_id');
    
            //atributos iniciales BD
            $table->string('name');
            $table->text('description');
            $table->float('price');
            $table->date('date');
            $table->time('hour');


            $table->timestamps();

            //definicion de foreign key
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
