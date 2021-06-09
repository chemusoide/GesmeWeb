@extends('layout.private.private')

@section('title', 'Usuarios')
@section('css', '/private/assets/css/pages/user.css')

@section('content')
<div class="page-user">
  <!-- Page -->
  <div class="page animsition">
    <div class="page-header">
      <h1 class="page-title">Pacientes</h1>
      <div class="page-header-actions">
        <ol class="breadcrumb">
          <li><a href="/private/index">CPQ</a></li>
          <li class="active">Pacientes</li>
        </ol>
      </div>
    </div>
    <div class="page-content">
      <!-- Panel Basic -->
      <div class="panel">
        <header class="panel-heading">
          <div class="panel-actions"></div>
          <h3 class="panel-title">Listado de pacientes</h3>
        </header>
        
        <div class="panel-body user-list-wrap">
          <div class="col-sm-12 text-right margin-bottom-20">
          	<div class="col-sm-2">
              <a class="btn btn-danger" onclick="initPantallaAltaModPacientes()" data-toggle="modal" data-target="#gestionPacientes" href="" aria-expanded="false" aria-controls="gestionPacientes">
                Crear Paciente
              </a>
            </div>
            <div class="col-sm-3">
              	<form action="./obtCitasMedDiario" method="get" id="fImpDoc" target="_blank">
					<div class="col-sm-12" id="divImpGrup" >
						<button type="submit" class="btn btn-success">Generar informe diario pacientes</button>
					</div>
				</form>
			</div>
			<div class="col-sm-2">
              <a class="btn btn-info" onclick="initPantallaCitasFinHoy()" data-toggle="modal" data-target="#citasFinHoy" href="" aria-expanded="false" aria-controls="citasFinHoy">
                Citas finalizadas hoy 
              </a>
            </div>
          </div>
          
          <div class="clearfix"></div>
          <div class="row text-left" id="formBusqPac">
	          <div class="col-sm-12">
					<h3 class="modal-title">Paciente</h3>
				</div>
				<div class="col-sm-4">
					<div class="form-group" id="fgNombreBusq">
						<label for="nompacBusq">Nombre</label>
						<input type="text" class="form-control input-lg" id="nompacBusq" name="nompacBusq" placeholder="Nombre">
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group" id="fgSurName1Busq">
						<label for="ap1pacBusq">Primer Apellido</label>
						<input type="text" class="form-control input-lg" id="ap1pacBusq" name="ap1pacBusq"  placeholder="Primer Apellido">
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group" id="fgSurName2Busq">
						<label for="ap2pacBusq">Segundo Apellido</label>
						<input type="text" class="form-control input-lg" id="ap2pacBusq" name="ap2pacBusq"  placeholder="Segundo Apellido">
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group" id="fgIdHistorialBusq">
						<label for="idHistorialBusq">Núm. Historial</label>
						<input type="number" class="form-control input-lg" id="idHistorialBusq" name="idHistorialBusq"  placeholder="Número Historia">
					</div>
				</div>
				 <div class="col-sm-4">
		            <div class="form-group" id="fgtipDocBusq">
		              <label for="tipdocBusq">Tipo Doc.</label>
		              <select class="form-control input-lg" id="tipdocBusq" name="tipdocBusq" >
		                <option value = "DNI">D.N.I</option>
		                <option value = "NIE">N.I.E</option>
		                <option value = "PAS">PASAPORTE</option>
		              </select>
		            </div>
		          </div>
		          <div class="col-sm-4">
		            <div class="form-group" id="fgDniBusq">
		              <label for="dniusr">Número Documento</label>
		              <input type="text" class="form-control input-lg text-uppercase" id="dniusrBusq" name="dniusrBusq"  placeholder="Introduce DNI" data-plugin="formatter" data-pattern="[[99999999]]-[[a]]">
		              <input type="text" class="form-control input-lg text-uppercase hide" id="nieusrBusq" name="nieusrBusq"  placeholder="Introduce NIE" data-plugin="formatter" data-pattern="[[a9999999]]-[[a]]">
		              <input type="text" class="form-control input-lg text-uppercase hide" id="passusrBusq" name="passusrBusq"  placeholder="Introduce Pasaporter" data-plugin="formatter" data-pattern="[[***************]]">
		            </div>
		          </div>
		          
				<div class="col-sm-12">
					<button type="button" class="btn btn-default" onclick = 'buscarPacienteNomAp()'>Buscar</button>
				</div>
	          <div class="col-sm-12 top15">
		          <table id="tablaPacientes" class="table table-hover dataTable table-striped width-full user-list">
		            <thead>
		              <tr>
		                <th>Nombre</th>
		                <th>Primer Apellido</th>
		                <th>Segundo Apellido</th>
		                <th>Seguro</th>
		                <th>Documento</th>
		                <th>Email</th>
		                <th>Telefonos</th>
		                <th>Acciones</th>
		              </tr>
		            </thead>
		            <tfoot>
		              <tr>
		                <th>Nombre</th>
		                <th>Primer Apellido</th>
		                <th>Segundo Apellido</th>
		                <th>Seguro</th>
		                <th>Documento</th>
		                <th>Email</th>
		                <th>Telefonos</th>
		                <th>Acciones</th>
		              </tr>
		            </tfoot>
		            <tbody>
		            </tbody>
		          </table>
	          </div>
          </div>
        </div>
      </div>
      <!-- End Panel Basic -->
    </div>
  </div>
  </div>
  <!-- End Page -->
  
  <!-- Modal Registro-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionPacientes"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onclick = "limpiarForm()" aria-label="Close">
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
              <label for="nompac">Nombre</label>
              <input type="text" class="form-control input-lg" id="nompac" name="nompac" placeholder="Nombre" required>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgSurName1">
              <label for="ap1pac">Primer Apellido</label>
              <input type="text" class="form-control input-lg" id="ap1pac" name="ap1pac"  placeholder="Primer Apellido">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgSurName2">
              <label for="ap2pac">Segundo Apellido</label>
              <input type="text" class="form-control input-lg" id="ap2pac" name="ap2pac"  placeholder="Segundo Apellido">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group has-datepicker" id="fgNac">
              <label for="fecnacpac">Fecha de nacimiento <!-- <i class="icon wb-calendar" aria-hidden="true"></i> --></label>
              <input type="text" class="form-control input-lg" id="fecnacpac" name="fecnacpac"  placeholder="Ej: 01/01/1985" data-plugin="formatter" data-pattern="[[99]]/[[99]]/[[9999]]">
            </div>
          </div>
          
          <div class="col-sm-3">
            <div class="form-group" id="fgSexpac">
              <label for="sexpac">Sexo</label>
              <select class="form-control input-lg" id="sexpac" name="sexpac" >
                <option value = "">Elegir opción</option>
                <option value = "H">Hombre</option>
                <option value = "M">Mujer</option>
              </select>
            </div>
          </div>
          
          <div class="col-sm-6">
            <div class="form-group" id="fgNumtel1">
              <label for="numtelfijusr">Teléfono 1</label>
              <input type="text" class="form-control input-lg" id="numtel1" name="numtel1" data-plugin="formatter" data-pattern="[[999]][[999]][[999]]" placeholder="Teléfono">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgNumtel2">
              <label for="numtelmovusr">Teléfono 2</label>
              <input type="text" class="form-control input-lg" id="numtel2" name="numtel2" data-plugin="formatter" data-pattern="[[999]][[999]][[999]]" placeholder="Teléfono alternativo">
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
          
			<div class="col-sm-3" id="fgIdHistorial">
				<div class="form-group">
					<label for="idHistorialBusq">Núm. Historial</label>
					<input type="number" disabled class="form-control input-lg" id="infoidHistorial" name="infoidHistorial">
				</div>
			</div>
          
          <div class="col-sm-9" >
            <div class="form-group" id="fgEma">
              <label for="emailpac">Email</label>
              <input type="email" class="form-control input-lg" id="emailpac" name="emailpac"  placeholder="Correo electrónico">
            </div>
          </div>
          
          <div class="col-sm-10">
            <div class="form-group" id="fgDir">
              <label for="dirpac">Domicilio</label>
              <input type="text" class="form-control input-lg" id="dirpac" name="dirpac" placeholder="Domicilio">
            </div>
          </div>
          
          <div class="col-sm-2">
            <div class="form-group" id="fgCp">
              <label for="cppac">Código Postal</label>
              <input type="text" class="form-control input-lg" id="cppac" name="cppac" placeholder="C.P">
            </div>
          </div>
         
          <div class="col-sm-6">
            <div class="form-group" id="fgPais">
              <label for="sIdpais">Pais</label>
              <select class="form-control input-lg" id="sIdpais" name="sIdpais" ></select>
            </div>
          </div>
           <div class="col-sm-6">
            <div class="form-group" id="fgSeguro">
              <label for="sIdseguro">Seguro</label>
              <select class="form-control input-lg" id="sIdseguro" name="sIdseguro" ></select>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group" id="fgCp">
              <label for="numseg">Número Seguro</label>
              <input type="text" class="form-control input-lg" id="numseg" name="numseg" placeholder="Número de Seguro">
            </div>
          </div>
          
          <div class="col-sm-12">
	          <div class="checkbox-custom checkbox-primary margin-left-5 col-sm-4" style="float:left;">
				<input type="checkbox" id="swilopd"  onchange=""/>
				<label for="swilopd"><span class=""><strong>Tiene firmada la hoja de LOPD</strong></span>
			  </div>
			  <div class="checkbox-custom checkbox-primary margin-left-5 col-sm-7">
				<input type="checkbox" id="swilopdcan" onchange=""/>
				<label for="swilopdcan"><span class=""><strong>cancela sus datos y por tanto no pueden salir en nigún lado</strong></span>
			  </div>
		  </div>
		  
          <div class="well well-lg">
             <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="comentario">Comentario</label>
                  <textarea class="form-control input-lg" id="comentario" name="comentario" rows="3"  placeholder="Introduce un comentario"></textarea>
                </div>
              </div>
            </div>
          </div>
          
          
          
          </div>   
      </form>    
      </div>
      
      <form action="./imprimirDoc" class="hide" method="post" id="fImpDocDatos" target="_blank">
      		<input type="hidden" name="impIdeDocDat" id="impIdeDoc">
			<input type="hidden" name="impIdePacDat" id="impIdePacDat">
			
		</form>
			
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick = 'limpiarForm(true)'>Cancelar</button>
        <button type="button" class="btn btn-success" onclick="guardarPaciente()">Guardar</button>
      </div>
    </div>
  </div>
  </div>
  <!-- End Modal Registro -->
  
    <!-- Modal Cita-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionCitas"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" onclick = "limpiarFormCitas()" aria-label="Close">
		        <span aria-hidden="true">×</span>
		        </button>
		        <h3 class="modal-title">Formulario de citas</h3>
		      </div>
		      <div class="modal-body">
		       	<ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
	              	<li class="active" id="oResumen" role="presentation"><a data-toggle="tab" href="#darCita" aria-controls="cuenta" role="tab">Dar Cita</a></li>
	                <li class="" id="oCuenta" role="presentation"><a data-toggle="tab" href="#citasActuales" aria-controls="cuenta" role="tab">Citas Activas</a></li>
              	</ul>
		       	<div class="tab-content">
					<div class="tab-pane active padding-10" id="darCita" role="tabpanel">
						<div class="row text-center">
				      	 <div class="col-sm-6">
				            <div class="form-group" id="fgEspec">
				              <label for="sexpac">Especialidad</label>
				              <select class="form-control" id="sEspecMed" name="sEspecMed" >
				              </select>
				            </div>
				          </div>
				          <div class="col-sm-6">
				            <div class="form-group" id="fgMedicos">
				              <label for="sexpac">Médicos Diponibles</label>
				              <select class="form-control" id="sMedico" name="sMedico" >
				              </select>
				            </div>
				          </div>
				         
				          <div id="dGestionCitasSolVac" class="col-sm-12 hide">
							 <a class="btn btn-danger col-sm-12" onclick="citasEnvacacionesInit()" data-toggle="modal" data-target="#gestionCitasVacaciones" href="" aria-expanded="false" aria-controls="gestionPacientes">
				                Citas en fechas con Doctor ausente
				             </a>
			             </div>
				       	<div id="divCal" class="page-main col-sm-12">
				        	<div id="calendar" class="fc fc-ltr fc-unthemed hide"></div>
				   		</div>
				   		<div class="modal-body">
	       
				        <div class="row text-center col-sm-4 hide" id="containerBtnCita">
				        <div class="col-sm-12" style= "max-height: 450px; overflow: auto;">
				        <div id='horasDisp' class="respuestas-pregunta-wrap col-md-12"></div>
				        <div class="col-sm-12 hide dObscita">
			                <div class="form-group">
			                  <label for="comentario">comentario</label>
			                  <textarea class="form-control input-lg" id="obscita" name="obscita" rows="3"  placeholder="Introduce un comentario"></textarea>
			                </div>
		              	</div>
						<a class="btn btn-warning" onclick="initPantallaGestionCita()" data-toggle="modal" data-target="#gestionCitasManu" href="" aria-expanded="false" aria-controls="gestionPacientes">
				        	Gestionar Cita Manual
				        </a>
				        </div>
				          <div class="col-sm-12">
				            <div class="modal-footer">
						        <button type="button" class="btn btn-default" onclick = "cancelGuardado()">Cancelar</button>
						        <button type="button" class="btn btn-success" onclick="guardarCita()">Guardar</button>
						    </div>
				          </div>
				        </div>
	          		</div>
			       </div>
					</div>
					<div class="tab-pane padding-10" id="citasActuales" role="tabpanel">
					
						<table id="tablaCitasActuales" class="table table-hover dataTable table-striped width-full user-list">
				            <thead>
				              <tr>
				                <th>Doctor</th>
				                <th>Fecha Cita</th>
				                <th>Hora Cita</th>
				                <th>Estado Cita</th>
				                <th>Acciones</th>
				              </tr>
				            </thead>
				            <tfoot>
				              <tr>
				                <th>Doctor</th>
				                <th>Fecha Cita</th>
				                <th>Hora Cita</th>
				                <th>Estado Cita</th>
				                <th>Acciones</th>
				              </tr>
				            </tfoot>
				            <tbody>
				            </tbody>
			          </table>
					
					</div>
				</div>
		       
		       
		      	
			</div>
		</div>
	</div>
