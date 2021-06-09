@extends('layout.private.registro')

@section('title', 'Registro Completado')

@section('content')
  <div class="page animsition vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
    <div class="page-content vertical-align-middle">
      <div class="brand">
        <img class="brand-img" src="/private/assets/images/Cabecera_verdeLogo1.png" alt="logo academia manu">
        <h1 class="brand-text">GesmeWeb</h1>
      </div>
      <div class="sweet-alert showSweetAlert visible">
        <div class="icon success animate" style="display: block;"> 
          <span class="line tip animateSuccessTip"></span> 
          <span class="line long animateSuccessLong"></span> 
          <div class="placeholder"></div> 
          <div class="fix"></div> 
        </div>
        <!-- <div class="icon custom" style="display: block; background-image: url(&quot;http://i.imgur.com/4NZ6uLY.jpg&quot;);width:80px; height:80px"></div> -->
        <h2>¡Registro Completado!</h2>
        <p class="lead text-muted" style="display: block;">Una vez el administrador te conceda acceso recibirás un email con tu contraseña para poder acceder!</p>
      </div>
      <footer class="page-copyright">
        <p>Web por <a href="http://www.policlinicoquirurgico.com/"><strong>Centro Policlínico Quirúrgico</strong></a></p>
        <p>© 2016. Todos los derechos reservados.</p>
      </footer>
    </div>
  </div>
@stop
@section('js', '/js/app/private/register.js')