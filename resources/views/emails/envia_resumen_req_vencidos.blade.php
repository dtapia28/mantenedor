<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet">
<div style="background-color: #E7EDF5; padding: 10px; font-family: 'Nunito', sans-serif;">
    <div style=" opacity:0.3; padding: 10px; text-align: center;">
        <a href="https://app.kinchika.com/login"><img src="{{ asset('img/logo-blue.png') }}" alt="logo"></a>
    </div>

    <div style="padding: 10px; color: black;" class="contenedor">
        <h1 style="text-align: center;">Informe requerimientos por vencer</h1>
        <hr>
        <h3><i>{{$valores['nombre_resolutor']}}</i></h3>
        <h2>Estos son los requerimientos que vencerán esta semana:</h2>

        @if(count($valores["vencidos_lunes"])>0)
        <div>
            <h2>Vencidos del lunes</h2>
            <p>
                <ul>
                @for ($i=0; $i<count((array)$valores["vencidos_lunes"]); $i++)              
                    <li>{{$valores['vencidos_lunes'][$i]['id2']}}</li>
                @endfor
                </ul>
            </p>
        </div>
        @endif
        @if(count($valores["vencidos_martes"])>0)
        <div>
             <h2>Vencidos del martes</h2>
             <ul>
            @for ($i=0; $i<count((array)$valores["vencidos_martes"]); $i++)         
                <li>{{$valores['vencidos_martes'][$i]['id2']}}</li>
            @endfor
             </ul>
        </div>
        @endif
        @if(count($valores["vencidos_miercoles"])>0)
        <div>
            <h2>Vencidos del miercoles</h2>
            <ul>
            @for ($i=0; $i<count((array)$valores["vencidos_miercoles"]); $i++)
                <li>{{$valores['vencidos_miercoles'][$i]['id2']}}</li>
            @endfor
            </ul>
        </div>
        @endif
        @if(count($valores["vencidos_jueves"])>0)
        <div>
            <h2>Vencidos del jueves</h2>
            <ul>
            @for ($i=0; $i<count((array)$valores["vencidos_jueves"]); $i++)             
                <li>{{$valores['vencidos_jueves'][$i]['id2']}}</li>
            @endfor
            </ul>
        </div>
        @endif
        @if(count($valores["vencidos_viernes"])>0)
        <div>
            <h2>Vencidos del viernes</h2>
            <ul>
            @for ($i=0; $i<count((array)$valores["vencidos_viernes"]); $i++)

                <li><a href="htpps://app.kinchika.cl/requerimientos/{{$valores['vencidos_viernes'][$i]['id']}}">{{$valores['vencidos_viernes'][$i]['id2']}}"</a></li>
            @endfor
            </ul>
        </div>
    </div>
</div>
<style>
    .contenedor {
        display: grid;
    }
    
    .contenedor div {
        border: 10px solid blue;
        background: azure;
    }
</style>
@endif
