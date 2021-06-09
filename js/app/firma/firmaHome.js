$(document).ready(function(){

});



function mostrarNumDoc(extension){
	//var strCompTip = '#tipdoc';
	
	if($( '#tipdoc' + extension).val()== 'DNI'){
		$( '#dniusr' + extension ).removeClass('hide');
		$( '#nieusr' + extension ).addClass('hide');
		$( '#passusr' + extension ).addClass('hide');
	}
	if($( '#tipdoc' + extension ).val()== 'NIE'){
		$( '#dniusr' + extension ).addClass('hide');
		$( '#nieusr' + extension ).removeClass('hide');
		$( '#passusr' + extension ).addClass('hide');
	}
	if($( '#tipdoc' + extension ).val()== 'PAS'){
		$( '#dniusr' + extension ).addClass('hide');
		$( '#nieusr' + extension ).addClass('hide');
		$( '#passusr' + extension ).removeClass('hide');
	}
}


$( '#tipdocBusq' ).change(function(v) {
	$( '#dniusrBusq' ).val('');
	$( '#nieusrBusq' ).val('');
	$( '#passusrBusq' ).val('');
	
	mostrarNumDoc('Busq');
});

function cargarProf(){
	$('.loader-wrap').removeClass("hide");
	$('#idProf').empty();
	$('#idProf').append(
			$('<option>', {
		    value: '',
		    text: 'Profesionales'
		}));
	$.ajax({
        url:   generarUrl('/private/verListaDocsPendFirma'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	console.info(data);
        	if(data){
        		listaDocsPend = data.listaDocsPend;
        		$('#idProf').append('<optgroup role="group" label="Medicos con cuenta">');
        		for(var i = 0; i< listaDocsPend.length; i++){
        			
        			var lDoc = listaDocsPend[i];
        			if(lDoc.idmed){
        				$('#idProf').append(
            					$('<option>', {
            				    value: lDoc.nombremed,
            				    text: lDoc.nombremed
            				}));
        			}
        			
        		}
        		$('#idProf').append('<optgroup role="group" label="Medicos sin cuenta">');
        		for(var i = 0; i< listaDocsPend.length; i++){
        			
        			var lDoc = listaDocsPend[i];
        			if(!lDoc.idmed){
        			$('#idProf').append(
        					$('<option>', {
        						value: lDoc.nombremed,
            				    text: lDoc.nombremed
        				}));
        			}
        		}
        	}
        }
		});
}

function busquedaMedFirma(){
	
	if(!$( '#idProf' ).val()){
		swal('Error', 'Seleccione uno de la lista' , 'error');
		return false;
	}
	
	window.location.href = generarUrl('/firma/firma-documentos-profesional/'+$( '#idProf' ).val());
	
}
function busquedaUsrFirma(){
	
	console.info('busquedaUsrFirma');
	console.info($( '#dniusrBusq' ).val());
	if(!$( '#dniusrBusq' ).val() || $( '#dniusrBusq' ).val() =='        - '){
		swal('Error', 'Introduzca un documento' , 'error');
		return false;
	}
	
	window.location.href = generarUrl('/firma/firma-documentos/'+$( '#dniusrBusq' ).val().toUpperCase());
	
	
}