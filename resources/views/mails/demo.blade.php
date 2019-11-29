Hola <i>{{ $demo->receiver }}</i>,
<p>Te informamos que te ha sido asignado el siguiente requerimiento:</p>
 
<div>
<p><b>Requerimiento: {{ $demo->rqId2 }}</p>
<p><b>La solicitud es: {{ $demo->rqSol }}</p>

<a href="easytask.itconsultants.cl/laravel/public/requerimientos/{$demo->id}">Link al requerimiento</a>

Atte.

EasyTask	
</div>
