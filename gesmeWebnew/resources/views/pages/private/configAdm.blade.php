@extends('layout.private.private')

@section('title', 'Configuraciones')
@section('css', '/private/assets/css/pages/user.css')

@section('content').


<div class="page-profile">
  <!-- Page -->
  <div class="page animsition">
    <div class="page-header">
      <div class="page-header-actions">
        <ol class="breadcrumb">
          <li><a href="/private/index">private</a></li>
          <li class="active">Configuraciones</li>
        </ol>
      </div>
    </div>
    <div class="page-content">
	
	<div class="panel">
        
        <div class="panel-body user-list-wrap">
		<div class="form-group row">
			<div class="col-sm-12">
				<h3>Configuraciones</h1>
			</div>
			<div class="clearfix"></div>
			
			<div id="listaTiposDocs">
				<a onclick="obtenerEspecialidades()" data-toggle="modal" href data-target="#gestionEspecialidades" >
					<div class="widget col-sm-3">
						<div class="widget-content widget-radius padding-20 bg-green-100 clearfix">
							<div class="pull-left white">
								<i class="icon icon-circle icon-2x wb-list bg-green-600" aria-hidden="true"></i>
							</div> 
							<div class="counter counter-md pull-left text-left">
								<div class="counter-number-group">
									<span class="counter-number-related text-capitalize font-size-18">Especialidades</span>
								</div>
							</div>
						</div>
					</div> 
				</a>	
        	</div>
        	
        	<div id="listaTiposDocs">
				<a onclick="obtenerSeguros()" data-toggle="modal" href data-target="#gestionSeguros" >
					<div class="widget col-sm-3">
						<div class="widget-content widget-radius padding-20 bg-blue-200 clearfix">
							<div class="pull-left white">
								<i class="icon icon-circle icon-2x wb-lock bg-blue-600" aria-hidden="true"></i>
							</div> 
							<div class="counter counter-md pull-left text-left">
								<div class="counter-number-group">
									<span class="counter-number-related text-capitalize font-size-18">Seguros</span>
								</div>
							</div>
						</div>
					</div> 
				</a>	
        	</div>
        	
        	<div id="listaTiposDocs">
				<a onclick="obtenerFestivosAnualInit()" data-toggle="modal" href data-target="#gestionFestivos" >
					<div class="widget col-sm-3">
						<div class="widget-content widget-radius padding-20 bg-brown-200 clearfix">
							<div class="pull-left white">
								<i class="icon icon-circle icon-2x wb-calendar bg-brown-600" aria-hidden="true"></i>
							</div> 
							<div class="counter counter-md pull-left text-left">
								<div class="counter-number-group">
									<span class="counter-number-related text-capitalize font-size-18">Días Festivos</span>
								</div>
							</div>
						</div>
					</div> 
				</a>	
        	</div>
        	
        	
        	<div id="listaTiposDocs">
				<a onclick="gestionCieInit()" data-toggle="modal" href data-target="#gestionCie" >
					<div class="widget col-sm-3">
						<div class="widget-content widget-radius padding-20 bg-orange-200 clearfix">
							<div class="pull-left white">
								<i class="icon icon-circle icon-2x wb-scissor bg-orange-600" aria-hidden="true"></i>
							</div> 
							<div class="counter counter-md pull-left text-left">
								<div class="counter-number-group">
									<span class="counter-number-related text-capitalize font-size-18">CIE - ES</span>
								</div>
							</div>
						</div>
					</div> 
				</a>	
        	</div>
        	
      	</div>
      <!-- End Panel Basic -->
    </div>
  </div>
	
    </div>
  </div>
  
  
  
  <!-- End Page -->
</div>