</div>
  <!-- End Modal Cita -->
  
  
<!-- Modal Historico Cita-->
<div class="modal fade modal-3d-flip-horizontal" id="historicoCitas"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        <span aria-hidden="true">×</span>
		        </button>
		        <h3 class="modal-title">Historico de citas</h3>
		      </div>
		      <div class="modal-body">
		       
		       <button type="button" class="btn btn-info" onclick = "imprimirHistCita()">Imprimir</button>
		       
		       <form action="./imprimirHistCita" class="hide" method="get" id="fHistPacPac" target="_blank">
					<input type="hidden" name="idPacList" id="idPacList">						
				</form>
					
						<table id="tablaHistoricoCitas" class="table table-hover dataTable table-striped width-full user-list">
				            <thead>
				              <tr>
				              	<th class="hide">hidden</th>
				                <th>Doctor</th>
				                <th>Fecha Cita</th>
				                <th>Hora Cita</th>
				                <th>Observaciones</th>
				                <th>Estado Cita</th>
				              </tr>
				            </thead>
				            <tfoot>
				              <tr>
				              	<th class="hide">hidden</th>
				                <th>Doctor</th>
				                <th>Fecha Cita</th>
				                <th>Hora Cita</th>
				                <th>Observaciones</th>
				                <th>Estado Cita</th>
				              </tr>
				            </tfoot>
				            <tbody>
				            </tbody>
			          </table>
				</div>
			</div>
		</div>
