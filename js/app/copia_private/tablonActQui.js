$(document).ready(function(){
      
      setTimeout(
    		  function() 
    		  {
    			  $('[name="liMenuActQui"]').addClass('active');
    			  obtenerDatosUsuarioSession();
    			  $('#fecintpro').datepicker({
    				  weekStart:1,
    				  startDate: "dateToday"
    				});
    		  }, 500);
    
});

var usuarioSes = null;
var listaUsrBusq = [];
var listaCieBusq = [];
var listaCieBusqPro = [];
var pacienteSelect = null;
var cieSelect = null;
var cieProSelect = null;
var numPasoAct = 1;
var listaActQuiAbrBusq = [];
var paciente = null;
var idDocAnestes = 5;
var idDocConsent = 6;
var docFirmaAnestesia = null;
var docFirmaConsentim = null;
var acqQuiUsado = null;
/********** nuevo acto **************/

function anterior(){
	if(numPasoAct == 2)
		initAltaActQui();
	else if(numPasoAct == 3)
		initAltaPaso2();
	else if(numPasoAct == 4)
		initAltapaso3();
	else if(numPasoAct == 5)
		initAltaPaso4();
}

function siguiente(){
	if(numPasoAct == 2)
		validarPaso2();
	else if(numPasoAct == 3)
		validarpaso3();
	else if(numPasoAct == 4)
		validarpaso4();
}

function validarPaso2(){
	if(!cieSelect || cieSelect.length < 1){
		swal('Error', 'Debe seleccionar al menos un CIE' , "error"); 
		return false;
	}else
		initAltapaso3();
}

function validarpaso3(){
	if(!cieProSelect || cieProSelect.length < 1){
		swal('Error', 'Debe seleccionar al menos un CIE' , "error"); 
		return false;
	}else
		initAltaPaso4();
}

function validarpaso4(){
	if(!$('#fecIniAct').val()|| !$('#minIniAct').val() || !$('#horIniAct').val() ){
		swal('Error', 'Debe rellenar todos los campos' , "error"); 
		return false;
	}else{
		var vSpan = document.createElement('span');
		vSpan.innerHTML = '<br><b>Fecha seleccionada: </b>' + $('#fecIniAct').val() + ' ' + $('#minIniAct').val() + ':' + $('#horIniAct').val() ;
		
		$('#contResumenfec').append(vSpan);
		initAltaPasoFin();
	}
		
}



function initAltaActQui(){
	numPasoAct = 1;
	$('#btnAnt').addClass('hide');
	$('#btnSig').addClass('hide');
	$('#btnGuardarActQui').addClass('hide');
	
	$('#contResumenPac').empty();
	$('#contResumenCie').empty();
	$('#contResumenCiePro').empty();
	$('#contResumenfec').empty();
	
	$('#paso1').removeClass('hide');
	$('#paso2').addClass('hide');
	$('#paso4').addClass('hide');
	
	$('#tablaPac').DataTable().clear().draw();
	
	
	listaUsrBusq = [];
	cieSelect = [];
	cieProSelect = [];
	pacienteSelect = null;
}

function initAltaPaso2(){
	numPasoAct = 2;
	cieSelect = [];
	
	$('#btnAnt').removeClass('hide');
	$('#btnSig').removeClass('hide');
	
	$('#paso1').addClass('hide');
	$('#paso2').removeClass('hide');
	$('#paso3').addClass('hide');
	$('#paso4').addClass('hide');
	
	$('#contResumenCie').empty();
	$('#contResumenCiePro').empty();
	$('#contResumenfec').empty();
	
	$('#tablaCie').DataTable().clear().draw();
	$('#claseCieBusq').val('');
}


