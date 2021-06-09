@extends('layout.private.private')

@section('title', 'Gestión de Visitas')
@section('css', '/private/assets/css/pages/user.css')

@section('content')
<div class="page-profile">
  <!-- Page -->
  <div class="page animsition">
    <div class="page-header">
      <h1 class="page-title">Tablón Actos quirúrgicos</h1>
      <div class="page-header-actions">
        <ol class="breadcrumb">
          <li><a href="/private/index">private</a></li>
          <li class="active">Actos Quirurjicos</li>
        </ol>
      </div>
    </div>
    <div class="page-content">


      <!-- Panel Basic -->
      <div class="panel">


        <div class="panel-body user-list-wrap">
             <div class="form-group row">
                  <div class="col-sm-12">
                    <h3>Opciones disponibles:</h3>
                </div>
                <div class="col-sm-12">
                    <button type="button" class="btn btn-primary btn-icon waves-effect waves-light"
                            data-toggle="modal" onclick="initAltaActQui()" data-target="#altaActQui" aria-expanded="false" aria-controls="altaActQui"  >
                        <i class="icon wb-replay" aria-hidden="true"></i> Nuevo Acto quirúrgico
                    </button>

                    <button type="button" class="btn btn-primary btn-icon waves-effect waves-light"
                            data-toggle="modal" onclick="initHistActQui()" data-target="#HistActQui" aria-expanded="false" aria-controls="HistActQui"  >
                        <i class="icon wb-inbox" aria-hidden="true"></i> Ver historico
                    </button>

                </div>
            </div>

        </div>
      </div>

      <div class="panel">
          <div class="panel-body user-list-wrap">
                 <div class="form-group row">
                      <div class="col-sm-12">
                        <h3>Actos Quirúrjicos Abiertos:</h3>
                    </div>
                    <div class="col-sm-12 top15">
                      <table id="tablaActQui" class="table table-hover dataTable table-striped width-full">
                         <thead>
                            <tr>
                               <th>Nombre</th>
                               <th>Apellidos</th>
                               <th  style="width: 150px;">Fecha intervención</th>
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


