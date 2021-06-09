@extends('layout.private.private')

@section('title', 'REGISTRO QUIRÓFANO')
@section('css', '/private/assets/css/pages/user.css')

@section('content')


<script type="text/javascript">
  var actquiActual = <?= json_encode($actqui) ?>;
</script>


<div class="page-profile">
  <!-- Page -->
  <div class="page animsition">
    <div class="page-header">
      <h1 class="page-title">REGISTRO QUIRÓFANO</h1>
      <div class="page-header-actions">
        <ol class="breadcrumb">
          <li><a href="/private/index">private</a></li>
          <li class="active">REGISTRO QUIRÓFANO</li>
        </ol>
      </div>
    </div>
    <div class="page-content">
      <!-- Panel Basic -->
      <div class="panel">
        <div class="panel-body">
        	<div>
        		<span>Paciente: <span id="nomPacCompleto"></span></span>
        	</div>
        
	          <table class="editable-table table table-striped editableTable"  style="cursor: pointer;">
	            <thead>
	              <tr>
	              	<th></th>
	             	<th></th>
	                <th>15</th>
	                <th>30</th>
	                <th>00</th>
	                <th>15</th>
	                <th>30</th>
	                <th>00</th>
	                <th>15</th>
	                <th>30</th>
	                <th>00</th>
	                <th>15</th>
	                <th>30</th>
	                <th>00</th>
	              </tr>
	            </thead>
	            <tbody>
	              <tr>
	                <th >Psis</th>
	                <th >mmHg</th>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	              </tr>
	              
	              <tr>
	                <th tabindex="1">Pdias</th>
	                <th tabindex="1">mmHg</th>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	              </tr>
	              
	              <tr>
	                <th tabindex="1" colspan="2">Frecuencia</th>
	                
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	              </tr>
	              
	              <tr>
	                <th tabindex="1" colspan="2">Sa02</th>
	                
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	              </tr>
	              
	              
	              
	            </tbody>
	            
	          </table>
	          
	          
	          <table class="editable-table table table-striped editableTable" id="" style="cursor: pointer;">
	            
	            <tbody>
	              
	              <tr>
	              	<th tabindex="1" colspan="2">SueroTerapia</th>
	                
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	                <td tabindex="1">0</td>
	              </tr>
	              
	            </tbody>
	            
	          </table>
          
        	<input style="position: absolute; top: 68px; left: 523px; padding: 8.008px; text-align: left; font: 300 14px/22px Roboto, sans-serif; width: 366px; height: 40px; border-width: 1px 0px 0px; border-style: solid none none; border-color: rgb(224, 224, 224) rgb(117, 117, 117) rgb(117, 117, 117); display: none;" class="error">
        	
        	<div class="col-sm-12">
        			<span><h3>Acceso Venoso</h3></span>
        		</div>
        	
        	<div class="col-sm-2">
        		
        		<div class="col-sm-6">
		            <div class="form-group">
		              <label for="sHoraIni">ESD</label>
		              <input type="text" class="form-control " id="nompacBusq" name="nompacBusq" placeholder="Nombre">
		            </div>
	         	</div>
	         	
	         	<div class="col-sm-6">
		            <div class="form-group">
		              <label for="sHoraIni">Calibre</label>
		              <input type="text" class="form-control " id="nompacBusq" name="nompacBusq" placeholder="Nombre">
		            </div>
	         	</div>
	         	
	         	<div class="col-sm-6">
		            <div class="form-group">
		              <label for="sHoraIni">ESI</label>
		              <input type="text" class="form-control " id="nompacBusq" name="nompacBusq" placeholder="Nombre">
		            </div>
	         	</div>
	         	
	         	<div class="col-sm-6">
		            <div class="form-group">
		              <label for="sHoraIni">Calibre</label>
		              <input type="text" class="form-control " id="nompacBusq" name="nompacBusq" placeholder="Nombre">
		            </div>
	         	</div>
        	</div>
        	
        	
        	
        	<div class="col-sm-4">
        		
        		<div class="col-sm-6">
		            <div class="form-group">
		              <label for="sHoraIni">T.ENDOTRAQUEAL</label>
		              <input type="text" class="form-control " id="nompacBusq" name="nompacBusq" placeholder="Nombre">
		            </div>
	         	</div>
	         	
	         	<div class="col-sm-6">
		            <div class="form-group">
		              <label for="sHoraIni">CH</label>
		              <input type="text" class="form-control " id="nompacBusq" name="nompacBusq" placeholder="Nombre">
		            </div>
	         	</div>
	         	
	         	<div class="col-sm-6">
		            <div class="form-group">
		              <label for="sHoraIni">MASCARILLA LARINGEA</label>
		              <input type="text" class="form-control " id="nompacBusq" name="nompacBusq" placeholder="Nombre">
		            </div>
	         	</div>
	         	
	         	<div class="col-sm-6">
		            <div class="form-group">
		              <label for="sHoraIni">CH</label>
		              <input type="text" class="form-control " id="nompacBusq" name="nompacBusq" placeholder="Nombre">
		            </div>
	         	</div>
	         	
	         	<div class="col-sm-8">
		            <div class="form-group">
		              <label for="sHoraIni">AGUJA RAQUI/EPI</label>
		              <input type="text" class="form-control " id="nompacBusq" name="nompacBusq" placeholder="Nombre">
		            </div>
	         	</div>
        	</div>
        	
        	
        	
        	<div class="col-sm-6">
        		<span><h3>MEDICACIÓN ANESTÉSICA</h3></span>
	        	<table class="editable-table table table-striped editableTable" id="" style="cursor: pointer;">
		            
		            <tbody>
		              
		              <tr>
		              	<th tabindex="1">PROPOFOL</th>
		                <td tabindex="1">0</td>
		                <td tabindex="1">0</td>
		                <td tabindex="1">0</td>
		              </tr>
		              <tr>
		              	<th tabindex="1">KURGAN 2g</th>
		                <td tabindex="1">0</td>
		                <td tabindex="1">0</td>
		                <td tabindex="1">0</td>
		              </tr>
		              <tr>
		              	<th tabindex="1">FENTANEST</th>
		                <td tabindex="1">0</td>
		                <td tabindex="1">0</td>
		                <td tabindex="1">0</td>
		              </tr>
		              <tr>
		              	<th tabindex="1">MIDAZOLAN</th>
		                <td tabindex="1">0</td>
		                <td tabindex="1">0</td>
		                <td tabindex="1">0</td>
		              </tr>
		              <tr>
		              	<th tabindex="1">ATROPINA</th>
		                <td tabindex="1">0</td>
		                <td tabindex="1">0</td>
		                <td tabindex="1">0</td>
		              </tr>
		              <tr>
		              	<th tabindex="1">SEVORANE</th>
		                <td tabindex="1">0</td>
		                <td tabindex="1">0</td>
		                <td tabindex="1">0</td>
		              </tr>
		              
		            </tbody>
		            
		        </table>
        	</div>
        	
        	<div class="col-sm-3">
        		<span><h3>TIPO ANESTESIA</h3></span>
        		<div class="checkbox-custom checkbox-primary">
	                <input type="checkbox" id="aneGEN"  onchange=""/>
	                <label for="aneGEN">GENERAL</label>
              	</div>
              	<div class="checkbox-custom checkbox-primary">
	                <input type="checkbox" id="anePLE"  onchange=""/>
	                <label for="anePLE">PLEXO</label>
              	</div>
              	<div class="checkbox-custom checkbox-primary">
	                <input type="checkbox" id="aneEXT"  onchange=""/>
	                <label for="aneEXT">EXTRADURAL</label>
              	</div>
              	<div class="checkbox-custom checkbox-primary">
	                <input type="checkbox" id="aneINT"  onchange=""/>
	                <label for="aneINT">INTRADURAL</label>
              	</div>
              	<div class="checkbox-custom checkbox-primary">
	                <input type="checkbox" id="aneLOC"  onchange=""/>
	                <label for="aneLOC">LOCAL</label>
              	</div>
        	</div>
        	
        	
        	<div class="col-sm-3">
        		<span><h3>POSICIÓN QUIRÚRGICA</h3></span>
        		<div class="checkbox-custom checkbox-primary">
	                <input type="checkbox" id="posSUP"  onchange=""/>
	                <label for="posSUP">Decúbito Supino</label>
              	</div>
              	<div class="checkbox-custom checkbox-primary">
	                <input type="checkbox" id="posPRO"  onchange=""/>
	                <label for="posPRO">Decúbito prono</label>
              	</div>
              	<div class="checkbox-custom checkbox-primary">
	                <input type="checkbox" id="posLAT"  onchange=""/>
	                <label for="posLAT">Decúbito lateral</label>
              	</div>
              	<div class="checkbox-custom checkbox-primary">
	                <input type="checkbox" id="posGIN"  onchange=""/>
	                <label for="posGIN">Ginecologica</label>
              	</div>
              	<div class="checkbox-custom checkbox-primary">
	                <input type="checkbox" id="posTRE"  onchange=""/>
	                <label for="posTRE">Tren/antitrendlg</label>
              	</div>
        	</div>
        	
        	<div class="col-sm-3">
        		<span><h3>ISQUEMIA</h3></span>
        		<div class="checkbox-custom checkbox-primary">
	                <input type="checkbox" id="isqESD"  onchange=""/>
	                <label for="isqESD">ESD</label>
              	</div>
              	<div class="checkbox-custom checkbox-primary">
	                <input type="checkbox" id="isqESI"  onchange=""/>
	                <label for="isqESI">ESI</label>
              	</div>
              	<div class="checkbox-custom checkbox-primary">
	                <input type="checkbox" id="isqEID"  onchange=""/>
	                <label for="isqEID">EID</label>
              	</div>
              	<div class="checkbox-custom checkbox-primary">
	                <input type="checkbox" id="isqEII"  onchange=""/>
	                <label for="isqEII">EII</label>
              	</div>
        	</div>
        	
        	<div class="col-sm-12">
        		<span><h3>OBSERVACIONES</h3></span>
                
                <textarea class="form-control input-lg" id="obscita" name="obscita" rows="3"  placeholder="Introduce un comentario"></textarea>
           
           </div>
        	
        </div>
      </div>
      <!-- End Panel Basic -->
    </div>
  </div>
  <!-- End Page -->
</div>




@stop
@section('js', '/private/assets/js/components/mindmup-editabletable.js')
@section('js1', '/js/app/private/plantillaRegQui.js')