function initAltapaso3(){
	numPasoAct = 3;
	cieProSelect = [];
	$('#contResumenCiePro').empty();
	
	$('#paso2').addClass('hide');
	$('#paso3').removeClass('hide');
	$('#paso4').addClass('hide');
	
	$('#tablaCiePro').DataTable().clear().draw();
	$('#codigoCieProBusq').val('');
	$('#desCieProBusq').val('');
	
}

function initAltaPaso4(){
	numPasoAct = 4;
	
	$('#contResumenfec').empty();
	
	$('#paso2').addClass('hide');
	$('#paso3').addClass('hide');
	$('#paso4').removeClass('hide');
}

function initAltaPasoFin(){
	numPasoAct = 5;
	$('#paso4').addClass('hide');
	$('#btnSig').addClass('hide');
	$('#btnGuardarActQui').removeClass('hide');
}

function seleccionarCiePro(orden){
	
	var resultFound = $.grep(cieProSelect, function(e){ return e.id == listaCieBusqPro[orden].id; });
	if(resultFound.length > 0){
		swal('Error', 'Este Cie ya fué añadido' , "error"); 
		return false;
	}
	console.info($('#btnTablaCIEPro'+orden));
	$('#btnTablaCIEPro'+orden).removeClass('btn-success'); 
	$('#btnTablaCIEPro'+orden).addClass('btn-default'); 
	cieProSelect.push(listaCieBusqPro[orden]);
	var vSpan = document.createElement('span')
	vSpan.innerHTML = '<br><b>CIE Seleccionado: </b>' + listaCieBusqPro[orden].codigo + ' - ' + listaCieBusqPro[orden].desciepro;
	$('#contResumenCiePro').append(vSpan);
	console.info(cieProSelect);
}

function buscarCieProByClaseDesc(){
	listaCieBusqPro = null;
	
	var tCie = $('#tablaCiePro').DataTable();
	tCie.clear().draw();
	var codigoCiePro = $('#codigoCieProBusq').val().replace(/\s+/g, '');
	if(codigoCiePro.length && codigoCiePro.length == 0 ){
		swal('Error', 'Debe aplicar un elemento de filtro' , "error"); 
		return false;
	}
	
	$('.loader-wrap').removeClass("hide");
	
	$.ajax({
	      url:  generarUrl('/private/buscarCieProByCodDesc'),
	      type:  'GET',
	      data:{'codigo': codigoCiePro.toUpperCase(),
	    	  'desciepro': $('#desCieProBusq').val()},
	      dataType: 'json',
	      success:  function (data) {
	    	  $('.loader-wrap').addClass("hide");
	          if(data && data.listaCiePro){
	        	  listaCieBusqPro = data.listaCiePro;
	        	  for(var i = 0; i < data.listaCiePro.length; i++){
	        		  
	        		  var resultFound = $.grep(cieProSelect, function(e){ return e.id == data.listaCiePro[i].id; });
	        		  var btnClass = 'btn-success';
	        		  if(resultFound.length > 0) 
	        			  btnClass = 'btn-default';
	        		  
        			  var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
	 					listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover"'
	 					+ 'data-original-title="Historial"><button id="btnTablaCIEPro'+i+'" type="button" onclick="seleccionarCiePro(\''+i+'\')" '
	 					+'class="btn '+ btnClass +' btn-icon waves-effect waves-light" ><i class="icon wb-check" aria-hidden="true"></i> Seleccionar</button></span>';
	 					listaBtn = listaBtn + '</div>';
	 					
	        		  tCie.row.add( [
	        	                     data.listaCiePro[i].codigo ,
	        	                     data.listaCiePro[i].desciepro,
	        	                     listaBtn
	        	                     
	        	                 ] ).draw( false );  
	        		  
	        	  }
	          }
	      }
	    });
	console.info(codigoCiePro);
}