<!-- Modal Nuevo Acto Quirúrjico-->
<div class="modal fade modal-3d-flip-horizontal" id="altaActQui"  data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Nuevo Acto Quirúrjico</h3>
        <div id="contResumen">
            <div id="contResumenPac"></div>
            <div class="contResumenCie"></div>
            <div class="contResumenCiePro"></div>
            <div id="contResumenfec"></div>
        </div>
      </div>
      <div class="modal-body">
               <div id="paso1">
                       <h3 class="modal-title">Paciente</h3>
                    <span>Debe seleccionar el paciente sobre el cual se ejecutará el acto quirúrgico</span>
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


             <div id="paso2" class="hide">
                 <h3 class="modal-title">Diagnóstico Preliminar</h3>
                <span>Debe seleccionar el/los CIE correspondientes</span>
                   <div class="col-sm-12">
                       <div class="col-sm-6">
                        <div class="form-group" id="fgNombreBusq">
                            <label for="nompacEsp">Cie</label>
                             <input type="text" class="form-control input-lg text-uppercase"
                             id="claseCieBusq" name="claseCieBusq"  placeholder="CIE" data-plugin="formatter" data-pattern="[[a99]].[[999]]">

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group" id="fgNombreBusq">
                            <label for="nompacEsp">Descripción</label>
                             <input type="text" class="form-control input-lg text-uppercase" id="desCieBusq" name="desCieBusq"  placeholder="Descripción">

                        </div>
                    </div>
                    <div class="col-sm-12">
                        <button id="busqCieBtn" type="button" class="btn btn-success" onclick = 'buscarCieByClaseDesc("")'>Buscar CIE</button>
                    </div>
               </div>

                   <div class="col-sm-12 top15">
                      <table id="tablaCie" class="table table-hover dataTable table-striped width-full user-list">
                            <thead>
                              <tr>
                                <th>Clase</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>
                            <tfoot>
                              <tr>
                                <th>Clase</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                              </tr>
                            </tfoot>
                            <tbody>
                            </tbody>
                      </table>
                </div>
            </div>

            <div id="paso3" class="hide">
                 <h3 class="modal-title">Procedimiento Preliminar</h3>
                <span>Debe seleccionar el/los CIE correspondientes</span>
                   <div class="col-sm-12">
                       <div class="col-sm-6">
                        <div class="form-group" id="fgNombreBusq">
                            <label for="codigoCieProBusq">Cie</label>
                             <input type="text" class="form-control input-lg text-uppercase"
                             id="codigoCieProBusq" name="codigoCieProBusq"  placeholder="CIE" >

                        </div>

                    </div>
                    <div class="col-sm-6">
                        <div class="form-group" id="fgNombreBusq">
                            <label for="nompacEsp">Descripción</label>
                             <input type="text" class="form-control input-lg text-uppercase" id="desCieProBusq" name="desCieProBusq"  placeholder="Descripción">

                        </div>
                    </div>
                    <div class="col-sm-12">
                        <button id="busqCieProBtn" type="button" class="btn btn-success" onclick = 'buscarCieProByClaseDesc()'>Buscar CIE</button>
                    </div>
               </div>

                   <div class="col-sm-12 top15">
                      <table id="tablaCiePro" class="table table-hover dataTable table-striped width-full user-list">
                            <thead>
                              <tr>
                                <th>Clase</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>
                            <tfoot>
                              <tr>
                                <th>Clase</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                              </tr>
                            </tfoot>
                            <tbody>
                            </tbody>
                      </table>
                </div>
            </div>



            <div id="paso4" class="hide">
                    <span>Seleccione la fecha de la intervención</span>
                   <div class="col-sm-12">
                           <div class="col-sm-3">
                               <label for="sexpac">Fecha Inicio</label>
                               <input data-provide="datepicker" class="form-control" id = "fecIniAct" data-date-format="dd/mm/yyyy">

                          </div>
                          <div class="col-sm-2">
                            <div class="form-group" id="fgSexpac">
                              <label for="sexpac">Hora Inicio</label>
                              <select class="form-control sHora" id="horIniAct" >
                                      <option value="">Horas</option>
                                      <option value="0">00</option>
                                      <option value="1">01</option>
                                      <option value="2">02</option>
                                      <option value="3">03</option>
                                      <option value="4">04</option>
                                      <option value="5">05</option>
                                      <option value="6">06</option>
                                      <option value="7">07</option>
                                      <option value="8">08</option>
                                      <option value="9">09</option>
                                      <option value="10">10</option>
                                      <option value="11">11</option>
                                      <option value="12">12</option>
                                      <option value="13">13</option>
                                      <option value="14">14</option>
                                      <option value="15">15</option>
                                      <option value="16">16</option>
                                      <option value="17">17</option>
                                      <option value="18">18</option>
                                      <option value="19">19</option>
                                      <option value="20">20</option>
                                      <option value="21">21</option>
                                      <option value="22">22</option>
                                      <option value="23">23</option>


                              </select>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="form-group" id="fgSexpac">
                              <label for="sexpac">Minutos inicio</label>
                              <select class="form-control sMinutos" id="minIniAct" >
                                      <option value="">Minutos</option>
                                      <option value="0">00</option>
                                      <option value="15">15</option>
                                      <option value="30">30</option>
                                      <option value="45">45</option>
                              </select>
                            </div>
                          </div>

                   </div>
            </div>



      </div>
      <div class="modal-footer">
          <button type="button" id="btnAnt" class="btn btn-danger hide" onclick = 'anterior()'>Anterior</button>
          <button type="button" id="btnSig" class="btn btn-info hide" onclick = 'siguiente()'>Siguiente</button>
          <button type="button" id="btnGuardarActQui" class="btn btn-success hide" onclick = 'guardarActQui()'>Finalizar</button>
      </div>
    </div>
  </div>
