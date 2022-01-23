<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMesozoicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mesozoics', function (Blueprint $table) {
            $table->id();
            $table->string('mesozonic_name_en');
            $table->string('mesozonic_name_vn');
            $table->integer('mesozonic_start');
            $table->integer('mesozonic_end');
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
        Schema::dropIfExists('mesozoics');
    }
}
