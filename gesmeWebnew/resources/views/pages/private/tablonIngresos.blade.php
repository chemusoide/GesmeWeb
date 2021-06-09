@extends('layout.private.private')

@section('title', 'Ingresos')
@section('css', '/private/assets/css/pages/user.css')

@section('content')

<script type="text/javascript">
  var listaMedicos = <?= json_encode($listaMedicos) ?>;
  var listaSegurosAct = <?= json_encode($listaSeguros) ?>;
  var tiposSolicitud = <?= json_encode($tiposSolicitud)?>;
</script>

<div class="page-user">
	
	
	<!-- Page -->
	<div class="page animsition">
		<div class="page-header">
			<h1 class="page-title">Ingresos</h1>
			<div class="page-header-actions">
				<ol class="breadcrumb">
					<li><a href="/private/index">CPQ</a></li>
					<li class="active">Ingresos</li>
				</ol>
			</div>
		</div>
		<div class="page-content container-fluid">
			<div class="panel">
	        
		        <div class="panel-body user-list-wrap">
			        <div class="col-sm-12">
						<h3>Opciones</h3>
					</div>
			        <div class="col-sm-4">
						<!-- Widget -->
						<a  data-toggle="modal" onclick="initGestionNuevoIngreso()" data-target="#gestionNuevoIngreso" href="" aria-expanded="false" aria-controls="gestionAntecedentes">
							<div class="widget">
								<div class="widget-content widget-radius padding-20 bg-blue-100 clearfix">
									<div class="counter counter-md pull-left text-left">
										<div class="counter-number-group">
											<span class="counter-number" ></span>
											<span class="counter-number-related text-capitalize font-size-18">Nuevo Ingreso</span>
										</div>
									</div>
									<div class="pull-right white">
										<i class="icon icon-circle icon-2x wb-user-add bg-blue-600" aria-hidden="true"></i>
									</div>
								</div>
							</div>
						</a>
						<!-- End Widget -->
					</div>
					
					<div class="col-sm-12">
						<h3>Listado de ingresos</h3>
					</div>
					
					<div class="col-sm-12 top15">
				      <table id="tablaIngresos" class="table table-hover dataTable table-striped width-full">
				         <thead>
				            <tr>
				               <th>Habitación</th>
				               <th>Paciente</th>
				               <th>Médico</th>
				               <th>Acciones</th>
				            </tr>
				         </thead>
				         <tbody></tbody>
				         <tfoot>
				         	<tr>
				               <th>Habitación</th>
				               <th>Paciente</th>
				               <th>Médico</th>
				               <th>Acciones</th>
				            </tr>
				         </tfoot>
				      </table>
				   </div>
					
					
					
		        </div>
	        </div>
			
		</div>
	</div>
  
  
	<form action="./imprimirAlta" method="get" id="fImpAlta" target="_blank">
		<input type="hidden" name="impIdeAlta" id="impIdeAlta">
			
	</form>  
  
  
</div>
  <!-- End Page -->
  
 

<!-- Ventanas -->

