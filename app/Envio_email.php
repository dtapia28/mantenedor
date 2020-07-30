<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Envio_email extends Model
{
    protected $table = 'envio_emails';

    /**
     * Fields that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['fecha', 'descripcion'];
}
