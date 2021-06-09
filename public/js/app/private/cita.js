$( document ).ready(function() {
    $('.changePhoto').click(function() {
        $('#files').click();
        $('#resize-image-wrap').load(document.URL +  ' #resize-image-wrap');
        $('#cropAvatar').addClass('hide');
    });
    $('#files').change(function() {
        $('.changePhoto').removeClass("hide");
    });
    cargarDetalleCita();
	paciente = [];
	paciente.id = citaActual.idpac;
});


var listAlergiasEspActuales = null;
var listaPruebas = null;
var whereInsertPlan = null;
var usuarioSes = null;
var listPlantilla = null;
var paciente = null;

function prepararDivAlergias(){
	$('#desAlergia').empty();
	if(listAlergiasActual && listAlergiasActual.length > 0){
		var strAlergia = '';
		var cont = 0;
		for(var i = 0; i < listAlergiasActual.length; i++){
			if(listAlergiasActual[i].codalergia){
				if(cont > 0){
					strAlergia = strAlergia + ', ';
				}
				strAlergia = strAlergia + listAlergiasActual[i].alergia;
				cont = cont + 1;
			}
			
		}
		if(strAlergia){
			$('#desAlergia').append(strAlergia);
			$('.divDefAlergia').removeClass('hide');
		}else
			$('.divDefAlergia').addClass('hide');
		
	}else{
		$('.divDefAlergia').addClass('hide');
	}
}

function fechaString(date){
    year = date.substring(0, 4);
    mes = date.substring(5, 7);
    dia = date.substring(8, 10);
    return dia + '/'+ mes + '/' + year;
}

function accionModificarEstadoCita(idCita, newEst){
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		data: {'id' : idCita, 'codestado' : newEst},
        url:   '/private/modificarEstadoCita',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.msgOk){
        		swal("Cita Modificada", data.msgOk, "success");
        	}
        }
});

}

function modificarEstadoCita(idCita, newEst){
	if(newEst == 'CAN'){
		swal({
	      	title: "Alerta",
			text: 'Se eliminará la cita.¿Desea Continuar?',
			type: "warning",
			showCancelButton: false,
			confirmButtonClass: 'btn-danger',
			confirmButtonText: 'Eliminar',
			cancelButtonText: 'Cancelar',
	        cancelButtonClass: 'btn-default',
	        closeOnCancel: true,
	        showCancelButton: true,
			closeOnConfirm: false
	    },
	    function(isConfirm) {
	    	if (isConfirm) {
	    		accionModificarEstadoCita(idCita, newEst);
	    	}
	    });
	}else if(newEst == 'ABR'){

		swal({
	      	title: "Alerta",
			text: 'Se inicirá la consulta.¿Desea Continuar?',
			type: "warning",
			showCancelButton: false,
			confirmButtonClass: 'btn-success',
			confirmButtonText: 'Si, iniciar consulta',
			cancelButtonText: 'Cancelar',
	        cancelButtonClass: 'btn-default',
	        closeOnCancel: true,
	        showCancelButton: true,
			closeOnConfirm: false
	    },
	    function(isConfirm) {
	    	if (isConfirm) {
	    		accionModificarEstadoCita(idCita, newEst);
	    	}
	    });
	
	}
}


function guardarModificarCita(accion){
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		data: {
			'idecita': idCita,
			'accion': accion,
			'lineaConsulta': $('#taLconsilt-wrap .note-editable').html(),
			'diagnostico': $('#taDiagnostico-wrap .note-editable').html(),
			'tratamiento': $('#taTratamiento-wrap .note-editable').html(),
			'id': idCita,
			'codestado' : accion=='GF'?'FIN':''
		},
        url:   '/private/guardarModificarCita',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
            if(data && data.msgOk){
	            	 
            	swal("Consulta Modificada", data.msgOk, "success");
            	
            }
            if(data && data.msgErr){
           	 
            	swal("Error", data.msgErr, "error");
            	
            }
        }
      });
}

