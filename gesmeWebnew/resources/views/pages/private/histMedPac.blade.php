@extends('layout.private.private')

@section('title', 'Historial')
@section('css', '/private/assets/css/pages/user.css')

@section('content')


<script type="text/javascript">
  var paciente = <?= json_encode($paciente) ?>;
  var listaCodesp = <?= json_encode($listaCodesp) ?>;
  var swiImpHist = <?= json_encode($swiImpHist) ?>;
</script>


<div class="page-profile">
  <!-- Page -->
  <div class="page animsition">
    <div class="page-header">
      <h1 class="page-title">Historial Médico</h1>
      <div class="page-header-actions">
        <ol class="breadcrumb">
          <li><a href="/private/index">private</a></li>
          <li class="active">Historial Médico</li>
        </ol>
      </div>
    </div>
    <div class="page-content">
      <!-- Panel Basic -->
      <div class="panel">
        
        <div class="panel-body">
       		<ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
              	<li class="active" id="oHist" role="presentation"><a data-toggle="tab" href="#tHist" aria-controls="tHist" role="tab">Historial</a></li>
                <li id="oPruCom" role="presentation"><a data-toggle="tab" href="#tPruCom" aria-controls="tPruCom" role="tab">Pruebas Complementaria</a></li>
            </ul>
            <div class="tab-content "> 
            	<div class="tab-pane active padding-10" id="tHist" role="tabpanel">
                  <div class="panel">
		                  <div class="form-group row">
				               	<h3>Seleccione las categorias que desea ver</h3>
							</div>
							
							<div class="form-group row">
								<div class="col-md-3">
									<div class="checkbox-custom checkbox-primary">
						                <input type="checkbox" id="verAle"  onchange=""/>
						                <label for="verAle">Alergías</label>
					              	</div>
					              	<div class="checkbox-custom checkbox-primary">
						                <input type="checkbox" id="verAnt" onchange="" />
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
				                    <input data-provide="datepicker" id = "dpDesde" data-date-format="dd/mm/yyyy"> <span> Hasta la actualidad</span>
				                  </div>
								</div>
								
									
								</div>
								
								<div class="col-md-12">
				                  <div class="input-group top15">
				                  	<button type="button" class="btn btn-success" onclick="buscarHistorial()">Buscar</button>
				                  </div>
								</div>
					             
							</div>
							
							
							<div class="form-group row">
								
								
								<div class="col-md-12">
									<div class="panel panel-bordered">
				        				  
				        				  <div class="panel-heading  bg-grey-200">
				        				  	<h3 class="panel-title">Datos Paciente</h3>
				              			  </div>
				              			  
				        				<div class="bg-grey-100 padding-20 col-md-12">
				        					<div class="col-md-4">
				        						<strong>Nombre:</strong>
				        						 <span class="sNombre" ></span>
				        					</div>
				        					<div class="col-md-4">
				        						<strong>Apellido: </strong>
				        						<span class="sAp1"></span>
				        					</div>
											<div class="col-md-4">
				        						<strong>Apellido 2: </strong>
				        						<span class="sAp2"></span>
				        					</div>
											<div class="col-md-3">
				        						<strong>sexo: </strong>
				        						<span class="sSex"></span>
				        					</div>
				        					<div class="col-md-5">
				        						<strong>DNI: </strong>
				        						<span class="sDni"></span>
				        					</div>
											<div class="col-md-4">
				        						<strong>Fec. Nacimiento: </strong>
				        						<span class="sFNa" class="time"></span>
				        					</div>
											
											<div class="col-md-10">
				        						<strong>Dirección: </strong>
				        						<span class="sDir"></span>
				        					</div>
											
											<div class="col-md-2">
				        						<strong>CP: </strong>
				        						<span class="sCp"></span>
				        					</div>
											
											  <div class="clearfix"></div>                		
				                  		</div>
				                  	</div>
								</div>
								<div class="col-md-12 contenedorHistorial">
								</div>
								
							</div>
							
								    <div class="col-md-12">
								<form action="../imprimirHistorial" method="get" id="fImpHist" target="_blank">
										<input type="hidden" name="impIdePac" id="impIdePac">
										<input type="hidden" name="impIdMed" id="impIdMed">
										<input type="hidden" name="impFechaDesde" id="impFechaDesde">
										<div id="divInputAdd">
										</div>
									
									<button type="button" class="btn btn-success hide" id="btnImpHist" onclick="imprimirHistorial()">Imprimir</button>
								
								</form>
								
								</div>
		                  </div>
		                </div>
		                
		                
		                
		                
		                <div class="tab-pane  padding-10" id="tPruCom" role="tabpanel">
		                
		                  <div class="panel">
		                    <div class="panel-heading">
		                      <h3 class="panel-title">Pruebas Complementaria </h3>
		                    </div>
		                    <div class="panel-body">
		                      
								<div class="form-group" id="contenedorPrubCom">
			  	
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
            
	        
				
				
        </div>
      </div>
      <!-- End Panel Basic -->
    </div>
  </div>
  <!-- End Page -->
</div>




@stop
@section('js', '/private/assets/vendor/jquery-strength/jquery-strength.min.js')
@section('js1', '/private/assets/js/components/jquery-strength.js')
@section('js2', '/js/app/private/historialFunComun.js')
@section('js3', '/js/app/private/historial.js')
@section('js4', '/js/app/private/pruebaComplementaria.js')

