@extends('layout.private.private')

@section('title', 'Usuarios')
@section('css', '/private/assets/css/pages/user.css')

@section('content')

<script type="text/javascript">
  var listaEmpresa = <?= json_encode($listaEmpresa) ?>;
</script>

<div class="page-user">
  <!-- Page -->
  <div class="page animsition">
    <div class="page-header">
      <h1 class="page-title">Usuarios</h1>
      <div class="page-header-actions">
        <ol class="breadcrumb">
          <li><a href="/private/index">CPQ</a></li>
          <li class="active">Usuarios</li>
        </ol>
      </div>
    </div>
    <div class="page-content">
      <div class="row">
        <div class="col-sm-6">
          <!-- Widget -->
          <div class="widget">
            <div class="widget-content widget-radius padding-30 bg-white clearfix">
              <div class="counter counter-md pull-left text-left">
                <div class="counter-number-group">
                  <span class="counter-number" id="numUsrAct"></span>
                  <span class="counter-number-related text-capitalize">Usuarios</span>
                </div>
                <div class="counter-label text-capitalize font-size-16">Activos</div>
              </div>
              <div class="pull-right white">
                <i class="icon icon-circle icon-2x wb-users bg-green-600" aria-hidden="true"></i>
              </div>
            </div>
          </div>
          <!-- End Widget -->
        </div>
        <div class="col-md-6">
          <!-- Widget -->
          <div class="widget">
            <div class="widget-content widget-radius padding-30 bg-white clearfix">
              <div class="counter counter-md pull-left text-left">
                <div class="counter-number-group">
                  <span class="counter-number" id="numUsrPen"></span>
                  <span class="counter-number-related text-capitalize">Usuarios</span>
                </div>
                <div class="counter-label text-capitalize font-size-16">Pendientes</div>
              </div>
              <div class="pull-right white">
                <i class="icon icon-circle icon-2x wb-users bg-orange-600" aria-hidden="true"></i>
              </div>
            </div>
          </div>
          <!-- End Widget -->
        </div>
      </div>
      <!-- Panel Basic -->
      <div class="panel">
        <header class="panel-heading">
          <div class="panel-actions"></div>
          <h3 class="panel-title">Listado de usuarios dados de alta en el sistema</h3>
        </header>
        <div class="panel-body user-list-wrap">
          <div class="filters">
            <div class="filter-title">
              <div class="checkbox-custom checkbox-primary">
                <input type="checkbox" id="verusrbaja" onchange="obtenerUsuarios()" />
                <label for="verusrbaja">Mostrar Dados de baja</label>
              </div>
            </div>
          </div>
          <div class="col-sm-12 text-right margin-bottom-20 hide" id="btnAltaUsr">
              <a class="btn btn-danger" onclick="initPantallaRegUsuarios()" data-toggle="modal" data-target="#gestionUsuarios" href="" aria-expanded="false" aria-controls="gestionUsuarios">
                Crear usuario
              </a>
          </div>
          <div class="clearfix"></div>
          <table id="tablaUsuarios" class="table table-hover dataTable table-striped width-full user-list">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>DNI</th>
                <th>Email</th>
                <th>Roles usuario</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>DNI</th>
                <th>Email</th>
                <th>Roles usuario</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </tfoot>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
      <!-- End Panel Basic -->
    </div>
  </div>
  <!-- End Page -->
  
  <!-- Modal -->
