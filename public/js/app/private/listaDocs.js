$(document).ready(function(){
      
      setTimeout(
    		  function() 
    		  {
    			  $('[name="liMenuDocs"]').addClass('active');
    		  }, 500);
});

var listaDocsActivos = null;
var pacientesActuales = null;
var medicosActuales = null;
var listDocsConfigActu = null;
var docImprimir = null;
var listaEspObtenidas = null;
var swiGrup = null;

var pagWizzard = 0;
var tieneDatsMed = 'N';
var tieneDatsPac = 'N';

function generarCombosHoras(){
	var horMaxAgenda = 24;
	var intervaloMins = 1;
	$('.sHora').empty();
	$('.sMinutos').empty();
	
	$('.sHora').append(
			$('<option>', {
		    value: '',
		    text: 'Hora'
		}));
	$('.sMinutos').append(
			$('<option>', {
		    value: '',
		    text: 'Minutos'
		}));
	for(var i = 1; i <= horMaxAgenda; i++){
		$('.sHora').append(
				$('<option>', {
			    value: i<10? '0'+i:i,
			    text: i<10? '0'+i:i
			}));
	}
	
	for(var i = 0; i < 60; i+=intervaloMins){
		$('.sMinutos').append(
				$('<option>', {
			    value: i<10? '0'+i:i,
			    text: i<10? '0'+i:i
			}));
	}
}

function volverPagina(){
	pagWizzard = pagWizzard - 1;
	paginadorWizzard('A')
}

function paginadorWizzard(accion){

	if(accion == 'S'){
		if(pagWizzard == 0 && tieneDatsPac == 'N'){
			pagWizzard = 1;
			paginadorWizzard('S');
		}
		if(pagWizzard == 1 && tieneDatsMed == 'N'){
			pagWizzard = 2;
			paginadorWizzard('S');
		}
	}
	
	if(accion == 'A'){
		if(pagWizzard == 1 && tieneDatsMed == 'N'){
			pagWizzard = 0;
			paginadorWizzard('A');
		}
	}
	
	if(pagWizzard == 0){
		cancelarSelectPac();
		$("#fImpDocDatos").addClass('hide');
		$("#formBusqDoc").addClass('hide');
		$("#btnVol").addClass('hide');
		$("#btnImp").addClass('hide');
	}
	
	if(pagWizzard == 1){
		cancelarSelectMed();
		if(tieneDatsPac == 'N')
			$("#btnVol").addClass('hide');
		else
			$("#btnVol").removeClass('hide');
		
		$("#fImpDocDatos").addClass('hide');
		$("#btnImp").addClass('hide');
	}
	
	if(pagWizzard == 2){
		$("#btnImp").removeClass('hide');
		$("#fImpDocDatos").removeClass('hide');
		if(tieneDatsPac == 'N' && tieneDatsMed == 'N')
			$("#btnVol").addClass('hide');
		else
			$("#btnVol").removeClass('hide');
	}
}

function imprimirDocGrupo(){
	var grupo = [];
	for(var i=0; i < listaDocsActivos.length; i++){
		var docActual = listaDocsActivos[i];
		if($('#docLis'+docActual.id).is(":checked"))
			grupo.push(docActual.id);
		
	}
	imprimirDoc(null, grupo);
}

