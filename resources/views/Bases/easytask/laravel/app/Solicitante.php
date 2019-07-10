<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    protected $table = 'solicitantes';


   /**
     * Fields that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['nombreSolicitante'];
}
