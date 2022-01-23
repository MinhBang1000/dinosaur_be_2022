<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDinosaurMesozoicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dinosaur_mesozoics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dinosaur_id');
            $table->unsignedBigInteger('mesozoic_id');
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
        Schema::dropIfExists('dinosaur_mesozoics');
    }
}
