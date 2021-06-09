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

function limpiarBusqueda(){
	$('#nompac').val('');
	$('#ap1pac').val('');
	$('#ap2pac').val('');
	document.getElementById('tipdocBusq').value  = 'DNI';
	mostrarNumDoc('Busq');
	$( "#dniusrBusq" ).val('');
	$( "#nieusrBusq" ).val('');
	$( "#passusrBusq" ).val('');
	$('#idHistorialBusq').val('');
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
	 initPantalla();
	if(verHist == 'S'){
		$.ajax({
			
			  data: {'idpac' : pacientesActuales[orden].id},
	          url:   './obtenerEspecialidadesPac',
	          type:  'GET',
	          dataType: 'json',
	          success:  function (data) {
				  $('.loader-wrap').addClass("hide");
	              if(data && data.listaCodesp){
	            	listaCodesp = data.listaCodesp;
	            	comboListaEspecialidades();
	              }
	              //historialFunComun.js
	             
	              if(verPruebasComp == 'S')
	            	  prepararVentanaPrueCompl();
	          }
	        });
		
	}else if(verPruebasComp == 'S'){
		 prepararVentanaPrueCompl();
	}
}

function mostrarNumDoc(extension){
	if($( "#tipdoc" + extension).val()== 'DNI'){
		$( "#dniusr" + extension ).removeClass('hide');
		$( "#nieusr" + extension ).addClass('hide');
		$( "#passusr" + extension ).addClass('hide');
	}
	if($( "#tipdoc" + extension ).val()== 'NIE'){
		$( "#dniusr" + extension ).addClass('hide');
		$( "#nieusr" + extension ).removeClass('hide');
		$( "#passusr" + extension ).addClass('hide');
	}
	if($( "#tipdoc" + extension ).val()== 'PAS'){
		$( "#dniusr" + extension ).addClass('hide');
		$( "#nieusr" + extension ).addClass('hide');
		$( "#passusr" + extension ).removeClass('hide');
	}
}

$( "#tipdocBusq" ).change(function(v) {
	$( "#dniusrBusq" ).val('');
	$( "#nieusrBusq" ).val('');
	$( "#passusrBusq" ).val('');
	mostrarNumDoc('Busq');
});

function buscarPacienteNomAp(){
	var numdoc = $( "#tipdocBusq" ).val()== 'DNI'? $("#dniusrBusq").val(): $( "#tipdocBusq" ).val()== 'NIE'? $("#nieusrBusq").val():$( "#tipdocBusq" ).val()== 'PAS'? $("#passusrBusq").val():'';
	numdoc = numdoc.replace(/\s/g, "") ;
	if(!$('#nompac').val() && !$('#ap1pac').val() && !$('#ap2pac').val() && (!numdoc || numdoc == '-') && !$('#idHistorialBusq').val()){
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
				'dniusr' : numdoc,
				'idHistorial' :$('#idHistorialBusq').val()
			},
			url:   './buscarPacienteNomAp',
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
          url:   './obtenerDatosUsuarioSession',
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