<div class="modal fade modal-3d-flip-horizontal" id="gestionUsuarios"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Formulario de Registo</h3>
      </div>
      <div class="modal-body">
        <form  method="POST" action=""  enctype="multipart/form-data" id="fpAlta" data-toggle="validator">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" id='id' name='id' >
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
            <div class="form-group" id="fgtipDoc">
              <label for="sexpac">Tipo Doc.</label>
              <select class="form-control input-lg" id="tipdoc" name="tipdoc" >
                <option value = "DNI">D.N.I</option>
                <option value = "NIE">N.I.E</option>
                <option value = "PAS">PASAPORTE</option>
              </select>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgDni">
              <label for="dniusr">Número Documento</label>
              <input type="text" class="form-control input-lg text-uppercase" id="dniusr" name="dniusr"  placeholder="Introduce DNI" data-plugin="formatter" data-pattern="[[99999999]]-[[a]]">
              <input type="text" class="form-control input-lg text-uppercase hide" id="nieusr" name="nieusr"  placeholder="Introduce NIE" data-plugin="formatter" data-pattern="[[a9999999]]-[[a]]">
              <input type="text" class="form-control input-lg text-uppercase hide" id="passusr" name="passusr"  placeholder="Introduce Pasaporter" data-plugin="formatter" data-pattern="[[***************]]">
            </div>
          </div>
          
          <div class="col-sm-3">
            <div class="form-group" id="fgFij">
              <label for="numtelfijusr">Teléfono 1</label>
              <input type="text" class="form-control input-lg" id="numtel1" name="numtel1" data-plugin="formatter" data-pattern="[[999]][[999]][[999]]" placeholder="Teléfono Fijo">
              <p class="help-block">123 123 123</p>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group" id="fgMvl">
              <label for="numtelmovusr">Teléfono 2</label>
              <input type="text" class="form-control input-lg" id="numtel2" name="numtel2" data-plugin="formatter" data-pattern="[[999]][[999]][[999]]" placeholder="Teléfono Movil">
              <p class="help-block">123 123 123</p>
            </div>
          </div>
          <div class="col-sm-6" >
            <div class="form-group" id="fgEma">
              <label for="emailusr">Email</label>
              <input type="email" class="form-control input-lg" id="emailusr" name="emailusr"  placeholder="Email">
              <p class="help-block">Introduce tu email de contacto. Lo necesitarás para acceder más tarde.</p>
            </div>
          </div>
      <div class="col-sm-6" >
      		<div class="form-group" id="fgRol">
	      		<label for="roles">Roles</label>
	       		<select id='sRoles' class="form-control" data-plugin="select2" multiple="multiple" data-placeholder="Roles:">
	               <optgroup id="roles" label="">
	               </optgroup>
	            </select>
            </div>
        </div>
         <div class="col-sm-6" >
      		<div class="form-group" id="fgRol">
	      		<label for="empresas">Empresas</label>
	       		<select id='sEmpresas' class="form-control" data-plugin="select2" multiple="multiple" data-placeholder="Empresas">
	               <optgroup id="empresas" label="">
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
         </div>
        
      </form>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick = 'limpiarPrivate()'>Cancelar</button>
        <button type="button" class="btn btn-success" onclick="guardarUsuario('S')">Guardar</button>
      </div>
    </div>
  </div>
  </div>
  <!-- End Modal -->
  
  
    <!-- Modal -->
