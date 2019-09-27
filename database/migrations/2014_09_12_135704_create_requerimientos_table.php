<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequerimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requerimientos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id2', 15);
            $table->string('textoRequerimiento', 200);
            $table->datetime('fechaEmail');
            $table->datetime('fechaSolicitud');
            $table->datetime('fechaCierre');
            $table->datetime('fechaRealCierre')->nullable();
            $table->integer('numeroCambios', false, false)->length(2)->default(0);
            $table->integer('porcentajeEjecutado', false, false)->length(3)->nullable();
            $table->string('cierre', 200)->nullable();
            $table->boolean('estado')->default(1);
            $table->bigInteger('idSolicitante')->unsigned();
            $table->foreign('idSolicitante')->references('id')->on('solicitantes');
            $table->bigInteger('idPrioridad')->unsigned();
            $table->foreign('idPrioridad')->references('id')->on('priorities');
            $table->bigInteger('resolutor')->unsigned();
            $table->foreign('resolutor')->references('id')->on('resolutors');
            $table->string('rutEmpresa', 10);
            $table->foreign('rutEmpresa')->references('rut')->on('empresas');
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
        Schema::dropIfExists('requerimientos');
    }
}