function imprimirDoc(orden, grupo){
	$("#formBusqPac").addClass('hide');
	$("#formBusqDoc").addClass('hide');
	$("#datsDocsDiv").addClass('hide');
	$("#divComentImp").addClass('hide');
	$("#divFirmMedImp").addClass('hide');
	$("#divResHisCliImp").addClass('hide');
	$("#divHaObsImp").addClass('hide');
	$("#divPezRemImp").addClass('hide');
	$("#horaIniInterImp").addClass('hide');
	
	$("#divAutoImp").addClass('hide');
	$("#divProImp").addClass('hide');
	$("#divEspImp").addClass('hide');
	$("#divNhistImp").addClass('hide');
	
	$("#divIntImp").addClass('hide');
	$("#divBoxImp").addClass('hide');
	$("#divNotImp").addClass('hide');
	$("#divImpEmp").addClass('hide');
	$("#divImpPruebas").addClass('hide');
	$("#fImpDocDatos").addClass('hide');
	
	$("#btnVol").addClass('hide');
	$("#btnImp").addClass('hide');
	
	$("#sNombreCompleto").html('');
	$("#impIdePacDat").val('');
	$("#impIdeMedDat").val('');
	$("#nompac").val('');
	$("#ap1pac").val('');
	$("#ap2pac").val('');
	$("#impObsDat").val('');
	$("#impResHisCli").val('');
	$("#impHaObsCli").val('');
	$("#impPezRem").val('');
	$("#impAuto").val('');
	$("#impProc").val('');
	$("#impEspe").val('');
	$("#nomDoc").val('');
	$("#apDoc").val('');
	$("#numColDoc").val('');
	$("#impNHist").val('');
	$("#impInterven").val('');
	$("#impBox").val('');
	$("#impNot").val('');
	$("#swiGrupo").val('N');
	$("#numGrupo").val(grupo);
	$("#impEmp").val('');
	
	
	
	$('#cPrea').prop('checked', true);
	$('#cAna').prop('checked', true);
	$('#cEco').prop('checked', true);
	$('#cRxt').prop('checked', true);
	$('#cAudio').prop('checked', true);
	$('#cVisi').prop('checked', true);
	$('#cEspi').prop('checked', true);
	
	pagWizzard = 0;
	
	var urlEjecutar = "";
	
	if(orden || orden == 0){
		urlEjecutar = '/private/buscarConfiguracionesDoc';
		docImprimir = listaDocsActivos[orden];
		
		if(listaDocsActivos[orden].nombre == "CONSENTIMIENTO ANESTESIA")
			$('#impFiMed').prop('checked', true);
		else
			$('#impFiMed').prop('checked', false);	
	}else{
		$("#swiGrupo").val('S');
		urlEjecutar = '/private/buscarConfiguracionesDocGrupo';
	}
	
	$('#tablaPacientes').DataTable().clear().draw();
	$('#tablaMedicos').DataTable().clear().draw();
    
	listDocsConfigActu = null;
	tieneDatsMed = 'N';
	tieneDatsPac = 'N';
	
	$.ajax({
			data: {'iddoc': listaDocsActivos&&listaDocsActivos[orden]?listaDocsActivos[orden].id:null,
					'numGrupo': grupo},
			url:   urlEjecutar,
			type:  'GET',
			dataType: 'json',
			success:  function (data) {
				console.info( data.listadoDocsConfig);
				if(data && data.listadoDocsConfig && data.listadoDocsConfig.length > 0){
					listDocsConfigActu = data.listadoDocsConfig;
					for(var i = 0; i < data.listadoDocsConfig.length; i++){
						
						if(data.listadoDocsConfig[i].dato == 'DATOS_PACIENTE'){
							$("#formBusqPac").removeClass('hide');
							tieneDatsPac = 'S';
						}
							
						if(data.listadoDocsConfig[i].dato == 'COMENTARIO')
							$("#divComentImp").removeClass('hide');
						if(data.listadoDocsConfig[i].dato == 'RESUMEN_HISTORIA_CLINICA')
							$("#divResHisCliImp").removeClass('hide');
						if(data.listadoDocsConfig[i].dato == 'HALLAZGOS_OBSERVADOS')
							$("#divHaObsImp").removeClass('hide');
						if(data.listadoDocsConfig[i].dato == 'DATOS_MEDICO')
							tieneDatsMed = 'S';
						if(data.listadoDocsConfig[i].dato == 'PIEZA_REMITIDA')
							$("#divPezRemImp").removeClass('hide');
						if(data.listadoDocsConfig[i].dato == 'AUTORIZACION')
							$("#divAutoImp").removeClass('hide');
						if(data.listadoDocsConfig[i].dato == 'PROCEDIMIENTO')
							$("#divProImp").removeClass('hide');
						if(data.listadoDocsConfig[i].dato == 'ESPECIALIDAD')
							$("#divEspImp").removeClass('hide');
						if(data.listadoDocsConfig[i].dato == 'NHISTORIA')
							$("#divNhistImp").removeClass('hide');
						if(data.listadoDocsConfig[i].dato == 'INTERVENCION')
							$("#divIntImp").removeClass('hide');
						if(data.listadoDocsConfig[i].dato == 'NOTAS')
							$("#divNotImp").removeClass('hide');
						if(data.listadoDocsConfig[i].dato == 'BOX')
							$("#divBoxImp").removeClass('hide');
						if(data.listadoDocsConfig[i].dato == 'FIRMA_MEDICO')
							$("#divFirmMedImp").removeClass('hide');
						if(data.listadoDocsConfig[i].dato == 'EMPRESA')
							$("#divImpEmp").removeClass('hide');
						if(data.listadoDocsConfig[i].dato == 'FECHA_HORA_USR'){
							generarCombosHoras();
							$("#horaIniInterImp").removeClass('hide');
						}
						if(data.listadoDocsConfig[i].dato == 'PRUEBAS')
							$("#divImpPruebas").removeClass('hide');
						
							
					}
					if(tieneDatsPac == 'N' && tieneDatsMed == 'S'){
						pagWizzard = 1;
						paginadorWizzard('S');
					}else if(tieneDatsPac == 'N' && tieneDatsMed == 'N'){
						pagWizzard = 2;
						paginadorWizzard('S');
					}
					
					$("#datosDoc").modal("toggle");
				}else{
					$("#impIdeDoc").val(docImprimir.id);
					$( "#fImpDoc" ).submit();
				}
			}
        });
}

