$(document).ready(function(){
	
      setTimeout(
    		  function() 
    		  {
    			  $('[name="liConfAdm"]').addClass('active');
    			  $('#dFest').datepicker({
    				  weekStart:1
    				})
    		  }, 500);
});
var listaEspAct = null;
var espActualizar = null;
var segurosActivos = null;
var segActualizar = null;

function eliminarEsp(orden){
	espActualizar = listaEspAct[orden];
	console.info(espActualizar);
	$('.loader-wrap').removeClass("hide");
	$.ajax({
        url:   generarUrl('/private/eliminarEspecialidad'),
        data:{'id' : espActualizar.id, 'codesp' : espActualizar.codesp},
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
           if(data && data.msgOk){
        	   obtenerEspecialidades();
        	   swal('Correcto', data.msgOk , "success"); 
           }
           if(data && data.msgErr)
        	   swal('Error', data.msgErr , "error"); 
        }
      });
}

function obtenerEspecialidades(){
	$('.loader-wrap').removeClass("hide");
	listaEspAct = null;
	espActualizar = null;
	
	var tEsp = $('#tablaCitasEspe').DataTable();
	tEsp.clear().draw();
	$.ajax({
	        url:   generarUrl('/private/obtenerEspecialidades'),
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	
	        	if(data && data.listEspec){
	        		listaEspAct = data.listEspec;
	        		for(var i = 0; i < data.listEspec.length; i++){
	        			var listaBtn = '<div class="btn-group" aria-label="Acciones Usuario" role="group">';
	        			listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Modificar"><button type="button" onclick="insertarModidicarEsp(\'M\', \''+i+'\')" class="btn btn-icon btn-flat btn-default" data-target="#gestionCitas" data-toggle="modal"><i class="icon wb-pencil" aria-hidden="true"></i></button></span>';
	        			listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Eliminar"><button type="button" onclick="eliminarEsp(\''+i+'\')" class="btn btn-icon btn-flat btn-default"><i class="icon wb-trash" aria-hidden="true"></i></button></span>';
	        			listaBtn = listaBtn + '</div>'
	        			tEsp.row.add( [
                            data.listEspec[i].especialidad ,
                            listaBtn
                            
                        ] ).draw( false );
	        		}
	        		$('[data-toggle="tooltip"]').tooltip();     

	        		$('.loader-wrap').addClass("hide");
	        	}
	        }
	});
}

function insertarModidicarEsp(action, orden){
	if(action == 'I')
		$('.titEsp').html('Insertar');
	else{
		$('.titEsp').html('Modificar');
		espActualizar = listaEspAct[orden];
		console.info(listaEspAct[orden]);
		$('#nompacEsp').val( listaEspAct[orden].especialidad);
	}
	
	$('#admEsp').removeClass('hide');
	
	$('#insertarModidicarEsp').addClass('hide');
	$('#guardarEsp').removeClass('hide');
	$('#cancelarModidicarEsp').removeClass('hide');
	
}

function cancelarModidicarEsp(){
	$('#admEsp').addClass('hide');
	
	$('#insertarModidicarEsp').removeClass('hide');
	$('#guardarEsp').addClass('hide');
	$('#cancelarModidicarEsp').addClass('hide');
	$('#nompacEsp').val('');
}
function guardarEspecialidad(vId){
	$('.loader-wrap').removeClass("hide");
	$.ajax({
        url:   generarUrl('/private/guardarEspecialidad'),
        data:{'especialidad': $('#nompacEsp').val(), 'id' : vId},
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
           if(data && data.msgOk){
        	   obtenerEspecialidades();
        	   cancelarModidicarEsp();
        	   swal('Cambio realizado', data.msgOk , "success"); 
           }
           if(data && data.msgErr)
        	   swal('Error', data.msgErr , "error"); 
        }
      });
}

