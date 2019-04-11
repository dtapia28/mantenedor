<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
   /**
     * Fields that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['namePriority'];
}
