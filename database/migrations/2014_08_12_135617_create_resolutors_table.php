<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResolutorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resolutors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombreResolutor', 50)->unique();
            $table->string('rutEmpresa', 10);
            $table->foreign('rutEmpresa')->references('rut')->on('empresas');
            $table->bigInteger('idTeam')->unsigned();
            $table->foreign('idTeam')->references('id')->on('teams');
            $table->boolean('lider')->default(0);
            $table->bigInteger('idUser')->unsigned();
            $table->foreign('idUser')->references('id')->on('users');
            $table->string('email', 50);            
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
        Schema::dropIfExists('resolutors');
    }
}
