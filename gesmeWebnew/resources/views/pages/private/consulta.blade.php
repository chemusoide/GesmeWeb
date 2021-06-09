@extends('layout.private.private')

@section('title', 'Mi Perfil')


@section('css', '/private/assets/vendor/blueimp-file-upload/jquery.fileupload.css')
@section('css1', '/private/assets/vendor/imageResizeCrop/component.css')
@section('css2', '/private/assets/css/pages/cita.css')



@section('content')
<script type="text/javascript">
  var idCita = <?= json_encode($id) ?>;
  var citaActual = <?= json_encode($cita) ?>;
  var detalleCitActual = <?= json_encode($detalleCita) ?>;
  var listAlergiasActual = <?= json_encode($listAlergias) ?>;
  var pacienteSelect = <?= json_encode($pacienteSelect) ?>;  
</script>
<div class="page-profile">
   <!-- Page -->
   <div class="page animsition">
      <div class="page-header">
         <h1 class="page-title"><strong>Consulta <span class="hide" id="lblFinal">Finalizada</span></strong></h1>
         <h4 class=""><strong>Paciente :</strong> <span class="datosTitPac">ansdjasnk</span></h4>
         <h4 id="cabSeg" class="hide"><strong>Seguro :</strong> <span class="datosSegPac"></span></h4>
         <div class="page-header-actions">
            <ol class="breadcrumb">
               <li><a href="/private/index">private</a></li>
               <li class="active">Consulta</li>
            </ol>
         </div>
      </div>
      <div class="page-content container-fluid hide" id="contenerdor">
         <div class="row">
            <div class="panel panel-bordered">
               <div class="panel-heading cHand" data-toggle="collapse" data-parent="#accordion" href="#collapseAl">
                  <h3 class="panel-title">ANTECEDENTES/HISTORIALES/PROCEDIMIENTOS/PRUEBAS COMPLEMENTARIAS</h3>
                  <div class="panel-actions">
                     <a class="panel-action icon wb-minus" data-toggle="collapse" data-parent="#accordion" href="#collapseAl"></a>
                  </div>
               </div>
               <div class="collapse panel-body" id="collapseAl">
                  <div class="col-sm-4">
                     <!-- Widget -->
                     <a  data-toggle="modal" id='acollapseAl' onclick="prepararVentanaAlergia()" data-target="#gestionAlergias" href="" aria-expanded="false" aria-controls="gestionAlergias">
                        <div class="widget">
                           <div class="widget-content widget-radius padding-20 bg-red-100 clearfix">
                              <div class="counter counter-md pull-left text-left">
                                 <div class="counter-number-group">
                                    <span class="counter-number" ></span>
                                    <span class="counter-number-related text-capitalize font-size-18">Alergias</span>
                                 </div>
                              </div>
                              <div class="pull-right white">
                                 <i class="icon icon-circle icon-2x wb-warning bg-red-600" aria-hidden="true"></i>
                              </div>
                           </div>
                        </div>
                     </a>
                     <!-- End Widget -->
                  </div>
                  <div class="col-sm-4">
                     <!-- Widget -->
                     <a  data-toggle="modal" onclick="prepararVentanaAntecedentes(true)" data-target="#gestionAntecedentes" href="" aria-expanded="false" aria-controls="gestionAntecedentes">
                        <div class="widget">
                           <div class="widget-content widget-radius padding-20 bg-green-100 clearfix">
                              <div class="counter counter-md pull-left text-left">
                                 <div class="counter-number-group">
                                    <span class="counter-number" ></span>
                                    <span class="counter-number-related text-capitalize font-size-18">Antecedentes</span>
                                 </div>
                              </div>
                              <div class="pull-right white">
                                 <i class="icon icon-circle icon-2x wb-eye bg-green-600" aria-hidden="true"></i>
                              </div>
                           </div>
                        </div>
                     </a>
                     <!-- End Widget -->
                  </div>
                  <div class="col-sm-4">
                     <!-- Widget -->
                     <a  data-toggle="modal" onclick="prepararVentanaHistConsultas()" data-target="#gestionHistConsultas" href="" aria-expanded="false" aria-controls="gestionHistConsultas">
                        <div class="widget">
                           <div class="widget-content widget-radius padding-20 bg-green-100 clearfix">
                              <div class="counter counter-md pull-left text-left">
                                 <div class="counter-number-group">
                                    <span class="counter-number" ></span>
                                    <span class="counter-number-related text-capitalize font-size-18">Historia / Anteriores Consultas</span>
                                 </div>
                              </div>
                              <div class="pull-right white">
                                 <i class="icon icon-circle icon-2x wb-folder bg-green-600" aria-hidden="true"></i>
                              </div>
                           </div>
                        </div>
                     </a>
                     <!-- End Widget -->
                  </div>
                  <div class="col-sm-4">
                     <!-- Widget -->
                     <a  data-toggle="modal" onclick="prepararVentanaPrueCompl()" data-target="#gestionPrueComple" href="" aria-expanded="false" aria-controls="gestionHistConsultas">
                        <div class="widget">
                           <div class="widget-content widget-radius padding-20 bg-green-100 clearfix">
                              <div class="counter counter-md pull-left text-left">
                                 <div class="counter-number-group">
                                    <span class="counter-number" ></span>
                                    <span class="counter-number-related text-capitalize font-size-18">Pruebas Complementarias</span>
                                 </div>
                              </div>
                              <div class="pull-right white">
                                 <i class="icon icon-circle icon-2x wb-gallery bg-green-600" aria-hidden="true"></i>
                              </div>
                           </div>
                        </div>
                     </a>
                     <!-- End Widget -->
                  </div>
                  <div class="col-sm-4">
                     <!-- Widget -->
                     <div class="widget">
                        <div class="widget-content widget-radius padding-20 bg-green-100 clearfix">
                           <div class="counter counter-md pull-left text-left">
                              <div class="counter-number-group">
                                 <span class="counter-number" ></span>
                                 <span class="counter-number-related text-capitalize font-size-18">Procedimientos</span>
                              </div>
                           </div>
                           <div class="pull-right white">
                              <i class="icon icon-circle icon-2x wb-scissor bg-green-600" aria-hidden="true"></i>
                           </div>
                        </div>
                     </div>
                     <!-- End Widget -->
                  </div>
                  <div class="col-sm-4">
                     <!-- Widget -->
                     <a  data-toggle="modal" onclick="prepararVentanaObs()" data-target="#gestionObservacion" href="" aria-expanded="false" >
                        <div class="widget">
                           <div class="widget-content widget-radius padding-20 bg-green-100 clearfix">
                              <div class="counter counter-md pull-left text-left">
                                 <div class="counter-number-group">
                                    <span class="counter-number" ></span>
                                    <span class="counter-number-related text-capitalize font-size-18">Observaciones</span>
                                 </div>
                              </div>
                              <div class="pull-right white">
                                 <i class="icon icon-circle icon-2x wb-info bg-green-600" aria-hidden="true"></i>
                              </div>
                           </div>
                        </div>
                     </a>
                     <!-- End Widget -->
                  </div>
               </div>
            </div>
            <div class="col-sm-12 divDefAlergia">
               <!-- Widget -->
               <div class="widget">
                  <div class="widget-content widget-radius padding-20 bg-red-200 clearfix">
                     <div class="pull-left white">
                        <i class="icon icon-circle icon-2x wb-warning bg-red-600" aria-hidden="true"></i>
                     </div>
                     <div class="counter counter-md pull-left text-left">
                        <div class="counter-number-group">
                           <span class="counter-number" ></span>
                           <span class="counter-number-related text-capitalize font-size-18">Paciente Con Alergias:</span>
                           <button type="button" class="btn btn-danger" onclick="collapseAlClick()">Añadir/eliminar</button>
                        </div>
                        <div class="counter-label text-capitalize font-size-16" id='desAlergia'></div>
                     </div>
                  </div>
               </div>
               <!-- End Widget -->
            </div>
            <div class="col-sm-12 divSinAlergia">
               <!-- Widget -->
               <div class="widget">
                  <div class="widget-content widget-radius padding-20 bg-green-200 clearfix">
                     <div class="pull-left white">
                        <i class="icon icon-circle icon-2x wb-thumb-up bg-green-600" aria-hidden="true"></i>
                     </div>
                     <div class="counter counter-md pull-left text-left">
                        <div class="counter-number-group">
                           <span class="counter-number" ></span>
                           <span class="counter-number-related text-capitalize font-size-18">Paciente Sin Alergias</span>
                           <button type="button" class="btn btn-danger" onclick="collapseAlClick()">Añadir/eliminar</button>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- End Widget -->
            </div>
            <div class="col-md-12">
               <!-- Panel -->
               <div class="panel">
                  <div class="panel-body">
                     <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
                        <li class="active" id="oLconsilt" role="presentation"><a data-toggle="tab" href="#lineasConsulta" role="tab">Lineas de consulta</a></li>
                        <li class="" id="oDiagnostico" role="presentation"><a data-toggle="tab" href="#opDiagnostico"  role="tab">Diagnóstico</a></li>
                        <li role="presentation"><a data-toggle="tab" href="#opTratamiento" aria-controls="password" role="tab">Tratamiento</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active padding-10" id="lineasConsulta" role="tabpanel">
                           <input type="hidden" id='formId' name='formId'>
                           <div class="panel">
                              <div class="panel-heading">
                                 <h3 class="panel-title">Lineas de consulta</h3>
                                 <button type="button" class="btn btn-success" onclick="insertarPlatilla('A')">Insertar Plantilla</button>
                              </div>
                              <div class="form-group top15" id="taLconsilt-wrap">
                                 <textarea name="content" data-plugin="summernote" rows="30" id="taLconsilt" style="display: none;"></textarea>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane padding-10" id="opDiagnostico" role="tabpanel">
                           <input type="hidden" id='formIdD' name='formId'>
                           <div class="panel">
                              <div class="panel-heading hDiagnostico">
                                 <h3 class="panel-title">Diagnostico</h3>
                                 <button type="button" class="btn btn-success" onclick="insertarPlatilla('D')">Insertar Plantilla</button>
                                 
                                 <button type="button" data-toggle="modal" data-target="#gestionHistConsultas" href="" aria-expanded="false" aria-controls="gestionHistConsultas"
                                 	class="btn btn-info" onclick="prepararVentanaHistConsultas('S')">Ver diagnósticos anteriores</button>
                                                       
                                 
                              </div>
                              <div class="form-group top15" id="taDiagnostico-wrap">
                                 <textarea name="content" data-plugin="summernote" rows="30" id="taDiagnostico" style="display: none;"></textarea>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane  padding-10" id="opTratamiento" role="tabpanel">
                           <div class="panel">
                              <div class="panel-heading">
                                 <h3 class="panel-title">Tratamiento</h3>
                                 <button type="button" class="btn btn-success" onclick="insertarPlatilla('T')">Insertar Plantilla</button>
                              </div>
                              <div class="form-group top15" id="taTratamiento-wrap">
                                 <textarea name="content" data-plugin="summernote" rows="30" id="taTratamiento" style="display: none;"></textarea>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-12 ">
                        <button type="button" class="btn btn-default btnSave" onclick="prepararGuardado('G')">Guardar</button>
                        <button type="button" class="btn btn-success btnFin" onclick="prepararGuardado('GF')">Finalizar</button>
                        <button type="button" class="btn btn-info btnFImp" onclick="prepararGuardado('GFIMP')">Finalizar e Imprimir</button>
                        <button type="button" class="btn btn-info btnImp hide" onclick="imprimirCita()">Imprimir</button>
                        <form action="../imprimirHistorial" method="get" id="fImpHist" target="_blank">
                           <input type="hidden" name="impIdePac" id="impIdePac">
                           <input type="hidden" name="impIdMed" id="impIdMed">
                           <input type="hidden" name="impFechaDesde" id="impFechaDesde">
                           <input type="hidden" name="impAlergia" id="impAlergia" value="S">
                           <input type="hidden" name="swiCodId" id="swiCodId" value="S">
                           <input type="hidden" name="impAntecedente" id="impAntecedente" value="N">
                           <input type="hidden" name="impHabito" id="impHabito" value="N">
                           <input type="hidden" name="impMorfologia" id="impMorfologia" value="N">
                           <input type="hidden" name="impListCitas" id="impListCitas" value="S">
                           <input type="hidden" name="impIdConsulta[]" id="impIdConsulta">
                           <input type="hidden" name="impTipConsulta[]" id="impTipConsulta">
                           <div id="divInputAdd">
                           </div>
                        </form>
                     </div>
                  </div>
                  <!-- End Panel -->
               </div>
            </div>
         </div>
      </div>
      <!-- End Page -->
   </div>
