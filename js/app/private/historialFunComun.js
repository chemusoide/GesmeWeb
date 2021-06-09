$(document).ready(function($) {
	$('.datepicker').datepicker({weekStart: 1,
		    format: "dd/mm/yyyy",
		    language: 'es'});
});


var listAntBusq = null;
var listHabBusq = null;
var listMorBusq = null;
var tipConConDatos = null;
var listPorTipos = null;
var crearPopInfo = 'S';
var listIngrBusq = null;

function comboListaEspecialidades(){
	if(listaCodesp && listaCodesp.length > 0){
		$('#tipCitasDisp').empty();
		$('.verConClass').removeClass('hide');
		for(var i = 0; i < listaCodesp.length; i++){
			var item = '<div class="checkbox-custom checkbox-primary col-md-3">'
                +'<input type="checkbox" id="ch'+listaCodesp[i].codesp+'"  onchange=""/>'
                +'<label for="ch'+listaCodesp[i].codesp+'">'+ listaCodesp[i].especialidad +'</label>'
                +'</div>';
			$('#tipCitasDisp').append(item);
		}
	}else{
		$('.verConClass').addClass('hide');
	}
}

function initPantalla(){
	if(paciente){
		$('.sNombre').html(paciente.nompac);
		$('.sAp1').html(paciente.ap1pac);
		$('.sAp2').html(paciente.ap2pac);
		$('.sSex').html(paciente.sexpac == 'M'?'Mujer': 'Hombre');
		$('.sDni').html(paciente.dniusr);
		$('.sFNa').html(fechaString(paciente.fecnacpac));
		$('.sDir').html(paciente.dirpac);
		$('.sCp').html(paciente.cppac);
		
		
		comboListaEspecialidades();
		
	}
}

function gestionChecksGenericos(tip, swiGen, id){
	
		var listaUsar = (tip == 'ANT' )?listAntBusq :(tip == 'HAB' )?listHabBusq :(tip == 'MOR' )?listMorBusq : (tip == 'ingreso' )?listIngrBusq : null;
		//Si es un generico se activan y se desactivantodos
		if(swiGen == 'S'){
			for(var i = 0; i < listaUsar.length; i++){
				$('#'+tip+ listaUsar[i].id).prop('checked', $('#imp'+tip).is(":checked"));
			}
		}else{
			if(!$('#'+tip+ id).is(":checked")){
				$('#imp'+tip).prop('checked', false);
			}else{
				var marcarCheckGen = true;
				for(var i = 0; i < listaUsar.length; i++){
					if(!$('#'+tip+ listaUsar[i].id).is(":checked")){
						marcarCheckGen = false;
						break;
					}
				}
				$('#imp'+tip).prop('checked', marcarCheckGen);	
			}
		}
}


function pepararDivsAntecedentes(tit, name, lista, tip){
	
	var strDiv = '<div class="panel panel-bordered">'
		  +'<div class="panel-heading  bg-blue-500" style=" cursor: pointer;cursor: hand;" data-toggle="collapse" data-parent="#accordion" href="#'+name+'">'
		  
			+'<div class="checkbox-custom checkbox-primary margin-left-5 ">'
		  		+'<input type="checkbox" id="imp'+tip+'"  onchange="gestionChecksGenericos(\''+tip+'\',\'S\')"/>'
		  		+'<label for="imp'+tip+'"><h3 class="h4"><strong>'+tit+'</strong></h3></label>'
	  		+'</div>'
	  		+'<div class="panel-actions">'
			  			+'<a class="panel-action icon text-success wb-minus" style="color:white;" data-toggle="collapse" data-parent="#accordion" href="#'+name+'"></a>'
			  		+'</div>'
			+'</div>'
		  +'<div id="'+name+'" class="collapse bg-blue-400 padding-20">';
	
	for(var i = 0; i < lista.length; i++){
		
		
		strDiv = strDiv +'<div class="panel panel-bordered">'
				  +'<div class="panel-heading  bg-blue-300">'
				  
				  +'<div class="checkbox-custom checkbox-primary margin-left-5 ">'
					+'<input type="checkbox" id="'+tip+ lista[i].id +'"  onchange="gestionChecksGenericos(\''+tip+'\',\'N\',\''+lista[i].id+'\')"/>'
					+'<label for="'+tip+ lista[i].id +'"><h3 class="h4"><strong>'+fechaString(lista[i].created_at)+'</strong></h3></label>'
					+'</div>'
				  
					  +'</div>'
					  
				+'<div class="bg-blue-100 padding-20">'
					+'<div class="mail-header-main">'
		  			+'<div>'
		  				+'<strong>Descripción</strong>'
		  			+'</div>'
		  		+'</div>'
		  		+'<div>'
		  			+ lista[i].desant
		  		+'</div>'
		  		+'<div class="clearfix"></div>';
				if(lista[i].obsant){
					strDiv = strDiv +'<div class="mail-header-main">'
			  			+'<div>'
			  				+'<strong>Observación</strong>'
			  			+'</div>'
			  		+'</div>'
			  		+'<div>'
			  			+ lista[i].obsant
		  		+'</div>';
		}
  		strDiv = strDiv +'</div></div>';
	}
	
	
	
	strDiv = strDiv +'</div></div>';
	$('.contenedorHistorial').append(strDiv);

}

