@extends('layout.private.private')

@section('title', 'Mi Perfil')
@section('css', '/private/assets/css/pages/user.css')

@section('content')
<div class="page-profile">
  <!-- Page -->
  <div class="page animsition">
    <div class="page-header">
      <h1 class="page-title">Tablon Enfermería</h1>
      <div class="page-header-actions">
        <ol class="breadcrumb">
          <li><a href="/private/index">private</a></li>
          <li class="active">Tablon Enfermería</li>
        </ol>
      </div>
    </div>
    <div class="page-content">
      <!-- Panel Basic -->
      <div class="panel">
        
        <div class="panel-body user-list-wrap">
				    	<div class="form-group row">
                          <div class="col-sm-12">
                          <h3>Listado Pacientes</h1>
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
        </div>
      </div>
      <!-- End Panel Basic -->
    </div>
  </div>
  <!-- End Page -->
</div>


  <!-- Modal Registro-->
<div class="modal fade modal-3d-flip-horizontal" id="gestionPacientes"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
  <!-- <div class="modal-dialog modal-center"> -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onclick = "limpiarForm()" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title">Datos Paciente</h3>
      </div>
      <div class="modal-body">
        <form  method="POST" action=""  enctype="multipart/form-data" id="fpAlta" data-toggle="validator">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" id='id' name='id' >
        <div class="row text-center">
          <div class="col-sm-6">
            <div class="form-group" id="fgNombre">
              <label for="nompac">Nombre</label>
              <input type="text" disabled class="form-control input-lg" id="nompac" name="nompac" placeholder="Nombre" required>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgSurName1">
              <label for="ap1pac">Primer Apellido</label>
              <input type="text" disabled class="form-control input-lg" id="ap1pac" name="ap1pac"  placeholder="Primer Apellido">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgSurName2">
              <label for="ap2pac">Segundo Apellido</label>
              <input type="text" disabled class="form-control input-lg" id="ap2pac" name="ap2pac"  placeholder="Segundo Apellido">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group has-datepicker" id="fgNac">
              <label for="fecnacpac">Fecha de nacimiento <!-- <i class="icon wb-calendar" aria-hidden="true"></i> --></label>
              <input type="text" disabled class="form-control input-lg" id="fecnacpac" name="fecnacpac"  placeholder="Ej: 01/01/1985" data-plugin="formatter" data-pattern="[[99]]/[[99]]/[[9999]]">
            </div>
          </div>
          
          <div class="col-sm-3">
            <div class="form-group" id="fgSexpac">
              <label for="sexpac">Sexo</label>
              <select disabled class="form-control input-lg" id="sexpac" name="sexpac" >
                <option value = "">Elegir opción</option>
                <option value = "H">Hombre</option>
                <option value = "M">Mujer</option>
              </select>
            </div>
          </div>
          
          <div class="col-sm-6">
            <div class="form-group" id="fgNumtel1">
              <label for="numtelfijusr">Teléfono 1</label>
              <input type="text" disabled class="form-control input-lg" id="numtel1" name="numtel1" data-plugin="formatter" data-pattern="[[999]][[999]][[999]]" placeholder="Teléfono">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgNumtel2">
              <label for="numtelmovusr">Teléfono 2</label>
              <input type="text" disabled class="form-control input-lg" id="numtel2" name="numtel2" data-plugin="formatter" data-pattern="[[999]][[999]][[999]]" placeholder="Teléfono alternativo">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" id="fgDni">
              <label for="dniusr">DNI</label>
              <input type="text" disabled class="form-control input-lg text-uppercase" id="dniusr" name="dniusr"  placeholder="Introduce DNI" data-plugin="formatter" data-pattern="[[99999999]]-[[a]]">
            </div>
          </div>
          
          
          <div class="col-sm-6" >
            <div class="form-group" id="fgEma">
              <label for="emailpac">Email</label>
              <input type="email" disabled class="form-control input-lg" id="emailpac" name="emailpac"  placeholder="Correo electrónico">
            </div>
          </div>
          
          <div class="col-sm-10">
            <div class="form-group" id="fgDir">
              <label for="dirpac">Domicilio</label>
              <input type="text" disabled class="form-control input-lg" id="dirpac" name="dirpac" placeholder="Domicilio">
            </div>
          </div>
          
          <div class="col-sm-2">
            <div class="form-group" id="fgCp">
              <label for="cppac">Código Postal</label>
              <input type="text" disabled class="form-control input-lg" id="cppac" name="cppac" placeholder="C.P">
            </div>
          </div>
         
          <div class="col-sm-6">
            <div class="form-group" id="fgPais">
              <label for="sIdpais">Pais</label>
              <select class="form-control input-lg"  disabled id="sIdpais" name="sIdpais" ></select>
            </div>
          </div>
           <div class="col-sm-6">
            <div class="form-group" id="fgSeguro">
              <label for="sIdseguro">Seguro</label>
              <select disabled class="form-control input-lg" id="sIdseguro" name="sIdseguro" ></select>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group" id="fgCp">
              <label for="numseg">Número Seguro</label>
              <input type="text" disabled class="form-control input-lg" id="numseg" name="numseg" placeholder="Número de Seguro">
            </div>
          </div>
          <div class="well well-lg">
             <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="comentario">Comentario</label>
                  <textarea disabled class="form-control input-lg" id="comentario" name="comentario" rows="3"  placeholder="Introduce un comentario"></textarea>
                </div>
              </div>
            </div>
          </div>
          </div>   
      </form>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick = 'limpiarForm()'>Cerrar</button>
      </div>
    </div>
  </div>
  </div>
  <!-- End Modal Registro -->





@stop
@section('js', '/private/assets/vendor/jquery-strength/jquery-strength.min.js')
@section('js1', '/private/assets/js/components/jquery-strength.js')
@section('js2', '/private/assets/vendor/toastr/toastr.js')
@section('js3', '/private/assets/js/components/toastr.js')
@section('js4', '/js/app/private/tablon.js')