function guardarEsp(){
	if(!$('#nompacEsp').val()){
		swal('Error', 'Introduce una especialidad', "error");
		return false;
	}
	if(espActualizar){
		swal({
	      	title: "Alerta",
			text: 'Se va a modificar la especialidad ' + espActualizar.especialidad.toUpperCase() + ' por '+ $('#nompacEsp').val().toUpperCase() +'.¿Desea Continuar?',
			type: "warning",
			showCancelButton: false,
			confirmButtonClass: 'btn-success',
			confirmButtonText: 'Continuar',
			cancelButtonText: 'Cancelar',
	        cancelButtonClass: 'btn-default',
	        closeOnCancel: true,
	        showCancelButton: true,
			closeOnConfirm: false
	    },
	    function(isConfirm) {
	    	if (isConfirm) {
	    		guardarEspecialidad(espActualizar.id);
	    	}
	    });
	}else
		guardarEspecialidad();
}

function obtenerSeguros(){
	$('.loader-wrap').removeClass("hide");
	segurosActivos = null;
	segActualizar = null;
	
	var tSeg = $('#tablaSeguro').DataTable();
	tSeg.clear().draw();
	$.ajax({
	        url:   generarUrl('/private/obtenerSegurosAdm'),
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	
	        	if(data && data.lisSeguros){
	        		segurosActivos= data.lisSeguros;
	        		for(var i = 0; i < data.lisSeguros.length; i++){
	        			var listaBtn = '<div class="btn-group" aria-label="Acciones Usuario" role="group">';
	        			listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Modificar"><button type="button" onclick="insertarModidicarSeg(\'M\', \''+i+'\')" class="btn btn-icon btn-flat btn-default" data-target="#gestionCitas" data-toggle="modal"><i class="icon wb-pencil" aria-hidden="true"></i></button></span>';
	        			listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Eliminar"><button type="button" onclick="eliminarSeg(\''+i+'\')" class="btn btn-icon btn-flat btn-default"><i class="icon wb-trash" aria-hidden="true"></i></button></span>';
	        			listaBtn = listaBtn + '</div>'
	        			tSeg.row.add( [
                            data.lisSeguros[i].nomseguro ,
                            listaBtn
                            
                        ] ).draw( false );
	        		}
	        		$('[data-toggle="tooltip"]').tooltip();     

	        		$('.loader-wrap').addClass("hide");
	        	}
	        }
	});
}

function eliminarSeg(orden){
	
	segActualizar = segurosActivos[orden];

	swal({
      	title: "Alerta",
		text: 'Se va a Eliminar el seguro ' + segActualizar.nomseguro.toUpperCase() + ' con lo que los pacientes que lo tengan asociado se les eliminara el seguro.¿Desea Continuar?',
		type: "warning",
		showCancelButton: false,
		confirmButtonClass: 'btn-success',
		confirmButtonText: 'Continuar',
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
    	        url:   generarUrl('/private/eliminarSeguro'),
    	        data:{'id' : segActualizar.id},
    	        type:  'GET',
    	        dataType: 'json',
    	        success:  function (data) {
    	        	$('.loader-wrap').addClass("hide");
    	           if(data && data.msgOk){
    	        	   obtenerSeguros();
    	        	   swal('Correcto', data.msgOk , "success"); 
    	           }
    	        }
    	      });
    	}
    });

}

function insertarModidicarSeg(action, orden){
	if(action == 'I')
		$('.titSeg').html('Insertar');
	else{
		$('.titSeg').html('Modificar');
		segActualizar = segurosActivos[orden];
		console.info(segurosActivos[orden]);
		$('#nomSeguro').val( segurosActivos[orden].nomseguro);
	}
	
	$('#admSeg').removeClass('hide');
	
	$('#insertarModidicarSeg').addClass('hide');
	$('#guardarSeg').removeClass('hide');
	$('#cancelarModidicarSeg').removeClass('hide');
	
}

function cancelarModidicarSeg(){
	$('#admSeg').addClass('hide');
	
	$('#insertarModidicarSeg').removeClass('hide');
	$('#guardarSeg').addClass('hide');
	$('#cancelarModidicarSeg').addClass('hide');
	$('#nomSeguro').val('');
}

function guardarSeguro(vId){
	$('.loader-wrap').removeClass("hide");
	$.ajax({
        url:   generarUrl('/private/guardarSeguro'),
        data:{'nomseguro': $('#nomSeguro').val(), 'id' : vId},
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
           if(data && data.msgOk){
        	   obtenerSeguros();
        	   cancelarModidicarSeg();
        	   swal('Cambio realizado', data.msgOk , "success"); 
           }
           if(data && data.msgErr)
        	   swal('Error', data.msgErr , "error"); 
        }
      });
}