</div>
  <!-- End Modal Historico Cita -->
  
<!-- Modal Citas manuales-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionCitasManu"   aria-hidden="true" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3>Gestión de citas manuales</h3>
      </div>
      <div class="modal-body">
		<div class="col-sm-4">
            <div class="form-group" id="fgSexpac">
              <label for="sHoraIni">Hora Inicio</label>
              <select class="form-control sHora" id="sHoraIni" >
              </select>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group" id="fgSexpac">
              <label for="sMinIni">Minutos inicio</label>
              <select class="form-control sMinutos" id="sMinIni" >
              </select>
            </div>
          </div>
     </div>
     <div class="modal-footer">
     	<button type="button" class="btn btn-default" onclick = "cancelCitaManual()">Cancelar</button>
		<button type="button" class="btn btn-success" onclick="continuarCitaManual()">Seleccionar</button>
     </div>
    </div>
  </div>
</div>
  
<!-- Modal Vacaciones-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionCitasVacaciones"   aria-hidden="true" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3>Citas en periodo ausencias del doctor</h3>
      </div>
      <div class="modal-body">
      	
	     <div class="col-sm-12">
			<table id="tablaCitasVacaciones" class="table table-hover dataTable table-striped width-full user-list">
				<thead>
				  <tr>
					<th>Nombre</th>
					<th>Apellidos</th>
					<th>Telefonos</th>
					<th>Fecha</th>
					<th>Especialidad</th>
					<th>Acciones</th>
				  </tr>
				</thead>
				<tfoot>
				 
				</tfoot>
				<tbody>
				</tbody>
			</table>
		</div>
     </div>
     	<div class="modal-footer">
     	
     	</div>
	</div>
