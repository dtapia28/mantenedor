<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requerimiento extends Model
{
       /**
     * Fields that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['textoRequerimiento', 'fechaEmail', 'fechaSolicitud',
							'fechaCierre', 'fechaRealCierre', 'numeroCambios',
							'porcentajeEjecutado', 'cierre', 'idSolicitante',
							'idPrioridad', 'resolutor', 'rutEmpresa', 'estado', 'id2', 'comentario', 'gestor', 'aprobacion', 'rechazo'];
}