</div>







<!-- Modal Historico ActQui-->
<div class="modal fade modal-3d-flip-horizontal" id="HistActQui"  data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Historico - Acto Quirúrjico</h3>

      </div>
      <div class="modal-body">

      <div class="col-sm-12 top15">
          <table id="tablaHistActQui" class="table table-hover dataTable table-striped width-full">
            <thead>
                <tr>
                       <th>Nombre</th>
                       <th>Apellidos</th>
                       <th  style="width: 150px;">Fecha intervención</th>
                       <th>Acciones</th>
                </tr>
             </thead>
             <tbody>
             </tbody>
          </table>
    </div>








      </div>
      <div class="modal-footer">
          <button type="button" id="btnGuardarActQui" class="btn btn-success hide" onclick = 'guardarActQui()'>Finalizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Info Acto Quirúrjico-->
<div class="modal fade modal-3d-flip-horizontal" id="infoActQui"  data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
            <h3 class="modal-title">Información Acto Quirúrjico</h3>
         </div>
         <div class="modal-body">
             <div id="listaProcedimiento">
                 <div class="panel panel-bordered">
                   <div class="panel-heading  bg-blue-500">
                      <div class="margin-left-20">
                         <label>
                            <h3 class="h4"><strong>Información General </strong></h3>
                         </label>
                      </div>
                      <div class="panel-actions"> </div>
                   </div>
                   <div class="list-group bg-grey-200 bg-inherit infoPacSelInfo">
                       <div class="col-sm-12"> <b>Paciente: </b><span id="nomPacSelInfo"></span></div>
                       <div class="col-sm-12"> <b>Fecha intervención: </b><span id="fecintSelInfo"></span></div>


                   </div>
                </div>
             </div>
             <div class="clearfix"></div>

             <div id="listaDiagnostico" class="margin-top-20">
                 <div class="panel panel-bordered">
                   <div class="panel-heading  bg-blue-500">
                      <div class="margin-left-20">
                         <label>
                            <h3 class="h4"><strong>Diagnosticos Iniciales del acto </strong></h3>
                         </label>
                      </div>
                      <div class="panel-actions"> </div>
                   </div>
                   <div class="list-group bg-grey-200 bg-inherit ciesSelInfo">

                   </div>
                </div>
             </div>

             <div id="listaProcedimiento" class="margin-top-20">
                 <div class="panel panel-bordered">
                   <div class="panel-heading  bg-blue-500">
                      <div class="margin-left-20">
                         <label>
                            <h3 class="h4"><strong>Procedimientos Iniciales del acto </strong></h3>
                         </label>
                      </div>
                      <div class="panel-actions"> </div>
                   </div>
                   <div class="list-group bg-grey-200 bg-inherit ciesProSelInfo">

                   </div>
                </div>
             </div>

             <div id="listaDiagnosticoFin" class="margin-top-20">
                 <div class="panel panel-bordered">
                   <div class="panel-heading  bg-blue-500">
                      <div class="margin-left-20">
                         <label>
                            <h3 class="h4">
                                <strong>Diagnosticos Finales del acto </strong>
                                <button type="button" id="btnDiaFin" class="btn btn-default" onclick = 'initModalDiagFin()'>Añadir</button>
                            </h3>
                         </label>
                      </div>
                      <div class="panel-actions"> </div>
                   </div>
                   <div class="list-group bg-grey-200 bg-inherit">
						
					   <div class="ciesSelInfoFin">
					   </div>



                           <div class="diagFinForm hide modal-body">
						   
                            <span>Debe seleccionar el/los CIE correspondientes</span>
                            <div class="contResumenCie col-sm-12"></div>
                               <div class="col-sm-12">
                                   <div class="col-sm-6">
                                    <div class="form-group" id="fgNombreBusqFin">
                                        <label for="nompacEspFin">Cie</label>
                                         <input type="text" class="form-control input-lg text-uppercase"
                                         id="claseCieBusqFin" name="claseCieBusqFin"  placeholder="CIE" data-plugin="formatter" data-pattern="[[a99]].[[999]]">

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="fgNombreBusq">
                                        <label for="nompacEsp">Descripción</label>
                                         <input type="text" class="form-control input-lg text-uppercase" id="desCieBusqFin" name="desCieBusqFin"  placeholder="Descripción">

                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button id="busqCieBtnFin" type="button" class="btn btn-success" onclick = 'buscarCieByClaseDesc("Fin")'>Buscar CIE</button>
                                </div>
                           </div>

                           <div class="col-sm-12 top15">
                              <table id="tablaCieFin" class="table table-hover dataTable table-striped width-full user-list">
                                    <thead>
                                      <tr>
                                        <th>Clase</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                      </tr>
                                    </thead>
                                    <tfoot>
                                      <tr>
                                        <th>Clase</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                      </tr>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                              </table>
                            </div>
                            <div class="col-sm-12">
                                <button type="button" id="saveBtnPre" class="btn btn-success" onclick = 'guardarEdicionCieFin()'>Guardar</button>
                                  <button type="button" id="cancelBtnPre" class="btn btn-danger" onclick = 'cancelarEdicionCieFin()'>Cancelar</button>
                            </div>

                        </div>



                           <div class="clearfix"></div>




                   </div>
                </div>
             </div>

             <div id="listaProcedimientoFin" class="margin-top-20">
                 <div class="panel panel-bordered">
                   <div class="panel-heading  bg-blue-500">
                      <div class="margin-left-20">
                         <label>
                            <h3 class="h4"><strong>Procedimientos Finales del acto </strong>
                            <button type="button" id="btnDiaFin" class="btn btn-default" onclick = 'initModalDiagProFin()'>Añadir</button>
                            </h3>
                         </label>
                      </div>
                      <div class="panel-actions"> </div>
                   </div>
                   <div class="list-group bg-grey-200 bg-inherit">
						<div class="ciesProSelInfoFin">
					   	</div>



                           <div class="diagProFinForm hide modal-body">
						   <div class="contResumenCiePro col-sm-12"></div>
                            <h3 class="modal-title">Procedimiento Final</h3>
			                <span>Debe seleccionar el/los CIE correspondientes</span>
			                   <div class="col-sm-12">
			                       <div class="col-sm-6">
			                        <div class="form-group" id="fgNombreBusq">
			                            <label for="codigoCieProBusqFin">Cie</label>
			                             <input type="text" class="form-control input-lg text-uppercase"
			                             id="codigoCieProBusqFin" name="codigoCieProBusqFin"  placeholder="CIE" >
			
			                        </div>
			
			                    </div>
			                    <div class="col-sm-6">
			                        <div class="form-group" id="fgNombreBusq">
			                            <label for="nompacEspFin">Descripción</label>
			                             <input type="text" class="form-control input-lg text-uppercase" id="desCieProBusqFin" name="desCieProBusqFin"  placeholder="Descripción">
			
			                        </div>
			                    </div>
			                    <div class="col-sm-12">
			                        <button id="busqCieProBtn" type="button" class="btn btn-success" onclick = 'buscarCieProByClaseDesc("Fin")'>Buscar CIE</button>
			                    </div>
			               </div>
			
			                   <div class="col-sm-12 top15">
			                      <table id="tablaCieProFin" class="table table-hover dataTable table-striped width-full user-list">
			                            <thead>
			                              <tr>
			                                <th>Clase</th>
			                                <th>Descripción</th>
			                                <th>Acciones</th>
			                              </tr>
			                            </thead>
			                            <tfoot>
			                              <tr>
			                                <th>Clase</th>
			                                <th>Descripción</th>
			                                <th>Acciones</th>
			                              </tr>
			                            </tfoot>
			                            <tbody>
			                            </tbody>
			                      </table>
			                </div>
                               
						<div class="col-sm-12">
                        	<button type="button" id="saveBtnPre" class="btn btn-success" onclick = 'guardarEdicionCieProFin()'>Guardar</button>
							<button type="button" id="cancelBtnPre" class="btn btn-danger" onclick = 'cancelarEdicionCieProFin()'>Cancelar</button>
                        </div>
                          

                        </div>



                           <div class="clearfix"></div>
                   </div>
                </div>
             </div>


             <div id="listaProcedimiento" class="margin-top-20">
                 <div class="panel panel-bordered">
                   <div class="panel-heading  bg-blue-500">
                      <div class="margin-left-20">
                         <label>
                            <h3 class="h4"><strong>Pruebas Complementarias </strong></h3>
                         </label>
                      </div>
                      <div class="panel-actions"> </div>
                   </div>
                   <div class="col-sm-12">
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

              <div class="clearfix"></div>

             <div id="consentInformados" class="margin-top-20">
                 <div class="panel panel-bordered">
                   <div class="panel-heading  bg-blue-500">
                      <div class="margin-left-20">
                         <label>
                            <h3 class="h4"><strong>Consentimientos Informados </strong></h3>
                         </label>
                      </div>
                      <div class="panel-actions"> </div>
                   </div>
                   <div class="list-group bg-grey-200 bg-inherit">
                           <div class="col-sm-12">
                               Consentimiento informado de anestesia :
                               <span data-toggle="tooltip" data-placement="top" data-trigger="hover" id="sDescConAnes" data-original-title="Descargar" class="hide" ><button type="button" onclick="descargarConsentimiento('ANE')" class="btn btn-warning btn-icon waves-effect waves-light"><i class="icon wb-eye " aria-hidden="true"></i></button></span>
                               <span data-toggle="tooltip" data-placement="top" data-trigger="hover" id="sNoConAnes" data-original-title="No disponible" class="hide"><button type="button"  class="btn btn-danger btn-icon waves-effect waves-light"><i class="icon wb-eye-close " aria-hidden="true"></i></button></span>
                           </div>
                           <div class="col-sm-12 margin-top-10">
                               Consentimiento informado de cirugia :
                               <span data-toggle="tooltip" data-placement="top" data-trigger="hover" id="sDescConInf" data-original-title="Descargar" class="hide"><button type="button" onclick="descargarConsentimiento(CIR)" class="btn btn-warning btn-icon waves-effect waves-light"><i class="icon wb-eye " aria-hidden="true"></i></button></span>
                               <span data-toggle="tooltip" data-placement="top" data-trigger="hover" id="sNoConInf" data-original-title="No disponible" class="hide"><button type="button"  class="btn btn-danger btn-icon waves-effect waves-light"><i class="icon wb-eye-close " aria-hidden="true"></i></button></span>
                           </div>
                   </div>
                </div>
             </div>

             <div class="clearfix"></div>




             <div class="clearfix"></div>


         </div>
      </div>
   </div>
