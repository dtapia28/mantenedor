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
            $table->string('textoRequerimiento', 200);
            $table->date('fechaEmail');
            $table->date('fechaSolicitud');
            $table->date('fechaCierre');
            $table->date('fechaRealCierre');
            $table->integer('numeroCambios', false, false)->length(2);
            $table->integer('porcentajeEjecutado', false, false)->length(3);
            $table->string('cierre', 200);
            $table->bigInteger('idSolicitante')->unsigned();
            $table->foreign('idSolicitante')->references('id')->on('solicitantes');
            $table->bigInteger('idPrioridad')->unsigned();
            $table->foreign('idPrioridad')->references('id')->on('priorities');
            $table->bigInteger('idResolutor')->unsigned();
            $table->foreign('idResolutor')->references('id')->on('resolutors');
            $table->bigInteger('idEmpresa')->unsigned();
            $table->foreign('idEmpresa')->references('id')->on('empresas');
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
