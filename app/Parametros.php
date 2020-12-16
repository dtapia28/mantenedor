<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parametros extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'parametros';
    /**
     * Fields that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['rutEmpresa', 'emailSupervisor', 'idColor'];
}
