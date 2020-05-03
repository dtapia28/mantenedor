<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';


   /**
     * Fields that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['rut','nombreEmpresa'];
}