function buscarDocumentos(tipDoc, nGrupo){
	listaDocsActivos = null;
	swiGrup = nGrupo?'S':'N';
	$('#listaDocs').empty();
	$('#divImpGrup').addClass('hide');
	$.ajax({
		  data: {'tipoDoc' : tipDoc,
				  'numGrupo': nGrupo},
          url:   '/private/buscarDocumentos',
          type:  'GET',
          dataType: 'json',
          success:  function (data) {
              if(data && data.listadoDocs){
				listaDocsActivos = data.listadoDocs;
				for(var i = 0; i < data.listadoDocs.length; i++){
					var docActual = data.listadoDocs[i];
					console.info(data.listadoDocs[i])
					var strAdd = 
						'<div class="widget">';
							if(swiGrup != 'S')
								strAdd = strAdd +'<a href="javascript:void(0);" onclick="imprimirDoc('+i+')">';
							strAdd = strAdd + '<div class="widget-content widget-radius padding-5 bg-green-100 clearfix">'+
								'<div class="counter counter-md pull-left text-left padding-5">';
								
								if(swiGrup == 'S'){
									$('#divImpGrup').removeClass('hide');
									strAdd = strAdd +'<div class="checkbox-custom checkbox-primary margin-left-5 ">'
										+'<input type="checkbox" id="docLis'+docActual.id+'" checked ';
										
										if(data.listaObligatorio){
											for(var j = 0; j < data.listaObligatorio.length; j++){
												if(data.listaObligatorio[j] == docActual.id){
													strAdd = strAdd +' disabled ';
													break;
												}
											}
										}
										
										strAdd = strAdd +'onchange=""/>'
										+'<label for="docLis'+docActual.id+'"><span class=""><strong>'+docActual.nombre+'</strong></span>'
									+'</div>'
								}else{
									strAdd = strAdd +'<span class=""><strong>'+docActual.nombre+'</strong></span>';
								}
							
								strAdd = strAdd + '</div>'+
								'<div class="pull-right white">'+
									'<i class="icon icon-circle icon wb-file bg-green-600" aria-hidden="true"></i>'+
								'</div>'+
							'</div>';
							if(swiGrup != 'S')
								strAdd = strAdd +'</a>';
						
							strAdd = strAdd +'</div>';
						$('#listaDocs').append(strAdd);
						
				}
              }
          }
        });
}