function divAlergiaHist(strAl, tip){

		var strDivAl =  
		'<div class="panel panel-bordered">'
			+'<div class="panel-heading  bg-red-300" style=" cursor: pointer;cursor: hand;" data-toggle="collapse" data-parent="#accordion" href="#collapseAl">'
				+'<div class="checkbox-custom checkbox-primary margin-left-5 ">'
					+'<input type="checkbox" id="impAle"  onchange=""/>'
					+'<label for="impAle"><h3 class="h4"><strong>Alergias</strong></h3></label>'
				+'</div>'
				+'<div class="panel-actions">'
					+'<a class="panel-action icon wb-minus" style="color:white;" data-toggle="collapse" data-parent="#accordion" href="#collapseAl"></a>'
				+'</div>'
	  		+'</div>'
			+'<div id="collapseAl" class="collapse  bg-red-100 padding-20">'
				+'<div>'
					+'<span>'+ strAl +'</span>'
					+'</div>'
				+'</div>'
			+'</div>'
		+'</div>';
	return strDivAl;
}


function gestionChecksCitas(swiGen, tip, id){

	if(swiGen == 'S'){
		for(var i = 0; i < tipConConDatos.length; i++){
			$('#impCit'+tipConConDatos[i]).prop('checked', $('#impCitas').is(":checked"));
			if(listPorTipos[tipConConDatos[i]]){
				for(var x = 0; x < listPorTipos[tipConConDatos[i]].length; x++){
					$('#impCit'+tipConConDatos[i]+listPorTipos[tipConConDatos[i]][x]).prop('checked', $('#impCitas').is(":checked"));
				}	
			}
			
		}
	}else{
		if(!id){
			var marcarHijas = true;
			if(!$('#impCit'+tip).is(":checked")){
				$('#impCitas').prop('checked', false);
				marcarHijas = false;
			}else{
				var marcarCheckGen = true;
				for(var i = 0; i < tipConConDatos.length; i++){
					if(!$('#impCit'+tipConConDatos[i]).is(":checked")){
						marcarCheckGen = false;
						break;
					}
				}
				$('#impCitas').prop('checked', marcarCheckGen);	
			}
			
			for(var i = 0; i < listPorTipos[tip].length; i++){
				$('#impCit'+tip+listPorTipos[tip][i]).prop('checked', marcarHijas);
			}
		}else{
			
			if(!$('#impCit'+tip+id).is(":checked")){
				$('#impCitas').prop('checked', false);
				$('#impCit'+tip).prop('checked', false);
			}else{
				var marcarCheckGen = true;
				var marcarCheckPadre = true;
				//Comprobamos al padre
				for(var i = 0; i < listPorTipos[tip].length; i++){
					if(!$('#impCit'+tip+listPorTipos[tip][i]).is(":checked")){
						marcarCheckPadre = false;
						break;
					}
				}
				$('#impCit'+tip).prop('checked', marcarCheckPadre);	
				//comprobamos el general
				if(marcarCheckPadre){
					for(var i = 0; i < tipConConDatos.length; i++){
					if(!$('#impCit'+tipConConDatos[i]).is(":checked")){
						marcarCheckGen = false;
						break;
					}
				}
				$('#impCitas').prop('checked', marcarCheckGen);
				}
			}
		
		}
		
	}
}


/*function gestionChecksIngresos(swiGen, id){

	//Si es un generico se activan y se desactivantodos
	if(swiGen == 'S'){
		for(var i = 0; i < listIngrBusq.length; i++){
			$('#'+tip+ listIngrBusq[i].id).prop('checked', $('#ingreso'+).is(":checked"));
		}
	}else{
		if(!$('#'+tip+ id).is(":checked")){
			$('#imp'+tip).prop('checked', false);
		}else{
			var marcarCheckGen = true;
			for(var i = 0; i < listaUsar.length; i++){
				if(!$('#'+tip+ listaUsar[i].id).is(":checked")){
					marcarCheckGen = false;
					break;
				}
			}
			$('#imp'+tip).prop('checked', marcarCheckGen);	
		}
	}
}*/

