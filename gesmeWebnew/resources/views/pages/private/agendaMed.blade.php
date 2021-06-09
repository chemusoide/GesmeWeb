@extends('layout.private.private')

@section('title', 'Busqueda Médicos')
@section('css', '/private/assets/css/pages/user.css')

@section('content')

<script type="text/javascript">
  var listaMedicos = <?= json_encode($listaMedicos) ?>;
</script>

<div class="page-profile">
  <!-- Page -->
  <div class="page animsition">
    <div class="page-header">
      <h1 class="page-title">Mi agenda</h1>
      <div class="page-header-actions">
        <ol class="breadcrumb">
          <li>private</li>
          <li class="active pagActual">mi-agenda</li>
        </ol>
      </div>
    </div>
    <div class="page-content">
      <!-- Panel Basic -->
      <div class="panel" id="panelFiltro">
        
        <div class="panel-body user-list-wrap">
			<div class="form-group row">
				<div class="col-sm-12">
					<h3 class="nombreMed"></h3>
				 
				<div id="divCal" class="page-main col-sm-12">
					<div id="calendar" class="fc fc-ltr fc-unthemed"></div>
				</div>
			   
					<div class="row text-center col-sm-9 hide" id="containerBtnCita">
						<div class="col-sm-12" >
							<div id='horasDisp' class="respuestas-pregunta-wrap col-md-12"></div>
							</div>
							<div class="modal-footer" >
									<button type="button" class="btn btn-default" onclick = "ampliaCalendario()">Ampliar calendario</button>
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
@section('js4', '/js/app/private/agendaMedicos.js')