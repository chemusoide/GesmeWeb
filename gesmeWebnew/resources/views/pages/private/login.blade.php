@extends('layout.private.login')

@section('title', 'Acceso')

@section('content')
  <div class="page animsition vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
    <div class="page-content vertical-align-middle">
      <div class="brand">
        <img class="brand-img" src="/private/assets/images/logo-academia-manu-full@2x.png" alt="logo academia manu">
        <h2 class="brand-text">Academia Manu</h2>
      </div>
      <div class="alert alert-alt alert-danger hide" role="alert">
        <h4 id='msgTit'></h4>
        <span id='msgTxt'></span>
      </div>
      <form method="post" action="JavaScript:loginUsuario()">
        <p>Introduce tus datos de acceso</p>
        <div class="form-group">
          <label class="sr-only" for="email_log">Email</label>
          <input type="email" class="form-control" id="email_log" name="email" placeholder="Email">
        </div>
        <div class="form-group">
          <label class="sr-only" for="password_log">Contraseña</label>
          <input type="password" class="form-control" id="password_log" name="password" placeholder="Password">
        </div>
        <div class="form-group clearfix">
          <a class="pull-right" href="/private/recordar-password">¿Has olvidado la contraseña?</a>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        <div class="margin-top-40">
          <strong class="font-size-18">¿Aún no tienes cuenta?</strong> <br> 
          Date de alta ya en el nuevo curso <a href="/private/registro">aquí</a>
        </div>
      </form>
      <!-- <div>
        <label>Name:</label>
        <input type="text" ng-model="yourName" placeholder="Enter a name here">
        <hr>
        <h1>Hello {{yourName}}!</h1> 
      </div> -->
      <footer class="page-copyright">
        <p>Web por <a href="http://www.dpicode.com/"><strong>dpi</strong>code.com</a></p>
        <p>© 2016. Todos los derechos reservados.</p>
      </footer>
    </div>
  </div>
@stop
@section('js', '/js/app/private/login.js')