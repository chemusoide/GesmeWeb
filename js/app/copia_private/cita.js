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
	strTit = pacienteSelect.nompac + ' ' + pacienteSelect.ap1pac + ' ' + pacienteSelect.ap2pac + ' - ' + pacienteSelect.dniusr;
	$('.datosTitPac').html(strTit.toUpperCase());
	
	var strSeg = citaActual.nomseguro + ' - ' + pacienteSelect.numseg;
	$('.datosSegPac').html(strSeg.toUpperCase());
	if(pacienteSelect.numseg)
		$('#cabSeg').removeClass('hide');
	
	if(citaActual.codestado == "FIN")
		prepararCitaFinalizada();
	
	//// REPORT BTN
	if(citaActual.idold || citaActual.idold2){
			
	}
	
});


var listAlergiasEspActuales = null;
var listaPruebas = null;
var whereInsertPlan = null;
var usuarioSes = null;
var listPlantilla = null;
var paciente = null;
var swiVerDiag = false;

var paginaActual = null;

function prepararCitaFinalizada(){
	$('.btnSave').addClass('hide');
	$('.btnFin').addClass('hide');
	$('.btnFImp').addClass('hide');
	$('.btnImp').removeClass('hide');
	$('#lblFinal').removeClass('hide');
}

function prepararDivAlergias(){
	$('#desAlergia').empty();
	if(listAlergiasActual && listAlergiasActual.length > 0){
		var strAlergia = '';
		var cont = 0;
		var swiTieneOtras = false;
		for(var i = 0; i < listAlergiasActual.length; i++){
			if(listAlergiasActual[i]){
				if(cont > 0){
					strAlergia = strAlergia + ', ';
				}
				strAlergia = strAlergia + listAlergiasActual[i].alergia;
				cont = cont + 1;
				if(i == 9)
					var swiTieneOtras = true;
			}
		}
		if(swiTieneOtras)
			strAlergia = strAlergia + (strAlergia.length>0?', Tiene otras alérgias': 'Tiene más alérgias');
		
		if(strAlergia){
			$('#desAlergia').append(strAlergia);
			$('.divDefAlergia').removeClass('hide');
			$('.divSinAlergia').addClass('hide');
		}else{
			$('.divDefAlergia').addClass('hide');
			$('.divSinAlergia').removeClass('hide');
		}		
	}else{
		$('.divDefAlergia').addClass('hide');
		$('.divSinAlergia').removeClass('hide');
	}
}


function accionModificarEstadoCita(idCita, newEst){
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		data: {'id' : idCita, 'codestado' : newEst},
        url:   generarUrl('/private/modificarEstadoCita'),
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

function permitirImprimiaCita(accion){
	//Se podrá imprimir siempre.
	/*if(accion=='GFIMP'){
		if( !$('#taDiagnostico-wrap .note-editable').html() || !$('#taTratamiento-wrap .note-editable').html()){
			swal("Error", 'No se puede imprimir ya que no tiene diagnostico y/o tratamiento', "error");
			return false;
		}
	}*/
	return true;
}


function guardarModificarCita(accion){
	
	
	if(permitirImprimiaCita(accion)){
		var tipoAux = 'POST';
			
		
		$('.loader-wrap').removeClass("hide");
		$.ajax({
			data: {
				'idecita': idCita,
				'accion': accion,
				'lineaConsulta': $('#taLconsilt-wrap .note-editable').html(),
				'diagnostico': $('#taDiagnostico-wrap .note-editable').html(),
				'tratamiento': $('#taTratamiento-wrap .note-editable').html(),
				'id': idCita,
				'codestado' : accion=='GF' || accion=='GFIMP'?'FIN':''
			},
	        url:   generarUrl('/private/guardarModificarCita'),
	        type:  tipoAux,
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	
	            if(data && data.msgOk){
	            	swal("Consulta Modificada", data.msgOk, "success");
	            	if(accion!='G')
	            		prepararCitaFinalizada();
	            	if(accion=='GFIMP')
	            		imprimirCita();
	            }
	            if(data && data.msgErr){
	           	 
	            	swal("Error", data.msgErr, "error");
	            	
	            }
	        }
	      });
	}
}

