<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avance extends Model
{
   /**
     * Fields that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['textAvance', 'fechaAvance', 'idRequerimiento'];
}