function pepararDivsCitas(data, tipConsulta){

	$('.contenedorHistorial').append(
				 '<div class="panel panel-bordered">'
				  
				  +'<div class="panel-heading  bg-orange-500" style=" cursor: pointer;cursor: hand;" data-toggle="collapse" data-parent="#accordion" href="#collapseCita">'

					+'<div class="checkbox-custom checkbox-primary margin-left-5 ">'
				  		+'<input type="checkbox" id="impCitas" onchange="gestionChecksCitas(\'S\')" />'
				  		+'<label for="impCitas"><h3 class="h4"><strong>Citas</strong></h3></label>'
			  		+'</div>'
				  		+'<div class="panel-actions">'
				  			+'<a class="panel-action icon wb-minus" style="color:white;" data-toggle="collapse" data-parent="#accordion" href="#collapseCita"></a>'
				  		+'</div>'
   			  +'</div>'
   			  
   			  
   			  
				+'<div id="collapseCita" class="collapse bg-orange-400 padding-20">'
					+'<div class="contenedorHistorialCitas">'
						
					+'</div>'
				+'</div>'
       	+'</div>');
	
	for(x = 0; x < tipConsulta.length; x++){
		
		var cont = 0;
		
		 for(var i = 0; i < data.listaCitas.length; i++){
			 if(tipConsulta[x] == data.listaCitas[i].codesp){
				 if(cont == 0){
					 listPorTipos[tipConsulta[x]] = [];
					 tipConConDatos.push(tipConsulta[x]);
					 $('.contenedorHistorialCitas').append(
             				 '<div class="panel panel-bordered">'
             				  
             				  +'<div class="panel-heading  bg-orange-300" style=" cursor: pointer;cursor: hand;" data-toggle="collapse" data-parent="#accordion" href="#collapse'+tipConsulta[x]+'">'
             				  	+'<div class="checkbox-custom checkbox-primary margin-left-5 ">'
      				  				+'<input type="checkbox" id="impCit'+tipConsulta[x]+'"  onchange="gestionChecksCitas(\'N\',\''+tipConsulta[x]+'\')"/>'
      				  				+'<label for="impCit'+tipConsulta[x]+'"><h3 class="h4"><strong>'+ data.listaCitas[i].especialidad +'</strong></h3></label>'
      				  			+'</div>'
             				  		+'<div class="panel-actions">'
             				  			+'<a class="panel-action icon wb-minus" style="color:white;" data-toggle="collapse" data-parent="#accordion" href="#collapse'+tipConsulta[x]+'"></a>'
             				  		+'</div>'
                   			  +'</div>'
                   			  
             				+'<div id="collapse'+tipConsulta[x]+'" class="collapse bg-orange-100 padding-20">'
             					
                       	+'</div>'
                       +'</div>');
      		   	}
				 
				 listPorTipos[tipConsulta[x]].push(data.listaCitas[i].id);
				 var fechaMostrar = data.listaCitas[i].fecinicita?data.listaCitas[i].fecinicita:data.listaCitas[i].feccita;
				 var strCitasAdd = '<div class="panel panel-bordered">'
   				  
   				  +'<div class="panel-heading  bg-orange-300">'
   				  	+'<div class="checkbox-custom checkbox-primary margin-left-5 ">'
			  				+'<input type="checkbox" id="impCit'+tipConsulta[x]+data.listaCitas[i].id+'"  onchange="gestionChecksCitas(\'N\',\''+tipConsulta[x]+'\',\''+data.listaCitas[i].id+'\')"/>'
			  				+'<label for="impCit'+tipConsulta[x]+data.listaCitas[i].id+'"><h3 class="h4"><strong>'+ fechaString(fechaMostrar)+'</strong></h3></label>'
			  			+'</div>'

         			  +'</div>'
         			  
   				+'<div id="collapse'+tipConsulta[x]+'" class="bg-orange-200 padding-20">'
   				+'<div>'
					+'<strong>Médico: </strong>'
					+'<span>'+data.listaCitas[i].nomusr + ' ' + data.listaCitas[i].apusr +'</span>'
				+'</div>';
				 
				if(data.listaCitas[i].lineaConsulta){
					strCitasAdd = strCitasAdd + '<div class="mail-header-main">'
            		+' <div>'
            		+'<strong>Lineas de Consulta</strong>'
            		+'</div>'
            		+'</div>'
            		
            		
            		+'<div>'
            		+ data.listaCitas[i].lineaConsulta
            		+'</div>';
            	}
				
				if(data.listaCitas[i].diagnostico){
					strCitasAdd = strCitasAdd + '<div class="clearfix"></div>'
               		
               		+'<div class="mail-header-main">'
               		+' <div>'
               		+'<strong>Diagnostico</strong>'
               		+'</div>'
               		+'</div>'
               		
               		+'<div>'
               		+ data.listaCitas[i].diagnostico
               		+'</div>';
            	}
				
				if(data.listaCitas[i].tratamiento){
					strCitasAdd = strCitasAdd + '<div class="clearfix"></div>'
               		
               		+'<div class="mail-header-main">'
               		+' <div>'
               		+'<strong>Tratamiento</strong>'
               		+'</div>'
               		+'</div>'
               		
               		+'<div>'
               		+ data.listaCitas[i].tratamiento
               		+'</div>';
            	}
				strCitasAdd = strCitasAdd +  '</div>'
					+'</div>';
				 
				 $('#collapse'+tipConsulta[x]).append(strCitasAdd);
				 cont = cont + 1;
			 }
     		  
     	  }  
	}

}