</div>
<!-- Modal Info Acto Quirúrjico-->


<!-- Modal Info Preoperatorio -->
<div class="modal fade modal-3d-flip-horizontal" id="infoPreope"  data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
            <h3 class="modal-title">Preoperatorio</h3>
         </div>
         <div class="modal-body">
             <input type="hidden" name="idpreop" id="idpreop">
            <div class="col-sm-3">
                <div class="form-group" id="fgSexpac">
                  <label for="sexpac">Sexo</label>
                  <select class="form-control input-lg" id="sexpre" name="sexpre" >
                    <option value = "">Elegir opción</option>
                    <option value = "H">Hombre</option>
                    <option value = "M">Mujer</option>
                  </select>
                </div>
              </div>
            <div class="col-sm-3">
                <div class="form-group" >
                  <label for="dirpac">Edad</label>
                  <input type="text" class="form-control input-lg" id="edadpre" name="edadpre" placeholder="Edad">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group" >
                  <label for="dirpac">T.A.:</label>
                  <input type="text" class="form-control input-lg" id="tapre" name="tapre" placeholder="T.A">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                  <label for="dirpac">F.C.:</label>
                  <input type="text" class="form-control input-lg" id="fcpre" name="fcpre" placeholder="F.C">
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                  <label for="dirpac">PESO (kgs):</label>
                  <input type="text" class="form-control input-lg" id="pesopre" name="pesopre" placeholder="Peso">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                  <label for="dirpac">ESTATURA (cms):</label>
                  <input type="text" class="form-control input-lg" id="estaturapre" name="estaturapre" placeholder="estatura">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                  <label for="dirpac">IMC:</label>
                  <input type="text" class="form-control input-lg" id="imcpre" name="imcpre" placeholder="IMC">
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                  <label for="comentario">ALERGIA</label>
                  <textarea class="form-control input-lg" id="alerpro" name="alerpro" rows="2"  placeholder="Alergias del paciente"></textarea>
                </div>
             </div>

             <div class="col-sm-12">
                <div class="form-group">
                  <label for="comentario">INTERVENCION</label>
                  <textarea class="form-control input-lg" id="intpro" name="intpro" rows="2"  placeholder="Intervención"></textarea>
                </div>
             </div>

             <div class="col-sm-4">
                <div class="form-group has-datepicker" id="">
                  <label for="fecnacpac">Fecha Intervención</label>
                  <input data-provide="datepicker" id = "fecintpro" data-date-format="dd/mm/yyyy">
                </div>
              </div>
              <div class="col-sm-12">
                  <label>HABITOS TOXICOS: </label>
                <div class="checkbox-custom checkbox-primary">
                    <input type="checkbox" id="habtoxTapro" name="habtoxPro"/>
                    <label for="habtoxTapro">TABACO</label>
                </div>
                <div class="checkbox-custom checkbox-primary usuAneCir">
                    <input type="checkbox" id="habtoxAlpro" name="usuAneCir"/>
                    <label for="habtoxAlpro">ALCOHOL</label>
                </div>

                 <div class="form-group">
                     <label>Otros</label>
                    <input type="text" class="form-control input-lg" id="habtoxOtpro" name="habtoxOtpro" placeholder="Otros">
                </div>

            </div>
            <div class="col-sm-12">
                <div class="form-group">
                  <label for="comentario">ANTECEDENTES PATÓLOGICOS:</label>
                  <textarea class="form-control input-lg" id="antpatpro" name="antppro" rows="2"  placeholder="Antecedentes patológicos"></textarea>
                </div>
             </div>
             <div class="col-sm-12">
                <div class="form-group">
                  <label for="comentario">ANTECEDENTES QUIRURGICOS:</label>
                  <textarea class="form-control input-lg" id="antquipro" name="antqpro" rows="2"  placeholder="Antecedentes quirúrgicos "></textarea>
                </div>
             </div>
             <div class="col-sm-12">
                <div class="form-group">
                  <label for="comentario">INCIDENCIAS:</label>
                  <textarea class="form-control input-lg" id="incipro" name="incipro" rows="2"  placeholder="Incidencias"></textarea>
                </div>
             </div>


             <div class="col-sm-12">
                <div class="form-group">
                  <label for="dirpac">FUNCION CARDIACA:</label>
                  <input type="text" class="form-control input-lg" id="fcapre" name="fcapre" placeholder="">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                  <label for="dirpac">E.C.G.:</label>
                  <input type="text" class="form-control input-lg" id="ecgpre" name="ecgpre" placeholder="">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                  <label for="dirpac">FUNCION RESPIRATORIA:</label>
                  <input type="text" class="form-control input-lg" id="frespre" name="frespre" placeholder="">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                  <label for="dirpac">ANALITICA:</label>
                  <input type="text" class="form-control input-lg" id="anapre" name="anapre" placeholder="">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                  <label for="dirpac">OTRA PATOLOGIA:</label>
                  <input type="text" class="form-control input-lg" id="opapre" name="opapre" placeholder="">
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                  <label for="dirpac">BOCA:</label>
                  <input type="text" class="form-control input-lg" id="bocpre" name="bocpre" placeholder="">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                  <label for="dirpac">PERIODONTITIS:</label>
                  <input type="text" class="form-control input-lg" id="perpre" name="perpre" placeholder="">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                  <label for="dirpac">PROTESIS:</label>
                  <input type="text" class="form-control input-lg" id="propre" name="propre" placeholder="">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                  <label for="dirpac">APERTURA:</label>
                  <input type="text" class="form-control input-lg" id="apepre" name="apepre" placeholder="">
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                  <label for="dirpac">MOV. CERVICAL:</label>
                  <input type="text" class="form-control input-lg" id="mcepre" name="mcepre" placeholder="">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                  <label for="dirpac">SIST. VENOSO:</label>
                  <input type="text" class="form-control input-lg" id="svepre" name="svepre" placeholder="">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                  <label for="dirpac">COLUMNA LUM:</label>
                  <input type="text" class="form-control input-lg" id="clupre" name="clupre" placeholder="">
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                  <label for="comentario">OBSERVACIONES:</label>
                  <textarea class="form-control input-lg" id="obspro" name="obspro" rows="2"  placeholder="Observaciones"></textarea>
                </div>
             </div>
             <div class="clearfix"></div>
         </div>

         <div class="modal-footer">
              <button type="button" id="saveBtnPre" class="btn btn-success" onclick = 'guardarInfoPreoperatorio()'>Guardar</button>
              <button type="button" id="cancelBtnPre" class="btn btn-danger" onclick = 'cerrarInfoPreoperatorio()'>Cancelar</button>
          </div>
      </div>
   </div>
