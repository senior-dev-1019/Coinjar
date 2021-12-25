<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sounds', function (Blueprint $table) {
            $table->id();
            $table->Integer('userId');
            $table->string('sound1');
            $table->string('sound2');
            $table->string('sound3');
            $table->string('sound4');
            $table->string('sound5');
            $table->string('sound1_check');
            $table->string('sound2_check');
            $table->string('sound3_check');
            $table->string('sound4_check');
            $table->string('sound5_check');
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
        Schema::dropIfExists('sounds');
    }
}
