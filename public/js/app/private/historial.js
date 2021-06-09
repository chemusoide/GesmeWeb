$(document).ready(function($) {
	$.fn.datepicker.defaults.language = 'es';
	$.fn.datepicker.defaults.weekStart = 1;
	$('.datepicker').datepicker();
	initPantalla();
});

var usuarioSes = null;

function crearInputAntecedentes(lista, tipo){
	if(lista){
		for(var i = 0; i < lista.length; i++){
			if($('#'+tipo+ lista[i].id).is(":checked")){
				$( "#divInputAdd").append('<input type="hidden" name="impList'+tipo+'[]" id="impList'+tipo+i+'">');					
				$( "#impList"+tipo+i).val(lista[i].id);
			}
		}
	}
	
}

function imprimirHistorial(){
	$('#divInputAdd').empty();
	var ImpCitas = 'N';
	//añadirmos los inputs
	if(tipConConDatos){
		ImpCitas = 'S';
		for(var i = 0; i < tipConConDatos.length; i++){
			$( "#divInputAdd").append('<input type="hidden" name="impTipConsulta[]" id="impTipConsulta'+i+'">');					
			$( "#impTipConsulta"+i).val(tipConConDatos[i]);
		}
		if(!$('#impCitas').is(":checked")){
		
			for(var i = 0; i < tipConConDatos.length; i++){
				if(listPorTipos[tipConConDatos[i]]){
					for(var x = 0; x < listPorTipos[tipConConDatos[i]].length; x++){
						if($('#impCit'+tipConConDatos[i]+listPorTipos[tipConConDatos[i]][x]).is(":checked")){
							$( "#divInputAdd").append('<input type="hidden" name="impIdConsulta[]" id="impIdConsulta'+listPorTipos[tipConConDatos[i]][x]+'">');					
							$( "#impIdConsulta"+listPorTipos[tipConConDatos[i]][x]).val(listPorTipos[tipConConDatos[i]][x]);
						}
					}	
				}
			}
		}
	}
	
	
	$( "#divInputAdd").append('<input type="hidden" name="impAlergia" id="impAlergia">');
	$( "#divInputAdd").append('<input type="hidden" name="impAntecedente" id="impAntecedente">');
	$( "#divInputAdd").append('<input type="hidden" name="impHabito" id="impHabito">');
	$( "#divInputAdd").append('<input type="hidden" name="impMorfologia" id="impMorfologia">');
	$( "#divInputAdd").append('<input type="hidden" name="impListCitas" id="impListCitas">');
	
	/******** Seteamos variables  ***********/
	//Elementos a imprimir
	$('#impAlergia').val(($('#impAle') && $('#impAle').is(":checked"))?'S':'N');

	// Si no esta marcado el Padre comprobamos insertaremos los que esten marcados
	$('#impAntecedente').val(($('#impANT') && $('#impANT').is(":checked"))?'S':'N');
	if($('#impAntecedente').val() == 'N'){
		crearInputAntecedentes(listAntBusq, 'ANT');
	}

	$('#impHabito').val(($('#impHAB') && $('#impHAB').is(":checked"))?'S':'N');
	if($('#impHabito').val() == 'N')
		crearInputAntecedentes(listHabBusq, 'HAB');
	
	$('#impMorfologia').val(($('#impMOR') && $('#impMOR').is(":checked"))?'S':'N');
	if($('#impMorfologia').val() == 'N')
		crearInputAntecedentes(listMorBusq, 'MOR');

	$('#impListCitas').val(ImpCitas);
	
	//Elementos necesarios
	$('#impIdMed').val(usuarioSes.id);
	$('#impFechaDesde').val(fechaStringEncode($('#dpDesde').val()));
	$('#impIdePac').val(paciente.id);
	$( "#fImpHist" ).submit();
}


function obtenerDatosUsuarioSession (){
	$('.loader-wrap').removeClass("hide");
		$.ajax({
          url:   '/private/obtenerDatosUsuarioSession',
          type:  'GET',
          dataType: 'json',
          success:  function (data) {
        	  $('.loader-wrap').addClass("hide");
        	  if(swiImpHist == 'S')
        		  $('#btnImpHist').removeClass('hide');
        	  else
        		  $('#btnImpHist').addClass('hide');
              if(data && data.usuario){
	            	 
               
                 usuarioSes = data.usuario;
              }
          }
        });
}

obtenerDatosUsuarioSession();
