@extends('layout.private.private')

@section('title', 'Busqueda Médicos')
@section('css', '/private/assets/css/pages/user.css')

@section('content')

<script type="text/javascript">
  var listaMedicos = <?= json_encode($listaMedicos) ?>;
  var listaOtrProf = <?= json_encode($listaOtrProf) ?>;
  var listaDocsPend = <?= json_encode($listaDocsPend) ?>;
  var listadoEmpresas = <?= json_encode($listadoEmpresas) ?>;
  
</script>

<div class="page-profile">
  <!-- Page -->
  <div class="page animsition">
    <div class="page-header">
    
    
    
      <h1 class="page-title">Médicos <button type="button" id="btnDocPendFirm" class="btn btn-danger hide" onclick="verListaDocsPendFirma()" data-toggle="modal" data-target="#docsPendFirm" >Documentos pendientes de firma</button></h1>  
      <div class="page-header-actions">
        <ol class="breadcrumb">
          <li><a href="/private/index">private</a></li>
          <li class="active pagActual">Médicos</li>
        </ol>
      </div>
    </div>
    <div class="page-content">
      <!-- Panel Basic -->
      <div class="panel" id="panelFiltro">
        
        <div class="panel-body user-list-wrap">
			<div class="form-group row">
				<div class="col-sm-12">
					<h3>Seleccione un médico para ver su disponibilidad</h3>
					<div class="col-sm-12">
						<div class="form-group col-sm-8" id="divMed" >
						  <label for="comentario">Médico</label>
						  
						  <select class="form-control input-lg" id="sMed" name="sMed" >
						  <option value = "">Elegir un Profesional</option>
						  <optgroup id="sMedOpMed" role="group" label="Medicos">
						  </select>
						</div>
						<div id="dBtnCitRap" class="col-sm-4  hide">
						 <a class="btn btn-info col-sm-12 top30" id="aGestionCitasSolVac" onclick="citaRapida()"  href="#"  >
			                Dar primera cita disponible para hoy
			             </a>
		             </div>
					</div>
					
					<div id="dGestionCitasSolVac" class="col-sm-12 hide">
						 <a class="btn btn-danger col-sm-12" id="aGestionCitasSolVac" onclick="citasEnvacacionesInit()" data-toggle="modal" data-target="#gestionCitasVacaciones" href="" aria-expanded="false" aria-controls="gestionPacientes">
			                Citas en fechas con Doctor ausente
			             </a>
		             </div>
		             
		             <div id="dCambioCita" class="col-sm-12 hide">
						<h3 class="col-sm-12" id="txtCambioCita"> </h3>
						<a class="btn btn-danger col-sm-3" id="" onclick="cancelarCambio()" href="#" aria-expanded="false">
			                Cancelar Cambio
			             </a>
		             </div>
					<!--  <div class="col-sm-3 top30">
						<div class="form-group" id="divImpInf" class="divImpInf">
						 
						</div>
					</div>-->
				  
				  
				  
				 
				<div id="divCal" class="page-main col-sm-12">
					<div id="calendar" class="fc fc-ltr fc-unthemed"></div>
				</div>
			   
					<div class="row text-center col-sm-9 hide" id="containerBtnCita">
						<div class="col-sm-12" >
							
				             <div id="dAbrirAgenda" class="col-sm-12 hide">
								 <a class="btn btn-danger col-sm-12" id="aAbrirAgenda" onclick="initPantallaVacaiones()" data-toggle="modal" data-target="#gestionVacaciones" href="" aria-expanded="false" aria-controls="gestionPacientes">
					                Abrir Agenda
					             </a>
				             </div>
							<div id='horasDisp' class="respuestas-pregunta-wrap col-md-12"></div>
							<a class="btn btn-warning" id="aCitManual" onclick="initPantallaGestionCita()" data-toggle="modal" data-target="#gestionCitas" href="" aria-expanded="false" aria-controls="gestionPacientes">
				                Gestionar Cita Manual
				             </a>
							</div>
								
							<div class="col-sm-12 top15" style="border-top:solid 1px;">
								<div class="modal-footer" >
									<button type="button" class="btn btn-default" onclick = "cancelGuardado()">Cancelar</button>
									<button type="button" class="btn btn-success" onclick="continuarCita()">Continuar</button>
									 <button type="button" class="btn btn-info" onclick = "imprimirListadoPacientes()">Imprimir Listado pacientes</button>
									 
									 <form action="./obtCitasMedDiario" class="hide" method="get" id="fListPac" target="_blank">
										<input type="hidden" name="fechaCitaListado" id="fechaCitaListado">
										<input type="hidden" name="medListado" id="medListado">
											
									</form>
								</div>
							</div>
					</div>
					<div class="row text-center col-sm-9 hide" id="containerInfoCita">
						<div class="col-sm-12" >
							<div class="col-sm-6">
								<div class="form-group" id="fgEspec">
									<label for="sexpac">Especialidad</label>
									<select class="form-control" id="sEspecMed" name="sEspecMed" ></select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group" id="empSelect">
									<label for="idEmpCita">Empresa</label>
									<select class="form-control input" id="idEmpCita" name="idEmpCita" >
					                
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group" id="fgIdHistorialBusq">
									<label for="idHistorialBusq">Núm. Historial</label>
									<input type="number" class="form-control input" id="idHistorialBusq" name="idHistorialBusq"  placeholder="Número Historia">
								</div>
							</div>
							<div class="col-sm-4" >
								<div class="form-group">
								  <label for="nompac">Nombre: <span class="hide" id="snompac"></span></label>
								  <input type="text" class="form-control input" id="nompac" name="nompac"  placeholder="Nombre del paciente">
								  
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group" id="fgSurName1">
								  <label for="ap1pac">Primer Apellido: <span class="hide" id="sap1pac"></span></label>
								  <input type="text" class="form-control input" id="ap1pac" name="ap1pac"  placeholder="Primer Apellido">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group" id="fgSurName2">
									<label for="ap2pac">Segundo Apellido: <span class="hide" id="sap2pac"></span></label>
									<input type="text" class="form-control input" id="ap2pac" name="ap2pac"  placeholder="Segundo Apellido">
									
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
					          <div class="col-sm-8">
					            <div class="form-group" id="fgDniBusq">
					              <label for="dniusr">Número Documento</label>
					              <input type="text" class="form-control input-lg text-uppercase" id="dniusrBusq" name="dniusrBusq"  placeholder="Introduce DNI" data-plugin="formatter" data-pattern="[[99999999]]-[[a]]">
					              <input type="text" class="form-control input-lg text-uppercase hide" id="nieusrBusq" name="nieusrBusq"  placeholder="Introduce NIE" data-plugin="formatter" data-pattern="[[a9999999]]-[[a]]">
					              <input type="text" class="form-control input-lg text-uppercase hide" id="passusrBusq" name="passusrBusq"  placeholder="Introduce Pasaporter" data-plugin="formatter" data-pattern="[[***************]]">
					            </div>
					          </div>
					          <div class="col-sm-12 hide dObscita">
				                <div class="form-group">
				                  <label for="comentario">comentario</label>
				                  <textarea class="form-control input-lg" id="obscita" name="obscita" rows="3"  placeholder="Introduce un comentario"></textarea>
				                </div>
				              </div>
							<div class="col-sm-12">
									<button type="button" class="btn btn-default hide" id="btnLimp" onclick = "seleccionarPaciente()">Limpiar</button>
									<button type="button" class="btn btn-success" id="btnBusPac" onclick="buscarPacienteNomAp()">Buscar</button>
							</div>
							
							
							<div class="col-sm-12 top15" style="border-top:solid 1px;">
								<div class="modal-footer" >
									<button type="button" class="btn btn-default" onclick = "volverPaso(true)">volver</button>
									<button type="button" class="btn btn-success" onclick="guardarCita()">Finalizar</button>
								</div>
							</div>
					</div>
				</div>
				
				<div id="dGestionVac" class="col-sm-12 hide">
					 <a class="btn btn-warning col-sm-12" id="aGestionVac" onclick="initPantallaVacaiones()" data-toggle="modal" data-target="#gestionVacaciones" href="" aria-expanded="false" aria-controls="gestionPacientes">
		                Gestionar Vacaciones Medico
		             </a>
	             </div>
			</div>
        </div>
      </div>
      <!-- End Panel Basic -->
	 
	  
    </div>
  </div>
  <!-- End Page -->
