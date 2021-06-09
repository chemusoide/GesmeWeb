@extends('layout.private.private')

@section('title', 'Tablon Médico')
@section('css', '/private/assets/css/pages/user.css')

@section('content')
<div class="page-profile">
  <!-- Page -->
  <div class="page animsition">
    <div class="page-header">
      <h1 class="page-title">Tablon Médico <span id="strDoPend" class='hide' style="color: red;"> -</span></h1>
      <div class="page-header-actions">
        <ol class="breadcrumb">
          <li><a href="/private/index">private</a></li>
          <li class="active">Tablon Médico</li>
        </ol>
      </div>
    </div>
    <div class="page-content">
	<!-- FIN  MIS CONFIGURACIONES -->
	<div class="panel panel-bordered">
		<a data-toggle="collapse" style="text-decoration: none" data-parent="#accordion" href="#collapseAl">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="icon wb-settings" aria-hidden="true"></i>Configuraciones</h3>
				<div class="panel-actions">
					<a class="panel-action icon wb-minus"></a>
				</div>
			</div>
		</a>
        <div class="collapse panel-body" id="collapseAl">

			<div class="col-sm-4">
          <!-- Widget -->
				<a  data-toggle="modal" onclick="prepararVentanaMisPlantillas()" data-target="#gestionPlantillas" href="" aria-expanded="false" aria-controls="gestionPlantillas">
				<div class="widget">
					<div class="widget-content widget-radius padding-20 bg-green-100 clearfix">
						<div class="counter counter-md pull-left text-left">
							<div class="counter-number-group">
								<span class="counter-number" ></span>
								<span class="counter-number-related text-capitalize font-size-18">Mis Plantillas</span>
							</div>
						</div>
						<div class="pull-right white">
							<i class="icon icon-circle icon-2x wb-star bg-green-600" aria-hidden="true"></i>
						</div>
					</div>
				</div>
				</a>
			<!-- End Widget -->
			</div>
				
        </div>
    </div>
	<!-- FIN  MIS CONFIGURACIONES -->
		
      <!-- Panel Basic -->
      <div class="panel">
	  
        
        <div class="panel-body user-list-wrap">
          <div class="form-group row">
                          <div class="col-sm-12">
                          <h3>Citas Para hoy</h1>
		                       <table id="tablaCitas" class="table table-hover dataTable table-striped width-full">
					            <thead>
					              <tr>
					                <th>Nombre</th>
					                <th>Apellidos</th>
									<th>Fec. Nacimiento</th>
									<th>Seguro</th>
					                <th>Comentario</th>
					                <th>Hora Cita</th>
					                <th>Estado Cita</th>
					                <th>Acciones</th>
					              </tr>
					            </thead>
					            <tbody>
					            </tbody>
					          </table>
				          </div>
				    	</div>
				    	<div class="form-group row">
				    	
                          <div class="col-sm-12">
                          <h3>Mis Pacientes</h3>
                          </div>
                          <div class="col-sm-12">
								<div class="col-sm-4">
									<div class="form-group" id="fgNombre">
										<label for="nompac">Nombre</label>
										<input type="text" class="form-control input-lg" id="nompac" name="nompac" placeholder="Nombre">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group" id="fgSurName1">
										<label for="ap1pac">Primer Apellido</label>
										<input type="text" class="form-control input-lg" id="ap1pac" name="ap1pac"  placeholder="Primer Apellido">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group" id="fgSurName2">
										<label for="ap2pac">Segundo Apellido</label>
										<input type="text" class="form-control input-lg" id="ap2pac" name="ap2pac"  placeholder="Segundo Apellido">
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
									<button type="button" class="btn btn-default" onclick = 'limpiarBusqueda()'>Limpiar</button>
									<button type="button" class="btn btn-success" onclick = 'obtenerMisPacientes()'>Buscar</button>
								</div>
						  </div>
                          <div class="col-sm-12 top15">
		                       <table id="tablaPac" class="table table-hover dataTable table-striped width-full user-list">
					            <thead>
					              <tr>
					                <th>Nombre</th>
					                <th>Primer Apellido</th>
					                <th>Segundo Apellido</th>
					                <th>DNI</th>
					                <th>Acciones</th>
					              </tr>
					            </thead>
					            <tbody>
					            </tbody>
					          </table>
                          </div>
                         </div>
                         
                         <div class="col-sm-12 top30">
				    		<button type="button" onclick = "verTodosLosPacientes()" class="btn btn-default btn-lg">Ver todos los pacientes</button>
				    	</div>
        </div>
      </div>
      <!-- End Panel Basic -->
    </div>
  </div>
  <!-- End Page -->
</div>


