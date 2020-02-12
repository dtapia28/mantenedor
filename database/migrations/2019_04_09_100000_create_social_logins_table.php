<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_logins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idUsers')->unsigned();
            $table->foreign('idUsers')->references('id')->on('users');
            $table->string('provider', 32);
            $table->text('social_id');
            $table->timestamps();
            
            $table->softDeletes();
        });
        
        Schema::table('users', function (Blueprint $table){
            $table->string('token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_logins');
    }
}

