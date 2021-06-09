@extends('layout.private.private')

@section('title', 'Gestión de Visitas')
@section('css', '/private/assets/css/pages/user.css')

@section('content')
<div class="page-profile">
  <!-- Page -->
  <div class="page animsition">
    <div class="page-header">
      <h1 class="page-title">Gestión de Visitas</h1>
      <div class="page-header-actions">
        <ol class="breadcrumb">
          <li><a href="/private/index">private</a></li>
          <li class="active">Visitas</li>
        </ol>
      </div>
    </div>
    <div class="page-content">
	
		
      <!-- Panel Basic -->
      <div class="panel">
	  
        
        <div class="panel-body user-list-wrap">
          <div class="form-group row">
                          <div class="col-sm-12">
                          <h3>Visitas pendientes <button type="button" onclick="obtenerVisitasUsr()" class="btn btn-primary btn-icon waves-effect waves-light" >
                          	<i class="icon wb-replay" aria-hidden="true"></i>Recargar</button></h3> 
                          	
		                       <table id="tablaVisitas" class="table table-hover dataTable table-striped width-full">
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
				    	
        </div>
      </div>
      <!-- End Panel Basic -->
    </div>
  </div>
  <!-- End Page -->
</div>


<!-- Modal Consulta Historico citas-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionVisitas"  data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Visita</h3>
      </div>
      <div class="modal-body">
       	
       	<div id="divShowDatosPac" class="col-sm-12">
       	
       	
       	
	       	<div class="col-sm-12">
				<span><strong>Nombre: </strong></span> <span id="sNomApPac"></span>
	       	</div>
	       	<div class="col-sm-12">
	       	
	       	<div class="panel-body">
	       		<ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
	              	<li class="active" id="oObs" role="presentation"><a data-toggle="tab" href="#tObs" aria-controls="tObs" role="tab">Observaciones</a></li>
	                <li id="oPruCom" role="presentation"><a data-toggle="tab" href="#tPruCom" aria-controls="tPruCom" role="tab">Pruebas Complementaria</a></li>
	            </ul>
	            <div class="tab-content "> 
	            	<div class="tab-pane active padding-10" id="tObs" role="tabpanel">
                		<div class="panel">
	                		<div class="form-group">
			                  	<textarea class="form-control input-lg" id="obsVisita" name="comentario" rows="3"  placeholder="Introduce un comentario"></textarea>
			                </div>
                		</div>
                	</div>
                	
                	<div class="tab-pane  padding-10" id="tPruCom" role="tabpanel">
		                
		                  <div class="panel">
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
						      <div class="modal-footer">
						      </div>
		                  </div>
		                </div>
	            </div>
            </div>
	       	
	       	
	       	
	       	
                
            </div>
       	
       	</div>
       	 
     	 
     	
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-default" onclick = 'cancelarModVisita()'>Cancelar</button>
        <button type="button" class="btn btn-success" onclick="finalizarVisita()">Finalizar</button>
      </div>
    </div>
  </div>
</div>


@stop
@section('js', '/private/assets/vendor/jquery-strength/jquery-strength.min.js')
@section('js1', '/private/assets/js/components/jquery-strength.js')
@section('js3', '/js/app/private/pruebaComplementaria.js')
@section('js4', '/js/app/private/tablonVisita.js')
