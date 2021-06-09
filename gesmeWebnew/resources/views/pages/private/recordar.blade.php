@extends('layout.login')

@section('title', 'Acceso')

@section('content')
  <div class="page animsition vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
    <div class="page-content vertical-align-middle">
      <div class="brand">
        <img class="brand-img" src="/private/assets/images/Cabecera_verdeLogo1.png" alt="logo academia manu">
        <h2 class="brand-text">GesmeWeb</h2>
        <h2 class="page-title">¿Has olvidado tu contraseña?</h2>
        <p>Introduce tu email para enviarte una contraeña nueva</p>
      </div>
      <div class="alert alert-alt alert-danger hide" role="alert">
        <h4 id='msgTit'>Error al acceder</h4>
        <span id='msgTxt'></span>
      </div>
      <form method="post" action="JavaScript:restablecerContrasena()">
        <div class="form-group">
          <label class="sr-only" for="email_log">Email</label>
          <input type="email" class="form-control" id="email_log" name="email" placeholder="Email">
        </div>
        <button type="submit" class="btn btn-warning btn-block" >Reestablecer contraseña</button>
        <div class="margin-top-40">
          <strong class="font-size-18">¿Recuerdas tu contraseña?</strong> <br> 
          Entra ahora a gesmeWeb <a href="/">aquí</a>
        </div>
      </form>
      <!-- <div>
        <label>Name:</label>
        <input type="text" ng-model="yourName" placeholder="Enter a name here">
        <hr>
        <h1>Hello {{yourName}}!</h1> 
      </div> -->
     <footer class="page-copyright">
        <p>Web por <a href="http://www.policlinicoquirurgico.com/"><strong>Centro Policlínico Quirúrgico</strong></a></p>
        <p>© 2016. Todos los derechos reservados.</p>
      </footer>
    </div>
  </div>
@stop
@section('js', '/js/app/private/login.js')