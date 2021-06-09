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
		
	 
		</div>
		<div id="footer">
			@if ( $nombreCompletoUsr )
			   <div style="text-align:right">
					<span>Firmado: <?php echo $nombreCompletoUsr; ?></span>
			   </div>
			@endif
			<div width= "100%">
				<div  style="padding-top: 50px;;position:absolute; left:0pt; width:192px;"> 
					<span class="page">Página <?php $PAGE_NUM ?></span>
				</div>
				@if ( $nombreCompletoUsr )
					<div  style="border:1px solid black; height: 80px; width:300px; margin-left:400px; background-color:#D8E0E5; padding:5px;font-size:10px;">
						<span>Palma de Mallorca a <?php echo date("d/m/y H:i"); ?> </span>
					</div>
				@endif
			</div>
			
		</div>
		<div class="print-historial">


		{!! $data['html'] !!}
			
	  
		</div>
	</body>
 </html>