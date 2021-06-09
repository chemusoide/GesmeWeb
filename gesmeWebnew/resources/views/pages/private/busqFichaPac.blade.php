@extends('layout.private.private')

@section('title', 'Busqueda Paciente')
@section('css', '/private/assets/css/pages/user.css')

@section('content')

<script type="text/javascript">
  var listaOpciones = <?= json_encode($listaOpciones) ?>;
  
</script>

<div class="page-profile">
  <!-- Page -->
  <div class="page animsition">
    <div class="page-header">
      <h1 class="page-title">Búsqueda paciente</h1>
      <div class="page-header-actions">
        <ol class="breadcrumb">
          <li><a href="/private/index">private</a></li>
          <li class="active pagActual">Búsqueda paciente</li>
        </ol>
      </div>
    </div>
    <div class="page-content">
      <!-- Panel Basic -->
      <div class="panel" id="panelFiltro">
        
        <div class="panel-body user-list-wrap">
			<div class="form-group row">
				<div class="col-sm-12">
				<h3>Búsqueda</h1>
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
					<button type="button" class="btn btn-success" onclick = 'buscarPacienteNomAp()'>Buscar</button>
				</div>
			  </div>
			</div>
        </div>
      </div>
      <!-- End Panel Basic -->
	  <!-- Panel Tabla Busqueda -->
	  
	  <div class="panel hide" id="panelBusq">
        
        <div class="panel-body user-list-wrap">
			<div class="form-group row">
				<div class="col-sm-12">
					<h3>Resultados</h1>
					<div class="col-sm-12">
						<table id="tablaPacientes" class="table table-hover dataTable table-striped width-full user-list">
							<thead>
							  <tr>
								<th>Nombre</th>
								<th>Primer Apellido</th>
								<th>Segundo Apellido</th>
								<th>DNI</th>
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
			</div>
        </div>
      </div>
	  <!-- FIN Panel Tabla Busqueda -->
	  <!-- Panel Tabla Busqueda -->
	  
	  <div class="panel hide" id="datosPac">
        
        <div class="panel-body user-list-wrap">
	        <button type="button" class="btn btn-info" onclick="volverBusqueda()">
				<i class="icon wb-reply" aria-hidden="true"></i> Volver
		 	</button>
			<div class="form-group row top10">
				<div class="col-sm-12">
						  
					<div class="panel-heading  bg-grey-200">
						<h3 class="panel-title">Paciente: <span id="nomComplePac"></span></h3>
					</div>
				</div>
			</div>
        </div>
		<!-- INIT TABS -->
		<div class="panel-body">
              <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
              	<li class="active" id="oDatper" role="presentation"><a data-toggle="tab" href="#tDatPer" aria-controls="tDatPer" role="tab">Datos Personales</a></li>
                <li id="oHist" class="hide" role="presentation"><a data-toggle="tab" href="#tHist" aria-controls="tHist" role="tab">Historial</a></li>
                <li id="oPruCom" role="presentation" class="hide"><a data-toggle="tab" href="#tPruCom" aria-controls="tPruCom" role="tab">Pruebas Complementaria</a></li>
              </ul>
			  
			  <!--Body Tabs -->
			  <div class="tab-content "> 
			  <div class="tab-pane active padding-10" id="tDatPer" role="tabpanel">
                  <div class="panel">
                    <div class="panel-heading">
                      <h3 class="panel-title">Datos Personales</h3>
                    </div>
                    <div class="panel-body">
					
						<div class="col-md-12">
							<strong>Nombre:</strong>
							<span class="sNombre" ></span>
						</div>
						<div class="col-md-12">
							<strong>Apellido: </strong>
							<span class="sAp1"></span>
						</div>
						<div class="col-md-12">
							<strong>Apellido 2: </strong>
							<span class="sAp2"></span>
						</div>
						<div class="col-md-12">
							<strong>sexo: </strong>
							<span class="sSex"></span>
						</div>
						<div class="col-md-12">
							<strong>DNI: </strong>
							<span class="sDni"></span>
						</div>
						<div class="col-md-12">
							<strong>Fec. Nacimiento: </strong>
							<span class="col-md-12" class="time"></span>
						</div>
						
						<div class="col-md-10">
							<strong>Dirección: </strong>
							<span class="col-md-12"></span>
						</div>
						
						<div class="col-md-2">
							<strong>CP: </strong>
							<span class="sCp"></span>
						</div>
					
						<div class="clearfix"></div>
						
                    </div>
                  </div>
                </div>
                
                <div class="tab-pane padding-10 hide" id="tHist" role="tabpanel">
                  <div class="panel">
                    <div class="panel-heading">
                      <h3 class="panel-title">Historial del Paciente</h3>
                    </div>
                    <div class="panel-body">
					
						<div class="form-group row">
							<h3>Seleccione las categorias que desea ver</h3>
						</div>
						
						<div class="form-group row">
							<div class="col-md-3">
								<div class="checkbox-custom checkbox-primary">
									<input type="checkbox" id="verAle"/>
									<label for="verAle">Alergías</label>
								</div>
								<div class="checkbox-custom checkbox-primary">
									<input type="checkbox" id="verAnt" />
									<label for="verAnt">Antecedentes</label>
								</div>
								<div class="checkbox-custom checkbox-primary verConClass">
									<input type="checkbox" id="verCon" onchange="changeVerCon()" />
									<label for="verCon">Consultas</label>
								</div>
								<div class="checkbox-custom checkbox-primary">
									<input type="checkbox" id="verIng" />
									<label for="verIng">Ingresos</label>
								</div>
							</div>
							<div class="col-md-9 hide" id="conTipCitasDisp">
							 <div class="col-md-12">
								<h3>Tipos de Consultas</h3>
							</div>
							
							<div class="col-md-12 padding-10" >
								<div id="tipCitasDisp"></div>
								<div class="input-group col-md-12">
								<span class="input-group-addon">
								  <i class="icon wb-calendar" aria-hidden="true"></i>
								</span>
								<input class="datepicker" id = "dpDesde" > <span> Hasta la actualidad</span>
							  </div>
							</div>
							
								
							</div>
							
							<div class="col-md-12">
							  <div class="input-group top15">
								<button type="button" class="btn btn-success" onclick="buscarHistorial()">Buscar</button>
							  </div>
							</div>
							 
						</div>

						<div class="col-md-12 contenedorHistorial">
						</div>
                    </div>
                  </div>
                </div>

                 <div class="tab-pane  padding-10 hide" id="tPruCom" role="tabpanel">
                  <div class="panel">
                    <div class="panel-heading">
                      <h3 class="panel-title">Pruebas Complementaria </h3>
                    </div>
                    <div class="panel-body">
                      
						<div class="form-group" id="contenedorPrubCom">
	  	
						</div>
						 
						<button type="button" class="btn btn-danger bdivContPru" onclick="mostrarDivNuevo('divContPru')">
							<i class="icon wb-plus" aria-hidden="true"></i> Nueva Prueba Complementaria
						 </button>
						<div class="divContPru well well-lg hide">
							<h3 class="title">Nueva Prueba Complementaria</h3>
							<input type="hidden" id="archB64" name="archB64">
							<label for="filePicker" class="top15">Selecciona una imagen o archivo PDF</label><br>
							<input type="file" id="filePicker">
							
							<label for="categoria top15">Tipo de Prueba</label>
							<select class="form-control input-lg" id="tipPrueba" name="tipPrueba" >
								<option value = ''>Elegir un tipo</option>
							</select>
							<label for="categoria top15">Acto quirujico (Elegir si corresponde)</label>
							<select class="form-control input-lg" id="actQui" name="actQui" >
								<option value = ''>Acto quirurjico</option>
							</select>
							<label for="pregunta" class="top15">Observaciones</label>
							<textarea class="form-control input-lg" id="divContPruObs" name="obsantnew" rows="3" placeholder="Escribe una Observación"></textarea>
						 
							<div class="botonera top15">
								<button type="button" class="btn btn-danger" onclick="ocultarDivNuevo('divContPru')">Cancelar</button>
								<button type="button" class="btn btn-success" onclick="guardarPruebaComplem('divContPru')">Guardar</button>
							</div>

						</div>
						<!-- Imagen -->
					 
					
						<div class="col-sm-12 top15">
						  <table id="tablaPruebas" class="table table-hover dataTable table-striped width-full">
							<thead>
							  <tr>
								<th>tipo Prueba</th>
								<th>Comentario</th>
								<th>Fecha Creacion</th>
								<th>tipo fichero</th>
								<th>Adjunto</th>
							  </tr>
							</thead>
							<tbody>
							</tbody>
						  </table>
						  </div> 
					  
					  
					  
                    </div>
                  </div>
                </div>
                
              </div>
			  <!--FIN Body Tabs -->
		</div>
		<!-- FIN INIT TABS -->
      </div>	  
	  <!-- FIN Panel Tabla Busqueda -->
	  
    </div>
  </div>
  <!-- End Page -->
</div>





@stop
@section('js', '/private/assets/vendor/jquery-strength/jquery-strength.min.js')
@section('js1', '/private/assets/js/components/jquery-strength.js')
@section('js2', '/js/app/private/busqFicha.js')
@section('js3', '/js/app/private/historialFunComun.js')
@section('js4', '/js/app/private/pruebaComplementaria.js')
