@extends('layout.login')

@section('title', 'Acceso')

@section('content')
  <div class="page animsition vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
    <div class="page-content vertical-align-middle">
      <div class="brand">
        <img class="brand-img" src="/private/assets/images/Cabecera_verdeLogo1.png" alt="logo CPQ">
      </div>
      <span class = "text-Corpotativo">gesmedWEB</span> 
      <div class="alert alert-alt alert-danger hide" role="alert">
        <h4 id='msgTit'></h4>
        <span id='msgTxt'></span>
      </div>
      <form id="flog" method="post" action="JavaScript:loginUsuario()">
        <p>Introduce tus datos de acceso</p>
        <div class="form-group">
          <label class="sr-only" for="email_log">Email</label>
          <input type="email" class="form-control" id="email_log" name="email" placeholder="Email">
        </div>
        <div class="form-group">
          <label class="sr-only" for="password_log">Contraseña</label>
          <input type="password" class="form-control" id="password_log" name="password" placeholder="Password">
        </div>
        
        <button type="submit" class="btn btn-warning btn-block">Entrar</button>
        <div class="form-group clearfix"></div>
        <div class="margin-top-10">
       		 <a class="pull-right" href="./recordar-password">¿Has olvidado la contraseña?</a>
        </div>
        <div class="form-group clearfix">
        
       			<span class="pull-left" >Date de alta <a href="./registro">aquí</a> </span>
      
          
          
        </div>
        <div class="">
          
        </div>
      </form>
      
      <form id="fselect" class="hide" method="post" action="JavaScript:accederEmpresa()">
        <p>Seleccione la empresa con la que quiere acceder</p>
        <div class="form-group">
          	<select class="form-control input-lg" id="empresaSel" name="empresaSel" >
                
        	</select>
        </div>
        
        
        <button type="submit" class="btn btn-warning btn-block">Entrar</button>
        <div class="form-group clearfix"></div>
        <div class="margin-top-10">
       		 <a class="pull-right" href="./recordar-password">¿Has olvidado la contraseña?</a>
        </div>
        <div class="form-group clearfix">
        
       			<span class="pull-left" >Date de alta <a href="./registro">aquí</a> </span>
      
          
          
        </div>
        <div class="">
          
        </div>
      </form>
      <!-- <div>
        <label>Name:</label>
        <input type="text" ng-model="yourName" placeholder="Enter a name here">
        <hr>
        <h1>Hello {{yourName}}!</h1> 
      </div> -->
      <footer class="page-copyright">
        <p>Web por <a href="https://www.policlinicoquirurgico.com/"><strong>Centro Policlínico Quirúrgico</strong></a></p>
        <p>© 2016. Todos los derechos reservados.</p>
      </footer>
    </div>
  </div>
@stop
@section('js', '/js/app/private/login.js')