function prepararVentanaAlergia(){
	$('#alergeno').val('');
	$("#sAlergiasEsp").select2('val', new Array());

	$('.loader-wrap').removeClass("hide");
	$.ajax({
		data: {	'idecpac': citaActual.idpac
			  },
        url:   '/private/prepararVentanaAlergia',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	var tAlerg = $('#tablaAlergias').DataTable();
        	tAlerg.clear().draw();
        	
        	if(data.listAlergiasEsp && !listAlergiasEspActuales){
        		listAlergiasEspActuales = data.listAlergiasEsp;
        		for(var i = 0; i < data.listAlergiasEsp.length; i++){
					$('#alergiasEsp').append(
						$('<option>', {
					    value: data.listAlergiasEsp[i].coddom,
					    text: data.listAlergiasEsp[i].desval
					}));
        		}
        	}
        	var selectedValues = new Array();
        	
        	if(listAlergiasActual && listAlergiasActual.length > 0){
        		for(var i = 0; i < listAlergiasActual.length; i++){
        			if(listAlergiasActual[i].codalergia)
        				selectedValues.push(listAlergiasActual[i].codalergia);
        		}
        	}
        	
        	$("#sAlergiasEsp").select2('val', selectedValues);
        	
        	if(data && data.listAlergias){
        		
        		for(var i = 0; i < data.listAlergias.length; i++){
        			if(!data.listAlergias[i].codalergia){
        				var algActual = data.listAlergias[i];
	           			var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
	                    listaBtn= listaBtn + '<span><button type="button" class="btn btn-icon btn-flat btn-default" data-toggle="tooltip" data-placement="top" data-trigger="hover" onclick="eliminarAlergia(\''+algActual.id+'\')" data-original-title="Eliminar Alergia"><i class="icon wb-close text-danger" aria-hidden="true"></i></button></span>';
	                    listaBtn = listaBtn + '</div>';
	           			tAlerg.row.add( [
	                                      algActual.alergia ,
	                                      fechaString(algActual.feccrea),
	                                      listaBtn
	                                  ] ).draw( false );
        			}
        			
        		}
        		$('[data-toggle="tooltip"]').tooltip();     

        	}
        }
      });
}

function guardarAlergia( tipo ){
	
	if(tipo){
		
		var alergiasSelect = [];
	    $('#sAlergiasEsp :selected').each(function(i, selected){ 
			alergiasSelect.push(selected.value);
		});
		
		$.ajax({
			data: {	'idecpac': citaActual.idpac,
					'alergiasSelect': alergiasSelect
				  },
	        url:   '/private/saveconfigAlergia',
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	            if(data && data.msgErr){
	            	swal("Error", data.msgErr, "error");
	            }
	            if(data && data.msgOk){
	            	$('#alergeno').val('');
	            	swal("Alergia Guardada", data.msgOk, "success");
	            	listAlergiasActual = data.listAlergias;
	            	prepararDivAlergias();
	            	prepararVentanaAlergia();
	            }
	        }
	      });
	}else{
		if($('#alergeno').val()){
			$('.loader-wrap').removeClass("hide");
			$.ajax({
				data: {	'idecpac': citaActual.idpac,
						'alergia': $('#alergeno').val()
					  },
		        url:   '/private/guardarAlergia',
		        type:  'GET',
		        dataType: 'json',
		        success:  function (data) {
		        	$('.loader-wrap').addClass("hide");
		            if(data && data.msgErr){
		            	swal("Error", data.msgErr, "error");
		            }
		            if(data && data.msgOk){
		            	$('#alergeno').val('');
		            	swal("Alergia Guardada", data.msgOk, "success");
		            	listAlergiasActual = data.listAlergias;
		            	prepararDivAlergias();
		            	prepararVentanaAlergia();
		            }
		        }
		      });
		}else{
			swal("Error", 'El campo alegia es obligatorio.', "error");
		}
	}
}