function seleccionarCie(orden){
	/*if(cieSelect && cieSelect.length == 3){
		swal('Error', 'El máximo de CIE seleccionados es de 3' , "error"); 
		return false 
	}*/
	var resultFound = $.grep(cieSelect, function(e){ return e.id == listaCieBusq[orden].id; });
	if(resultFound.length > 0){
		swal('Error', 'Este Cie ya fué añadido' , "error"); 
		return false;
	}
	console.info($('#btnTablaCIE'+orden));
	$('#btnTablaCIE'+orden).removeClass('btn-success'); 
	$('#btnTablaCIE'+orden).addClass('btn-default'); 
	cieSelect.push(listaCieBusq[orden]);
	var vSpan = document.createElement('span')
	vSpan.innerHTML = '<br><b>CIE Seleccionado: </b>' + listaCieBusq[orden].clase + ' - ' + listaCieBusq[orden].descie;
	$('#contResumenCie').append(vSpan);
	console.info(cieSelect);
}

function buscarCieByClaseDesc(){
	listaCieBusq = null;
	
	var tCie = $('#tablaCie').DataTable();
	tCie.clear().draw();
	var claseCie = $('#claseCieBusq').val().replace(/\s+/g, '');
	if(claseCie.length == 0 && claseCie == '.' && !$('#desCieBusq').val()){
		swal('Error', 'Debe aplicar un elemento de filtro' , "error"); 
		return false;
	}
	if(claseCie && (claseCie.indexOf(".") < 3 || claseCie.length == 4)){
		claseCie = claseCie.substring(0, claseCie.indexOf("."));
	}
	$('.loader-wrap').removeClass("hide");
	
	$.ajax({
	      url:  generarUrl('/private/buscarCieByClaseDesc'),
	      type:  'GET',
	      data:{'clase': claseCie.toUpperCase(),
	    	  'descie': $('#desCieBusq').val()},
	      dataType: 'json',
	      success:  function (data) {
	    	  $('.loader-wrap').addClass("hide");
	          if(data && data.listaCie){
	        	  listaCieBusq = data.listaCie;
	        	  for(var i = 0; i < data.listaCie.length; i++){
	        		  
	        		  var resultFound = $.grep(cieSelect, function(e){ return e.id == data.listaCie[i].id; });
	        		  var btnClass = 'btn-success';
	        		  if(resultFound.length > 0) 
	        			  btnClass = 'btn-default';
	        		  
        			  var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
	 					listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover"'
	 					+ 'data-original-title="Historial"><button id="btnTablaCIE'+i+'" type="button" onclick="seleccionarCie(\''+i+'\')" '
	 					+'class="btn '+ btnClass +' btn-icon waves-effect waves-light" ><i class="icon wb-check" aria-hidden="true"></i> Seleccionar</button></span>';
	 					listaBtn = listaBtn + '</div>';
	 					
	        		  tCie.row.add( [
	        	                     data.listaCie[i].clase ,
	        	                     data.listaCie[i].descie,
	        	                     listaBtn
	        	                     
	        	                 ] ).draw( false );  
	        		  
	        	  }
	          }
	      }
	    });
	console.info(claseCie);
}

function mostrarNumDoc(extension, str){
	var strParam = str?str:'';
	if($( '#'+strParam+'tipdoc' + extension).val()== 'DNI'){
		$( '#'+strParam+'dniusr' + extension ).removeClass('hide');
		$( '#'+strParam+'nieusr' + extension ).addClass('hide');
		$( '#'+strParam+'passusr' + extension ).addClass('hide');
	}
	if($( '#'+strParam+'tipdoc' + extension ).val()== 'NIE'){
		$( '#'+strParam+'dniusr' + extension ).addClass('hide');
		$( '#'+strParam+'nieusr' + extension ).removeClass('hide');
		$( '#'+strParam+'passusr' + extension ).addClass('hide');
	}
	if($( '#'+strParam+'tipdoc' + extension ).val()== 'PAS'){
		$( '#'+strParam+'dniusr' + extension ).addClass('hide');
		$( '#'+strParam+'nieusr' + extension ).addClass('hide');
		$( '#'+strParam+'passusr' + extension ).removeClass('hide');
	}
}

