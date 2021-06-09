@extends('layout.private.registro')

@section('title', 'Registro')

@section('content')
<div class="loader-wrap hide">
  <div class="loader-circle loader vertical-align-middle" data-type="default"></div>
</div>

  <div class="page animsition vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
    <div class="page-content vertical-align-middle">
      <div class="brand">
        <img class="brand-img" src="/private/assets/images/Cabecera_verdeLogo1.png" alt="logo GesmeWeb">
        <h1 class="brand-text">GesmeWeb</h1>
      </div>
      <div class="alert alert-alt alert-danger hide" role="alert" id='msgInfo'>
       
      </div>
     <form  method="POST" action=""  enctype="multipart/form-data" id="fpAlta" data-toggle="validator">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" id='id' name='id' >
          <div class="text-center" >
            <h2 class="form-title">Formulario de Registo</h2>
          </div>
        <div class="row text-center">
          <div class="col-sm-6">
            <div class="form-group" id="fgNombre">
              <label for="nomusr">Nombre</label>
              <input type="text" class="form-control input-lg" id="nomusr" name="nomusr" placeholder="Nombre" required>
              <p class="help-block">Introduce tu nombre</p>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgSurName">
              <label for="apusr">Apellidos</label>
              <input type="text" class="form-control input-lg" id="apusr" name="apusr"  placeholder="Apellidos">
              <p class="help-block">Introduce tus apellidos</p>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgDni">
              <label for="dniusr">DNI</label>
              <input type="text" class="form-control input-lg text-uppercase" id="dniusr" name="dniusr"  placeholder="Dni" data-plugin="formatter" data-pattern="[[99999999]]-[[a]]">
              <p class="help-block">Introduce tu DNI</p>
            </div>
          </div>
          
          <div class="col-sm-6">
            <div class="form-group" id="fgFij">
              <label for="numtelfijusr">Teléfono 1</label>
              <input type="text" class="form-control input-lg" id="numtel1" name="numtel1" data-plugin="formatter" data-pattern="[[999]][[999]][[999]]" placeholder="Teléfono Fijo">
              <p class="help-block">123 123 123</p>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgMvl">
              <label for="numtelmovusr">Teléfono 2</label>
              <input type="text" class="form-control input-lg" id="numtel2" name="numtel2" data-plugin="formatter" data-pattern="[[999]][[999]][[999]]" placeholder="Teléfono Movil">
              <p class="help-block">123 123 123</p>
            </div>
          </div>
          <div class="col-sm-6" >
            <div class="form-group" id="fgEma">
              <label for="emailusr">Email</label>
              <input type="email" data-plugin="formatter"   class="form-control input-lg" id="emailusr" name="emailusr"  placeholder="Email">
              <p class="help-block">Introduce tu email de contacto. Lo necesitarás para acceder más tarde.</p>
            </div>
          </div>
      <div class="col-sm-12" >
      		<div class="form-group" id="fgRol">
	      		<label for="roles">Roles</label>
	       		<select id='sRoles' class="form-control" data-plugin="select2" multiple="multiple" data-placeholder="Roles:">
	               <optgroup id="roles" label="">
	               </optgroup>
	            </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group hide" id="fgColeg">
              <label for="colegiado">Número colegiado</label>
              <input type="text" class="form-control input-lg" id="colegiado" name="colegiado" placeholder="Núm. Colegiado">
              <p class="help-block">Introduce número de colegiado</p>
            </div>
          </div>
        <div class="col-sm-6" >
        	<div class="form-group hide" id="fgEsp">
      		<label for="emailusr">Especialidades</label>
       		<select id='sEspecialidad' class="form-control" data-plugin="select2" multiple="multiple" data-placeholder="Especialidades:">
               <optgroup id="especialidad" label="">
               </optgroup>
            </select>
            </div>
        </div>
          <div class="col-sm-12 margin-top-40 text-right">
            <button type="button" onclick="guardarUsuario()" class="btn btn-primary btn-huge">Registrarme</button>
          </div>
        </div>
        <div class="margin-top-20 text-right">
          <strong class="font-size-20">¿Ya estás registrado?</strong> <br> 
          Accede al sistema desde <a href="/">aquí</a>
        </div>
        
      </form>      
      <footer class="page-copyright">
        <p>Web por <a href="https://www.policlinicoquirurgico.com/"><strong>Centro Policlínico Quirúrgico</strong></a></p>
        <p>© 2016. Todos los derechos reservados.</p>
      </footer>
    </div>
  </div>
@stop
@section('js', '/js/app/private/register.js')