function guardarSeg(){
	if(!$('#nomSeguro').val()){
		swal('Error', 'Introduce una especialidad', "error");
		return false;
	}
	if(segActualizar){
		swal({
	      	title: "Alerta",
			text: 'Se va a modificar el seguro ' + segActualizar.nomseguro.toUpperCase() + ' por '+ $('#nomSeguro').val().toUpperCase() +'.¿Desea Continuar?',
			type: "warning",
			showCancelButton: false,
			confirmButtonClass: 'btn-success',
			confirmButtonText: 'Continuar',
			cancelButtonText: 'Cancelar',
	        cancelButtonClass: 'btn-default',
	        closeOnCancel: true,
	        showCancelButton: true,
			closeOnConfirm: false
	    },
	    function(isConfirm) {
	    	if (isConfirm) {
	    		guardarSeguro(segActualizar.id);
	    	}
	    });
	}else
		guardarSeguro();
}

/*********** Gestion Vacaciones ************/
$('#dFest').on('changeDate', function(ev){
    $(this).datepicker('hide');
});

function obtenerFestivosAnualInit(){
	var y = new Date().getFullYear();
	$('#anyAc').html(y);
	$('#anySeg').html(parseInt(y) + 1);
	obtenerFestivosAnual();
}
function eliminarFestivo(id, t){
	swal({
      	title: "Alerta",
		text: '¿Desea eliminar el registro?',
		type: "warning",
		showCancelButton: false,
		confirmButtonClass: 'btn-success',
		confirmButtonText: 'Continuar',
		cancelButtonText: 'Cancelar',
        cancelButtonClass: 'btn-default',
        closeOnCancel: true,
        showCancelButton: true,
		closeOnConfirm: false
    },
    function(isConfirm) {
    	if (isConfirm) {
    		$.ajax({
    	          url:  generarUrl('/private/eliminarFestivo'),
    	          data:{'id':id},
    	          type:  'GET',
    	          dataType: 'json',
    	          success:  function (data) {
    	              if(data && data.msgOk){
    	            	  swal('Cambio realizado', data.msgOk , "success");
    	            	  obtenerFestivosAnual(t);
    	              }
    	          }
    	        });
    	}
    });
}

function obtenerFestivosAnual(t){
	$('.loader-wrap').removeClass("hide");
	var y = new Date().getFullYear();
	if(t == 'SIG'){
		y = parseInt(y) + 1;
		$('#anyAc').removeClass('btn-info');
		$('#anyAc').addClass('btn-default');
		$('#anySeg').removeClass('btn-default');
		$('#anySeg').addClass('btn-info');
	}else{
		$('#anyAc').addClass('btn-info');
		$('#anyAc').removeClass('btn-default');
		$('#anySeg').addClass('btn-default');
		$('#anySeg').removeClass('btn-info');
	}
		
	var tFest = $('#tablaFest').DataTable();
	tFest.clear().draw();
	$.ajax({
        url:  generarUrl('/private/obtenerFestivosAnual'),
        data:{ 'anyoFest': y},
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	if(data && data.listFestivos){
        		for(var i = 0; i < data.listFestivos.length; i++){
        			var listaBtn = '<div class="btn-group" aria-label="Acciones Usuario" role="group">';
        			listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Eliminar"><button type="button" onclick="eliminarFestivo(\''+ data.listFestivos[i].id+'\', \''+ t +'\')" class="btn btn-icon btn-flat btn-default"><i class="icon wb-trash" aria-hidden="true"></i></button></span>';        			listaBtn = listaBtn + '</div>'
        			tFest.row.add( [
                        fechaString(data.listFestivos[i].fecha) ,
                        listaBtn
                        
                    ] ).draw( false );
        		}
        		$('[data-toggle="tooltip"]').tooltip();     

        	}
        	$('.loader-wrap').addClass("hide");
        
        	       
        }
      });
}

function insertarModidicarFest(action){
	if(action == 'I')
		$('.titFest').html('Insertar');

	$('#admFest').removeClass('hide');
	$('#insertarModidicarFest').addClass('hide');
	$('#guardarFest').removeClass('hide');
	$('#cancelarModidicarFest').removeClass('hide');
	
}