$( "#tipdocBusq" ).change(function(v) {
	$( "#dniusrBusq" ).val('');
	$( "#nieusrBusq" ).val('');
	$( "#passusrBusq" ).val('');
	mostrarNumDoc('Busq');
});

function limpiarBusqueda(str){
	var strParam = str?str:'';
	pacienteSelect = null;
	
	$('#'+strParam+'nompac').val('');
	$('#'+strParam+'ap1pac').val('');
	$('#'+strParam+'ap2pac').val('');
	document.getElementById(strParam+'tipdocBusq').value  = 'DNI';
	mostrarNumDoc('Busq', str);
	$( '#'+strParam+'dniusrBusq' ).val('');
	$( '#'+strParam+'nieusrBusq' ).val('');
	$( '#'+strParam+'passusrBusq' ).val('');
	$( '#'+strParam+'idHistorialBusq').val('');
}

function seleccionarPac(pos){
	console.info(listaUsrBusq[pos]);
	pacienteSelect = listaUsrBusq[pos];
	var vSpan = document.createElement('span')
	vSpan.innerHTML = '<b>Paciente Seleccionado: </b>' + pacienteSelect.nompac + ' ' + pacienteSelect.ap1pac + ' ' + pacienteSelect.ap2pac + ' - ' + pacienteSelect.dniusr;
	$('#contResumenPac').append(vSpan);
	
	initAltaPaso2();
}

function obtenerMisPacientes(str){
	var usr = usuarioSes;
	var strParam = str?str:'';
	listaUsrBusq = [];
	pacienteSelect = null;
	
	var numdoc = $( '#'+strParam+'tipdocBusq' ).val()== 'DNI'? $('#'+strParam+'dniusrBusq').val(): $( '#'+strParam+'tipdocBusq' ).val()== 'NIE'? $('#'+strParam+'nieusrBusq').val():$( '#'+strParam+'tipdocBusq' ).val()== 'PAS'? $('#'+strParam+'passusrBusq').val():'';
	numdoc = numdoc.replace(/\s/g, "") ;
	if(!$('#'+strParam+'nompac').val() && !$('#'+strParam+'ap1pac').val() && !$('#'+strParam+'ap2pac').val() && (!numdoc || numdoc == '-') && !$('#'+strParam+'idHistorialBusq').val()){
		swal("Error", 'Debe especificar almenos un elemento en la busqueda', "error");
		return false;
	}
	$('.loader-wrap').removeClass("hide");
	
	var url = '/private/obtenerMisPacientes';
	if(str)
		url = '/private/buscarPacienteNomAp';
	
	$.ajax({
		data :{'idmed' : usr.id,
			'nompac': $('#'+strParam+'nompac').val(),
			'ap1pac': $('#'+strParam+'ap1pac').val(),
			'ap2pac': $('#'+strParam+'ap2pac').val(),
			'dniusr' : numdoc,
			'idHistorial' :$('#'+strParam+'idHistorialBusq').val()
			},
        url:  generarUrl(url),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	var tPac = str?$('#tablaPacEsp').DataTable(): $('#tablaPac').DataTable();
            tPac.clear().draw();
        	
            if(data && data.pacientes){
            	listaUsrBusq = data.pacientes;
            	for(var i = 0; i < data.pacientes.length; i++){
            		var pacAct =  data.pacientes[i];
        			
            		var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
 					listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover"'
 					+ 'data-original-title="Historial"><button type="button" onclick="seleccionarPac(\''+i+'\')" '
 					+'class="btn btn-success btn-icon waves-effect waves-light" ><i class="icon wb-check" aria-hidden="true"></i> Seleccionar</button></span>';
 					listaBtn = listaBtn + '</div>';
        			
        			tPac.row.add( [
                        pacAct.nompac ,
                        pacAct.ap1pac,
                        pacAct.ap2pac,
                        pacAct.dniusr,
                        listaBtn
                        
                    ] ).draw( false );
                } 
        		$('[data-toggle="tooltip"]').tooltip();
            }
            
            if(data && data.listaPacientes){
            	for(var i = 0; i < data.listaPacientes.length; i++){
            		var pacAct =  data.listaPacientes[i];
        			
            		var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
 					listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Historial"><button type="button" onclick="selectPaciente(\''+i+'\')" class="btn btn-success btn-icon waves-effect waves-light" ><i class="icon wb-folder " aria-hidden="true"></i></button></span>';
 					listaBtn = listaBtn + '</div>';
        			
        			tPac.row.add( [
                        pacAct.nompac ,
                        pacAct.ap1pac,
                        pacAct.ap2pac,
                        pacAct.dniusr,
                        listaBtn
                        
                    ] ).draw( false );
                } 
        		$('[data-toggle="tooltip"]').tooltip();
            }
            
        }
      });
}