function crearPopUpGen(){
	$( "body" ).append(`<div class="modal fade modal-3d-flip-horizontal" id="mGenerico"   aria-hidden="true" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
			<h3 class="modal-title mGenTit"></h3>
		</div>
		
		<div class="modal-body mGenBody">
			
			


		</div>
		
		
		
		
		
	</div>
	<div class="modal-footer">
	</div>
</div>
</div>`);
	$('#mGenerico').modal('toggle');
}

function verInformeAlta(id){
	if(crearPopInfo == 'S'){
		crearPopInfo = 'N';
		crearPopUpGen();
	}else
		$('#mGenerico').modal('toggle');
	
	$('.mGenTit').empty();
	$('.mGenTit').append('Informe de alta');
	$('.mGenBody').empty();
	
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		  url: generarUrl('/private/obtenerInformeAlta'),
		  data:{
			  'idingreso' : id
		  },
		  type:  'GET',
		  dataType: 'json',
		  success:  function (data) {
			  $('.loader-wrap').addClass("hide");
		      if(data && data.ingreso){
		    	 console.info();
		    	 $('.mGenBody').append(data.ingreso.infalta);
		    	  
		      }
		  }
	});
}

function pepararDivsIngresos(lista){

	
	$('.contenedorHistorial').append(
				 '<div class="panel panel-bordered">'
				  
				  +'<div class="panel-heading  bg-teal-500" style=" cursor: pointer;cursor: hand;" data-toggle="collapse" data-parent="#accordion" href="#collapseIngresos">'

					+'<div class="checkbox-custom checkbox-primary margin-left-5 ">'
				  		+'<input type="checkbox" id="impingreso" onchange="gestionChecksGenericos(\'ingreso\',\'N\')" />'
				  		+'<label for="impIngresos"><h3 class="h4"><strong>Ingresos</strong></h3></label>'
			  		+'</div>'
				  		+'<div class="panel-actions">'
				  			+'<a class="panel-action icon wb-minus" style="color:white;" data-toggle="collapse" data-parent="#accordion" href="#collapseIngresos"></a>'
				  		+'</div>'
   			  +'</div>'
   			  
   			  
   			  
				+'<div id="collapseIngresos" class="collapse bg-teal-400 padding-20">'
					+'<div class="contenedorHistorialIngresos">'
						
					+'</div>'
				+'</div>'
       	+'</div>');
	
	for(var i = 0; i < lista.length; i++){
		
		var med = lista[i].desmed;
		
		if(med.indexOf("-")>0)
			med = med.substring(0, med.indexOf("-"));
		
		var strDiv = '<div class="panel panel-bordered">'
				  +'<div class="panel-heading  bg-teal-300">'
				  
				  +'<div class="checkbox-custom checkbox-primary margin-left-5 ">'
					+'<input type="checkbox" id="ingreso'+ lista[i].id +'"  onchange="gestionChecksGenericos(\'ingreso\',\'N\',\''+lista[i].id+'\')"/>'
					+'<label for="ingreso'+ lista[i].id +'"><h3 class="h4"><strong>Fecha Ingreso'+fechaString(lista[i].fecingreso)+'</strong></h3></label>'
					+'</div>'
				  
					  +'</div>'
					  
				+'<div class="bg-teal-100 padding-20">'
					+'<div class="mail-header-main">'
		  			+'<div>'
		  				+'<h3><strong>Información</strong></h3>'
		  			+'</div>'
		  			+ `<div class="col-md-4">
						<span><strong>Centro: </strong>`+ lista[i].nombre +`</span>
					</div>
					<div class="col-md-4">
						<span><strong>Fec. Alta: </strong>` + fechaString(lista[i].fecalta) + `</span>
					</div>
					<div class="col-md-4">
						<span><strong>Médico Responsable: </strong>`+ med +`</span>
					</div>`
					
					+`<div class="col-md-12">
	                  <div class="input-group top15">
	                  	<button type="button" class="btn btn-success" onclick="verInformeAlta(`+lista[i].id+`)">Informe de alta</button>
	                  	
	                  </div>
					</div>`
					+'<div class="clearfix"></div>'
		  		+'</div>'
		  		+'<div>'
		  		+'</div>';
  		
  		$('.contenedorHistorialIngresos').append(strDiv);
	}
	

}