<div class="modal fade modal-3d-flip-horizontal" id="gestionAgenda"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Configuración de Agenda</h3>
      </div>
      <div class="modal-body">
        <form  method="POST" action=""  enctype="multipart/form-data" id="fpAlta" data-toggle="validator">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" id='idusrConfig' name='idusrConfig' >
           <input type="hidden" id='idConfig' name='idConfig' >
           
           <div class="col-sm-12">
	      	<div class="checkbox-custom checkbox-primary">
				<input type="checkbox" id="swiCambia" value="S" onchange="gestionFormSwiCambia(this)" />
				<label for="swiCambia"><strong>Agenda indefinida o por defecto</strong></label>
			</div>
	         
	         
	         <div class="span5 col-md-5 " id="datepicker-container">
		         <div class="input-daterange input-group datepicker" >
				    <input type="text" class="input-sm form-control" id="fecIniCambia" name="start">
				    <span class="input-group-addon">hasta</span>
				    <input type="text" class="input-sm form-control" id="fecFinCambia" name="end">
				</div>
			</div>
          
			
			
			
	      </div>
           <div class="col-sm-9" >
      		<div class="form-group" id="fgRol">
	      		<label for="roles">Días de la semana</label>
	       		<select id='sDias' class="form-control" data-plugin="select2" multiple="multiple" data-placeholder="Días">
	               <optgroup id="roles" label="">
	               	<option value = "L">Lunes</option>
	               	<option value = "M">Martes</option>
	               	<option value = "X">Miercoles</option>
	               	<option value = "J">Jueves</option>
	               	<option value = "V">Viernes</option>
	               	<option value = "S">Sábado</option>
	               	<option value = "D">Domingo</option>
	               </optgroup>
	            </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group" id="fDurCon">
              <label for="sexpac">Duracion consulta</label>
              <select class="form-control" id="sDurCon" name="sexpac" >
                <option value = "">Duración</option>
				<option value = "1">1 minuto</option>
				<option value = "2">2 minutos</option>
                <option value = "5">5 minutos</option>
                <option value = "10">10 minutos</option>
                <option value = "15">15 minutos</option>
                <option value = "30">30 minutos</option>
                <option value = "45">45 minutos</option>
                <option value = "60">60 minutos</option>
              </select>
            </div>
          </div>
          
          	<div class="col-sm-4">
	            <div class="form-group">
	              <label for="sexpac">Empresa</label>
	              <select class="form-control sEmpresa" id="sEmpresa" >
	              </select>
	            </div>
	        </div>
	        <div class="col-sm-2">
	            <div class="form-group">
	              <label for="sexpac">Hora Inicio</label>
	              <select class="form-control sHora" id="sHoraIni" >
	              </select>
	            </div>
	          </div>
	          <div class="col-sm-2">
	            <div class="form-group">
	              <label for="sexpac">Minutos inicio</label>
	              <select class="form-control sMinutos" id="sMinIni" >
	              </select>
	            </div>
	          </div>
	          
	          <div class="col-sm-2">
	            <div class="form-group">
	              <label for="sexpac">Hora Fin</label>
	              <select class="form-control sHora" id="sHoraFin" >
	              </select>
	            </div>
	          </div>
	          <div class="col-sm-2">
	            <div class="form-group">
	              <label for="sexpac">Minutos Fin</label>
	              <select class="form-control sMinutos" id="sMinFin" >
	              </select>
	            </div>
	          </div>
          
          <div class="col-sm-12" id="btnAddAgenda">
            <div class="form-group">
              <button type="button" class="btn btn-default" onclick = 'limpiarFormAgenda()'>Cancelar</button>
        	  <button type="button" class="btn btn-success" onclick="guardarAgenda('S')">Guardar</button>
            </div>
          </div>
          <div class="col-sm-12 hide" id="btnModAgenda">
            <div class="form-group" >
              <button type="button" class="btn btn-default" onclick = 'cancelaEdicion()'>Cancelar Modificar</button>
        	  <button type="button" class="btn btn-warning" onclick="guardarAgenda('S')">Modificar</button>
            </div>
          </div>
        	 
      	</form>    
      </div>
      <div class="clearfix"></div>
      
      <div class="col-sm-12">
          <table id="tablaAgenda" class="table table-hover dataTable table-striped width-full user-list">
            <thead>
              <tr>
                <th>Días de la semana</th>
                <th>Vigencia</th>
                <th>Empresa</th>
                <th>Hora inicio</th>
                <th>Hora Fin</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Días de la semana</th>
                <th>Vigencia</th>
                <th>Empresa</th>
                <th>Hora inicio</th>
                <th>Hora Fin</th>
                <th>Acciones</th>
              </tr>
            </tfoot>
            <tbody>
            </tbody>
          </table>
          </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
  </div>
  <!-- End Modal -->

@stop
@section('js', '/js/app/private/usuariosRegistrados.js')
@section('js1', '/js/app/private/register.js')