</div>
</div>
     
<!-- Modal Comentarios-->
<div class="modal fade modal-3d-flip-horizontal" id="insModComentarios"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        <span aria-hidden="true">×</span>
		        </button>
		        <h3 class="modal-title">Comentarios</h3>
		      </div>
		      <div class="modal-body">
		       
					<div class="row">
		              <div class="col-sm-12">
		                <div class="form-group">
		                  <label for="comentario">Comentario</label>
		                  <textarea class="form-control input-lg" id="comentarioMod" name="comentarioMod" rows="3"  placeholder="Introduce un comentario"></textarea>
		                </div>
		              </div>
		            </div>
						
			</div>
			<div class="modal-footer">
     			<button type="button" class="btn btn-default" onclick = "$('#insModComentarios').modal('toggle')">Cancelar</button>
				<button type="button" class="btn btn-success" onclick="modificarMsgCita()">Modificar</button>
     		</div>
			</div>
		</div>
	</div>
  <!-- End Modal Comentarios -->
  
  <!-- Modal Citas Finalizadas hoy-->
<div class="modal fade modal-3d-flip-horizontal" id="citasFinHoy"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        <span aria-hidden="true">×</span>
		        </button>
		        <h3 class="modal-title">Citas Finalizadas hoy</h3>
		      </div>
		      <div class="modal-body">		       

					
						<table id="tablaCitasHoy" class="table table-hover dataTable table-striped width-full user-list">
				            <thead>
				              <tr>
				              	<th>Paciente</th>
				                <th>Doctor</th>
				                <th>Especialidad</th>
				              </tr>
				            </thead>
				            <tfoot>
				              <tr>
				                <th>Paciente</th>
				                <th>Doctor</th>
				                <th>Especialidad</th>
				              </tr>
				            </tfoot>
				            <tbody>
				            </tbody>
			          </table>
				</div>
			</div>
		</div>
</div>
  <!-- End Modal Citas Finalizadas hoy -->
  
  <!-- INICIO modal Docs Firmados -->
  <div class="modal fade modal-3d-flip-horizontal" id="gestionDocsFirmados"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        <span aria-hidden="true">×</span>
		        </button>
		        <h3 class="modal-title">Documentos del paciente</h3>
		      </div>
		      <div class="modal-body">		       

					
						<table id="tablaFirmaDoc" class="table table-hover dataTable table-striped width-full user-list">
				            <thead>
				              <tr>
				              	<th>Documento</th>
				                <th>Fecha</th>
				                <th>Acciones</th>
				              </tr>
				            </thead>
				            
				            <tbody>
				            </tbody>
			          </table>
			          
			          <form action="./verDocFirmado" class="hide" method="get" id="fverFirma" target="_blank">
						<input type="hidden" name="id" id="iddoc">						
					</form>
				</div>
			</div>
		</div>
	</div>
  <!-- FIN modal Docs Firmados -->

@stop
@section('js', '/js/app/private/pacientesRegistrados.js')