function guardarActQui(){
	
	var oCies =[];
	$.ajax({
	      url:   generarUrl('/private/guardarActQui'),
	      data: {
	    	 'fecIniAct' : fechaStringEncode($('#fecIniAct').val()),
	    	 'minIniAct' : $('#minIniAct').val(),
	    	 'horIniAct' : $('#horIniAct').val(),
	    	 'idpac' : pacienteSelect.id,
	    	 'cie' : cieSelect,
	    	 'ciePro' : cieProSelect,
	    	 'idusr' : usuarioSes.id
	      },
	      type:  'GET',
	      dataType: 'json',
	      success:  function (data) {
	          if(data && data.msgOk){
	        	  swal('Dado de alta', data.msgOk , "success");
	        	  $("#altaActQui").modal("toggle");
	          }
	      }
	    });
	
	
}
/********** nuevo acto **************/


/*********** INFO ACTO QUIRURJICO *****************/
function descargarConsentimiento(tip){

    /*var exten = 'pdf';
    var newlink = document.createElement('a');
	newlink.href = 'data:application/octet-stream;base64,' + tip == 'ANE'?docFirmaAnestesia[0].archivo:docFirmaConsentim[0].archivo;
	newlink.download ='Fichero.'+exten;
	document.body.appendChild(newlink);
	newlink.click();
	document.body.removeChild(newlink);
	*/

	$("#iddoc").val(tip == 'ANE'?docFirmaAnestesia[0].id:docFirmaConsentim[0].id);
	$( "#fverFirma" ).submit();

}