</div>

<!-- Modal Pacientes Encontrados-->
<div class="modal fade modal-3d-flip-horizontal" id="panelBusq"   aria-hidden="true" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3>Resultados</h3>
      </div>
      <div class="modal-body">
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
     <div class="modal-footer">
     </div>
    </div>
  </div>
</div>

<!-- Modal Alta Rápida-->
<div class="modal fade modal-3d-flip-horizontal" id="panelAltRap"   aria-hidden="true" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3>Alta Rápida de Paciente</h3>
      </div>
      <div class="modal-body">
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
		</div>
     </div>
     <div class="modal-footer">
     	<button type="button" class="btn btn-default" onclick = 'cancelaAltaRapida()'>Cancelar</button>
        <button type="button" class="btn btn-success" onclick="altaRapidaPacientes()">Guardar</button>
     </div>
    </div>
  </div>
</div>

 <!-- Modal Pacientes-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionPacientes"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onclick = "" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Información de usuario</h3>
      </div>
      <div class="modal-body">
        <form  method="POST" action=""  enctype="multipart/form-data" id="fpAlta" data-toggle="validator">
          <div class="row text-center">
          <div class="col-sm-6">
            <div class="form-group" id="fgNombreMod">
              <label for="infonompac">Nombre</label>
              <input disabled type="text" class="form-control input-lg" id="infonompac" name="infonompac" placeholder="Nombre" required>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgSurName1Mod">
              <label for="infoap1pac">Primer Apellido</label>
              <input disabled type="text" class="form-control input-lg" id="infoap1pac" name="infoap1pac"  placeholder="Primer Apellido">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgSurName2Mod">
              <label for="infoap2pac">Segundo Apellido</label>
              <input disabled type="text" class="form-control input-lg" id="infoap2pac" name="infoap2pac"  placeholder="Segundo Apellido">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group has-datepicker" id="fgNacMod">
              <label for="infofecnacpac">Fecha de nacimiento <!-- <i class="icon wb-calendar" aria-hidden="true"></i> --></label>
              <input disabled type="text" class="form-control input-lg" id="infofecnacpac" name="infofecnacpac"  placeholder="Ej: 01/01/1985" data-plugin="formatter" data-pattern="[[99]]/[[99]]/[[9999]]">
            </div>
          </div>
         
          <div class="col-sm-3">
            <div class="form-group" id="fgSexpacMod">
              <label for="sexpac">Sexo</label>
              <select disabled class="form-control input-lg" id="infosexpac" name="sexpac" >
                <option value = "">Elegir opción</option>
                <option value = "H">Hombre</option>
                <option value = "M">Mujer</option>
              </select>
            </div>
          </div>
          
          <div class="col-sm-6">
            <div class="form-group" id="fgNumtel1Mod">
              <label for="infonumtelfijusr">Teléfono 1</label>
              <input disabled type="text" class="form-control input-lg" id="infonumtel1" name="infonumtel1" data-plugin="formatter" data-pattern="[[999]][[999]][[999]]" placeholder="Teléfono">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgNumtel2Mod">
              <label for="infonumtelmovusr">Teléfono 2</label>
              <input disabled type="text" class="form-control input-lg" id="infonumtel2" name="infonumtel2" data-plugin="formatter" data-pattern="[[999]][[999]][[999]]" placeholder="Teléfono alternativo">
            </div>
          </div>
          
          <div class="col-sm-6">
            <div class="form-group" id="fgtipDocMod">
              <label for="tipdocAlta">Tipo Doc.</label>
              <select class="form-control input-lg" id="tipdocinfo" name="tipdocinfo" >
                <option value = "DNI">D.N.I</option>
                <option value = "NIE">N.I.E</option>
                <option value = "PAS">PASAPORTE</option>
              </select>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgDniMod">
              <label for="dniusrAlta">DNI</label>
              <input type="text" class="form-control input-lg text-uppercase" id="infodniusr" name="infodniusr"  placeholder="Introduce DNI" data-plugin="formatter" data-pattern="[[99999999]]-[[a]]">
              <input type="text" class="form-control input-lg text-uppercase hide" id="infonieusr" name="infonieusr"  placeholder="Introduce NIE" data-plugin="formatter" data-pattern="[[a9999999]]-[[a]]">
              <input type="text" class="form-control input-lg text-uppercase hide" id="infopassusr" name="infopassusr"  placeholder="Introduce Pasaporter" data-plugin="formatter" data-pattern="[[***************]]">
            </div>
          </div>
          
           <div class="col-sm-3">
					<div class="form-group" id="fgIdHistorialBusq">
						<label for="idHistorialBusq">Núm. Historial</label>
						<input type="number" disabled class="form-control input-lg" id="infoidHistorial" name="infoidHistorial">
					</div>
				</div>
          
          <div class="col-sm-9" >
            <div class="form-group" id="fgEmaMod">
              <label for="infoemailpac">Email</label>
              <input disabled type="email" class="form-control input-lg" id="infoemailpac" name="infoemailpac"  placeholder="Correo electrónico">
            </div>
          </div>
          
          <div class="col-sm-10">
            <div class="form-group" id="fgDirMod">
              <label for="infodirpac">Domicilio</label>
              <input disabled type="text" class="form-control input-lg" id="infodirpac" name="infodirpac" placeholder="Domicilio">
            </div>
          </div>
          
          <div class="col-sm-2">
            <div class="form-group" id="fgCpMod">
              <label for="infocppac">Código Postal</label>
              <input disabled type="text" class="form-control input-lg" id="infocppac" name="infocppac" placeholder="C.P">
            </div>
          </div>
          
           <div class="col-sm-6">
            <div class="form-group" id="fgSeguro">
              <label for="sIdseguro">Seguro</label>
              <select disabled class="form-control input-lg" id="sIdseguro" name="sIdseguro" ></select>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgCp">
              <label for="numseg">Número Seguro</label>
              <input disabled type="text" class="form-control input-lg" id="numseg" name="numseg" placeholder="Número de Seguro">
            </div>
          </div>
         
          <div class="well well-lg">
             <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="infocomentario">Comentario</label>
                  <textarea disabled class="form-control input-lg" id="infocomentario" name="infocomentario" rows="3"  placeholder="Introduce un comentario"></textarea>
                </div>
              </div>
            </div>
          </div>
          </div>   
      </form>    
      </div>
      <div class="modal-footer">
       		<button type="button" id="bInitModPac" class="btn btn-warning waves-effect waves-light"  onclick = "initModPac()">
       			<i class="icon wb-pencil" aria-hidden="true"></i> Modificar Paciente
       		</button>
       		<button type="button" id="bGuardarModPac" class="btn btn-success waves-effect waves-light hide"  onclick = "guardarModPac()">
       			<i class="icon wb-check-mini" aria-hidden="true"></i> Guardar
       		</button>
       		<button type="button" id="bCancelModPac" class="btn btn-danger waves-effect waves-light hide"  onclick = "cancelarModPac()">
       			<i class="icon wb-close-mini" aria-hidden="true"></i> Cancelar
       		</button>
      </div>
    </div>
  </div>
  </div>
  <!-- End Modal Pacientes -->
  
  <!-- Modal Pacientes Encontrados-->
