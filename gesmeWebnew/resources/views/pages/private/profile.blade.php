@extends('layout.private.private')

@section('title', 'Mi Perfil')
@section('css', '/private/assets/css/pages/profile.css')
@section('css1', '/private/assets/vendor/jquery-strength/jquery-strength.css')
@section('css2', '/private/assets/vendor/toastr/toastr.css')

@section('content')
<div class="page-profile">
  <!-- Page -->
  <div class="page animsition">
    <div class="page-header">
      <h1 class="page-title">Mi Perfil</h1>
      <div class="page-header-actions">
        <ol class="breadcrumb">
          <li><a href="/private/index">private</a></li>
          <li class="active">Mi Perfil</li>
        </ol>
      </div>
    </div>
    <div class="page-content container-fluid hide" id="contenerdor">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Panel -->
          <div class="panel">
            <div class="panel-body">
              <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
                <li class="active" id="oCuenta" role="presentation"><a data-toggle="tab" href="#cuenta" aria-controls="cuenta" role="tab">Mi Cuenta</a></li>
                <li role="presentation"><a data-toggle="tab" href="#password" aria-controls="password" role="tab">Contraseña</a></li>
              </ul>

              <div class="tab-content "> 
                <div class="tab-pane active padding-10" id="cuenta" role="tabpanel">
                  <input type="hidden" id='formId' name='formId'>
                  <div class="panel">
                    <div class="panel-heading">
                      <h3 class="panel-title">Cambia tus configuraciones básicas </h3>
                    </div>
                    <div class="panel-body">
                      <form class="form">
                         <div class="form-group row">
                          <div class="col-sm-6">
                            <div class="form-group" id="fgNombre">
				              <label for="nomusr">Nombre</label>
				              <input type="text" class="form-control input-lg" id="nomusr" name="nomusr" placeholder="Nombre" required>
				            </div>
                          </div>
                          <div class="col-sm-6">
                            <label for="apusr">Apellidos</label>
              				<input type="text" class="form-control input-lg" id="apusr" name="apusr"  placeholder="Apellidos">
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-sm-6">
                            <label for="dniusr">DNI</label>
             				<input type="text" class="form-control input-lg text-uppercase" id="dniusr" name="dniusr"  placeholder="Dni" data-plugin="formatter" data-pattern="[[99999999]]-[[a]]">
                          </div>
                          <div class="col-sm-6">
                              <label for="numtelfijusr">Teléfono 1</label>
				              <input type="text" class="form-control input-lg" id="numtel1" name="numtel1" data-plugin="formatter" data-pattern="[[999]][[999]][[999]]" placeholder="Teléfono Fijo">
				              <p class="help-block">123 123 123</p>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-sm-6">
                            <label for="numtelmovusr">Teléfono 2</label>
				              <input type="text" class="form-control input-lg" id="numtel2" name="numtel2" data-plugin="formatter" data-pattern="[[999]][[999]][[999]]" placeholder="Teléfono Movil">
				              <p class="help-block">123 123 123</p>
                          </div>
                          <div class="col-sm-6">
                            <label for="emailusr">Email</label>
              				<input type="email" class="form-control input-lg" id="emailusr" name="emailusr"  placeholder="Email">
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-sm-12" id='divCol'>
                            <label for="colegiado">Número colegiado</label>
              				<input type="text" class="form-control input-lg" id="colegiado" name="colegiado" placeholder="Núm. Colegiado">
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-sm-12 text-right">
                            <button type="button" onclick = "actualizarDatosUsuario()" class="btn btn-success btn-lg">Guardar </button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                 <div class="tab-pane  padding-10" id="password" role="tabpanel">
                  <div class="panel">
                    <div class="panel-heading">
                      <h3 class="panel-title">Cambia tu contraseña </h3>
                    </div>
                    <div class="panel-body">
                      <form class="form">
                        <div class="form-group row">
                          <div class="col-sm-12">
                            <label class="control-label">Contraseña Actual: </label>
                            <input type="password" id="oldPassword" class="form-control input-lg" name="actual-pass" placeholder="Contraseña actual">
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-sm-6">
                            <label class="control-label">Nueva Contraseña: </label>
                            <input type="password" id="newPassword" class="form-control password-strength input-lg" data-plugin="strength" data-show-toggle="false" name="new_pass" placeholder="Nueva Contraseña">
                          </div>
                          <div class="col-sm-6">
                            <label class="control-label">Repite Contraseña: </label>
                            <input type="password" id="confirmPassword" class="form-control password-strength input-lg" data-plugin="strength" data-show-toggle="false" name="new_pass-repeat" placeholder="Repite Contraseña">
                            <p class="help-block text-danger" id="helpNewPass"></p>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-sm-12">
                            <button type="button" id = 'btnCamPass' onclick="actualizarPass()" class="btn btn-success btn-lg">Cambiar Contraseña </button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
          <!-- End Panel -->
        </div>
      </div>
    </div>
  </div>
  <!-- End Page -->
</div>

@stop
@section('js', '/private/assets/vendor/jquery-strength/jquery-strength.min.js')
@section('js1', '/private/assets/js/components/jquery-strength.js')
@section('js2', '/private/assets/vendor/toastr/toastr.js')
@section('js3', '/private/assets/js/components/toastr.js')
@section('js4', '/js/app/private/perfil.js')