function verInfoActqui(pos){
	$('.ciesSelInfo').empty();
	$('.ciesProSelInfo').empty();
	$('#sDescConAnes').addClass('hide');
	$('#sNoConAnes').addClass('hide');
	$('#sDescConInf').addClass('hide');
	$('#sNoConInf').addClass('hide');
	docFirmaAnestesia = null;
	docFirmaConsentim = null;
	
	var actQuiAct = listaActQuiAbrBusq[pos];
	$('#nomPacSelInfo').html(actQuiAct.nompac + ' ' + actQuiAct.ap1pac + ' ' + actQuiAct.ap2pac);
	var fec = fechaString(actQuiAct.fecint) ;
	$('#fecintSelInfo').html(fec + actQuiAct.fecint.substring(fec.length, actQuiAct.fecint.length - 3));
	
	//En pruebaComplementaria.js
	paciente = {'id': actQuiAct.idpac};
	prepararVentanaPrueCompl(actQuiAct.id);
	
	
	$.ajax({
	      url:   generarUrl('/private/getDatosInfoActQui'),
	      type:  'GET',
	      data: {'idAct' : actQuiAct.id},
	      dataType: 'json',
	      success:  function (data) {
	    	  $("#infoActQui").modal("toggle");
	    	  if(data){
	    		  if(data.listaCieInfo){
		        	  data.listaCieInfo.forEach(function( e ) {
		        		  var strHtml = '<div class="list-group-item grey-600 waves-effect waves-block waves-classic" href="javascript:void(0)">'
						        +' <i class="icon wb-medium-point"></i> ' + e.clase + ' - ' + e.descie 
						      +'</div>';
			        	  $('.ciesSelInfo').append(strHtml); 
		        	  });
		          }
	    		  
	    		  if(data.listaCieProInfo){
		        	  data.listaCieProInfo.forEach(function( e ) {
		        		  var strHtml = '<div class="list-group-item grey-600 waves-effect waves-block waves-classic" href="javascript:void(0)">'
						        +' <i class="icon wb-medium-point"></i> ' + e.codigo + ' - ' + e.desciepro 
						      +'</div>';
			        	  $('.ciesProSelInfo').append(strHtml); 
		        	  });
		          }
	    		  
	    		  if(data.listaDocsFirmada){
	    			  docFirmaAnestesia = $.grep(data.listaDocsFirmada, function(e){ return e.iddoc == idDocAnestes; });
	    			  docFirmaConsentim = $.grep(data.listaDocsFirmada, function(e){ return e.iddoc == idDocConsent; });
	    			  if(docFirmaAnestesia && docFirmaAnestesia.length > 0)
	    				  $('#sDescConAnes').removeClass('hide');
	    			  else
	    				  $('#sNoConAnes').removeClass('hide');
	    			  
	    			  if(docFirmaConsentim && docFirmaConsentim.length > 0)
	    				  $('#sDescConInf').removeClass('hide');
	    			  else
	    				  $('#sNoConInf').removeClass('hide');
	    			  
	    		  }
	    	  }
	         
	      }
	    });
	
}



function obtenerActQuiAbiertos (){
	$('.loader-wrap').removeClass("hide");
	listaActQuiAbrBusq = [];
	$.ajax({
      url:   generarUrl('/private/obtenerActQuiAbiertosByUsr'),
      data:{'idusr' : usuarioSes.id},
      type:  'GET',
      dataType: 'json',
      success:  function (data) {
    	  $('.loader-wrap').addClass("hide");
    	  var tActQui = $('#tablaActQui').DataTable();
    	  tActQui.clear().draw();
      	
          if(data && data.listaActQuiAbr){
        	listaActQuiAbrBusq = data.listaActQuiAbr;
          	for(var i = 0; i < listaActQuiAbrBusq.length; i++){
          		var actQuiAct =  listaActQuiAbrBusq[i];
      			
          		var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
				
          		listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover"'
					+ 'data-original-title="Acto Quirúrgico"><button type="button" onclick="verInfoActqui(\''+i+'\')" '
					+'class="btn btn-success btn-icon waves-effect waves-light" ><i class="icon wb-plus-circle" aria-hidden="true"></i> Ver</button></span>';
          		
          		var iconBtn = 'thumb-up';
          		var tipBtn = 'primary';
          		if(!actQuiAct.idpreop){
          			iconBtn = 'thumb-down';
          			tipBtn = 'danger';
          		}
          		
          		listaBtn = listaBtn +  '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Añadir Preoperatorio">'
				 + '<button type="button" class="btn btn-'+tipBtn+' btn-icon waves-effect waves-light"'
				 + 'data-toggle="modal" onclick="iniPreoperatorio('+i+')" data-target="#infoPreope" aria-expanded="false" aria-controls="infoPreope"  >'
				 + '<i class="icon wb-'+iconBtn+'" aria-hidden="true"></i> Preoperatorio'
				 + '</button>'
				 + '</span>';
				listaBtn = listaBtn + '</div>';
				
				
				/*listaBtn = listaBtn + '<div class="btn-group" role="group">'
				 + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Añadir Preoperatorio">'
				 + '<button type="button" class="btn btn-primary btn-icon waves-effect waves-light"'
				 + 'data-toggle="modal" onclick="iniPreoperatorio()" data-target="#infoPreope" aria-expanded="false" aria-controls="infoPreope"  >'
				 + '<i class="icon wb-replay" aria-hidden="true"></i>Preoperatorio'
				 + '</button>'
				 + '</span>'
				 + '</div>';*/
      			
				
				
          		tActQui.row.add( [
                      actQuiAct.nompac ,
                      actQuiAct.ap1pac + ' ' + actQuiAct.ap2pac,
                      
                      fechaString(actQuiAct.fecint),
                      listaBtn
                      
                  ] ).draw( false );
              } 
      		$('[data-toggle="tooltip"]').tooltip();
          }
          
      }
    });
}