</div>


 <!-- Modal Registro Alergias-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionAlergias"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onclick = "limpiarForm()" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Antecedentes</h3>
      </div>
      <div class="modal-body">
      
            <div class="col-sm-6">
	            <div class="form-group" id="fgAlerg">
	              <label for="colegiado">Alergia:</label>
	              <input type="text" class="form-control input" id="alergeno" name="alergeno" placeholder="">
	            </div>
          	</div>
	          <div class="col-sm-6">
		        <button type="button" class="btn btn-default top27" onclick = 'limpiarFormAlergias()'>Limpiar</button>
		        <button type="button" class="btn btn-success top27" onclick="guardarAlergia()">Añadir alergia</button>
		      </div>

      	<div class="clearfix"></div>
      	<div class="col-sm-12 top15">
          <table id="tablaAlergias" class="table table-hover dataTable table-striped width-full user-list">
            <thead>
              <tr>
                <th>Alergia</th>
                <th>Fecha Creacion</th>
                <th>Acciones</th>
              </tr>
            </thead>
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
  
  <!-- Modal Registro Antecedentes-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionAntecedentes"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onclick = "limpiarForm()" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Antecedentes del Paciente</h3>
      </div>
      <div class="modal-body">
       		<ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
              	<li id="oAntecedentes" role="presentation" class="active" ><a data-toggle="tab" href="#opAntecedentes" role="tab">Antecedentes</a></li>
                <li id="oHabitos" role="presentation" ><a data-toggle="tab" href="#opHabitos"  role="tab">Hábitos Tóxicos</a></li>
                <li id="oMorfologia" role="presentation"><a data-toggle="tab" href="#opMorfologia" role="tab">Morfología</a></li>
      		</ul>
      		
      		<div class="tab-content">
				<div class="tab-pane active padding-10" id="opAntecedentes" role="tabpanel">
                  <input type="hidden" id='formIdA' name='formId'>
                  <div class="panel">
                   
                    <button type="button" class="btn btn-danger bdivContAnt" onclick="mostrarDivNuevo('divContAnt')">
			            <i class="icon wb-plus" aria-hidden="true"></i> Nuevo antecedente
			         </button>
			         <div class="divContAnt hide">
			         	<div class="well well-lg">
				            <div class="pregunta">
				              <h3 class="title">Nuevo Antecedente</h3>
				              <label for="pregunta">Descripción</label>
				              <textarea class="form-control input-lg" id="divContAntDes" name="desantnew" rows="5" placeholder="Descripción del antecedente"></textarea>
				              <label for="pregunta">Observaciones</label>
				              <textarea class="form-control input-lg" id="divContAntObs" name="obsantnew" rows="2" placeholder="Escribe una Observación"></textarea>
				            </div>
				            <div class="botonera top15">
						        <button type="button" class="btn btn-danger" onclick="ocultarDivNuevo('divContAnt')">Cancelar</button>
						        <button type="button" class="btn btn-success" onclick="guardarAntecedente('divContAnt', 'ANT')">Guardar</button>
					    	</div>
			          	</div>
			         </div>
			         <div class="clearfix"></div>
      				 <div class="col-sm-12 top15">
      				    <table id="tablaAntecedente" class="table table-hover dataTable table-striped width-full user-list">
				            <thead>
				              <tr>
				                <th>Antecedentes</th>
				                <th>Alta</th>
				                <th>Acciones</th>
				              </tr>
				            </thead>
				            <tbody>
				            </tbody>
			          </table>
			         </div>
                  </div>
                </div>
                
                
                <div class="tab-pane padding-10" id="opHabitos" role="tabpanel">
                  <input type="hidden" id='formIdH' name='formId'>
                  <div class="panel">
                   
                    <button type="button" class="btn btn-danger bdivContHab" onclick="mostrarDivNuevo('divContHab')">
			            <i class="icon wb-plus" aria-hidden="true"></i> Nuevo Hábito
			         </button>
			         <div class="divContHab hide">
			         	<div class="well well-lg">
				            <div class="pregunta">
				              <h3 class="title">Nuevo Hábito</h3>
				              <label for="pregunta">Descripción</label>
				              <textarea class="form-control input-lg" id="divContHabDes" name="desantnew" rows="5" placeholder="Descripción del Hábito"></textarea>
				              <label for="pregunta">Observaciones</label>
				              <textarea class="form-control input-lg" id="divContHabObs" name="obsantnew" rows="2" placeholder="Escribe una Observación"></textarea>
				            </div>
				            <div class="botonera top15">
						        <button type="button" class="btn btn-danger" onclick="ocultarDivNuevo('divContHab')">Cancelar</button>
						        <button type="button" class="btn btn-success" onclick="guardarAntecedente('divContHab', 'HAB')">Guardar</button>
					    	</div>
			          	</div>
			          	
			         </div>
			         <div class="clearfix"></div>
      				 <div class="col-sm-12 top15">
      				    <table id="tablaHab" class="table table-hover dataTable table-striped width-full user-list">
				            <thead>
				              <tr>
				                <th>Habitos</th>
				                <th>Alta</th>
				                <th>Acciones</th>
				              </tr>
				            </thead>
				            <tbody>
				            </tbody>
			          </table>
			         </div>
                  </div>
                </div>
                
                
                <div class="tab-pane padding-10" id="opMorfologia" role="tabpanel">
                  <input type="hidden" id='formIdM' name='formId'>
                  <div class="panel">
                   
                    <button type="button" class="btn btn-danger bdivContMor" onclick="mostrarDivNuevo('divContMor')">
			            <i class="icon wb-plus" aria-hidden="true"></i> Nueva Morfología
			         </button>
			         <div class="divContMor hide">
			         	<div class="well well-lg">
				            <div class="pregunta">
				              <h3 class="title">Nueva Morfología</h3>
				              <label for="pregunta">Descripción</label>
				              <textarea class="form-control input-lg" id="divContMorDes" name="divContMorDes" rows="5" placeholder="Descripción de la Morfología"></textarea>
				              <label for="pregunta">Observaciones</label>
				              <textarea class="form-control input-lg" id="divContMorObs" name="divContMorObs" rows="2" placeholder="Escribe una Observación"></textarea>
				            </div>
				            <div class="botonera top15">
						        <button type="button" class="btn btn-danger" onclick="ocultarDivNuevo('divContMor')">Cancelar</button>
						        <button type="button" class="btn btn-success" onclick="guardarAntecedente('divContMor', 'MOR')">Guardar</button>
					    	</div>
			          	</div>
			          	
			         </div>
			           <div class="clearfix"></div>
      				 <div class="col-sm-12 top15">
      				    <table id="tablaMor" class="table table-hover dataTable table-striped width-full user-list">
				            <thead>
				              <tr>
				                <th>Morfología</th>
				                <th>Alta</th>
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
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
  </div>