<!-- Modal Especialidad-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionEspecialidades"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        <span aria-hidden="true">×</span>
		        </button>
		        <h3 class="modal-title">Gestionar Especialidades</h3>
		      </div>
		      <div class="modal-body">
	     		 
	     		 <div class="clearfix"></div>
	     		 
					 <div class="row text-left hide" id="admEsp">
			          <div class="col-sm-12">
							<h3 class="modal-title titEsp"></h3>
						</div>
						<div class="col-sm-6">
							<div class="form-group" id="fgNombreBusq">
								<label for="nompacEsp">Especialidad</label>
								<input type="text" class="form-control input-lg" id="nompacEsp" name="nompacEsp" placeholder="Especialidad">
							</div>
						</div>
				      </div>  
				
				
		       		<button id="insertarModidicarEsp" type="button" class="btn btn-default" onclick = 'insertarModidicarEsp("I")'>Nueva Especialidad</button>
		       		<button id="guardarEsp" type="button" class="btn btn-success hide" onclick = 'guardarEsp()'>Guardar</button>
		       		<button id="cancelarModidicarEsp" type="button" class="btn btn-default hide" onclick = 'cancelarModidicarEsp()'>Cancelar</button>
					<div class="clearfix"></div>
					<div class="tab-pane padding-10" id="" role="">
					
						<table id="tablaCitasEspe" class="table table-hover dataTable table-striped width-full user-list">
				            <thead>
				              <tr>
				                <th>Especialidad</th>
				                <th>Acciones</th>
				              </tr>
				            </thead>
				            <tfoot>
				              <tr>
				                <th>Especialidad</th>
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
  <!-- End Modal Especialidad -->
  
  
  <!-- Modal Especialidad-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionFestivos"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        <span aria-hidden="true">×</span>
		        </button>
		        <h3 class="modal-title">Gestionar Vacaciones</h3>
		      </div>
		      <div class="modal-body">
	     		 
	     		 <div class="clearfix"></div>
	     		 
					 <div class="row text-left hide" id="admFest">
			          <div class="col-sm-12">
							<h3 class="modal-title titFest"></h3>
						</div>
									<div class="col-md-12 padding-10">
						 <span> Festivo</span>
					        <div class="input-group col-md-12">
				                <span class="input-group-addon">
				                	<i class="icon wb-calendar" aria-hidden="true"></i>
				                </span>
				                <input data-provide="datepicker" id="dFest" data-date-format="dd/mm/yyyy">
				             </div>
						</div>
				      </div>  
				
				
		       		<button id="insertarModidicarFest" type="button" class="btn btn-success" onclick = 'insertarModidicarFest("I")'>Añadir Festivo</button>
		       		<button id="guardarFest" type="button" class="btn btn-success hide" onclick = 'guardarFest()'>Guardar</button>
		       		<button id="cancelarModidicarFest" type="button" class="btn btn-default hide" onclick = 'cancelarModidicarSeg()'>Cancelar</button>
					<div class="clearfix"></div>
					<div class="tab-pane padding-10" id="" role="">
					<div class="top15">
						<button id="anyAc" type="button" class="btn btn-default" onclick = 'obtenerFestivosAnual("ACT")'></button>
		       			<button id="anySeg" type="button" class="btn btn-default" onclick = 'obtenerFestivosAnual("SIG")'></button>
		       		</div>
		       		<div class="top15"></div>
						<table id="tablaFest" class="table table-hover dataTable table-striped width-full user-list top15">
				            <thead>
				              <tr>
				                <th>Fecha</th>
				                <th>Acciones</th>
				              </tr>
				            </thead>
				            <tfoot>
				              <tr>
				                <th>Día</th>
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
  <!-- End Modal Especialidad -->
  