</div>
<!-- Modal Info Preoperatorio -->


<!-- Modal Imagenes -->
<div class="modal fade modal-3d-flip-horizontal" id="imgMod"  data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
            <h3 class="modal-title">Imágenes</h3>
         </div>
         <div class="modal-body">
         <div class="col-sm-9">
             <canvas id="simple_sketch" style="background-color: #FAFFBD;" width="600" height="450"></canvas>
         </div>
         <div class="col-sm-3">
             <button type="button" id="borrarSketch" class="btn btn-success" onclick = 'reiniciarImagen()'>Borrar</button>
              <input type="hidden" id="archB64" name="archB64">
                            <label for="filePickerSketch" class="top15">Selecciona una imagen</label><br>
                            <input type="file" id="filePickerSketch">
         </div>

             <div class="clearfix"></div>
         </div>

         <div class="modal-footer">
              <button type="button" id="btnAnt" class="btn btn-success" onclick = 'guardarInfoPreoperatorio()'>Guardar</button>
              <button type="button" id="btnSig" class="btn btn-danger" onclick = 'cerrarimgMod()'>Cancelar</button>
          </div>
      </div>
   </div>
</div>
<!-- Modal Imagenes -->

<form action="./verDocFirmado" class="hide" method="get" id="fverFirma" target="_blank">
    <input type="hidden" name="id" id="iddoc">
</form>





<!-- Modal Add Diagnostico Final-->
<div class="modal fade modal-3d-flip-horizontal" id="modalDiagFin"  data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h3 class="modal-title">Diagnosticos Finales</h3>
              </div>



      <div class="modal-footer">
          <button type="button" id="btnGuardarActQui" class="btn btn-success hide" onclick = 'guardarActQui()'>Finalizar</button>
      </div>
    </div>
  </div>
</div>















@stop
@section('js', '/private/assets/vendor/jquery-strength/jquery-strength.min.js')
@section('js1', '/private/assets/vendor/sketch/sketch.min.js')
@section('js3', '/js/app/private/pruebaComplementaria.js')
@section('js4', '/js/app/private/tablonActQui.js')