<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role_User extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'role_user';

    /**
     * Fields that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'user_id'];
}