<!-- Modal Consulta Historico citas-->
<div class="modal fade modal-3d-flip-horizontal" id="modalVerTodos"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Listado de pacientes</h3>
      </div>
      <div class="modal-body">
       	<div id="divShowAviso" class="col-sm-12">
       		<h4 class="modal-title">Está intentando acceder a datos de otros pacacientes que no están en su ámbito. Acceder a estos datos sin justificación, podría suponer un delito ¿Desea Continuar? Justifique el motivo por el cual accede</h4>
       		<textarea class="form-control input-lg col-sm-12" id="comentario" name="comentario" rows="3"  placeholder="Motivo del acceso"></textarea>
       		<div class="col-sm-12 top15">
	    		<button type="button" onclick = "accesoListadoPacientes()" class="btn btn-success btn-lg">Continuar</button>
	    		<button type="button" onclick = "verTodosLosPacientes()" class="btn btn-danger btn-lg">Cancelar</button>
	    	</div>
       	</div>
       	<div id="divShowDatosPac" class="col-sm-12 hide">
       	
       	
       	
       	<div class="col-sm-12">
			<div class="col-sm-4">
				<div class="form-group" id="lpNombre">
					<label for="nompac">Nombre</label>
					<input type="text" class="form-control input-lg" id="lpnompac" name="lpnompac" placeholder="Nombre">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group" id="lpSurName1">
					<label for="lpap1pac">Primer Apellido</label>
					<input type="text" class="form-control input-lg" id="lpap1pac" name="lpap1pac"  placeholder="Primer Apellido">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group" id="lpSurName2">
					<label for="lpap2pac">Segundo Apellido</label>
					<input type="text" class="form-control input-lg" id="lpap2pac" name="lpap2pac"  placeholder="Segundo Apellido">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group" id="lpIdHistorialBusq">
					<label for="lpidHistorialBusq">Núm. Historial</label>
					<input type="number" class="form-control input-lg" id="lpidHistorialBusq" name="lpidHistorialBusq"  placeholder="Número Historia">
				</div>
			</div>
			<div class="col-sm-4">
	            <div class="form-group" id="lptipDocBusq">
	              <label for="lptipdocBusq">Tipo Doc.</label>
	              <select class="form-control input-lg" id="lptipdocBusq" name="lptipdocBusq" >
	                <option value = "DNI">D.N.I</option>
	                <option value = "NIE">N.I.E</option>
	                <option value = "PAS">PASAPORTE</option>
	              </select>
	            </div>
	         </div>
	         <div class="col-sm-4">
	            <div class="form-group" id="lpDniBusq">
	              <label for="dniusr">Número Documento</label>
	              <input type="text" class="form-control input-lg text-uppercase" id="lpdniusrBusq" name="lpdniusrBusq"  placeholder="Introduce DNI" data-plugin="formatter" data-pattern="[[99999999]]-[[a]]">
	              <input type="text" class="form-control input-lg text-uppercase hide" id="lpnieusrBusq" name="lpnieusrBusq"  placeholder="Introduce NIE" data-plugin="formatter" data-pattern="[[a9999999]]-[[a]]">
	              <input type="text" class="form-control input-lg text-uppercase hide" id="lppassusrBusq" name="lppassusrBusq"  placeholder="Introduce Pasaporter" data-plugin="formatter" data-pattern="[[***************]]">
	            </div>
	         </div>
	         <div class="col-sm-12">
				<button type="button" class="btn btn-default" onclick = "limpiarBusqueda('lp')">Limpiar</button>
				<button type="button" class="btn btn-success" onclick = "obtenerMisPacientes('lp')">Buscar</button>
			</div>
       	</div>
       	
       	
       		<div class="col-sm-12 top15">
	       		<table id="tablaPacEsp" class="table table-hover dataTable table-striped width-full user-list">
					<thead>
					  <tr>
						<th>Nombre</th>
						<th>Primer Apellido</th>
						<th>Segundo Apellido</th>
						<th>DNI</th>
						<th>Acciones</th>
					  </tr>
					</thead>
					<tbody>
					</tbody>
			  </table>
		  </div>
       	</div>
       	 
     	 
     	
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

  
  <!-- Modal Mis Plantillas-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionPlantillas"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Mis Plantillas</h3>
      </div>
		<div class="modal-body">
			<button type="button" class="btn btn-danger bdivContPlan" onclick="mostrarDivNuevo('divContPlan')">
				<i class="icon wb-plus" aria-hidden="true"></i> Nueva Plantilla
			</button>
			<div class="divContPlan well well-lg hide col-sm-12">
				<h3 class="title">Nueva Plantilla</h3>
				<input type="hidden" class="divContPlanId" id='idPlantilla' name='idPlantilla' >
				<div class="col-sm-6">
					<div class="form-group" id="fgNombre">
					  <label for="nompac">Título</label>
					  <input type="text" class="form-control input-lg divContPlanForm" id="titPlantilla" name="titPlantilla" placeholder="Título" required>
					</div>
				</div>
				<div class="col-sm-12" id="txtplantilla-wrap">
					<textarea class="form-control input-lg col-sm-12 divContPlanForm" name="" data-plugin="summernote" rows="3" id="txtplantilla" style="display: none;" placeholder="Introduzca el texto de la plantilla"></textarea>
				</div>
				
				 
				<div class="col-sm-12 botonera top15">
					<button type="button" class="btn btn-danger" onclick="ocultarDivNuevo('divContPlan')">Cancelar</button>
					<button type="button" class="btn btn-success" onclick="insertarModificarNuevaPlantilla()">Guardar</button>
				</div>
			</div>

		
			<div class="col-sm-12 top15">
			  <table id="tablaPlantilla" class="table table-hover dataTable table-striped width-full">
				<thead>
				  <tr>
					<th>Titulo</th>
					<th>Texto</th>
					<th>Fecha Creacion</th>
					<th>Acciones</th>
				  </tr>
				</thead>
				<tbody>
				</tbody>
			  </table>
			</div>       	
			<div class="modal-footer">
			</div>
		</div>
  </div>
</div>
</div>
  




@stop
@section('js', '/private/assets/vendor/jquery-strength/jquery-strength.min.js')
@section('js1', '/private/assets/js/components/jquery-strength.js')
@section('js2', '/private/assets/vendor/toastr/toastr.js')
@section('js3', '/private/assets/js/components/toastr.js')
@section('js4', '/js/app/private/perfil.js')