function limpiarFormAlergias(){
	$('#alergeno').val('');
}

function eliminarAlergia(idAlergia){
	swal({
      	title: "Alerta",
		text: 'Se eliminará la alergia.¿Desea Continuar?',
		type: "warning",
		showCancelButton: false,
		confirmButtonClass: 'btn-danger',
		confirmButtonText: 'Eliminar',
		cancelButtonText: 'Cancelar',
        cancelButtonClass: 'btn-default',
        closeOnCancel: true,
        showCancelButton: true,
		closeOnConfirm: false
    },
    function(isConfirm) {
    	if (isConfirm) {
    		$('.loader-wrap').removeClass("hide");
    		$.ajax({
    			data: {	'id': idAlergia
    				  },
    	        url:   '/private/eliminarAlergia',
    	        type:  'GET',
    	        dataType: 'json',
    	        success:  function (data) {
    	        	$('.loader-wrap').addClass("hide");
    	            if(data && data.msgErr){
    	            	swal("Error", data.msgErr, "error");
    	            }
    	            if(data && data.msgOk){
    	            	$('#alergeno').val('');
    	            	swal("Alergia Eliminada", data.msgOk, "success");
    	            	listAlergiasActual = data.listAlergias;
    	            	prepararDivAlergias();
    	            	prepararVentanaAlergia();
    	            }
    	        }
    	      });
    	}
    });
	

}



/******     Antecedentes      *********/

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


function guardarAntecedente(divMostrar, codAnt){
	
	if(!$('#'+divMostrar+'Des').val()){
		swal("Datos Incompletos", 'La descripción es Obligatoria', "error");
		return false;
	}
	
	$('.loader-wrap').removeClass("hide");
	
	$.ajax({
		data: {
			'codant' : codAnt,
			'idpac' : citaActual.idpac,
			'idcita' : citaActual.id,
			'desant' : $('#'+divMostrar+'Des').val(),
			'obsant' : $('#'+divMostrar+'Obs').val()
		},
        url:   '/private/guardarAntecedente',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
      	  $('.loader-wrap').addClass("hide");
            if(data && data.msgOk){
            	swal("", data.msgOk, "success");
        		prepararVentanaAntecedentes(false);
            	ocultarDivNuevo(divMostrar);
            }
        }
      });
}

function eliminarAnt(idAnt){
		swal({
      	title: "Alerta",
		text: 'Se eliminará el antecedente.¿Desea Continuar?',
		type: "warning",
		showCancelButton: false,
		confirmButtonClass: 'btn-danger',
		confirmButtonText: 'Eliminar',
		cancelButtonText: 'Cancelar',
        cancelButtonClass: 'btn-default',
        closeOnCancel: true,
        showCancelButton: true,
		closeOnConfirm: false
    },
    function(isConfirm) {
    	if (isConfirm) {
    		$('.loader-wrap').removeClass("hide");
    		
    		$.ajax({
    			data: {
    				'id' : idAnt
    			},
    	        url:   '/private/eliminarAntecedente',
    	        type:  'GET',
    	        dataType: 'json',
    	        success:  function (data) {
    	      	  $('.loader-wrap').addClass("hide");
    	            if(data){
    	            	if(data.msgOk){
    	            		prepararVentanaAntecedentes(false);
    	            		swal("", data.msgOk, "success");
    	            		
    	            	}
    	             
    	            }
    	        }
    	      });
    	}
    });
	
}

