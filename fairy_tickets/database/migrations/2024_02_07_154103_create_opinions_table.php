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
        Schema::create('opinions', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('purchase_id');

            // atributos iniciales BD
            $table->string('name');
            $table->unsignedSmallInteger('star_rating')->unsigned()->between(1,5);
            $table->unsignedSmallInteger('face_rating')->unsigned()->between(1,3);
            $table->string('title');
            $table->text('comment');

            // timestamps
            $table->timestamps();

            // definicion constraint FK
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opinions');
    }
};