/*************************Preoperatorio*********************************/

function resetFormPreope(){
	 $('#idpreop').val('');
	  $('#sexpre').val('') ;
	  $('#edadpre').val('');
	  $('#tapre').val('');
	  $('#fcpre').val('');
	  $('#pesopre').val('');
	  $('#estaturapre').val('');
	  $('#imcpre').val('');
	  $('#alerpro').val('');
	  $('#intpro').val('');
	  $("#fecintpro").datepicker("update", '');
	  $("#habtoxTapro").prop('checked', false);
	  $("#habtoxAlpro").prop('checked', false);
	  $('#habtoxOtpro').val('');
	  $('#antpatpro').val('');
	  $('#antquipro').val('');
	  $('#incipro').val('');
	  $('#fcapre').val('');
	  $('#ecgpre').val('');
	  $('#frespre').val('');
	  $('#anapre').val('');
	  $('#opapre').val('');
	  $('#bocpre').val('');
	  $('#perpre').val('');
	  $('#propre').val('');
	  $('#apepre').val('');
	  $('#mcepre').val('');
	  $('#svepre').val('');
	  $('#clupre').val('');
	  $('#obspro').val('');
}

function iniPreoperatorio(pos){
	//preopOriginal = null;
	resetFormPreope();
	acqQuiUsado = listaActQuiAbrBusq[pos];
	$.ajax({
	      url:   generarUrl('/private/obtenerPreoperatorio'),
	      type:  'GET',
	      data: {'idact': acqQuiUsado.id},
	      dataType: 'json',
	      success:  function (data) {
	          if(data && data.preoperatorioBBDD){
	        	  //preopOriginal = data.preoperatorioBBDD
	        	  $('#idpreop').val(data.preoperatorioBBDD.id);
	        	  $('#sexpre').val(data.preoperatorioBBDD.sexpre) ;
	        	  $('#edadpre').val(data.preoperatorioBBDD.edadpre);
	        	  $('#tapre').val(data.preoperatorioBBDD.edadpre);
	        	  $('#fcpre').val(data.preoperatorioBBDD.fcpre);
	        	  $('#pesopre').val(data.preoperatorioBBDD.pesopre);
	        	  $('#estaturapre').val(data.preoperatorioBBDD.estaturapre);
	        	  $('#imcpre').val(data.preoperatorioBBDD.imcpre);
	        	  $('#alerpro').val(data.preoperatorioBBDD.alerpro);
	        	  $('#intpro').val(data.preoperatorioBBDD.intpro);
	        	  $("#fecintpro").datepicker("update", fechaString(data.preoperatorioBBDD.fecintpro));
	        	  $("#habtoxTapro").prop('checked', data.preoperatorioBBDD.habtoxTapro == 'S'? true:false);
	        	  $("#habtoxAlpro").prop('checked', data.preoperatorioBBDD.habtoxAlpro == 'S'? true:false);
	        	  $('#habtoxOtpro').val(data.preoperatorioBBDD.habtoxOtpro);
	        	  $('#antpatpro').val(data.preoperatorioBBDD.antpatpro);
	        	  $('#antquipro').val(data.preoperatorioBBDD.antquipro);
	        	  $('#incipro').val(data.preoperatorioBBDD.incipro);
	        	  $('#fcapre').val(data.preoperatorioBBDD.fcapre);
	        	  $('#ecgpre').val(data.preoperatorioBBDD.ecgpre);
	        	  $('#frespre').val(data.preoperatorioBBDD.frespre);
	        	  $('#anapre').val(data.preoperatorioBBDD.anapre);
	        	  $('#opapre').val(data.preoperatorioBBDD.opapre);
	        	  $('#bocpre').val(data.preoperatorioBBDD.bocpre);
	        	  $('#perpre').val(data.preoperatorioBBDD.perpre);
	        	  $('#propre').val(data.preoperatorioBBDD.propre);
	        	  $('#apepre').val(data.preoperatorioBBDD.apepre);
	        	  $('#mcepre').val(data.preoperatorioBBDD.mcepre);
	        	  $('#svepre').val(data.preoperatorioBBDD.svepre);
	        	  $('#clupre').val(data.preoperatorioBBDD.clupre);
	        	  $('#obspro').val(data.preoperatorioBBDD.obspro);
	          }
	      }
	    });
		
}