function pintarListasAntecedentes(tabla, lista){

	var tAnt = tabla.DataTable();
	tAnt.clear().draw();
	
	
	for(var i = 0; i < lista.length; i++){

		var antActual = lista[i];
			var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
        listaBtn= listaBtn + '<span><button type="button" class="btn btn-icon btn-flat btn-default" data-toggle="tooltip" data-placement="top" data-trigger="hover" onclick="eliminarAnt(\''+antActual.id+'\')" data-original-title="Eliminar Alergia"><i class="icon wb-close text-danger" aria-hidden="true"></i></button></span>';
        listaBtn = listaBtn + '</div>';
        var desAntecedente =  antActual.desant;
        if(antActual.obsant)
        	desAntecedente = desAntecedente + ' <span><i class="icon wb-info-circle text-success" aria-hidden="true"></i></span>';
        tAnt.row.add( [
                          desAntecedente,
                          fechaString(antActual.created_at),
                          listaBtn
                      ] ).draw( false );
	
	}

}

function prepararVentanaAntecedentes(init){
	$('.loader-wrap').removeClass("hide");
	if(init){
		$('#oAntecedentes').addClass('active');
		$('#oHabitos').removeClass('active');
		$('#oMorfologia').removeClass('active');
		
		$('#opAntecedentes').addClass('active');
		$('#opHabitos').removeClass('active');
		$('#opMorfologia').removeClass('active');
	}
	
	$.ajax({
		data: {
			'idpac' : citaActual.idpac
		},
        url:   '/private/prepararVentanaAntecedentes',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
      	  $('.loader-wrap').addClass("hide");
            if(data){
            	if(data.listAnt){
            		pintarListasAntecedentes($('#tablaAntecedente'), data.listAnt)
            	}
            	if(data.listHab){
            		pintarListasAntecedentes($('#tablaHab'), data.listHab)
            	}
            	if(data.listMor){
            		pintarListasAntecedentes($('#tablaMor'), data.listMor)
            	}
            	
            }
        }
      });
}



/******     FIn Antecedentes      *********/

/******     Consultas      *********/

function prepararVentanaHistConsultas(){
	$('.loader-wrap').removeClass("hide");
	$('#contenedorHist').empty();
	$('#contenedorConPrev').empty();
	$.ajax({
		data: {
			'idpac' : citaActual.idpac,
			'idcita' : citaActual.id,
			'idusr':  citaActual.idusr
		},
        url:   '/private/prepararVentanaHistConsultas',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
      	  $('.loader-wrap').addClass("hide");
      	  if(data && data.listaCodesp){
      		  for(var i = 0; i < data.listaCodesp.length; i ++){
      			var espeAct = data.listaCodesp[i];
      	        var listaBtn= '<button type="button" class="btn btn-primary" data-toggle="tooltip"'+
      	        'data-placement="top" data-trigger="hover" onclick="verHistoricoCitas(\''+espeAct.codesp+'\')" data-original-title="' + espeAct.especialidad + '">'+
      	        espeAct.especialidad + '</button>';
      	      
      	        $('#contenedorConPrev').append(listaBtn);
      	        
      		  }
      	  }
      }
      });
}