function cancelarModidicarSeg(){
	$('#admFest').addClass('hide');
	$('#insertarModidicarFest').removeClass('hide');
	$('#guardarFest').addClass('hide');
	$('#cancelarModidicarFest').addClass('hide');
	$('#dFest').val('');
}

function guardarFest(){

	if(!$('#dFest').val())
		swal('Error', data.msgErr , "error");
	else{
		$('.loader-wrap').removeClass("hide");

		$.ajax({
	          url:  generarUrl('/private/guardarFestivo'),
	          type:  'GET',     
	          data:{'fecha': fechaStringEncode($('#dFest').val())},
	          dataType: 'json',
	          success:  function (data) {
	              if(data && data.msgOk){
	            	  cancelarModidicarSeg();
	            	  obtenerFestivosAnual();
	            	  swal('Cambio realizado', data.msgOk , "success");
	              }
	          }
	        });
		$('.loader-wrap').addClass("hide");

	}
	
}
/***********FIN Gestion Vacaciones ************/

/********** GETION CIE *******************/

function gestionCieInit(){
	$('#tablaCie').DataTable().clear().draw();
}

function buscarCieByClaseDesc(){
	var tCie = $('#tablaCie').DataTable();
	tCie.clear().draw();
	var claseCie = $('#claseCieBusq').val().replace(/\s+/g, '');
	if(claseCie == '.'){
		swal('Error', 'Debe aplicar un elemento de filtro' , "error"); 
		return false;
	}
	if(claseCie.indexOf(".") <= 3 && claseCie.length == 4){
		claseCie = claseCie.substring(0, claseCie.indexOf("."));
	}
	$('.loader-wrap').removeClass("hide");
	
	$.ajax({
	      url:  generarUrl('/private/buscarCieByClaseDesc'),
	      type:  'GET',
	      data:{'clase': claseCie.toUpperCase()},
	      dataType: 'json',
	      success:  function (data) {
	    	  $('.loader-wrap').addClass("hide");
	          if(data && data.listaCie){
	        	  for(var i = 0; i < data.listaCie.length; i++){
	        		  tCie.row.add( [
	        	                     data.listaCie[i].clase ,
	        	                     data.listaCie[i].descie
	        	                     
	        	                 ] ).draw( false );  
	        	  }
	          }
	      }
	    });
	console.info(claseCie);
}

function prepararAddCie(){
	$('#admCie').removeClass('hide');
	$('#claseCieAdd').val('');
	$('#addCieBtn').addClass('hide');
	
}

function cancelarAddCie(){
	$('#admCie').addClass('hide');
	$('#claseCieAdd').val('');
	$('#addCieBtn').removeClass('hide');
	
}

function guardarCie(){
	var claseCie = $('#claseCieAdd').val().replace(/\s+/g, '');
	if(!claseCie || claseCie == '.' || !$('#desCieAdd').val()){
		swal('Error', 'Todos los campos son obligatorios' , "error"); 
		return false;
	}
	if(claseCie.indexOf(".") < 3 ){
		swal('Error', 'La clase debe tener como mínimo una longitud de 3 caracteres' , "error"); 
		return false;
	}
	if(claseCie.indexOf(".") == 3 && claseCie.length == 4){
		claseCie = claseCie.substring(0, claseCie.indexOf("."));
	}
	
	$.ajax({
	      url:  generarUrl('/private/guardarCie'),
	      type:  'GET',
	      data: {
	    	  'clase': claseCie.toUpperCase(),
	    	  'descie' : $('#desCieAdd').val()
	      },
	      dataType: 'json',
	      success:  function (data) {
	          if(data && data.msgOk){
	        	  swal('Correcto', data.msgOk , "success");
	        	  cancelarAddCie();
	          }else if(data && data.msgErr){
	        	  swal('Error', data.msgErr , "error");
	          }
	      }
	    });
}



/**********FIN GETION CIE *******************/

function obtenerDatosUsuarioSession (){
	$.ajax({
      url:  generarUrl('/private/obtenerDatosUsuarioSession'),
      type:  'GET',
      dataType: 'json',
      success:  function (data) {
          if(data && data.usuario){
          }
      }
    });
}
obtenerDatosUsuarioSession();
