@extends('layout.private.private')

@section('title', 'Documentos')
@section('css', '/private/assets/css/pages/user.css')

@section('content').

<script type="text/javascript">
  var listaTipos = <?= json_encode($listaTipos) ?>;
  var listaEsp = <?= json_encode($listaEsp) ?>;
  var listadoEmpresas = <?= json_encode($listadoEmpresas) ?>;
  var idEmpresaSes = <?= json_encode($idEmpresaSes) ?>;
</script>

<div class="page-profile">
  <!-- Page -->
  <div class="page animsition">
    <div class="page-header">
      <h1 class="page-title">Documentos</h1>
      <div class="page-header-actions">
        <ol class="breadcrumb">
          <li><a href="/private/index">private</a></li>
          <li class="active">Documentos</li>
        </ol>
      </div>
    </div>
    <div class="page-content">
	
	<div class="panel">
        
        <div class="panel-body user-list-wrap">
		<div class="form-group row">
			<div class="col-sm-12">
				<h3>Tipos de documento</h1>
			</div>
				<div class="clearfix"></div>
				<div id="listaTiposDocs">
						
				
				<div>
			</div>
        </div>
      </div>
      <!-- End Panel Basic -->
    </div>
  </div>
	
      <!-- Panel Basic -->
      <div class="panel">
        
        <div class="panel-body user-list-wrap">
		<div class="form-group row">
			<div class="col-sm-12">
				<h3>Lista de documentos Disponibles</h1>
			</div>
			<form action="./imprimirDoc" method="post" id="fImpDoc" target="_blank">
				<input type="hidden" name="impIdeDocDat" id="impIdeDoc">
				<div class="clearfix"></div>
				<div id="listaDocs">
				</div>				
				
				<div class="col-sm-12 hide" id="divImpGrup" >
					<button type="button" class="btn btn-default" onclick = 'imprimirDocGrupo()'>Imprimir</button>
				</div>
					
			</form>
			</div>
        </div>
      </div>
      <!-- End Panel Basic -->
    </div>
  </div>
  
  
  
  <!-- End Page -->
</div>


  <!-- Modal Datos informe-->
