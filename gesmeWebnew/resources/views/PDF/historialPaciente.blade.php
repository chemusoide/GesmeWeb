<html>
  <head>
   <style>
     @page { margin-top : 120px;
	 margin-left : 50px;
	 margin-right : 50px;
	 margin-bottom : 170px; }
     #header { position: fixed; left: 0px; top: -100px; right: 0px; height: 80px; text-align: left;}
     #footer { position: fixed; left: 0px; bottom: -220px; right: 0px; height: 200px; }
     #footer .page:after { content: counter(page); }
   </style>
  <body>
  <div id="header">
          
			<div  style="padding-top: 20px;position:absolute; left:0pt; width:192px;"> 
					@if ( $data['empresa'] && $data['empresa'] == "2" )
						<img src="img/pdf/cabecera_Empresa_Luz.jpeg" width="243" height="52">
					@else
						<img src="img/pdf/cabecera_Empresa.jpg" width="243" height="52">
					@endif
			</div>
			<div  style="height: 80px; width:350px; margin-left:350px;padding-top: 20px; font-size:12px;">
				<div>
				<strong>Paciente</strong>
				</div>
				<div class="print50"> 
					<strong>Nombre:</strong>
					<span class="sNombre" ><% $data['nompac']%></span>
				
					<strong>Apellidos: </strong>
					<% $data['ap1pac']%> <% $data['ap2pac']%></span>
				</div>
				<div class="print50">
					<strong>DNI: </strong>
					<span class="sDni"><%$data['dniusr']%></span>
				
					<strong>Fec. Nacimiento: </strong>
					@if ( $data['fecnacpac'] && $data['fecnacpac'] == "H" )
					<span class="sFNa" class="time"><?php echo date("d/m/Y", strtotime($data['fecnacpac']));?></span>
					@endif
					<strong>sexo: </strong>
					@if ( $data['sexpac'] && $data['sexpac'] == "H" )
						<span class="sSex">Hombre</span>
					@endif
					@if ( $data['sexpac'] && $data['sexpac'] == "M" )
						<span class="sSex">Mujer</span>
					@endif
				</div>
				<div class="print50">
					<strong>Nº de historia: </strong>
					<span class="shistoria"><%$data['id']%></span>
				</div>
			</div>
		 
      </div>
       <div id="footer">
		   <div style="text-align:right">
				<span>Firmado: <?php echo $nombreCompletoUsr; ?></span>
		   
		   </div>
			<div width= "100%">
				<div  style="padding-top: 50px;;position:absolute; left:0pt; width:192px;"> 
					<span class="page">Página <?php $PAGE_NUM ?></span>
				</div>
				<div  style="border:1px solid black; height: 80px; width:300px; margin-left:400px; background-color:#D8E0E5; padding:5px;font-size:10px;">
					<span>Palma de Mallorca a <?php echo date("d/m/y H:i"); ?> </span>
				</div>
			</div>
			
		</div>
  <div class="print-historial">
 
  
      <div  class="print50">
       
      @if ($listaCitas && count($listaCitas) > 1)
		  <div class="col-sm-12 print100">
			  <h2>Historial Médico</h2>
		  </div>
	  @endif
      <!-- ALERGIAS -->
      
      @if ( $listAlergias && count($listAlergias) > 0 )
      	<?php $resultado = ''; ?>
	    @foreach ($listAlergias as $alergia)
	    	 <?php if(strlen($resultado) > 0){
	    	 	$resultado=$resultado. ", " .  $alergia->alergia;
	    	 }else{
	    	 	$resultado=$resultado .  $alergia->alergia;
	    	 } ?>
	    	
		@endforeach
      
	      <div class="panel panel-bordered">
			  
			  	<h4 class="panel-title"><strong>ALERGIAS</strong></h4>
	  		 <div  class="padding-20">
				<span><?php echo $resultado; ?></span>
			</div>
			</div>
		</div>
      @endif
      
      
       @if ( !$listAlergias || count($listAlergias) == 0 )
      	<?php $resultado = 'PACIENTE SIN ALERGIAS'; ?>
	    
      
	      <div class="panel panel-bordered">
			  
			  	<strong>ALERGIAS</strong>
	  		 <div>
				<span><?php echo $resultado; ?></span>
			</div>
			</div>
		</div>
      
      @endif
      
      <!-- FIN ALERGIAS -->
      
      <!-- ANTECEDENTES -->
      
	    @if ( $listAnt && count($listAnt) > 0 )
			<div class="panel panel-bordered">
				 <div class="panel-heading  ">
				  	<h3 class="panel-title"><strong>Antecedentes</strong></h3>
				  	<hr>
				</div>
			<div  class="bg-blue-400 padding-20">
			
			
			 	@foreach ($listAnt as $item)
				
					 <div class="panel panel-bordered">
							  <div class="panel-heading">
							  	<h3 class="panel-title"><?php  echo date("d/m/Y", strtotime($item->created_at));?> </h3>
								  </div>
								  
							<div class=" padding-20">
								<div class="mail-header-main">
					  			<div>
					  				<strong>Descripción</strong>
					  			</div>
					  		</div>
					  		<div>
					  			<?php echo $item->desant;?>
					  		</div>
					  		<div class="clearfix"></div>
							@if ( $item->obsant && strlen($item->obsant) > 0 )
								<div class="mail-header-main">
						  			<div>
						  				<strong>Observación</strong>
						  			</div>
						  		</div>
						  		<div>
						  			<?php echo $item->obsant;?>
					  			</div>
					  		@endif
					 	</div>
					 </div>
				
		  		@endforeach
				</div>
			</div>
	    @endif
      
      <!-- FIN ANTECEDENTES -->
      
      <!-- HAB. TOX. -->
      
	    @if ( $listHab && count($listHab) > 0 )
			<div class="panel panel-bordered">
				 <div class="panel-heading  ">
				  	<h3 class="panel-title"><strong>Hábitos Tóxicos</strong></h3>
				  	<hr>
				</div>
			<div  class="bg-blue-400 padding-20">
			
			
			 	@foreach ($listHab as $item)
				
					 <div class="panel panel-bordered">
							  <div class="panel-heading">
							  	<h3 class="panel-title"><?php  echo date("d/m/Y", strtotime($item->created_at));?> </h3>
								  </div>
								  
							<div class=" padding-20">
								<div class="mail-header-main">
					  			<div>
					  				<strong>Descripción</strong>
					  			</div>
					  		</div>
					  		<div>
					  			<?php echo $item->desant;?>
					  		</div>
					  		<div class="clearfix"></div>
							@if ( $item->obsant && strlen($item->obsant) > 0 )
								<div class="mail-header-main">
						  			<div>
						  				<strong>Observación</strong>
						  			</div>
						  		</div>
						  		<div>
						  			<?php echo $item->obsant;?>
					  			</div>
					  		@endif
					 	</div>
					 </div>
				
		  		@endforeach
				</div>
			</div>
	    @endif
      
      <!-- FIN HAB. TOX. -->
      
      <!-- MORFOLOGÍA -->
      
	    @if ( $listMor && count($listMor) > 0 )
	    <!--<div class="panel panel-bordered" style="page-break-before: avoid;>-->
			<div class="panel panel-bordered">
				 <div class="panel-heading  ">
				  	<h3 class="panel-title"><strong>Morfología</strong></h3>
				  	<hr>
				</div>
			<div  class="bg-blue-400 padding-20">
			
			
			 	@foreach ($listMor as $item)
				
					 <div class="panel panel-bordered">
							  <div class="panel-heading">
							  	<h3 class="panel-title"><?php echo date("d/m/Y", strtotime($item->created_at)); ?> </h3>
								  </div>
								  
							<div class=" padding-20">
								<div class="mail-header-main">
					  			<div>
					  				<strong>Descripción</strong>
					  			</div>
					  		</div>
					  		<div>
					  			<?php echo $item->desant;?>
					  		</div>
					  		<div class="clearfix"></div>
							@if ( $item->obsant && strlen($item->obsant) > 0 )
								<div class="mail-header-main">
						  			<div>
						  				<strong>Observación</strong>
						  			</div>
						  		</div>
						  		<div>
						  			<?php echo $item->obsant;?>
					  			</div>
					  		@endif
					 	</div>
					 </div>
				
		  		@endforeach
				</div>
			</div>
	    @endif
      
      <!-- FIN MORFOLOGÍA -->
      
	  <!-- CITAS -->
	  
	  
	  
	   @if ( $listaCitas && count($listaCitas) > 0 )
		   			  
				 
				 
					@if ( $listaCitas && count($listaCitas) > 1 )
						<div class="panel-heading">
							<h3 class="panel-title"><strong>CONSULTAS</strong></h3>
							<hr>
						</div>
					@endif
					
				  
			@foreach ($impTipConsulta as $objArr)
				<?php $cont = 0; ?>
				@foreach ($listaCitas as $item)
					
					@if ( $objArr == $item->codesp)
						@if ( $cont == 0 )
							@if ( $listaCitas && count($listaCitas) > 1 )
								<h4 class="panel-title"><strong><?php echo strtoupper($item->especialidad); ?></strong></h4>
							@else
								<hr>
							@endif
								
							<?php $cont = $cont + 1; ?>
						@endif
						
						
							<h4 class="panel-title"><?php echo date("d/m/Y", strtotime($item->fecinicita)); ?> - Médico: <span><?php echo $item->nomusr; ?> <?php echo $item->apusr; ?> </span></h4>
								
                       	@if ( $item->lineaConsulta )	
						<div>
							
							
							@if ( $listaCitas && count($listaCitas) > 1 )
								<strong>LÍNEAS DE CONSULTA</strong>
							@else
								<hr>
							@endif
						</div>
						
						<div>
							<?php echo $item->lineaConsulta; ?>
						</div>
						@endif
						<div>
							<strong>DIAGNÓSTICO</strong>
						</div>
						<div>
							<?php echo $item->diagnostico; ?>
						</div>
						<div>
							<strong>TRATAMIENTO</strong>
						</div>
						<div>
							<?php echo $item->tratamiento; ?>
						</div>
					
					@endif
				
				@endforeach
			
			@endforeach
	   
	   @endif
	   
	  
	  
	  
	  <!-- FIN CITAS -->
	  
	  <!-- INGRESOS -->
	  
	  @if ( $listIngreso && count($listIngreso) > 0 )
			<div class="panel panel-bordered">
				 <div class="panel-heading  ">
				  	<h3 class="panel-title"><strong>Ingresos</strong></h3>
				  	<hr>
				</div>
			<div  class="bg-blue-400 padding-20">
			
			
			 	@foreach ($listIngreso as $item)
				
					 <div class="panel panel-bordered">
							  <div class="panel-heading">
							  	<h3 class="panel-title">Fecha Ingreso: <?php  echo date("d/m/Y", strtotime($item->created_at));?> </h3>
							  </div>
							<div>
							<span><strong>Fecha Alta:</strong> <?php  echo date("d/m/Y", strtotime($item->fecalta));?></span>
							</div>  
							<div class=" padding-20">
								<div class="mail-header-main">
					  			<div>
					  				<strong>Informe de alta</strong>
					  			</div>
					  		</div>
					  		<div>
					  			<?php echo $item->infalta;?>
					  		</div>
					  		<div class="clearfix"></div>
							
					 	</div>
					 </div>
				
		  		@endforeach
				</div>
			</div>
	    @endif
	  
	  <!-- FIN INGRESOS -->
	  
	  
  </div>
   </body>
 </html>