function cerrarInfoPreoperatorio(){
	$('#infoPreope').modal("hide");
	obtenerActQuiAbiertos();
}

function guardarInfoPreoperatorio(){
	
	var param = {'idact': acqQuiUsado.id,
				'id' : $('#idpreop').val(),
				'sexpre' : $('#sexpre').val() ,
				'edadpre' : $('#edadpre').val(),
				'tapre' : $('#tapre').val(),
				'fcpre' :$('#fcpre').val() ,
				'pesopre' : $('#pesopre').val(),
				'estaturapre' : $('#estaturapre').val(),
				'imcpre' : $('#imcpre').val(),
				'alerpro' : $('#alerpro').val(),
				'intpro' : $('#intpro').val(),
				'fecintpro' : $('#fecintpro').val()?fechaStringEncode($('#fecintpro').val()):'',
				'habtoxTapro' : $('#habtoxTapro').is(":checked")?'S':'N',
				'habtoxAlpro' : $('#habtoxAlpro').is(":checked")?'S':'N',
				'habtoxOtpro' : $('#habtoxOtpro').val(),
				'antpatpro' : $('#antpatpro').val(),
				'antquipro' : $('#antquipro').val(),
				'incipro' : $('#incipro').val(),
				
				'fcapre' : $('#fcapre').val(),
				'ecgpre' : $('#ecgpre').val(),
				'frespre' : $('#frespre').val(),
				'anapre' : $('#anapre').val(),
				'opapre' : $('#opapre').val(),
				
				'bocpre' : $('#bocpre').val(),
				'perpre' : $('#perpre').val(),
				'propre' : $('#propre').val(),
				'apepre' : $('#apepre').val(),
				'mcepre' : $('#mcepre').val(),
				'svepre' : $('#svepre').val(),
				'clupre' : $('#clupre').val(),
				'obspro' : $('#obspro').val()
	};
	
	console.info(param);
	
	$.ajax({
	      url:   generarUrl('/private/guardarInfoPreoperatorio'),
	      type:  'GET',
	      data: param,
	      dataType: 'json',
	      success:  function (data) {
	          if(data && data.msgOk){
	        	  $('#idpreop').val(data.preope.id);
	        	  swal('', data.msgOk , "success");
	          }
	        	  
	      }
	    });
}
/*************************Preoperatorio*********************************/

function obtenerDatosUsuarioSession (){
	$.ajax({
      url:   generarUrl('/private/obtenerDatosUsuarioSession'),
      type:  'GET',
      dataType: 'json',
      success:  function (data) {
          if(data && data.usuario){
        	usuarioSes = data.usuario;
        	obtenerActQuiAbiertos();
          }
      }
    });
}
