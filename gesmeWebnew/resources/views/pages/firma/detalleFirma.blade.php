@extends('layout.private/registro')

@section('title', 'Acceso')
@section('css', '/private/assets/css/pages/user.css')

@section('content')
<script type="text/javascript">
  var listIdFirma = <?= json_encode($listIdFirma) ?>;
  var tipFirma = <?= json_encode($tipFirma) ?>;
</script>

  <div class="page animsition vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
    <div class="page-content vertical-align-middle  text-left">
      
      
      <form method="post" action="JavaScript:guardarDocFirmado()">
        <h2 id="titMsg"></h2>
        <h4 id="subMsg"></h4>
        <div class="contenidoDoc">
        
        </div>
        <div style="margin-top: 50px;">
        	<div class="form-group text-left" style="margin-bottom:0px;" id="divAcpRev">
				<div class="radio-custom radio-primary col-sm-2" style="margin-bottom:0px; margin-top:0px;">
 	               <input  type="radio" id="selOptSi"  name="acp" value="S">
 	                <label for="selOptSi"><span class="left10"> Acepto</span></label>
 	            </div>
 	            <div class="radio-custom radio-primary col-sm-2" style="margin-bottom:0px; margin-top:0px;">
 	               <input  type="radio" id="selOptNo"  name="acp" value="N">
 	                <label for="selOptNo"><span class="left10">Revoco</span> </label>
 	            </div>
 	        </div>
        	<div class="top15 col-sm-12">
        		<canvas id="simple_sketch" style="background-color: #FAFFBD;" width="300" height="150" ></canvas>
        	</div>
        </div>
        <button type="submit" id="btnGuarda" class="btn btn-warning btn-block">Guardar Documento firmado</button>
      </form>
    </div>
  </div>
@stop
@section('js', '/js/app/private/general.js')
@section('js1', '/js/app/firma/firmaDetalle.js')