function buscarHistorial(){
	 $('.contenedorHistorial').empty();
	$('.loader-wrap').removeClass("hide");
	
	var verAlergias = 'N';
	var verAntecede = 'N';
	var verConsulta = 'N';
	var tipConsulta = [];
	tipConConDatos = [];
	listPorTipos = [];
	var verIngresos = 'N';
	
	if($('#verAle').is(":checked"))
		verAlergias = 'S';
	if($('#verAnt').is(":checked"))
		verAntecede = 'S';
	if($('#verIng').is(":checked"))
		verIngresos = 'S';
	if($('#verCon').is(":checked")){
		
		if(listaCodesp){
			for(var i = 0; i < listaCodesp.length; i++){
				if($('#ch'+listaCodesp[i].codesp).is(":checked")){
					tipConsulta.push(listaCodesp[i].codesp);
				}
			}
		}
		
		verConsulta = 'S';
	}

	$.ajax({
		data: { 'verAlergias' : verAlergias,
				'verAntecede' : verAntecede,
				'verIngresos' : verIngresos,
				'verConsulta' : verConsulta,
				'tipConsulta' : tipConsulta,
				'fechaDesde' : fechaStringEncode($('#dpDesde').val()),
				'idMed' : usuarioSes.id,
				'id' : paciente.id},
        url:  generarUrl('/private/buscarHistorial'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	//ALERGIAS
        	if(data && data.listAlergias){
        		var strAlergias = '';
          	  for(var i = 0; i < data.listAlergias.length; i++){
          		  if(strAlergias.length > 0 ){
          			  if(i == data.listAlergias.length - 1)
          				strAlergias = strAlergias + ' y ' + data.listAlergias[i].alergia;
          			  else
          				strAlergias = strAlergias + ', ' + data.listAlergias[i].alergia;
          		  }else
          			strAlergias = data.listAlergias[i].alergia;
          	  
          	  }
          		  
          		$('.contenedorHistorial').append(divAlergiaHist(strAlergias));          		
               
            }
        	
        	//ANTECEDENTES
        	listAntBusq = null;
			listHabBusq = null;
			listMorBusq = null;
        	if(data && data.listAnt){
				listAntBusq = data.listAnt;
        		pepararDivsAntecedentes('Antecedentes', 'collapseAn',data.listAnt, 'ANT' );
        	}
        		
        	if(data && data.listHab){
				listHabBusq = data.listHab;
        		pepararDivsAntecedentes('Hábitos Tóxicos', 'collapseHa',data.listHab, 'HAB');
        	}
        		
        	if(data && data.listMor){
				listMorBusq = data.listMor;
        		pepararDivsAntecedentes('Morfología', 'collapseMo',data.listMor, 'MOR');
        	}
        		
        	
        	
        	//citas
        	if(data && data.listaCitas)
        		pepararDivsCitas(data, tipConsulta);
        	
        	//Ingresos
        	if(data && data.listIngresos){
        		listIngrBusq = data.listIngresos;
        		pepararDivsIngresos(data.listIngresos);
        	}
        		
        	
        	
        	
        }
	});
	
}


function changeVerCon(){
	if($('#verCon').is(":checked"))
		$('#conTipCitasDisp').removeClass('hide');
	else
		$('#conTipCitasDisp').addClass('hide');
}