<!-- Modal Registro Antecedentes-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionNuevoIngreso"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h3 class="modal-title">Nuevo Ingreso</h3>
			</div>
			
			<!-- REGISTRAR USUARIO -->
			<div class="modal-body registro-usr">
				<div class="row text-center">
					<div class="col-sm-6">
		            <div class="form-group" id="fgNombreAlta">
		              <label for="nompacAlta">Nombre</label>
		              <input type="text" class="form-control input-lg" id="nompacAlta" name="nompacAlta" placeholder="Nombre" required>
		            </div>
		          </div>
		          <div class="col-sm-6">
		            <div class="form-group" id="fgSurName1Alta">
		              <label for="ap1pacAlta">Primer Apellido</label>
		              <input type="text" class="form-control input-lg" id="ap1pacAlta" name="ap1pacAlta"  placeholder="Primer Apellido">
		            </div>
		          </div>
		          <div class="col-sm-6">
		            <div class="form-group" id="fgSurName2Alta">
		              <label for="ap2pacAlta">Segundo Apellido</label>
		              <input type="text" class="form-control input-lg" id="ap2pacAlta" name="ap2pacAlta"  placeholder="Segundo Apellido">
		            </div>
		          </div>
		          
		          <div class="col-sm-6">
		            <div class="form-group" id="fgSeguro">
		              <label for="sIdseguroAlta">Seguro</label>
		              <select class="form-control input-lg" id="sIdseguroAlta" name="sIdseguroAlta" ></select>
		            </div>
		          </div>
		          
		          <div class="col-sm-3">
		            <div class="form-group" id="fgSexpacAlta">
		              <label for="sexpacAlta">Sexo</label>
		              <select class="form-control input-lg" id="sexpacAlta" name="sexpac" >
		                <option value = "">Elegir opción</option>
		                <option value = "H">Hombre</option>
		                <option value = "M">Mujer</option>
		              </select>
		            </div>
		          </div>
		          <div class="col-sm-3">
		            <div class="form-group" id="fgFijAlta">
		              <label for="numtelfijusr">Teléfono 1</label>
		              <input type="text" class="form-control input-lg" id="numtel1Alta" name="numtel1Alta" data-plugin="formatter" data-pattern="[[999]][[999]][[999]]" placeholder="Teléfono">
		              <p class="help-block">123 123 123</p>
		            </div>
		          </div>
		          <div class="col-sm-3">
		            <div class="form-group" id="fgtipDoc">
		              <label for="tipdocAlta">Tipo Doc.</label>
		              <select class="form-control input-lg" id="tipdocAlta" name="tipdocAlta" >
		                <option value = "DNI">D.N.I</option>
		                <option value = "NIE">N.I.E</option>
		                <option value = "PAS">PASAPORTE</option>
		              </select>
		            </div>
		          </div>
		          <div class="col-sm-3">
		            <div class="form-group" id="fgDniAlta">
		              <label for="dniusrAlta">DNI</label>
		              <input type="text" class="form-control input-lg text-uppercase" id="dniusrAlta" name="dniusrAlta"  placeholder="Introduce DNI" data-plugin="formatter" data-pattern="[[99999999]]-[[a]]">
		              <input type="text" class="form-control input-lg text-uppercase hide" id="nieusrAlta" name="nieusrAlta"  placeholder="Introduce NIE" data-plugin="formatter" data-pattern="[[a9999999]]-[[a]]">
		              <input type="text" class="form-control input-lg text-uppercase hide" id="passusrAlta" name="passusrAlta"  placeholder="Introduce Pasaporter" data-plugin="formatter" data-pattern="[[***************]]">
		            </div>
		          </div>
		           <div class="col-sm-12">
			         <button type="button" class="btn btn-default" onclick="cancelarAltaRapida()">Cancelar</button>
			         <button type="button" class="btn btn-success" onclick="confirmarAltaRapida()">Crear Paciente</button>
			      </div>
				</div>
		     </div>
			
			<!-- SELECCION DE PACIENTE -->
			<div class="modal-body selecPac">
				
				<div class="form-group row">
				   <div class="col-sm-12">
				      <h3>SELECCIÓN DE PACIENTE</h3>
				   </div>
				   <div class="col-sm-12">
				      <div class="col-sm-4">
				         <div class="form-group" id="fgNombre">
				            <label for="nompac">Nombre</label>
				            <input type="text" class="form-control input-lg" id="nompacBAlta" name="nompacBAlta" placeholder="Nombre">
				         </div>
				      </div>
				      <div class="col-sm-4">
				         <div class="form-group" id="fgSurName1">
				            <label for="ap1pac">Primer Apellido</label>
				            <input type="text" class="form-control input-lg" id="ap1pacBAlta" name="ap1pacBAlta" placeholder="Primer Apellido">
				         </div>
				      </div>
				      <div class="col-sm-4">
				         <div class="form-group" id="fgSurName2">
				            <label for="ap2pacBAlta">Segundo Apellido</label>
				            <input type="text" class="form-control input-lg" id="ap2pacBAlta" name="ap2pacBAlta" placeholder="Segundo Apellido">
				         </div>
				      </div>
				      <div class="col-sm-4">
				         <div class="form-group" id="fgIdHistorialBusq">
				            <label for="idHistorialBAlta">Núm. Historial</label>
				            <input type="number" class="form-control input-lg" id="idHistorialBusq" name="idHistorialBusq" placeholder="Número Historia">
				         </div>
				      </div>
				      <div class="col-sm-4">
				         <div class="form-group" id="fgtipDocBusq">
				            <label for="tipdocBAlta">Tipo Doc.</label>
				            <select class="form-control input-lg" id="tipdocBAlta" name="tipdocBAlta">
				               <option value="DNI">D.N.I</option>
				               <option value="NIE">N.I.E</option>
				               <option value="PAS">PASAPORTE</option>
				            </select>
				         </div>
				      </div>
				      <div class="col-sm-4">
				         <div class="form-group" id="fgDniBusq">
				            <label for="dniusr">Número Documento</label>
				            <input type="text" class="form-control input-lg text-uppercase" id="dniusrBAlta" name="dniusrBAlta" placeholder="Introduce DNI" data-plugin="formatter" data-pattern="[[99999999]]-[[a]]">
				            <input type="text" class="form-control input-lg text-uppercase hide" id="nieusrBAlta" name="nieusrBAlta" placeholder="Introduce NIE" data-plugin="formatter" data-pattern="[[a9999999]]-[[a]]">
				            <input type="text" class="form-control input-lg text-uppercase hide" id="passusrBAlta" name="passusrBAlta" placeholder="Introduce Pasaporter" data-plugin="formatter" data-pattern="[[***************]]">
				         </div>
				      </div>
				      <div class="col-sm-12">
				         <button type="button" class="btn btn-default" onclick="limpiarBusqueda()">Limpiar</button>
				         <button type="button" class="btn btn-success" onclick="buscarPacienteNomAp()">Buscar</button>
				      </div>
				   </div>
				   <div class="col-sm-12 top15">
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
				         <tbody></tbody>
				      </table>
				   </div>
				</div>


			</div>
			<!-- INFORMACIÓN DEL INGRESO -->
			<div class="modal-body infoIngreso">
				
				<div class="form-group row">
				   <div class="col-sm-12">
				      <h3>INFORMACIÓN DEL INGRESO</h3>
				   </div>
				   <div class="col-sm-12">
				      <div class="col-sm-5">
				         <div class="form-group" id="fgRedporAlta">
				            <label for="redporAlta">Remitido desde</label>
				            <select class="form-control input-lg" id="redporAlta" name="redporAlta">
				            	<option value="">Remitido desde ...</option>
				               	<option value="CPQ">Centro policlínico quirúrgico</option>
				               	<option value="CLU">Clínica Luz</option>
				              	<option value="CAJ">Centro ajeno</option>
				               	<option value="MAJ">Médico ajeno</option>
				            </select>
				         </div>
				      </div>
				      
				      <div class="col-sm-5 fgDesRedPorAlta hide">
				         <div class="form-group" id="fgDesRedPorAlta">
				            <label for="ap2pacBAlta">Médico o centro remitente</label>
				            <input type="text" class="form-control input-lg" id="desRedPorAlta" name="desRedPorAlta" placeholder="Remitente">
				         </div>
				      </div>
				      
				      <div class="form-group col-sm-8">
						  <label for="comentario">Médico</label>
						  
						  <select class="form-control input-lg" id="sMedAlta" name="sMedAlta" >
						  <option value = "">Elegir un Profesional</option>
						  <option value = "URG">URGENCIAS</option>
						  <optgroup id="sMedOpMed" role="group" label="Medicos">
						  </select>
					 </div>
					 
					 <div class="form-group col-sm-8" >
						  <label for="comentario">Asignar Habitación</label>
						  
						  <select class="form-control input-lg" id="sHabAlta" name="sHabAlta" >
						 
						  </select>
					 </div>
				      
				   </div>
				   
				   <div class="col-sm-12">
				   		<button type="button" class="btn btn-default" onclick = "cancelGuardado()">Cancelar</button>
						<button type="button" class="btn btn-success" onclick="insertarIngreso()">Finalizar</button>
				   </div>
				  
				</div>


			</div>
			
			
			
			
			
		</div>
		<div class="modal-footer">
		</div>
	</div>