function verHistoricoCitas(codEsp){
	$('.loader-wrap').removeClass("hide");
	$('#contenedorHist').empty();
	$.ajax({
		data: {
			'idpac' : citaActual.idpac,
			'idcita' : citaActual.id,
			'idusr':  citaActual.idusr,
			'codesp': codEsp
		},
        url:   '/private/verHistoricoCitas',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
      	  $('.loader-wrap').addClass("hide");
      	 
          if(data && data.listaCitas){
        	  for(var i = 0; i < data.listaCitas.length; i++){
        		  
        		  
        		  
        		  $('#contenedorHist').append(
        				 '<div class="panel panel-bordered">'
        				  
        				  +'<div class="panel-heading  bg-blue-200">'
        				  	+'<h3 class="panel-title">'+fechaString(data.listaCitas[i].fecinicita)+ ' - '+ data.listaCitas[i].especialidad +'</h3>'
        				  		+'<div class="panel-actions">'
        				  			+'<a class="panel-action icon wb-minus" data-toggle="collapse" data-parent="#accordion" href="#collapse'+i+'"></a>'
        				  		+'</div>'
              			  +'</div>'
              			  
              			  
              			  
        				+'<div id="collapse'+i+'" class="collapse bg-blue-100 padding-20">'
        					+'<div>'
        						+'<strong>Fecha de la cita:</strong>'
        						+' <span class="time">'+fechaString(data.listaCitas[i].fecinicita)+'</span>'
        					+'</div>'
        					+'<div>'
        						+'<strong>Médico: </strong>'
        						+'<span>'+data.listaCitas[i].nomusr + ' ' + data.listaCitas[i].apusr +'</span>'
        					+'</div>'
        					
                  		
                  		+'<div class="mail-header-main">'
                  		+' <div>'
                  		+'<strong>Lineas de Consulta</strong>'
                  		+'</div>'
                  		+'</div>'
                  		
                  		
                  		+'<div>'
                  		+ data.listaCitas[i].lineaConsulta
                  		+'</div>'
                  		+'<div class="clearfix"></div>'
                  		
                  		+'<div class="mail-header-main">'
                  		+' <div>'
                  		+'<strong>Diagnostico</strong>'
                  		+'</div>'
                  		+'</div>'
                  		
                  		+'<div>'
                  		+ data.listaCitas[i].diagnostico
                  		+'</div>'
                  		+'<div class="clearfix"></div>'
                  		
                  		+'<div class="mail-header-main">'
                  		+' <div>'
                  		+'<strong>Tratamiento</strong>'
                  		+'</div>'
                  		+'</div>'
                  		
                  		+'<div>'
                  		+ data.listaCitas[i].tratamiento
                  		+'</div>'
                  		+'</div>'
                  		+'</div>'
                  		+'</div>');
        	  }         
             
          }
      }
      });
}


/**** Observaciones ****/
function limpiarFormObs(){
	 $('#divContObsObs').val('');
}

function eliminarObservacion(idObs){

	swal({
      	title: "Alerta",
		text: 'Se eliminará la Observación.¿Desea Continuar?',
		type: "warning",
		showCancelButton: false,
		confirmButtonClass: 'btn-danger',
		confirmButtonText: 'Eliminar',
		cancelButtonText: 'Cancelar',
        cancelButtonClass: 'btn-default',
        closeOnCancel: true,
        showCancelButton: true,
		closeOnConfirm: false
    },
    function(isConfirm) {
    	if (isConfirm) {
    		$('.loader-wrap').removeClass("hide");
    		$.ajax({
    			data: {	'id': idObs
    				  },
    	        url:   '/private/eliminarObservacion',
    	        type:  'GET',
    	        dataType: 'json',
    	        success:  function (data) {
    	        	$('.loader-wrap').addClass("hide");
    	            if(data && data.msgErr){
    	            	swal("Error", data.msgErr, "error");
    	            }
    	            if(data && data.msgOk){
    	            	swal("Observación Eliminada", data.msgOk, "success");
    	            	prepararVentanaObs();
    	            }
    	        }
    	      });
    	}
    });
	


}

function prepararVentanaObs(){
	$('.loader-wrap').removeClass("hide");
	$.ajax({
        url:   '/private/prepararVentanaObs',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
      	  $('.loader-wrap').addClass("hide");
	      	var tObs = $('#tablaObs').DataTable();
	      	tObs.clear().draw();
            if(data ){
            	
            	for(var i = 0; i < data.listaObs.length; i++){
            		
    				var obsAct = data.listaObs[i];
           			var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
                    listaBtn= listaBtn + '<span><button type="button" class="btn btn-icon btn-flat btn-default" data-toggle="tooltip" data-placement="top" data-trigger="hover" onclick="eliminarObservacion(\''+obsAct.id+'\')" data-original-title="Eliminar Observación"><i class="icon wb-close text-danger" aria-hidden="true"></i></button></span>';
                    listaBtn = listaBtn + '</div>';
                    tObs.row.add( [
           			               	  fechaString(obsAct.created_at),
           			               	  obsAct.observacion,
                                      listaBtn
                                  ] ).draw( false );
    			
        			
        		}
        		$('[data-toggle="tooltip"]').tooltip();	        
            }
            
            
        }
      });
}