function prepararGuardado(accion){
	if(accion!='G'){
		swal({
	      	title: "Alerta",
			text: 'Si finaliza la cita no se podrán realizar mas cambios,¿desea finalizarla?',
			type: "warning",
			showCancelButton: false,
			confirmButtonClass: 'btn-danger',
			confirmButtonText: 'Finalizar',
			cancelButtonText: 'Cancelar',
	        cancelButtonClass: 'btn-default',
	        closeOnCancel: true,
	        showCancelButton: true,
			closeOnConfirm: false
	    },
	    function(isConfirm) {
	    	if (isConfirm) {
	    		guardarModificarCita(accion);
	    	}
	    });
	}else
		guardarModificarCita(accion);
}

function prepararVentanaAlergia(){
	$('#alergeno').val('');
	//$("#sAlergiasEsp").select2('val', new Array());

	$('.loader-wrap').removeClass("hide");
	$.ajax({
		data: {	'idecpac': citaActual.idpac
			  },
        url:   generarUrl('/private/prepararVentanaAlergia'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	var tAlerg = $('#tablaAlergias').DataTable();
        	tAlerg.clear().draw();
        	
        	/*if(data.listAlergiasEsp && !listAlergiasEspActuales){
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
        	}*/
        	
        	//$("#sAlergiasEsp").select2('val', selectedValues);
        	
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
	        url:   generarUrl('/private/saveconfigAlergia'),
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
		        url:   generarUrl('/private/guardarAlergia'),
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

function collapseAlClick(){
	$('#acollapseAl').click();
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
    	        url:   generarUrl('/private/eliminarAlergia'),
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
        url:   generarUrl('/private/guardarAntecedente'),
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
    	        url:   generarUrl('/private/eliminarAntecedente'),
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
        url:   generarUrl('/private/prepararVentanaAntecedentes'),
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

function prepararVentanaHistConsultas(verDiag){
	swiVerDiag = false;
	if(verDiag && verDiag == 'S')
		swiVerDiag=true;
	$('.loader-wrap').removeClass("hide");
	$('#contenedorHist').empty();
	$('#contenedorHistPag').empty();
	$('#contenedorConPrev').empty();
	$.ajax({
		data: {
			'idpac' : citaActual.idpac,
			'idcita' : citaActual.id,
			'idusr':  citaActual.idusr
		},
        url:   generarUrl('/private/prepararVentanaHistConsultas'),
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

function reportarCitaOldById(id){
	console.info(id);
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		data: {
			'idcita' : id
		},
        url:   generarUrl('/private/reportarCitaOldById'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
			$('.loader-wrap').addClass("hide");
			$('#btnRep'+id).addClass('hide');
			swal("", 'Incidencia Reportada', "success");
          
      }
      });
}

function generarListaCitas(data, c, oc){

	  for(var i = 0; i < data.listaCitas.length; i++){
		  //swiVerDiag
		  var OpCl = oc=='C'?'collapse':'';
		  
		  var strDivCit = '<div class="panel panel-bordered">'
			  
		  +'<div class="panel-heading  bg-blue-200 cHand" data-toggle="'+OpCl+'" data-parent="#accordion" href="#collapse'+i+'">'
		  	+'<h3 class="panel-title">'+fechaString(data.listaCitas[i].fecinicita)+ ' - '+ data.listaCitas[i].especialidad +'</h3>'
		  		+'<div class="panel-actions">'
		  			+'<a class="panel-action icon wb-minus" data-toggle="'+OpCl+'" data-parent="#accordion" href="#collapse'+i+'"></a>'
		  		+'</div>'
		  +'</div>';
		  strDivCit = strDivCit +'<div id="collapse'+i+'" class="'+OpCl+' bg-blue-100 padding-20">';
		  
		  //// REPORT BTN
			if(data.listaCitas[i].idold || data.listaCitas[i].idold2){
				if( data.listaCitas[i].swireportada =='S'){
					strDivCit = strDivCit +'<div id="btnRep'+ data.listaCitas[i].id +'">'
						+'<strong>Cita importada del programa antiguo REPORTADA</strong>'
						+'</div>';
				}else{
					strDivCit = strDivCit +'<div id="btnRep'+ data.listaCitas[i].id +'">'
						+'<strong>Cita importada del programa antiguo si hay algun error reportelo:</strong>'
							+'<button type="button" class="btn btn-danger" onclick="reportarCitaOldById('+ data.listaCitas[i].id +')">Añadir/eliminar</button>'
						+'</div>';
				}
					
			}
		  
		  
			strDivCit = strDivCit +'<div>'
			+'<strong>Fecha de la cita:</strong>'
				+' <span class="time">'+fechaString(data.listaCitas[i].fecinicita)+'</span>'
			+'</div>'
			+'<div>'
				+'<strong>Médico: </strong>'
				+'<span>'+data.listaCitas[i].nomusr + ' ' + data.listaCitas[i].apusr +'</span>'
			+'</div>';
		
		
		if(!swiVerDiag){
			strDivCit = strDivCit +'<div class="mail-header-main">'
        		+' <div style="font-size: 20px;">'
        		+'<b><u>Lineas de Consulta</u></b>'
        		+'</div>'
        		+'</div>';
			if(data.listaCitas[i].idusr != usuarioSes.id){
    			strDivCit = strDivCit +'<div style="color: red;">No tiene permiso para ver esta información, contacte con el médico que escribió la línea o con el director médico del centro</div>';
    		}else{
    			strDivCit = strDivCit +'<div>' + data.listaCitas[i].lineaConsulta +'</div>';
    		}
		}
		
		
		
		strDivCit = strDivCit + '<div class="clearfix"></div>'
		
		+'<div class="mail-header-main">'
		+' <div style="font-size: 20px;">'
		+'<strong><u>Diagnóstico</u></strong>'
		+'</div>'
		+'</div>'
		
		+'<div>'
		+ data.listaCitas[i].diagnostico
		+'</div>'
		+'<div class="clearfix"></div>'
		if(!swiVerDiag){
			strDivCit = strDivCit +'<div class="mail-header-main">'
    		+' <div style="font-size: 20px;">'
    		+'<strong><u>Tratamiento</u></strong>'
    		+'</div>'
    		+'</div>'
    		
    		+'<div>'
    		+ data.listaCitas[i].tratamiento
    		+'</div>';
		}
		
		strDivCit = strDivCit +'</div>'
		+'</div>'
		+'</div>';
		  
		  c.append(strDivCit);
	  }         
   

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
        url:   generarUrl('/private/verHistoricoCitas'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
      	  $('.loader-wrap').addClass("hide");
      	 
          if(data && data.listaCitas)
        	  generarListaCitas(data, $('#contenedorHist'),'C');
          
      }
      });
}

function verHistoricoCitasPaginado(codEsp, swiBtn){
	if(swiBtn)
		paginaActual = 1;
	$('.loader-wrap').removeClass("hide");
	$('#contenedorHistPag').empty();
	$('#contenedorHist').empty();
	$('.fHistPag').empty();
	swiVerDiag=true;
	$.ajax({
		data: {
			'idpac' : citaActual.idpac,
			'idcita' : citaActual.id,
			'idusr':  citaActual.idusr,
			'codesp': codEsp,
			'numPag': paginaActual
		},
        url:   generarUrl('/private/verHistoricoCitasPaginado'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
      	  $('.loader-wrap').addClass("hide");
      	 
          if(data && data.listaCitas){
        	  generarListaCitas(data, $('#contenedorHistPag'), 'O');
        	  paginador($('.fHistPag'),'historicoCitasPagina' , paginaActual, data.total, data.paginador)
          }
        	  
          
          
      }
      });
}

function historicoCitasPagina(pag){
	paginaActual = pag;
	verHistoricoCitasPaginado(citaActual.codesp, false);
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
    	        url:   generarUrl('/private/eliminarObservacion'),
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
        url:   generarUrl('/private/prepararVentanaObs'),
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
        url:   generarUrl('/private/guardarObs'),
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
        url:   generarUrl('/private/listadoPlantillasUsuario'),
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

function imprimirCita(){
	if(permitirImprimiaCita('GFIMP')){
		$('#impIdePac').val(citaActual.idpac);
		$('#impIdMed').val(citaActual.idusr);
		$('#impTipConsulta').val(citaActual.codesp);
		$('#impIdConsulta').val(citaActual.id);
		
		$( "#fImpHist" ).submit();
	}
}

function obtenerDatosUsuarioSession (){
	$('.loader-wrap').removeClass("hide");
		$.ajax({
          url:   generarUrl('/private/obtenerDatosUsuarioSession'),
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
	if(citaActual){
		console.info(citaActual);
		$('.hDiagnostico').append('<button type="button" data-toggle="modal" data-target="#gestionHistConsultasPag" href="" aria-expanded="false" aria-controls="gestionHistConsultas"'+
	     	'class="btn btn-info" onclick="verHistoricoCitasPaginado(\''+citaActual.codesp+'\', true)">Ver diagnósticos de '+ citaActual.especialidad +'</button>');
	}
}




prepararDivAlergias();
obtenerDatosUsuarioSession();