<div class="modal fade modal-3d-flip-horizontal" id="datosDoc"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onclick = "limpiarForm()" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Datos Documento</h3>
      </div>
      <div class="modal-body">
        
        <div class="row text-left hide" id="formBusqPac">
			<div class="col-sm-12">
				<h3 class="modal-title">Paciente</h3>
			</div>
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
			<div class="col-sm-12">
				<button type="button" class="btn btn-default" onclick = 'buscarPacienteNomAp()'>Buscar</button>
			</div>
			 <div class="clearfix"></div>
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
      
      <div class="row text-left hide" id="formBusqDoc">
			<div class="col-sm-12">
				<h3 class="modal-title">Médico</h3>
			</div>
			
			<div class="col-sm-12">
				<div class="checkbox-custom checkbox-primary">
					<input type="checkbox" id="usuMedAll" name="usuMedAll"/>
					<label for="usuMedAll">Usar mismo medico para todos los informes (actualmente es solo para un medico)</label>
				</div>
				<div class="checkbox-custom checkbox-primary usuAneCir">
					<input type="checkbox" id="usuAneCir" name="usuAneCir"/>
					<label for="usuAneCir">Cirujano/Anestesista: El cirujano que firmará todas menos "CONSENTIMIENTO Y AUTORIZACIÓN PARA ANESTESIA" y "ESTUDIO PREOPERATORIO"</label>
				</div>
			</div>
			
			<div class="col-sm-12">
				<button type="button" class="btn btn-success" id="btnOtrMed" onclick = 'initAddOtroMed()'>Medico no registrado</button>
				
				<div class="col-sm-4 formOtroMed">
					<div class="form-group" id="fOMNombre">
						<label for="nompac">Nombre</label>
						<input type="text" class="form-control input-lg" id="oMNomDoc" name="oMNomDoc" placeholder="Nombre">
					</div>
				</div>
				<div class="col-sm-4 formOtroMed">
					<div class="form-group" id="fOMSurName1">
						<label for="ap1pac">Apellidos</label>
						<input type="text" class="form-control input-lg" id="oMApDoc" name="oMApDoc"  placeholder="Apellidos">
					</div>
				</div>
				
				<div class="col-sm-12 formOtroMed">
					<button type="button" class="btn btn-success" onclick = 'guardarOtrosMed()'>Añadir</button>
					<button type="button" class="btn btn-danger" onclick = 'cancelarAddOtrosMed()'>Cancelar</button>
				</div>
			</div>
			
			<div  class="col-sm-12 dosImpSelect">
				<span> <strong>Medicos Actual: </strong></span> 
				<div id="medsSelectBusqAct" class="col-sm-12">
				</div>
			</div>
			
			<div class="form-group col-sm-12 dosImpSelect">
	      		<label for="roles">Documentos</label>
	       		<select id='sdocsConMed' class="form-control" data-plugin="select2" multiple="multiple" data-placeholder="Documentos:">
	               <optgroup id="docConMed" label="">
	               </optgroup>
	            </select>
	            
	            <div class="col-sm-12">
					<button type="button" class="btn btn-primary" id="btnGCD" onclick = 'guardarConfigDoc()'>Guardar y añadir</button>
					<button type="button" class="btn btn-success" id="btnGCCD" onclick = 'guardarContinuarConfigDoc()'>Guardar y continuar</button>
				
				</div>
	            
            </div>
				<div class="col-sm-4 top15 formBusqDatos">
					<div class="form-group" id="fgNombre">
						<label for="nompac">Nombre</label>
						<input type="text" class="form-control input-lg" id="nomDoc" name="nomDoc" placeholder="Nombre">
					</div>
				</div>
				<div class="col-sm-4 top15 formBusqDatos">
					<div class="form-group" id="fgSurName1">
						<label for="ap1pac">Primer Apellido</label>
						<input type="text" class="form-control input-lg" id="apDoc" name="apDoc"  placeholder="Apellidos">
					</div>
				</div>
				<div class="col-sm-4 top15 formBusqDatos">
					<div class="form-group" id="fgSurName2">
						<label for="ap2pac">Nº Colegiado</label>
						<input type="text" class="form-control input-lg" id="numColDoc" name="numColDoc"  placeholder="Número de colegiado">
					</div>
				</div>
				<div class="col-sm-12 top5 formBusqDatos">
					<button type="button" class="btn btn-default" onclick = 'buscarDocNomAp()'>Buscar</button>
				</div>
				 <div class="clearfix"></div>
				<div class="col-sm-12 top5 formBusqDatos">
					<table id="tablaMedicos" class="table table-hover dataTable table-striped width-full user-list">
			            <thead>
			              <tr>
			              	<th>ID</th>
			                <th>Nombre</th>
			                <th>Apellidos</th>
			                <th>Nº Colegiado</th>
			                <th>Fecha Baja</th>
			                <th>Acciones</th>
			              </tr>
			            </thead>
			            <tfoot>
			             
			            </tfoot>
			            <tbody>
			            </tbody>
		          	</table>
				</div>
			
			<div  class="col-sm-12 consfigActuales">
				<span> <strong>Medicos Configurados: </strong></span> 
				<div class="medsSelectBusq" class="col-sm-12">
				</div>
			</div>
							  
      </div>
	  
		<form action="./imprimirDoc" class="hide" method="post" id="fImpDocDatos" target="_blank">
			<input type="hidden" name="impIdePacDat" id="impIdePacDat">
			<input type="hidden" name="impIdeDocDat" id="impIdeDocDat">
			<input type="hidden" name="impIdeMedDat" id="impIdeMedDat">
			<input type="hidden" name="impNomMedDat" id="impNomMedDat">
			<input type="hidden" name="impIdeMedDatDoc" id="impIdeMedDatDoc">
			<input type="hidden" name="swiGrupo" id="swiGrupo">
			<input type="hidden" name="numGrupo" id="numGrupo">
			
			<div class="clearfix"></div>
			<div id="datsDocsDiv" class="hide">
				<span> <strong>El Paciente seleccionado es: </strong></span> <span id="sNombreCompleto"></span> 
			</div>
			<div id="datsMedDocsDiv" class="hide">
				<span> <strong>El Médico seleccionado es: </strong></span> <span id="sNombreCompletoMed"></span> 
			</div>
			<div id="datsVarMedDocsDiv" class="hide">
				<span> <strong>Medicos Configurados: </strong></span> 
				<div class="medsSelectBusq" class="col-sm-12">
				</div>
			</div>
			
			<div class="col-sm-12 hide" id="divFirmaDoc">
				<input type="checkbox" id="swiFirma" name="swiFirma" data-plugin="switchery" data-switchery="true" style="display: none;">
				<label class="padding-top-3" for="inputBasicOff"><strong>Firma paciente en dispositivo</strong></label>
        	</div>
        	
        	<div class="col-sm-12 hide" id="divFirmaMedDoc">
				<input type="checkbox" id="swiFirmaMed" name="swiFirmaMed" data-plugin="switchery" data-switchery="true" style="display: none;">
				<label class="padding-top-3" for="inputBasicOff"><strong>Firma medico en dispositivo</strong></label>
        	</div>
			
			 <div class="col-sm-12 hide" id="divCitPac">
			  	<div class="checkbox-custom checkbox-primary">
					<input type="checkbox" id="swiPacJus" name="swiPacJus" onchange="verOcultarDatol()" value="S"/>
					<label for="swiPacJus">Solicitantes del Justificante es el propio paciente</label>
				</div>
				<div class="col-sm-4">
					<div class="form-group hide dSol" >
						<label for="nompac">Nombre Solicitante</label>
						<input type="text" class="form-control input-lg" id="nombreSol" name="nombreSol" placeholder="Nombre">
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group dSol" >
						<label for="nompac">Apellidos del solicitante</label>
						<input type="text" class="form-control input-lg" id="apSol" name="apSol" placeholder="Apellidos">
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group dSol" >
						<label for="nompac">Documento Solicitante</label>
						<input type="text" class="form-control input-lg" id="solDoc" name="solDoc" placeholder="Documento">
					</div>
				</div>
	            <div class="form-group" >
	              <label for="sexpac">Listado de citas</label>
	              <select class="form-control input-lg" id="idCitaSelect" name="idCitaSelect" >
	                <option value = ""></option>
	              </select>
	            </div>
	            
	            
	           
	          </div>
	          
	          <div class="col-sm-12">
                <div class="form-group" >
	              <label for="listEmpresa">Listado de Empresas</label>
	              <select class="form-control input-lg" id="listEmpresa" name="listEmpresa" >
	                <option value = ""></option>
	              </select>
	            </div>
             </div>
			
			<div class="col-sm-12">
                <div class="form-group hide" id="divFirmMedImp">
					<div class="checkbox-custom checkbox-primary">
						<input type="checkbox" id="impFiMed" name="impFiMed" value="S"/>
						<label for="impFiMed">INCLUIR FIRMA DR.VERD</label>
					</div>
                </div>
            </div>
            
            <div class="col-sm-12">
                <div class="form-group hide" id="divLitPrivado">
					<div class="checkbox-custom checkbox-primary">
						<input type="checkbox" id="impLitPrivado" name="impLitPrivado" value="S"/>
						<label for="impLitPrivado">NO USAR SEGURO DEL PACIENTE (PRIVADO)</label>
					</div>
                </div>
            </div>
            
            <div class="col-sm-9">
				<div class="form-group" id="divMedNoObl">
					<label for="nompac">Nombre médico no obligatorio</label>
					<input type="text" class="form-control input-lg" id="nomMedNoObl" name="nomMedNoObl" placeholder="Médico">
				</div>
			</div>
			
			<div class="col-sm-3">
	              <div class="form-group has-datepicker col-sm-12 hide" id="fGene">
					<label for="sfechGen">Fecha</label>
					<input class="col-sm-12 cdpickDesde input-lg" data-provide="datepicker" id="sfechGen" name="sfechGen" data-date-format="dd/mm/yyyy"> 
				  </div>
             </div>
            
            <div class="col-sm-12 hide" id="divActqui">
            
            <label for="categoria top15">Acto quirujico (Elegir si corresponde)</label>
			<select class="form-control input-lg" id="actQui" name="actQui" >
				<option value = ''>Acto quirurjico</option>
			</select>
            
            </div>
            
            
            
            <div class="col-sm-12">
				<div class="form-group" class="hide" id="divImpPruebas">
					<h3 class="modal-title">Pruebas a realizar</h3>
					<div class="checkbox-custom checkbox-primary col-sm-4">
	                	<input type="checkbox" id="cPrea" name="cPrea" />
	                	<label for="cPrea">PREANESTÉSIA (DR. VERD)</label>
              		</div>
              		<div class="checkbox-custom checkbox-primary col-sm-4">
	                	<input type="checkbox" id="cAna" name="cAna" />
	                	<label for="cAna">ANÁLISIS</label>
              		</div>
              		<div class="checkbox-custom checkbox-primary col-sm-4">
	                	<input type="checkbox" id="cEco" name="cEco" />
	                	<label for="cEco">ECOGRAFÍA</label>
              		</div>
              		<div class="checkbox-custom checkbox-primary col-sm-4">
	                	<input type="checkbox" id="cRxt" name="cRxt" />
	                	<label for="cRxt">RX TÓRAX</label>
              		</div>
              		<div class="checkbox-custom checkbox-primary col-sm-4">
	                	<input type="checkbox" id="cAudio" name="cAudio" />
	                	<label for="cAudio">AUDIO</label>
              		</div>
              		<div class="checkbox-custom checkbox-primary col-sm-4">
	                	<input type="checkbox" id="cVisi" name="cVisi" />
	                	<label for="cVisi">VISIÓN</label>
              		</div>
              		<div class="checkbox-custom checkbox-primary col-sm-4">
	                	<input type="checkbox" id="cEspi" name="cEspi" />
	                	<label for="cEspi">ESPIROMETRÍA</label>
              		</div>
              		<div class="checkbox-custom checkbox-primary col-sm-4">
	                	<input type="checkbox" id="cEle" name="cEle" />
	                	<label for="cEle">ELECTRO</label>
              		</div>
				</div>
			</div>
			
			
			
			
			<div class="col-sm-12">
				<div class="form-group hide" id="divImpEmp">
					<label for="comentario">Empresa</label>
					<textarea class="form-control input-lg" id="impEmp" name="impEmp" rows="1"  placeholder="Indique la empresa"></textarea>
				</div>
			</div>
			
			<div class="col-sm-12">
			
                <div class="form-group hide" id="divComentImp">
                  <label for="comentario">Observaciones/Conceptos</label>
                  <textarea class="form-control input-lg" data-plugin="summernote" id="impObsDat" name="impObsDat" rows="3"  placeholder="Introduce las observaciones necesarias"></textarea>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group hide" id="divAutoImp">
                  <label for="comentario">Autorización</label>
                  <textarea class="form-control input-lg" id="impAuto" name="impAuto" rows="1"  placeholder="Autorización"></textarea>
                </div>
              </div>
			   <div class="col-sm-12">
                <div class="form-group hide" id="divProImp">
                  <label for="comentario">Procedimiento</label>
                  <textarea class="form-control input-lg" id="impProc" name="impProc" rows="1"  placeholder="Procedimiento"></textarea>
                </div>
              </div>
			   <div class="col-sm-12">
                <div class="form-group hide" id="divEspImp">
                  <label for="comentario">Especialidad</label>
				  <select class="form-control input-lg" id="impEspe" name="impEspe" >
					<option value = "">Elegir opción</option>
				  </select>
                  <!--<textarea class="form-control input-lg" id="impEspe" name="impEspe" rows="1"  placeholder="Autorización"></textarea>-->
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group hide" id="divResHisCliImp">
                  <label for="comentario">Resumen Historia Clínica</label>
                  <textarea class="form-control input-lg" id="impResHisCli" name="impResHisCli" rows="3"  placeholder="Introduce las observaciones necesarias"></textarea>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group hide" id="divHaObsImp">
                  <label for="comentario">Hallazgos Observados</label>
                  <textarea class="form-control input-lg" id="impHaObsCli" name="impHaObsCli" rows="3"  placeholder="Introduce las observaciones necesarias"></textarea>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group hide" id="divPezRemImp">
                  <label for="comentario">Pieza Remitida</label>
                  <textarea class="form-control input-lg" id="impPezRem" name="impPezRem" rows="3"  placeholder="Pieza Remitida"></textarea>
                </div>
              </div>
              
              <div class="col-sm-12" id="horaIniInterImp">
                 <div class="col-sm-4">
		            <div class="form-group has-datepicker" id="fgNac">
		              <label for="fecnacpac">Fecha Intervención</label>
		              <input type="text" class="form-control input-lg" id="sFecIni" name="sFecIni"  data-plugin="formatter" data-pattern="[[99]]/[[99]]/[[9999]]">
		            </div>
		          </div>

				<div class="col-sm-4">
		            <div class="form-group">
		              <label>Hora Inicio Intervención</label>
		              <select class="form-control sHora" name="sHoraIni" id="sHoraIni" >
		              </select>
		            </div>
		          </div>
		          <div class="col-sm-4">
		            <div class="form-group">
		              <label>Minutos inicio Intervención</label>
		              <select class="form-control sMinutos" id="sMinIni" name="sMinIni">
		              </select>
		            </div>
		          </div>


              </div>
              
              <div class="col-sm-12">
                <div class="form-group hide" id="divNhistImp">
                  <label for="comentario">Número de Historia</label>
                  <textarea class="form-control input-lg" id="impNHist" name="impNHist" rows="1"  placeholder="Número de historia"></textarea>
                </div>
              </div>
              
              
              <div class="col-sm-12">
                <div class="form-group hide" id="divIntImp">
                  <label for="comentario">Intervención</label>
                  <textarea class="form-control input-lg" id="impInterven" name="impInterven" rows="1"  placeholder="Intervención"></textarea>
                </div>
              </div>
              
              <div class="col-sm-12">
                <div class="form-group hide" id="divBoxImp">
                  <label for="comentario">BOX</label>
                  <textarea class="form-control input-lg" id="impBox" name="impBox" rows="1"  placeholder="Box"></textarea>
                </div>
              </div>
              
              <div class="col-sm-12">
                <div class="form-group hide" id="divNotImp">
                  <label for="comentario">Notas</label>
                  <textarea class="form-control input-lg" id="impNot" name="impNot" rows="3"  placeholder="Notas"></textarea>
                </div>
              </div>
              
              <div class="col-sm-4">
	              <div class="form-group has-datepicker col-sm-12 hide" id="fgDesde">
					<span class="input-group-addon">
					  <i class="icon wb-calendar" aria-hidden="true"><label style="margin-left: 5px;" for="fecnacpac"> Fecha Desde</label></i>
					</span>
					<input class="col-sm-12 cdpickDesde" data-provide="datepicker" id="sFecDesde" name="sFecDesde" data-date-format="dd/mm/yyyy"> 
				  </div>
             </div>
	          
	         <div class="col-sm-4">
	              <div class="form-group has-datepicker col-sm-12 hide" id="fgHasta">
					<span class="input-group-addon">
					  <i class="icon wb-calendar" aria-hidden="true"><label style="margin-left: 5px;" for="fecnacpac">Fecha Hasta</label></i>
					</span>
					<input class="col-sm-12 cdpickHasta" data-provide="datepicker" id="sFecHasta" name="sFecHasta" data-date-format="dd/mm/yyyy"> 
				  </div>
             </div>
             
           <div class="col-sm-12">
            <div class="form-group" id="fgSeguro">
              <label for="sIdseguro">Seguro</label>
              <select class="form-control input-lg" id="sIdseguro" name="sIdseguro" ></select>
            </div>
          </div>
	          
	         <div class="col-sm-12">
						<div class="form-group hide" id="divMed" >
						  <label for="sMed">Médico</label>
						  <select class="form-control input-lg" id="sMed" name="sMed" >
						  </select>
						</div>
					</div>
		
		<div class="col-sm-12">
        	<div class="form-group hide" id="divImporte">
            	<label for="importe">Importe</label>
				<input type="text" class="form-control input-lg" id="importe" name="importe" placeholder="Importe">
			</div>
		</div>
              
		</form>
		
		<div class="col-sm-12 top15">
				<button type="button" class="btn btn-primary hide" id="btnVol" onclick = 'volverPagina()'>Volver</button>
				<button type="button" class="btn btn-success hide" id="btnImp" onclick = 'imprimirImpDocDatos()'>Finalizar</button>
				
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
@section('js4', '/js/app/private/listaDocs.js')
