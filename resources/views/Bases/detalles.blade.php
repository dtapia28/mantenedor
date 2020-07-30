<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>@yield('titulo') - Mantenedor</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/estilos.css') }}" rel="stylesheet">
</head>

<body>

<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="/">Mantenedor</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav bd-navbar-nav flex-row">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/empresas') }}">Empresas</a>               
                </li>
                <li>
                     <a class="nav-link" href="{{ url('/priorities') }}">Prioridades</a>                	
                </li>
                <li>
                     <a class="nav-link" href="{{ url('/requerimientos') }}">Requerimientos</a>                	
                </li>
                <li>
                     <a class="nav-link" href="{{ url('/solicitantes') }}">Solicitantes</a>                 
                </li>
                <li>
                     <a class="nav-link" href="{{ url('/teams') }}">Teams</a>                 
                </li>
                <li>
                     <a class="nav-link" href="{{ url('/resolutors') }}">Resolutores</a>                
                </li>                                                                   
            </ul>
        </div>
    </nav>
</header>

<!-- Begin page content -->
<main role="main" class="container">
	<header>
		@yield('tituloRequerimiento')
	</header>
    <div class="row mt-3">
        <div id="contenedor" class="col-8">
        	<section id="articulos">
        		<article id="requerimiento">
            		@yield('requerimiento')        			
        		</article>
            </section>
            <aside>                
        		<section id="avances">
            		@yield('avances')        			
        		</section>
                <section id="anidado">
                    @yield('anidado')
                </section>
            </aside>    
        </div>        
    </div>
    <footer>
    	@yield('footerMain')
    </footer>    
</main>




<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>