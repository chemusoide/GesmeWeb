@extends('layout.private/registro')

@section('title', 'Acceso')

@section('content')
  <div class="page animsition vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
    <div class="page-content vertical-align-middle">
      <div class="brand">
        <img class="brand-img" src="/private/assets/images/Cabecera_verdeLogo1.png" alt="logo CPQ">
      </div>
      
      
      <div class="panel">
      	<div class="panel-body">
        	<ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
            	<li class="active" id="oCuenta" role="presentation"><a data-toggle="tab" href="#tPac" aria-controls="cuenta" role="tab">Pacientes</a></li>
            	<li role="presentation"><a data-toggle="tab" href="#tMed" aria-controls="tMed" role="tab" onclick="cargarProf()">Profesionales</a></li>
         	</ul>

     		<div class="tab-content ">
	     		<div class="tab-pane active padding-10" id="tPac" role="tabpanel">
	     			<form method="post" action="JavaScript:busquedaUsrFirma()">
			        <p>Introduce tus datos de acceso</p>
			        <div class="form-group" id="fgtipDocBusq">
			        	<label for="tipdocBusq">Tipo Doc.</label>
			        	<select class="form-control input-lg" id="tipdocBusq" name="tipdocBusq" >
			        		<option value = "DNI">D.N.I</option>
			        		<option value = "NIE">N.I.E</option>
			        		<option value = "PAS">PASAPORTE</option>
			        	</select>
					</div>
			        <div class="form-group" id="fgDniBusq">
			        	<label for="dniusr">Número Documento</label>
			        	<input type="text" class="form-control input-lg text-uppercase" id="dniusrBusq" name="dniusrBusq"  placeholder="Introduce DNI" data-plugin="formatter" data-pattern="[[99999999]]-[[a]]">
			        	<input type="text" class="form-control input-lg text-uppercase hide" id="nieusrBusq" name="nieusrBusq"  placeholder="Introduce NIE" data-plugin="formatter" data-pattern="[[a9999999]]-[[a]]">
			        	<input type="text" class="form-control input-lg text-uppercase hide" id="passusrBusq" name="passusrBusq"  placeholder="Introduce Pasaporter" data-plugin="formatter" data-pattern="[[***************]]">
			        </div>
			        <button type="submit" class="btn btn-warning btn-block">Entrar</button>
			      </form> 
	     		</div>
	     		<div class="tab-pane padding-10" id="tMed" role="tabpanel">
	     			<form method="post" action="JavaScript:busquedaMedFirma()">
		     			<p>Seleccione el profesional que debe firmar</p>
				        <div class="form-group" id="fgtipDocBusq">
				        	<label for="tipdocBusq">Profesional</label>
				        	<select class="form-control input-lg" id="idProf" name="idProf" >
				        		
				        	</select>
						</div>
						<button type="submit" class="btn btn-warning btn-block">Entrar</button>
	     			</form>
	     			
	     		</div>
	     		
			</div>
      </div>
      
    </div>
  </div>
@stop
@section('js', '/js/app/private/general.js')
@section('js1', '/js/app/firma/firmaHome.js')