<!-- Modal Vacaciones-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionSeguros"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        <span aria-hidden="true">×</span>
		        </button>
		        <h3 class="modal-title">Gestionar Seguros</h3>
		      </div>
		      <div class="modal-body">
	     		 
	     		 <div class="clearfix"></div>
	     		 
					 <div class="row text-left hide" id="admSeg">
			          <div class="col-sm-12">
							<h3 class="modal-title titSeg"></h3>
						</div>
						<div class="col-sm-6">
							<div class="form-group" id="fgNombreBusq">
								<label for="nompacEsp">Seguro</label>
								<input type="text" class="form-control input-lg" id="nomSeguro" name="nomSeg" placeholder="Nombre del seguro">
							</div>
						</div>
				      </div>  
				
				
		       		<button id="insertarModidicarSeg" type="button" class="btn btn-default" onclick = 'insertarModidicarSeg("I")'>Nuevo Seguro</button>
		       		<button id="guardarSeg" type="button" class="btn btn-success hide" onclick = 'guardarSeg()'>Guardar</button>
		       		<button id="cancelarModidicarSeg" type="button" class="btn btn-default hide" onclick = 'cancelarModidicarSeg()'>Cancelar</button>
					<div class="clearfix"></div>
					<div class="tab-pane padding-10" id="" role="">
					
						<table id="tablaSeguro" class="table table-hover dataTable table-striped width-full user-list">
				            <thead>
				              <tr>
				                <th>Seguro</th>
				                <th>Acciones</th>
				              </tr>
				            </thead>
				            <tfoot>
				              <tr>
				                <th>Seguro</th>
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
  <!-- End Modal Vacaciones -->
  
  
<!-- Modal CIE-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionCie"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        <span aria-hidden="true">×</span>
		        </button>
		        <h3 class="modal-title">Gestionar CIE</h3>
		      </div>
		      <div class="modal-body">
	     		 
	     		 <div class="clearfix"></div>
	     		 
					 <div class="row text-left hide" id="admCie">
			          <div class="col-sm-12">
							<h3 class="modal-title titSeg">Añadir un nuevo CIE</h3>
						</div>
						<div class="col-sm-3">
							<div class="form-group" id="fgNombreBusq">
								<label for="nompacEsp">Clase</label>
								<input type="text" class="form-control input-lg text-uppercase" 
								 id="claseCieAdd" name="claseCieAdd"  placeholder="Clase" data-plugin="formatter" data-pattern="[[a99]].[[999]]">
							</div>
						</div>
						<div class="col-sm-9">
							<div class="form-group" id="fgNombreBusq">
								<label for="nompacEsp">Descripción</label>
								<input type="text" class="form-control input-lg" id="desCieAdd" name="desCieAdd" placeholder="Descripción">
							</div>
						</div>
						<div class="col-sm-9">
							<button id="guardarCIE" type="button" class="btn btn-success" onclick = 'guardarCie()'>Guardar</button>
		       				<button id="cancelarAddCIE" type="button" class="btn btn-default" onclick = 'cancelarAddCie()'>Cancelar</button>
						</div>
		       		 
				      </div>  
				
				
		       		
					<div class="clearfix"></div>
					
					<div class="col-sm-12">
							<h3 class="modal-title titSeg">Buscador</h3>
						</div>
						<div class="col-sm-6">
							<div class="form-group" id="fgNombreBusq">
								<label for="nompacEsp">Cie</label>
								 <input type="text" class="form-control input-lg text-uppercase" 
								 id="claseCieBusq" name="claseCieBusq"  placeholder="CIE" data-plugin="formatter" data-pattern="[[a99]].[[999]]">
								
							</div>
						</div>
						<div class="col-sm-12">
							<button id="busqCieBtn" type="button" class="btn btn-success" onclick = 'buscarCieByClaseDesc()'>Buscar CIE</button>
							<button id="addCieBtn" type="button" class="btn btn-info" onclick = 'prepararAddCie()'>Añadir CIE</button>
						</div>
				      </div>
					<div class="clearfix"></div>
					<div class="tab-pane padding-10" id="" role="">
					
						<table id="tablaCie" class="table table-hover dataTable table-striped width-full user-list">
				            <thead>
				              <tr>
				                <th>Clase</th>
				                <th>Descripción</th>
				              </tr>
				            </thead>
				            <tfoot>
				              <tr>
				                <th>Clase</th>
				                <th>Descripción</th>
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
  <!-- End Modal CIE -->



@stop
@section('js', '/private/assets/vendor/jquery-strength/jquery-strength.min.js')
@section('js1', '/private/assets/js/components/jquery-strength.js')
@section('js2', '/private/assets/vendor/toastr/toastr.js')
@section('js3', '/private/assets/js/components/toastr.js')
@section('js4', '/js/app/private/configAdm.js')
