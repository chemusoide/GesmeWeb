$(document).ready(function(){
      
      setTimeout(
    		  function() 
    		  {
    			  $('[name="liMenuFicha"]').addClass('active');
    		  }, 500);
    
});

var usuarioSes = null;
var pacientesActuales = null;
var paciente = null;
var listaCodesp = null;
var verHist = 'N';
var verPruebasComp = 'N';

function mostrarDivNuevo(divMostrar){
	
	$('.'+divMostrar).removeClass('hide');
	$('.b'+divMostrar).addClass('hide');
	
}

function ocultarDivNuevo(divMostrar){
	
	$('.'+divMostrar).addClass('hide');
	$('.b'+divMostrar).removeClass('hide');
	$('#'+divMostrar+'Obs').val('');
	$('#'+divMostrar+'Des').val('');
	
}

function fechaString(date){
	if(!date || date == '0000-00-00')
		return '';
	year = date.substring(0, 4);
    mes = date.substring(5, 7);
    dia = date.substring(8, 10);
    if(year.indexOf("/") >= 0 || mes.indexOf("/") >= 0 || dia.indexOf("/") >= 0  )
    	return null;
    return dia + '/'+ mes + '/' + year;
}

function limpiarBusqueda(){
	$('#nompac').val('');
	$('#ap1pac').val('');
	$('#ap2pac').val('');
}

function verFichaPaciente(orden){
	$('.loader-wrap').removeClass("hide");
	$('#panelBusq').addClass('hide');
	$('#panelFiltro').addClass('hide');
	$('.page-title').html('Ficha Paciente');
	$('.pagActual').html('Ficha Paciente');
	$('#nomComplePac').html(pacientesActuales[orden].ap1pac+ ' ' + pacientesActuales[orden].ap1pac + ', ' + pacientesActuales[orden].nompac);
		
	$('#datosPac').removeClass('hide');
	
	paciente = pacientesActuales[orden];
	if(verHist == 'S'){
		$.ajax({
			
			  data: {'idpac' : pacientesActuales[orden].id},
	          url:   '/private/obtenerEspecialidadesPac',
	          type:  'GET',
	          dataType: 'json',
	          success:  function (data) {
				  $('.loader-wrap').addClass("hide");
	              if(data && data.listaCodesp){
	            	listaCodesp = data.listaCodesp;
	              }
	              //historialFunComun.js
	              initPantalla();
	              if(verPruebasComp == 'S')
	            	  prepararVentanaPrueCompl();
	          }
	        });
		
	}else if(verPruebasComp == 'S'){
		 prepararVentanaPrueCompl();
	}
	
	
	
	
}

function buscarPacienteNomAp(){
	if(!$('#nompac').val() && !$('#ap1pac').val() && !$('#ap2pac').val()){
		swal("Error", 'Debe especificar almenos un elemento en la busqueda', "error");
		return false;
	}
	$('.loader-wrap').removeClass("hide");
	
	$('#panelBusq').removeClass('hide');
	
	pacientesActuales = null;
	var tPac = $('#tablaPacientes').DataTable();
    tPac.clear().draw();
	$.ajax({
			data: {
				'nompac': $('#nompac').val(),
				'ap1pac': $('#ap1pac').val(),
				'ap2pac': $('#ap2pac').val(),
			},
			url:   '/private/buscarPacienteNomAp',
			type:  'GET',
			dataType: 'json',
			success:  function (data) {
				if(data && data.listaPacientes){
                    pacientesActuales = data.listaPacientes;
            		for(var i = 0; i < data.listaPacientes.length; i++){
						var pacActual = data.listaPacientes[i];
                        var dni = '<span class="text-uppercase">'+pacActual.dniusr+'</span>'
                        var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
                        listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Seleccionar Paciente"><button type="button" onclick="verFichaPaciente(\''+i+'\')" class="btn btn-icon btn-flat btn-default"><i class="icon wb-check-circle" aria-hidden="true"></i></button></span>';
                        '</div>';
                        
                        var numtel = pacActual.numtel1 + ' - ' + pacActual.numtel2;
            			tPac.row.add( [
                            pacActual.nompac ,
                            pacActual.ap1pac,
                            pacActual.ap2pac,
							dni,
                            listaBtn
                            
                        ] ).draw( false );
                    }    
                    $('[data-toggle="tooltip"]').tooltip();     

                     $('.loader-wrap').addClass("hide");
                }
            
          }
        });
}

function volverBusqueda(){
	limpiarBusqueda();
	$('#tablaPacientes').DataTable().clear().draw();
	$('.loader-wrap').addClass("hide");
	$('#panelBusq').removeClass('hide');
	$('#panelFiltro').removeClass('hide');
	$('.page-title').html('Búsqueda paciente');
	$('.pagActual').html('Búsqueda paciente');
	$('#datosPac').addClass('hide');
	
}

function obtenerDatosUsuarioSession (){
		$.ajax({
          url:   '/private/obtenerDatosUsuarioSession',
          type:  'GET',
          dataType: 'json',
          success:  function (data) {
              if(data && data.usuario){
            	usuarioSes = data.usuario;
              }
          }
        });
}

function initPantallaBusqueda(){
	//recorremos los permisos
	if(listaOpciones){
		for(var i = 0; i < listaOpciones.length; i++){
			if(listaOpciones[i].opcion == 'verHist'){
				verHist = 'S';
				$('#oHist').removeClass('hide');
				$('#tHist').removeClass('hide');
			}
			if(listaOpciones[i].opcion == 'verPruebasComp'){
				verPruebasComp = 'S';
				$('#oPruCom').removeClass('hide');
				$('#tPruCom').removeClass('hide');
			}
		}
	}
		console.info(listaOpciones);
}
initPantallaBusqueda();
obtenerDatosUsuarioSession();