function genNumHist(strNum){
	var strAux = String(strNum);
	var lStrNum = strAux.length;
	for(var i = lStrNum ; i < 9; i++){
		strAux = "0" + strAux;
	}
	return strAux;
}

function seleccionarPaciente(orden){
	
	if(pacientesActuales[orden]){
		var pac = pacientesActuales[orden];
		$("#formBusqPac").addClass('hide');
		$("#datsDocsDiv").removeClass('hide');
		$("#sNombreCompleto").html(pac.nompac+' '+pac.ap1pac+ ' '+pac.ap2pac);
		$("#impIdePacDat").val(pac.id);
		$("#impNHist").val(genNumHist(pac.id));
		pagWizzard = 1;
		paginadorWizzard('S');
	}
}

function seleccionarMedico(orden){
	
	if(medicosActuales[orden]){
		var med = medicosActuales[orden];
		$("#formBusqDoc").addClass('hide');
		$("#datsMedDocsDiv").removeClass('hide');
		$("#sNombreCompletoMed").html(med.apusr+ ', '+med.nomusr);
		$("#impIdeMedDat").val(med.id);
		pagWizzard = 2;
		paginadorWizzard('S');
	}
}

function cancelarSelectPac(){
	$("#formBusqPac").removeClass('hide');
	$("#datsDocsDiv").addClass('hide');
	$("#sNombreCompleto").html('');
	$("#impIdePacDat").val('');
}

function cancelarSelectMed(){
	$("#formBusqDoc").removeClass('hide');
	$("#datsMedDocsDiv").addClass('hide');
	$("#sNombreCompletoMed").html('');
	$("#impIdeMedDat").val('');
}