</div>


<!-- Modal Gestión solicitudes-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionSolicitudes"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h3 class="modal-title">Solicitudes</h3>
			</div>
			
			<!-- REGISTRAR USUARIO -->
			<div class="modal-body resumenSol">
				<div class="form-group row">
					<div class="col-sm-12">
						<button type="button" class="btn btn-primary nSolicitud" onclick = "initNewSol()">Nueva solicitud</button>
						
					</div>
					
					
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						 <h4>Solicitudes Actuales</h4>
					</div>
					<div class="col-sm-12 top15">
				      <table id="tablaSolicitudesAct" class="table table-hover dataTable table-striped width-full user-list">
				         <thead>
				            <tr>
				               <th>Tipo</th>
				               <th>Descripción </th>
				               <th>fecha </th>
				               <th>Acciones</th>
				            </tr>
				         </thead>
				         <tbody></tbody>
				      </table>
				   </div>
					
				</div>
			</div>
			
			<div class="modal-body nuevaSolForm">
				<div class="form-group row">
					<div class="col-sm-12">
				      <h3>Nueva solicitud</h3>
				   	</div>
				   	
				   	<!-- Formulario -->
				   	<div class="col-sm-4">
         				<div class="form-group" id="fgtipSol">
				            <label for="tipSolAlta">Tipo solicitud</label>
				            <select class="form-control input-lg" id="tipSolAlta" name="tipSolAlta">
				            </select>
         				</div>
      				</div>
      				<div class="col-sm-12">
                		<div class="form-group">
	                  		<label for="comentario">Descripción de la solicitud</label>
	                  		<textarea class="form-control input-lg" id="desSolAlta" name="comentario" rows="3" placeholder="Describe la solicitud"></textarea>
                		</div>
              		</div>
              		
              		<div class="col-sm-12">
              			<button type="button" class="btn btn-primary nSolicitud" onclick = "volverGestionSolucitudes()">Cancelar</button>
						<button type="button" class="btn btn-primary nSolicitud" onclick = "insertNuevaSolicitud()">Guardar</button>
					</div>
				   	<!-- Fin formulario -->
				</div>
				
		    </div>
			
			
			
			
			
			
			
		</div>
		<div class="modal-footer">
		</div>
	</div>
