<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id2', 15);
            $table->string('textoTarea', 200);
            $table->datetime('fechaSolicitud');
            $table->datetime('fechaCierre');             
            $table->boolean('estado')->default(1); 
            $table->bigInteger('idRequerimiento')->unsigned();
            $table->foreign('idRequerimiento')->references('id')->on('requerimientos');
            $table->bigInteger('resolutor')->unsigned();
            $table->foreign('resolutor')->references('id')->on('resolutors');            
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
        Schema::dropIfExists('tareas');
    }
}
