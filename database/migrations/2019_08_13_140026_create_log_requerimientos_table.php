<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogRequerimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_requerimientos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idRequerimiento')->unsigned();
            $table->foreign('idRequerimiento')->references('id')->on('requerimientos');
            $table->bigInteger('idUsuario')->unsigned();
            $table->foreign('idUsuario')->references('id')->on('users');
            $table->string('campo', 50);
            $table->string('tipo', 20);
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
        Schema::dropIfExists('log_requerimientos');
    }
}