<!-- Modal Consulta Historico citas-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionHistConsultas"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Historico de consultas</h3>
      </div>
      <div class="modal-body">
       	 <h4 class="modal-title">Especialiad Consultas Previas</h4>
     	 <div  class="btn-group padding-20" role="group" id="contenedorConPrev">
      	
	  	
       	</div>
     	 <h4 class="modal-title">Listado de consultas</h4>
     	<div class="form-group" id="contenedorHist">
      	
	  	
       </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>


<!-- Modal Consulta Historico citas Paginado-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionHistConsultasPag"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Historico de consultas</h3>
      </div>
      <div class="modal-body">
       	 
     	 <h4 class="modal-title">Listado de consultas de <? echo $cita->{'especialidad'} ?></h4>
     	<div class="form-group" id="contenedorHistPag">
      	
	  	
       </div>
      </div>
      <div class="modal-footer fHistPag">
      </div>
    </div>
  </div>
</div>

  <!-- Modal Pruebas Complementarias-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionPrueComple"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Pruebas Complementarias</h3>
      </div>
      <div class="modal-body">
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



  <!-- Modal Observaciones-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionObservacion"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Observaciones</h3>
      </div>
      <div class="modal-body">
       	 
       		<button type="button" class="btn btn-danger bdivContObs" onclick="mostrarDivNuevo('divContObs')">
           		<i class="icon wb-plus" aria-hidden="true"></i> Nueva Observación
         	</button>
        	<div class="divContObs well well-lg hide">
         	      <h3 class="title">Nueva Observación</h3>
			      <textarea class="form-control input-lg" id="divContObsObs" name="obsantnew" rows="3" placeholder="Escribe una Observación"></textarea>
	             
	            <div class="botonera top15">
				    <button type="button" class="btn btn-danger" onclick="ocultarDivNuevo('divContObs')">Cancelar</button>
		        	<button type="button" class="btn btn-success" onclick="guardarObs()">Guardar</button>
    			</div>
			</div>
       	<!-- Imagen -->
     
    
    	<div class="col-sm-12 top15">
          <table id="tablaObs" class="table table-hover dataTable table-striped width-full">
            <thead>
              <tr>
                <th>Fecha Creacion</th>
                <th>Observacion</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          </div>   
    

       	
       	
       	<!-- Fin PDF -->
       	
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>  
</div>

<!-- Modal Consulta Historico citas-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionMisPlantillas"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Mis Plantillas</h3>
      </div>
      <div class="modal-body">
       	 <h4 class="modal-title">Seleccione la plantilla a insertar</h4>
     	 <div class="col-sm-12 top15">
      		<table id="tablaPlantilla" class="table table-hover dataTable table-striped width-full">
	            <thead>
	              <tr>
	                <th>Titulo</th>
	                <th>Plantilla</th>
	                <th>Acciones</th>
	              </tr>
	            </thead>
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


@stop


@section('js', '/private/assets/vendor/imageResizeCrop/component.js')
@section('js1', '/private/assets/js/apps/app.js')
@section('js2', '/js/app/private/cita.js')
@section('js3', '/js/app/private/pruebaComplementaria.js')
