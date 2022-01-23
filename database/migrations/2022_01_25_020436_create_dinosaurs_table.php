<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDinosaursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dinosaurs', function (Blueprint $table) {
            $table->id();
            $table->string('dinosaur_name_en');
            $table->string('dinosaur_name_vn');
            $table->string('author');
            $table->string('description_en');
            $table->string('description_vn');
            $table->unsignedBigInteger('diet_id');
            $table->unsignedBigInteger('category_id');
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
        Schema::dropIfExists('dinosaurs');
    }
}