<div class="modal fade modal-3d-flip-horizontal" id="panelMedDia"   aria-hidden="true" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3>Medicos con citas para el día: <strong><span id="fechaPanelMedDia"> </span></strong></h3>
      </div>
      <div class="modal-body">
		<div class="col-sm-12">
			<table id="tablaMedDia" class="table table-hover dataTable table-striped width-full user-list">
				<thead>
				  <tr>
					<th>Nombre</th>
					<th>Apellidos</th>
					<th>Número Colegiado</th>
				  </tr>
				</thead>
				<tfoot>
				 
				</tfoot>
				<tbody>
				</tbody>
			</table>
		</div>
     </div>
     <form action="./imprimirListadoUsr" method="get" target="_blank">
		<input type="hidden" name="fechaCitaListado" id="fechaCitaListadoUsr">		
		<button type="submit" class="btn btn-default top15 left45">Imprimir</button>	
	</form>
     <div class="modal-footer">
     </div>
    </div>
  </div>
</div>

<!-- Modal Citas manuales-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionCitas"   aria-hidden="true" role="dialog" tabindex="-1">
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
<div class="modal fade modal-3d-flip-horizontal" id="gestionVacaciones"   aria-hidden="true" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3>Gestión de Vacaciones</h3>
      </div>
      <div class="modal-body">
      	<div class="col-md-4 padding-10" >
			<div class="col-md-12 padding-10" >
			 <span> Inicio Vacaciones</span>
		        <div class="input-group col-md-12">
	                <span class="input-group-addon">
	                	<i class="icon wb-calendar" aria-hidden="true"></i>
	                </span>
	                <input data-provide="datepicker" id = "dpIni" data-date-format="dd/mm/yyyy">
	             </div>
			</div>
			<div class="col-md-12 padding-10" >
			 <span> Fin Vacaciones</span>
		        <div class="input-group col-md-12">
	                <span class="input-group-addon">
	                	<i class="icon wb-calendar" aria-hidden="true"></i>
	                </span>
	                <input data-provide="datepicker" id = "dpFin" data-date-format="dd/mm/yyyy">
	             </div>
			</div>
			<div class="col-md-12 padding-10" >
		     	<button type="button" class="btn btn-default" onclick = "cancelCitaManual()">Limpiar</button>
				<button type="button" class="btn btn-success" onclick="guardarVacaciones()">Guardar</button>
	    	</div>
	     </div>
	     
	     <div class="col-sm-8">
			<table id="tablaVacaciones" class="table table-hover dataTable table-striped width-full user-list">
				<thead>
				  <tr>
					<th>Fecha Inicio</th>
					<th>Fecha Fin</th>
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

<!-- Modal docsPendFirm-->
<div class="modal fade modal-3d-flip-horizontal" id="docsPendFirm"   aria-hidden="true" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3>Médicos con documentos pendientes de firma</h3>
      </div>
      <div class="modal-body">
		<div class="col-sm-12">
			<table id="tablaMedDocsFirma" class="table table-hover dataTable table-striped width-full user-list">
				<thead>
				  <tr>
					<th>Médico</th>
					<th>Núm. Docs. pendientes</th>
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
<!-- Modal docsPendFirm-->

@stop
@section('js4', '/js/app/private/busqMedicos.js')