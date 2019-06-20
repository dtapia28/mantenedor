<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnidadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anidados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idRequerimientoBase')->unsigned();
            $table->foreign('idRequerimientoBase')->references('id')->on('requerimientos');
            $table->bigInteger('idRequerimientoAnexo')->unsigned();
            $table->foreign('idRequerimientoAnexo')->references('id')->on('requerimientos');                        
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
        Schema::dropIfExists('anidados');
    }
}