function guardarObs(){
	if(!$('#divContObsObs').val()){
		swal("Error", 'Observación Obligatoria', "error");
		return false;
	}
	$('.loader-wrap').removeClass("hide");
	
	$.ajax({
		data: {
			'observacion': $('#divContObsObs').val().replace(/["']/g, "&#34;"),
			'idcita': idCita
		},
        url:   '/private/guardarObs',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
	      	if(data && data.msgOk){
	      		ocultarDivNuevo('divContObs');
	      		limpiarFormObs();
        		prepararVentanaObs();
	    		swal("Observaciones", data.msgOk, "success");
	    	}
        }
      });
	
}


/**** FIN Observaciones ****/

/***** Insertar Plantilla *****/

function seleccionarPlantilla(orden){
	 
	if(whereInsertPlan == 'A')
		$('#taLconsilt-wrap .note-editable').html($('#taLconsilt-wrap .note-editable').html() + " " + listPlantilla[orden].txtplantilla);
	if(whereInsertPlan == 'D')
		$('#taDiagnostico-wrap .note-editable').html($('#taDiagnostico-wrap .note-editable').html() + " " + listPlantilla[orden].txtplantilla);
	if(whereInsertPlan == 'T')
		$('#taTratamiento-wrap .note-editable').html($('#taTratamiento-wrap .note-editable').html() + " " + listPlantilla[orden].txtplantilla);
	$("#gestionMisPlantillas").modal("toggle");
}

function insertarPlatilla(tipo){
	whereInsertPlan = tipo;
	$("#gestionMisPlantillas").modal("toggle");
	
	var tPlantilla = $('#tablaPlantilla').DataTable();
	tPlantilla.clear().draw();
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		  data: {'idmed' : usuarioSes.id},
        url:   '/private/listadoPlantillasUsuario',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass('hide');
			listPlantilla = data.listadoPlatillas;
			if(listPlantilla){
				for(var i = 0; i < listPlantilla.length; i++){
					var plantillaAct =  listPlantilla[i];
					
					var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
					listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Seleccionar Plantilla"><button type="button" onclick="seleccionarPlantilla(\''+i+'\')" class="btn btn-success btn-icon waves-effect waves-light" ><i class="icon wb-plus-circle " aria-hidden="true"></i></button></span>';
					listaBtn = listaBtn + '</div>';
					
					tPlantilla.row.add( [
						plantillaAct.tituloplantilla ,
						plantillaAct.txtplantilla,
						listaBtn
						
					] ).draw( false );
				} 
				$('[data-toggle="tooltip"]').tooltip();
			}
        }
      });
}


/***** Fin Insertar Plantilla *****/

function obtenerDatosUsuarioSession (){
	$('.loader-wrap').removeClass("hide");
		$.ajax({
          url:   '/private/obtenerDatosUsuarioSession',
          type:  'GET',
          dataType: 'json',
          success:  function (data) {
        	  $('.loader-wrap').addClass("hide");
              if(data && data.usuario){
            	  usuarioSes = data.usuario;
            	  
            	  $('#contenerdor').removeClass('hide');
               
            	  return data.usuario.nomusr;
              }
          }
        });
}

function cargarDetalleCita(){
	if(detalleCitActual){
		$('#taLconsilt-wrap .note-editable').html(detalleCitActual.lineaConsulta);
		$('#taDiagnostico-wrap .note-editable').html(detalleCitActual.diagnostico);
		$('#taTratamiento-wrap .note-editable').html(detalleCitActual.tratamiento);
	}
}




prepararDivAlergias();
obtenerDatosUsuarioSession();