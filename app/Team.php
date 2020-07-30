<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';

       /**
     * Fields that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['nameTeam', 'rutEmpresa', 'id2'];
}
