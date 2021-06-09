$(document).ready(function(){
      
      setTimeout(
    		  function() 
    		  {
    			  $('[name="liMenuDocs"]').addClass('active');
    			  $('.cdpickDesde').datepicker({
    				  weekStart:1
    				});
    			  $('#sFecHasta').datepicker({
    				  weekStart:1
    				});
    		  }, 500);
});

$( ".cdpickDesde" ).change(function(v) {
	if($('#sFecDesde') && $('#sFecDesde').val()){
		$(this).datepicker('hide');
		$('#sFecHasta').val('');
		year = $('#sFecDesde').val().substring(6, 10);
	    mes = $('#sFecDesde').val().substring(3, 5);
	    dia = $('#sFecDesde').val().substring(0, 2);
	    var dateObt = new Date( year, mes-1, dia );
	    $('#sFecHasta').data('datepicker').setStartDate(dateObt);
	}
		
});

$( "#usuAneCir" ).change(function() {
	 if(this.checked){
		 $("#usuMedAll").prop('checked', false);
		 objMedDocs = [];
		 medicosActuales = new Array();
		 medicosActuales.push(usrMateoDefault);
		 seleccionarMedico(0);
		 medsSelectBusqAct = usrMateoDefault;
		 
		 var arrDocsSelecMateo = new Array();
		 for(var i = 0; i < listaDocsActivos.length; i++){
			 if(listaDocsActivos[i].subtipo == 'ANESTESIA')
				 arrDocsSelecMateo.push(listaDocsActivos[i].id)
		 }
		 $("#sdocsConMed").select2('val', arrDocsSelecMateo);
		 guardarConfigDoc();
	 }else{
		 cancelarSelectMed();
	 }
		 
});
$( "#usuMedAll" ).change(function() {
	 if(this.checked){
		 $("#usuAneCir").prop('checked', false);
		 cancelarSelectMed();
	 } 
		 
});