</div>


<!-- Modal Evolutivos-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionEvolutivos"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h3 class="modal-title">Evolutivos</h3>
			</div>
			
			<!-- REGISTRAR USUARIO -->
			<div class="modal-body resumenEvo">
				<div class="form-group row">
					<div class="col-sm-12">
						<button type="button" class="btn btn-primary nSolicitud" onclick = "initNewEvo('N')">Añadir Evolutivo</button>
						<button type="button" class="btn btn-success nSolicitud" onclick = "initNewEvo('S')">Informe de alta</button>
						
					</div>
					
					
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						 <h4>Evolutivos actuales</h4>
					</div>
					<div class="col-sm-12 top15">
				      <table id="tablaEvolutivosAct" class="table table-hover dataTable table-striped width-full user-list">
				         <thead>
				            <tr>
				               <th>Médico</th>
				               <th>Descripción </th>
				               <th>fecha </th>
				               <th>Acciones</th>
				            </tr>
				         </thead>
				         <tbody></tbody>
				      </table>
				   </div>
					
				</div>
			</div>
			
			<div class="modal-body newEvo">
				<div class="form-group row">
					<div class="col-sm-12">
						 <h4 class="detNewEvol">Nuevo Evolutivo</h4>
					</div>
					
					
				</div>
				<div class="form-group">
					<div class="top15 sm-12" id="descEvoNew-wrap">
						
                    	<textarea name="content" data-plugin="summernote" rows="30" id="descEvoNew" style="display: none;"></textarea>
                    </div>
                    <div class="top15 col-sm-12">
              			<button type="button" class="btn btn-primary nSolicitud" onclick = "volverGestionEvolutivo()">Cancelar</button>
						<button type="button" class="btn btn-primary nSolicitud" onclick = "insertNuevoEvolutivo()">Guardar</button>
					</div>
				</div>
				
				
			</div>
			
			
		</div>
		<div class="modal-footer">
		</div>
	</div>
</div>