function buscarPacienteNomAp(){
	if(!$('#nompac').val() && !$('#ap1pac').val() && !$('#ap2pac').val()){
		swal("Error", 'debe especificar almenos un elemento en la busqueda', "error");
		return false;
	}
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
                        listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Seleccionar Paciente"><button type="button" onclick="seleccionarPaciente(\''+i+'\')" class="btn btn-icon btn-flat btn-default"><i class="icon wb-check-circle" aria-hidden="true"></i></button></span>';
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

function buscarDocNomAp(){
	if(!$('#nomDoc').val() && !$('#apDoc').val() && !$('#numColDoc').val()){
		swal("Error", 'debe especificar almenos un elemento en la busqueda', "error");
		return false;
	}
	medicosActuales = null;
	var tDoctor = $('#tablaMedicos').DataTable();
	tDoctor.clear().draw();
	$.ajax({
			data: {
				'nomusr': $('#nomDoc').val(),
				'apusr': $('#apDoc').val(),
				'numcoleg': $('#numColDoc').val(),
			},
			url:   '/private/buscarMedNomApCol',
			type:  'GET',
			dataType: 'json',
			success:  function (data) {
				if(data && data.listaUsuarios){
					medicosActuales = data.listaUsuarios;
            		for(var i = 0; i < data.listaUsuarios.length; i++){
						var medActual = data.listaUsuarios[i];
                        var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
                        listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Seleccionar Médico"><button type="button" onclick="seleccionarMedico(\''+i+'\')" class="btn btn-icon btn-flat btn-default"><i class="icon wb-check-circle" aria-hidden="true"></i></button></span>';
                        '</div>';
                        
                        tDoctor.row.add( [
                          	medActual.id ,
                          	medActual.nomusr ,
                          	medActual.apusr,
                          	medActual.numcoleg,
                          	medActual.fecbajadmin,
                            listaBtn
                            
                        ] ).draw( false );
                    }    
                    $('[data-toggle="tooltip"]').tooltip();     

                     $('.loader-wrap').addClass("hide");
                }
                 
            
          }
        });
}

function imprimirImpDocDatos(){
	var imprimir = true;
	
	$("#impIdeDocDat").val(docImprimir?docImprimir.id:null);
	
	for(var i = 0; i < listDocsConfigActu.length; i++){
						
		if(listDocsConfigActu[i].dato == 'DATOS_PACIENTE' && !$("#impIdePacDat").val()){
			swal("Error", 'Debe indicar un paciente', "error");
			return false;
		}	
		
		if(listDocsConfigActu[i].dato == 'DATOS_MEDICO' && !$("#impIdeMedDat").val()){
			swal("Error", 'Debe indicar un médico', "error");
			return false;
		}
	}
	if(imprimir){
		$( "#fImpDocDatos" ).submit();
		$("#datosDoc").modal("toggle");
	}
		
}

function generarDivGrup(id, texto){
	var stVacio = "";
	return '<a onclick="buscarDocumentos(\''+stVacio+'\',\''+ id +'\')" href="javascript:void(0);">'
						+'<div class="widget col-sm-4">'+
						'<div class="widget-content widget-radius padding-20 bg-green-100 clearfix">'+
						  
						 ' <div class="pull-left white">'+
						'	<i class="icon icon-circle icon-2x wb-folder bg-green-600" aria-hidden="true"></i>'+
						'  </div>'+
						  
						'  <div class="counter counter-md pull-left text-left">'+
					'		<div class="counter-number-group">'+
					'		  <span class="counter-number" ></span>'+
					'		  <span class="counter-number-related text-capitalize font-size-18">'+texto+'</span>'+
					'		</div>'+
					'	  </div>'+
					'	</div>'+
					'</div>'+ 
					'</a>';
}

function anadirGrupos(){
	$('#listaTiposDocs').append(generarDivGrup('1', 'Grupo Ingreso' ));
	$('#listaTiposDocs').append(generarDivGrup('2', 'Grupo Preoperatorio' ));
	$('#listaTiposDocs').append(generarDivGrup('3', 'Grupo Revisiones' ));
}

function initPantalla(){
	
	//Añadismos los estaticos
	
	if(listaEsp){
				  
		  if(!listaEspObtenidas){
			listaEspObtenidas = listaEsp;
			for(var i = 0; i < listaEsp.length; i++){
			  $('#impEspe').append(
				$('<option>', {
					value: listaEsp[i].especialidad,
					text: listaEsp[i].especialidad
				}));
			}
		  }
		  
	}
	
	anadirGrupos();
	
	for(var i = 0; i < listaTipos.length; i++){
		var strAdd = '<a onclick="buscarDocumentos(\''+listaTipos[i].coddom+'\')" href="javascript:void(0);">'
							+'<div class="widget col-sm-4">'+
							'<div class="widget-content widget-radius padding-20 bg-green-100 clearfix">'+
							  
							 ' <div class="pull-left white">'+
							'	<i class="icon icon-circle icon-2x wb-folder bg-green-600" aria-hidden="true"></i>'+
							'  </div>'+
							  
							'  <div class="counter counter-md pull-left text-left">'+
						'		<div class="counter-number-group">'+
						'		  <span class="counter-number" ></span>'+
						'		  <span class="counter-number-related text-capitalize font-size-18">'+listaTipos[i].desval+'</span>'+
						'		</div>'+
						'	  </div>'+
						'	</div>'+
						'</div>'+ 
						'</a>';
		
		$('#listaTiposDocs').append(strAdd);
		
	}
	
	
}


function obtenerDatosUsuarioSession (){
		$.ajax({
          url:   '/private/obtenerDatosUsuarioSession',
          type:  'GET',
          dataType: 'json',
          success:  function (data) {
              if(data && data.usuario){
              }
          }
        });
}
initPantalla();
obtenerDatosUsuarioSession();