$( "#sFecHasta" ).select(function(v) {
	$(this).datepicker('hide');
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
var listaDocsMed = null;
var objMedDocs = null;
var medsSelectBusqAct = null;
var objListDocAsocidos = null;

var usrMateoDefault = null;
var grupoSelect = null;

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

function valoresDefaultoCombo (){
	$('#sMed').empty();
	$('#sMed').append(
			$('<option>', {
		    value: '',
		    text: 'Elegir médico'
		}));
	
	$.ajax({
		url:   generarUrl('/private/obtenerListaMed'),
		type:  'GET',
		dataType: 'json',
		success:  function (data) {
			if(data && data.listaMedicos){
				for(var i=0; i < data.listaMedicos.length; i++){
					$('#sMed').append(
							$('<option>', {
						    value: data.listaMedicos[i].id,
						    text: data.listaMedicos[i].apusr + ', '+data.listaMedicos[i].nomusr
						}));
				}
			}
      }
    });
	
	$('#sIdseguro').empty();
	$('#sIdseguro').append(
			$('<option>', {
		    value: '',
		    text: 'Seguros'
		}));
	$.ajax({
		url:   generarUrl('/private/obtenerSegurosAdm'),
		type:  'GET',
		dataType: 'json',
		success:  function (data) {
			if(data && data.lisSeguros){
				for(var i=0; i < data.lisSeguros.length; i++){
					$('#sIdseguro').append(
							$('<option>', {
						    value: data.lisSeguros[i].id,
						    text: data.lisSeguros[i].nomseguro
						}));
				}
			}
      }
    });
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
		
		objMedDocs = [];
		$(".dosImpSelect").addClass('hide');
		$(".consfigActuales").addClass('hide');
		$(".formBusqDatos").removeClass('hide');
		
		$('#usuMedAll').prop('checked', true);
		$("#usuAneCir").prop('checked', false);
		
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
	reqFirma = false
	reqFirmaMed = false;
	var grupo = [];
	for(var i=0; i < listaDocsActivos.length; i++){
		var docActual = listaDocsActivos[i];
		if($('#docLis'+docActual.id).is(":checked")){
			grupo.push(docActual.id);
			mostrarReqFirma(i);
		}
			
		
	}
	imprimirDoc(null, grupo);
}

function guardarContinuarConfigDoc(){
	guardarConfigDoc();
	$("#formBusqDoc").addClass('hide');
	pagWizzard = 2;
	paginadorWizzard('S');
}

function guardarConfigDoc(){
	
	$('#medsSelectBusqAct').empty();
	if(!objListDocAsocidos)
		objListDocAsocidos = [];
	var auxMed = [];
	auxMed.id = medsSelectBusqAct.id;
	auxMed.nomusr =  medsSelectBusqAct.apusr+ ', '+medsSelectBusqAct.nomusr;
	var docSelect = [];
	var strDocs = '';
	$('#sdocsConMed :selected').each(function(i, selected){ 
		docSelect.push(selected.value);
		objListDocAsocidos.push(selected.value);
		if(strDocs.length > 0)
			strDocs = strDocs + ', ';
		strDocs = strDocs + selected.text
	});
	auxMed.docSelect = docSelect
	objMedDocs.push(auxMed);
	$('.medsSelectBusq').append('<p>'+ medsSelectBusqAct.apusr+ ', '+medsSelectBusqAct.nomusr +': ' + strDocs +'</p>');
	
	$(".consfigActuales").removeClass('hide');
	$(".formBusqDatos").removeClass('hide');	
	$(".dosImpSelect").addClass('hide');
	
	///// se ocualtan los medicos que no existen
	
	$('.formBusqDatos').removeClass('hide');
	$('#btnOtrMed').removeClass('hide');
	$('.formOtroMed').addClass('hide');
	$('#oMApDoc').val('');
	$('#oMNomDoc').val('');
	$('#oMApDoc').val('');
}

$('#sdocsConMed').on("select2:select", function(e) {
	if($('#sdocsConMed :selected').length == $('#sdocsConMed option').length){
		
		$("#btnGCCD").removeClass('hide');
		$("#btnGCD").addClass('hide');
	}else{
		$("#btnGCCD").addClass('hide');
		$("#btnGCD").removeClass('hide');
	}
	
});

$('#sdocsConMed').on("select2:unselect", function(e) { 
	$("#btnGCCD").addClass('hide');
	$("#btnGCD").removeClass('hide');
});

function obtenerUltimasCitasPac(){
	$('#idCitaSelect').empty();
	$.ajax({
		data: {
			'idpac': $("#impIdePacDat").val()
		},
		url:   generarUrl('/private/obtenerUltimasCitasPac'),
		type:  'GET',
		dataType: 'json',
		success:  function (data) {
			if(data && data.listaCitas){
				for(var i = 0; i < data.listaCitas.length; i++){
					$('#idCitaSelect').append(
						$('<option>', {
					    value: data.listaCitas[i].id,
					    text: fechaString(data.listaCitas[i].feccita) + ' - ' + data.listaCitas[i].especialidad + ' - ' + data.listaCitas[i].desval.toUpperCase()
					}));
        		}
			}
			
			if(data && data.listaVisitas){
				$('#idCitaSelect').append('<optgroup id="otrCitas" role="group" label="Otras Visitas">');
				for(var i = 0; i < data.listaVisitas.length; i++){
					$('#otrCitas').append(
						$('<option>', {
					    value: 'visita-' + data.listaVisitas[i].id,
					    text: fechaString(data.listaVisitas[i].fecvisita) + ' - ' + data.listaVisitas[i].nomrol + ' - ' + data.listaVisitas[i].desval.toUpperCase()
					}));
				}
				
			}
             
        
      }
    });
}

function mostrarReqFirma(o){
	
	if(listaDocsActivos[o] && listaDocsActivos[o].reqfirmapac == 'S'){
		reqFirma = true
		$("#divFirmaDoc").removeClass('hide');
	}
	
	if(listaDocsActivos[o] && listaDocsActivos[o].reqfirmamed == 'S'){
		reqFirmaMed = true
		$("#divFirmaMedDoc").removeClass('hide');
	}
	
		
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
	
	$("#fgDesde").addClass('hide');
	$("#fgHasta").addClass('hide');
	$("#fgSeguro").addClass('hide');
	$("#divMedNoObl").addClass('hide');
	
	$("#divMed").addClass('hide');
	
	$("#divCitPac").addClass('hide');
	$('.dSol').addClass('hide');
	
	
	$("#btnVol").addClass('hide');
	$("#btnImp").addClass('hide');
	$("#fGene").addClass('hide');
	$("#divImporte").addClass('hide');
	
	$(".dosImpSelect").addClass('hide');
	$(".consfigActuales").addClass('hide');
	$(".formBusqDatos").removeClass('hide');
	$('.formOtroMed').addClass('hide');
	if(!grupo || (grupo && !reqFirma))
		$("#divFirmaDoc").addClass('hide');
	if(!grupo || (grupo && !reqFirmaMed))
		$("#divFirmaMedDoc").addClass('hide');
	
	$("#sNombreCompleto").html('');
	$("#impIdePacDat").val('');
	$("#impIdeMedDat").val('');
	$("#nompac").val('');
	$("#ap1pac").val('');
	$("#ap2pac").val('');
	$('#impObsDat').code('');
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
	$("#nomMedNoObl").val('');
	$("#importe").val('');
	$("#sfechGen").datepicker().datepicker("setDate", new Date());
	
	$('#listEmpresa').val(idEmpresaSes);
	//$('#swiFirma').prop('checked', true);
	$('#impLitPrivado').prop('checked', false);
	if($('[name="swiFirma"]').is(':checked'))
		$('#swiFirma').click();
	if($('[name="swiFirmaMed"]').is(':checked'))
		$('#swiFirmaMed').click();
	
	$("#apSol").val('');
	$("#nombreSol").val('');
	$("#solDoc").val('');
	objMedDocs = null;
	
	/*** Acto Qui solo para grupo de preoperatorio ***/
	$('#actQui').empty();
	$('#actQui').append(
			$('<option>', {
		    value: '',
		    text: 'Acto quirurjico'
		}));
	
	
	/*** Fin act qui ***/
	
	$('#cPrea').prop('checked', true);
	$('#cAna').prop('checked', true);
	$('#cEco').prop('checked', true);
	$('#cRxt').prop('checked', true);
	$('#cAudio').prop('checked', true);
	$('#cVisi').prop('checked', true);
	$('#cEspi').prop('checked', true);
	$('#cEle').prop('checked', true);
	$('#usuMedAll').prop('checked', true);
	$("#usuAneCir").prop('checked', false);
	$('#swiPacJus').prop('checked', true);
	
	pagWizzard = 0;
	
	var urlEjecutar = "";
	listaDocsMed = null;
	
	if(orden || orden == 0){
		reqFirma = false;
		reqFirmaMed = false;
		urlEjecutar = '/private/buscarConfiguracionesDoc';
		docImprimir = listaDocsActivos[orden];
		mostrarReqFirma(orden);
		
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
			url:   generarUrl(urlEjecutar),
			type:  'GET',
			dataType: 'json',
			success:  function (data) {
				if(data && data.listaDocsMed)
					listaDocsMed = data.listaDocsMed;
				
				if((data && data.listadoDocsConfig && data.listadoDocsConfig.length > 0)  || reqFirma == true || reqFirmaMed == true){
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
							if(data.listadoDocsConfig[i].dato == 'FIRMA_MEDICO_MATEO')
								$("#divFirmMedImp").removeClass('hide');
							if(data.listadoDocsConfig[i].dato == 'EMPRESA')
								$("#divImpEmp").removeClass('hide');
							if(data.listadoDocsConfig[i].dato == 'FECHA_HORA_USR'){
								generarCombosHoras();
								$("#horaIniInterImp").removeClass('hide');
							}
							if(data.listadoDocsConfig[i].dato == 'PRUEBAS')
								$("#divImpPruebas").removeClass('hide');
							
							if(data.listadoDocsConfig[i].dato == 'DATOS_CONSULTA')
								$("#divCitPac").removeClass('hide');
							
							if(data.listadoDocsConfig[i].dato == 'FECHA_DESDE')
								$("#fgDesde").removeClass('hide');
							if(data.listadoDocsConfig[i].dato == 'FECHA_HASTA')
								$("#fgHasta").removeClass('hide');
							if(data.listadoDocsConfig[i].dato == 'SEGURO'){
								$("#fgSeguro").removeClass('hide');
								$("#divMed").removeClass('hide');
								valoresDefaultoCombo();
							}
							if(data.listadoDocsConfig[i].dato == 'INFO_SEGURO'){
								$("#divLitPrivado").removeClass('hide');
							}
							if(data.listadoDocsConfig[i].dato == 'MEDICO_NO_OBL')
								$("#divMedNoObl").removeClass('hide');
							if(data.listadoDocsConfig[i].dato == 'FECHA_GENERICA')
								$("#fGene").removeClass('hide');
							if(data.listadoDocsConfig[i].dato == 'IMPORTE_TOTAL')
								$("#divImporte").removeClass('hide');
							
								
						}
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
	
	/*** Acto Qui solo para grupo de preoperatorio ***/
	grupoSelect = nGrupo;
	$('#actQui').empty();
	$('#actQui').append(
			$('<option>', {
		    value: '',
		    text: 'Acto quirurjico'
		}));
	if(grupoSelect && grupoSelect == 2){
		$('#divActqui').removeClass('hide');
	}else{
		$('#divActqui').addClass('hide');
	}
	
	
	/*** Fin act qui ***/
	
	$.ajax({
		  data: {'tipoDoc' : tipDoc,
				  'numGrupo': nGrupo},
          url:   './buscarDocumentos',
          type:  'GET',
          dataType: 'json',
          success:  function (data) {
              if(data && data.listadoDocs){
				listaDocsActivos = data.listadoDocs;
				for(var i = 0; i < data.listadoDocs.length; i++){
					var docActual = data.listadoDocs[i];
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
				
				/*****USUARIO MATEO*******/
				usrMateoDefault = null;
				$('.usuAneCir').addClass('hide');
				if(data && data.usuMateo){
					usrMateoDefault = data.usuMateo;
					$('.usuAneCir').removeClass('hide');
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

function obtenerActQuiAbiertosByIdpac(p){

	if($('#actQui')){
		$('#divActqui').addClass('hide');
		$('#actQui').empty();
		$('#actQui').append(
				$('<option>', {
			    value: '',
			    text: 'Acto quirurjico'
			}));
	}
	
	$.ajax({
		data: {'idpac': p.id},
		url:   generarUrl('/private/obtenerActQuiAbiertosByIdpac'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
	      	
            if(data ){
            	
            	if($('#actQui') && data.listaActQuiAbr && data.listaActQuiAbr.length > 0){
            		$('#divActqui').removeClass('hide');
            		for(var i = 0; i < data.listaActQuiAbr.length; i++){
    					$('#actQui').append(
    						$('<option>', {
    					    value: data.listaActQuiAbr[i].id,
    					    text: data.listaActQuiAbr[i].id + ' - ' + data.listaActQuiAbr[i].fecint
    					}));
            		}
            	}
            }
        }
      });
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
		obtenerUltimasCitasPac();
		
		/*** Acto Qui solo para grupo de preoperatorio ***/
		
		if(grupoSelect && grupoSelect == 2){
			obtenerActQuiAbiertosByIdpac(pac);
		}
		
	}
}

function seleccionarMedico(orden, fakeId, fakeName, fakeAp){
	$('#medsSelectBusqAct').empty();
	$("#datsVarMedDocsDiv").addClass('hide');
	$("#datsMedDocsDiv").addClass('hide');
	medsSelectBusqAct = null;
	$("#sdocsConMed").select2('val', null);
	
	if((orden && medicosActuales[orden]) || fakeId){
		var med = null;
		if(orden){
			med = medicosActuales[orden];
		}else{
			med ={
					'apusr': fakeAp,
					'nomusr': fakeName,
					'id': fakeId
				}
		}
		if(!objMedDocs)
			objMedDocs = [];
		
		
		
		$("#sNombreCompletoMed").html(med.apusr+ ', '+med.nomusr);
		$("#impNomMedDat").val(med.apusr+ ', '+med.nomusr);
		$("#impIdeMedDat").val(med.id);
		
		
		if($('#usuMedAll').is(":checked") || !listaDocsMed){
			$("#datsMedDocsDiv").removeClass('hide');
			objMedDocs = [];
			$("#formBusqDoc").addClass('hide');
			pagWizzard = 2;
			paginadorWizzard('S');
		}else{
			$("#datsVarMedDocsDiv").removeClass('hide');
			$('#medsSelectBusqAct').append('<p>'+ med.apusr+ ', '+med.nomusr +'</p>');
			medsSelectBusqAct = med;
			$('#sdocsConMed').empty();
			for(var i = 0; i < listaDocsMed.length; i++){
				var encontrado = false;
				if(objListDocAsocidos){
					for(var j = 0; j < objListDocAsocidos.length; j++){
						if(listaDocsMed[i].iddoc == objListDocAsocidos[j]){
							encontrado = true;
							break;
						}
					}	
				}
				if(!encontrado){
					$('#sdocsConMed').append(
							$('<option>', {
						    value:listaDocsMed[i].iddoc,
						    text: listaDocsMed[i].nombre
						}));
				}
				
    		}
			
			$(".dosImpSelect").removeClass('hide');
			$(".formBusqDatos").addClass('hide');
			
		}
	}
}

function cancelarSelectPac(){
	$("#formBusqPac").removeClass('hide');
	$("#datsDocsDiv").addClass('hide');
	$("#sNombreCompleto").html('');
	$("#impIdePacDat").val('');
	objListDocAsocidos = [];
	objMedDocs = [];
}

function cancelarSelectMed(){
	$("#formBusqDoc").removeClass('hide');
	$("#datsMedDocsDiv").addClass('hide');
	$("#sNombreCompletoMed").html('');
	$("#impIdeMedDat").val('');
	$(".medsSelectBusq").empty();
	$('#tablaMedicos').DataTable().clear().draw();
	objListDocAsocidos = null;
	objMedDocs = null;
	
	$('.formBusqDatos').removeClass('hide');
	$('#btnOtrMed').removeClass('hide');
	$('.formOtroMed').addClass('hide');
	$('#oMApDoc').val('');
	$('#oMNomDoc').val('');
	$('#oMApDoc').val('');
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
			url:   './buscarMedNomApCol',
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
	if(listDocsConfigActu){
		for(var i = 0; i < listDocsConfigActu.length; i++){
			
			if(listDocsConfigActu[i].dato == 'DATOS_PACIENTE' && !$("#impIdePacDat").val()){
				swal("Error", 'Debe indicar un paciente', "error");
				return false;
			}	
			
			if(listDocsConfigActu[i].dato == 'DATOS_MEDICO' && !$("#impIdeMedDat").val()){
				swal("Error", 'Debe indicar un médico', "error");
				return false;
			}
			if(listDocsConfigActu[i].dato == 'CITAS_DISP' && !$("#sFecHasta").val() && !$("#sFecDesde").val() && !$("#sIdseguro").val()){
				swal("Error", 'Debe filtrar por algún campo', "error");
				return false;
			}
		}
	}
	
	if(imprimir){
	    if(objMedDocs){
	    	var arrJSON = new Array();
	    	for(var i = 0; i < objMedDocs.length; i++){
	    		var jsonArg = new Object();
	    		jsonArg.id =  objMedDocs[i].id;
	    		jsonArg.nomusr =  objMedDocs[i].nomusr;
	    		jsonArg.documentos = objMedDocs[i].docSelect;
	    		
	    		arrJSON.push(jsonArg);
	    	}
	    	
	    	var strJSON = JSON.stringify(arrJSON);
	    	
	    	$("#impIdeMedDatDoc").val(strJSON);
	    }
		
		
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
	$('#listaTiposDocs').append(generarDivGrup('1', 'Ingreso' ));
	$('#listaTiposDocs').append(generarDivGrup('2', 'Preoperatorio' ));
	$('#listaTiposDocs').append(generarDivGrup('3', 'Revisiones' ));
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
	
	if(listadoEmpresas){
		$('#listEmpresa').empty();
		$('#listEmpresa').append(
				$('<option>', {
					value: '',
					text: 'Empresa'
				}));
		for(var i =0; i < listadoEmpresas.length; i++){
			$('#listEmpresa').append(
				$('<option>', {
					value: listadoEmpresas[i].id,
					text: listadoEmpresas[i].nombre
				}));
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

function initAddOtroMed(){
	$('.formBusqDatos').addClass('hide');
	$('#btnOtrMed').addClass('hide');
	$('.formOtroMed').removeClass('hide');
}

function guardarOtrosMed(){
	$('.formOtroMed').addClass('hide');
	seleccionarMedico(null, 'FAKEID'+$('#oMApDoc').val()+$('#oMNomDoc').val(), $('#oMNomDoc').val(), $('#oMApDoc').val());
}

function cancelarAddOtrosMed(){
	$('.formBusqDatos').removeClass('hide');
	$('#btnOtrMed').removeClass('hide');
	$('.formOtroMed').addClass('hide');
	$('#oMApDoc').val('');
	$('#oMNomDoc').val('');
	$('#oMApDoc').val('');
}


function verOcultarDatol(){
	if($('#swiPacJus').is(":checked")){
		$('.dSol').addClass('hide');
		$("#apSol").val('');
		$("#nombreSol").val('');
		$("#solDoc").val('');
	}else{
		$('.dSol').removeClass('hide');
	}
}

function obtenerDatosUsuarioSession (){
		$.ajax({
          url:   './obtenerDatosUsuarioSession',
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