<!-- Modal Medicacion-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionMedicacion"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h3 class="modal-title">Medicación</h3>
			</div>
			
			<!-- REGISTRAR USUARIO -->
			<div class="modal-body resumenMedi">
				<div class="form-group row">
					<div class="col-sm-12">
						<button type="button" class="btn btn-primary nSolicitud" onclick = "initNewMedi()">Pautar</button>
						
					</div>
					
					
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						 <h4>Medicación Pautada</h4>
					</div>
					<div class="col-sm-12 top15">
				      <table id="tablaMedicacionAct" class="table table-hover dataTable table-striped width-full user-list">
				         <thead>
				            <tr>
				               <th>Medicación</th>
				               <th>Pauta</th>
				               <th>Fecha inicio</th>
				               <th>Fecha Fin</th>
				               <th>Médico</th>
				               <th>Acciones</th>
				            </tr>
				         </thead>
				         <tbody></tbody>
				      </table>
				   </div>
					
				</div>
			</div>
			
			<div class="modal-body newMedi">
				<div class="form-group row">
					<div class="col-sm-12">
						 <h4>Nueva medicación</h4>
					</div>
					
					
				</div>
				<div class="form-group">
				<form id="nuevoMedForm">
					<div class="top15 col-sm-6">
						<label for="comentario">Medicamento</label>
                    	<select id='sMedicamentos' name ="idmedicamento" class="form-control input-lg" data-plugin="select2" multiple="multiple" data-placeholder="Medicamentos:" style="width: 100%;">
			               <optgroup id="Medicamentos" label="">
			               </optgroup>
			            </select>
                    </div>
                    
                     <div class="col-sm-2">
				         <div class="form-group" id="fgDosis">
				            <label for="id">Dosis</label>
				            <input type="number" class="form-control input" id="idDosis" name="dosis" placeholder="Dosis">
				         </div>
				      </div>
				      
				      <div class="col-sm-3">
				         <div class="form-group" id="fgPeriodicidad">
				         	<label for="dIni">Vías de administración</label>
				            <select class="form-control sHora" id="sTipVia" name="tipvia" > 
				            <option value = "">Vía</option>
					            <option value = "VO">Oral</option>
					            <option value = "VG">Gastroentérica</option>
					            <option value = "VP">Parenteral</option>
				            </select>
				         </div>
				      </div>
				      
				    <div class="col-sm-12">
						<div class="checkbox-custom checkbox-primary">
							<input type="checkbox" id="swiPrecisa" name="swiprecisa"/>
							<label for="swiPrecisa">Suministrar solo sí precisa.</label>
						</div>
					</div>
				      
				      <div class="col-sm-12">
				      	<h2 for="id">Pauta Tratamiento</h2>
				      </div>
				      
				      <div class="col-sm-3">
				         <div class="form-group" id="fgPeriodicidad">
				         	<label for="dIni">Intervalo - Horas</label>
				            <select class="form-control sHora" id="sNumHpauta" name="sNumHpauta" > </select>
				         </div>
				      </div>
				      <div class="col-sm-3">
				         <div class="form-group" id="fgPeriodicidad">
				         	<label for="dIni">Intervalo - Minutos</label>
				            <select class="form-control sHora" id="sNumMpauta" name="sNumMpauta" > 
				            </select>
				         </div>
				      </div>
				      
				      <div class="col-sm-3">
				         <div class="form-group" id="fgFini">
				            <label for="dIni">Fecha inicio</label>
				            <input class="form-control datepicker" id="dIni" name="fecini" >
				         </div>
				      </div>
				      
				      <div class="col-sm-3">
				         <div class="form-group" id="fgFfin">
				            <label for="dFin">Fecha Fin</label>
				            <input class="form-control datepicker" id="dFin" name="fecfin"  >
				         </div>
				      </div>
				      
				      <div class="col-sm-12 fObs">
		                <div class="form-group">
		                  <label for="comentario">Observaciones</label>
		                  <textarea class="form-control input-lg" id="obsPauta" name="descripcion" rows="3"  placeholder="Introduce una observación si procede"></textarea>
		                </div>
		              </div>
                    
                     <div class="clearfix top15"></div>
                    <div class="top15 col-sm-12 top15">
              			<button type="button" class="btn btn-primary nSolicitud" onclick = "volverGestionMedicacion()">Cancelar</button>
						<button type="button" class="btn btn-primary nSolicitud" onclick = "guardarMedicamentosIngreso()">Guardar</button>
					</div>
					</form>
				</div>
				
				
			</div>
			
			
		</div>
		<div class="modal-footer">
		</div>
	</div>
</div>

<!-- Modal Medicacion enfermera-->
<div class="modal fade modal-3d-flip-horizontal " id="gestionMedicacionEnf"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
	<div class="modal-dialog modal-lg modal-custom-lg1380">
		<div class="modal-content mcActMed">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h3 class="modal-title">Medicación Pautada</h3>
			</div>
			
			<!-- REGISTRAR USUARIO -->
			<div class="modal-body resumenMediPau">
				
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btnHistMed" onclick = "initVtnMedHistorico()">Historico</button>
			</div>
		</div>
		<div class="clearfix"></div>
		
		<div class="modal-content hide mcHistMed">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h3 class="modal-title">Histórico medicación</h3>
			</div>
			
			<!-- REGISTRAR USUARIO -->
			<div class="modal-body">
				
				<table id="tablaHistMedicacion" class="table table-hover dataTable table-striped width-full">
				         <thead>
				            <tr>
				               <th>Fecha Adm.</th>
				               <th>Fecha Pautada</th>
				               <th>Medicación</th>
				               <th>Adm. por</th>
				            </tr>
				         </thead>
				         <tbody></tbody>
				         <tfoot>
				         	<tr>
				               <th>Fecha Adm.</th>
				               <th>Fecha Pautada</th>
				               <th>Medicación</th>
				               <th>Adm. por</th>
				            </tr>
				         </tfoot>
				      </table>
				
			</div>
			
			
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btnHistMed" onclick = "initVtnMedSuministrada()">Actual</button>
			</div>
		</div>
	</div>
</div>


@stop
@section('js', '/js/app/private/tablonIngresos.js')
