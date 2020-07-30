<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model{    
    /**     * The database table used by the model.     *     * @var string     */    
    protected $table = 'tareas';    
    /**     * Fields that can be mass assigned.     *     * @var array     */    
    protected $fillable = ['id2', 'textoTarea', 'fechaSolicitud', 'fechaCierre', 'estado',     					
                          'idRequerimiento', 'resolutor','titulo_tarea'];		
    
}