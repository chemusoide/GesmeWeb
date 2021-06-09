$(document).ready(function($) {
  $("#confirmPassword").keyup(checkPasswordMatch);

   $("#newPassword").keyup(checkPasswordMatch);
   $("#btnCamPass").prop('disabled', true);
   
   $('#tablaCitas').DataTable( {
       "order": [[ 3, "asc" ]]
   } );
});

var usuarioSes = null;
var plantillasBusqueda = null;

function checkPasswordMatch() {
  var password = $("#newPassword").val();
  var confirmPassword = $("#confirmPassword").val();

  if (password != confirmPassword){
  	$("#helpNewPass").html("Las contraseñas no coinciden");
  	$("#btnCamPass").prop('disabled', true);
  }else{
  	 $("#helpNewPass").html("");
  	 $("#btnCamPass").prop('disabled', false);
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
        		if(newEst == 'ABR')
        			continuarCita(idCita);
        		else
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

function continuarCita(idCita){
	window.location.href = '/private/gestion_cita/'+idCita;
}

 function obtenerDatosUsuarioSession (){
		$.ajax({
          url:   '/private/obtenerDatosUsuarioSession',
          type:  'GET',
          dataType: 'json',
          success:  function (data) {

              if(data && data.usuario){
	            //FORM 
	            $('#formId').val(data.usuario.id);
	            $('#nomusr').val(data.usuario.nomusr);
	            $('#apusr').val(data.usuario.apusr);
	            $('#emailusr').val(data.usuario.emailusr);
	            $('#dniusr').val(data.usuario.dniusr);
	            $('#numtel1').val(data.usuario.numtel1);
	            $('#numtel2').val(data.usuario.numtel2);
	            $('#colegiado').val(data.usuario.numcoleg);
	            
	            if(!data.usuario.numcoleg){
	            	$('#divCol').addClass('hide');
	            }else{
					obtenerCitasUsr(data.usuario);
	            	obtenerMisPacientes(data.usuario)
	            }
	            	
	            	 
	            $('#contenerdor').removeClass('hide');
				usuarioSes = data.usuario;
                return data.usuario.nomusr;
              }
          }
        });
}
 
function obtenerCitasUsr(usr){
	$.ajax({
		data :{'idmed' : usr.id},
        url:   '/private/obtenerCitasUsr',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {

        	var tCit = $('#tablaCitas').DataTable();
        	tCit.clear().draw();
        	
            if(data && data.citas){
            	for(var i = 0; i < data.citas.length; i++){
        			
        			citaAct =  data.citas[i];
        			
        			
					var hours = Math.floor( citaAct.hora / 60 );  
					var minutes =  (citaAct.hora % 60);
					
					if(hours<10) hours = '0'+hours;
					if(minutes<10) minutes = '0'+minutes;
					 var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
					var estCita = 'Sin Estado';
					if(data.citas[i].codestado){
						
						if(data.citas[i].codestado == 'PLN'){
							estCita = '<span class="label  label-info">'+data.citas[i].desval+'</span>';
						}
							
						if(data.citas[i].codestado == 'CAN')
							estCita = '<span class="label  label-danger">'+data.citas[i].desval+'</span>';
						if(data.citas[i].codestado == 'ESP'){
							estCita = '<span class="label  label-warning">'+data.citas[i].desval+'</span>';
							listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Iniciar Consulta"><button type="button" onclick="modificarEstadoCita(\''+data.citas[i].id+'\',\'ABR\')" class="btn btn-success btn-icon waves-effect waves-light" ><i class="icon wb-heart " aria-hidden="true"></i></button></span>';
						}
							
						if(data.citas[i].codestado == 'ABR'){
							estCita = '<span class="label  label-success">'+data.citas[i].desval+'</span>';
							listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Continuar Consulta"><button type="button" onclick="continuarCita(\''+data.citas[i].id+'\')" class="btn btn-success btn-icon waves-effect waves-light" ><i class="icon wb-layout " aria-hidden="true"></i></button></span>';
						}
							
						
						if(data.citas[i].codestado == 'FIN')
							estCita = '<span class="label  label-primary">'+data.citas[i].desval+'</span>';
						
					}
					 listaBtn = listaBtn + '</div>';
        			
        			tCit.row.add( [
                        citaAct.nompac ,
                        citaAct.ap1pac,
                        citaAct.ap2pac,
                        hours+':'+minutes,
                        estCita,
                        listaBtn
                        
                    ] ).draw( false );
                } 
        		$('[data-toggle="tooltip"]').tooltip();
            }
            
            var tHistCit = $('#tablaHistCitas').DataTable();
            tHistCit.clear().draw();
        	
            if(data && data.histCitas){
            	for(var i = 0; i < data.histCitas.length; i++){
        			
        			citaAct =  data.histCitas[i];
        			
					 tHistCit.row.add( [
                        citaAct.nompac ,
                        citaAct.ap1pac,
                        citaAct.ap2pac,
                        fechaString(citaAct.feccita)
                        
                    ] ).draw( false );
                } 
        		$('[data-toggle="tooltip"]').tooltip();
            }
            
            
            
        }
      });
}

function initHistPac(idPacFunc){
	window.location.href = '/private/historial/'+idPacFunc;
}


function obtenerMisPacientes(usr){
	$.ajax({
		data :{'idmed' : usr.id},
        url:   '/private/obtenerMisPacientes',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
            
            var tPac = $('#tablaPac').DataTable();
            tPac.clear().draw();
        	
            if(data && data.pacientes){
            	for(var i = 0; i < data.pacientes.length; i++){
            		var pacAct =  data.pacientes[i];
        			
            		var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
 					listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Historial"><button type="button" onclick="initHistPac(\''+pacAct.id+'\')" class="btn btn-success btn-icon waves-effect waves-light" ><i class="icon wb-folder " aria-hidden="true"></i></button></span>';
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

function actualizarPass(){
	if(!$("#newPassword").val() || !$("#oldPassword").val()){
	      swal('Error', 'Faltan campos por rellenar', "warning");
				return false;
	}else if($("#newPassword").val() == $("#oldPassword").val()){
		swal('Error', 'No se puede reutilizar la contraseña', "warning");
		return false;
	}

	 		var parametros = {
	 			 'id': $('#formId').val() 
				,'newPassword':$("#newPassword").val()
				,'oldPassword':$('#oldPassword').val()
	            

	 		};
	 		console.info(parametros);
	 		
			$.ajax({
			  data:  parametros,
	          url:   '/private/actualizarPass',
	          type:  'GET',
	          dataType: 'json',
	          success:  function (data) {

	              if(data && data.msgOk){
	                swal('Estupendo!', 'Contraseña cambiada', "success");
	                $("#newPassword").val('');
	                $("#oldPassword").val('');
	                $("#confirmPassword").val('');
	                obtenerDatosUsuarioSession();
	              }else if(data && data.msgError){
	                swal('Ups! Algo va mal', data.msgError, "error");
	              }
	          }
	        });
}

function verTodosLosPacientes(){
	$('#modalVerTodos').modal('toggle');
	$('#divShowDatosPac').addClass("hide");
	$('#divShowAviso').removeClass("hide");
	$('#comentario').val('');
}

function accesoListadoPacientes(){
	if(!$('#comentario').val()){
		swal('Error!', 'Debe especificar el motivo por el cual quiere acceder a esta información', "error");
		return false;
	}
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		data :{'idmed' : usuarioSes.id,
			   'comentario': $('#comentario').val()},
        url:   '/private/accesoListadoPacientes',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
			$('.loader-wrap').addClass("hide");
			$('#divShowDatosPac').removeClass("hide");
			$('#divShowAviso').addClass("hide");
            
            var tPac = $('#tablaPacEsp').DataTable();
            tPac.clear().draw();
        	
            if(data && data.pacientes){
            	for(var i = 0; i < data.pacientes.length; i++){
            		var pacAct =  data.pacientes[i];
        			
            		var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
 					listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Historial"><button type="button" onclick="initHistPac(\''+pacAct.id+'\')" class="btn btn-success btn-icon waves-effect waves-light" ><i class="icon wb-folder " aria-hidden="true"></i></button></span>';
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

/************ Mis Configuraciones *************/

function mostrarDivNuevo(divMostrar){
	
	$('.'+divMostrar).removeClass('hide');
	$('.b'+divMostrar).addClass('hide');
	
}

function ocultarDivNuevo(divMostrar){
	
	$('.'+divMostrar).addClass('hide');
	$('.b'+divMostrar).removeClass('hide');
	$('.'+divMostrar+'Form').val('');
	$('.'+divMostrar+'Id').val('');
	
}
//Plantillas

function initModPlantilla(orden){
	console.info(plantillasBusqueda[orden]);
	mostrarDivNuevo('divContPlan');
	$('#titPlantilla').val(plantillasBusqueda[orden].tituloplantilla);
	$('#txtplantilla').val(plantillasBusqueda[orden].txtplantilla);
	$('#idPlantilla').val(plantillasBusqueda[orden].id);
}

function eliminarPlantilla(orden){
	swal({
	      	title: "Alerta",
			text: 'Se eliminará la Plantilla.¿Desea Continuar?',
			type: "warning",
			showCancelButton: false,
			confirmButtonClass: 'btn-danger',
			confirmButtonText: 'Eliminar',
			cancelButtonText: 'Cancelar',
	        cancelButtonClass: 'btn-default',
	        closeOnCancel: true,
	        showCancelButton: true,
			closeOnConfirm: true
	    },
	    function(isConfirm) {
	    	if (isConfirm) {
				$.ajax({
				data:  {'id': plantillasBusqueda[orden].id,
						'idmed': usuarioSes.id},
				url:   '/private/eliminarPlantillaUsuario',
				type:  'GET',
				dataType: 'json',
				success:  function (data) {
					plantillasBusqueda = null;
					if(data.listadoPlatillas)
						prepararTablaPlantillas(data.listadoPlatillas);
					}
				});
	    	}
	    });
}

function prepararTablaPlantillas(listPlantilla){
	plantillasBusqueda = null;
	var tPlantilla = $('#tablaPlantilla').DataTable();
	tPlantilla.clear().draw();
	
	if(listPlantilla){
		plantillasBusqueda = listPlantilla;
		for(var i = 0; i < listPlantilla.length; i++){
			var plantillaAct =  listPlantilla[i];
			
			var strTxtPlantilla = plantillaAct.txtplantilla;
			
			if(strTxtPlantilla.length > 150)
				strTxtPlantilla = strTxtPlantilla.substring(0, 150) + '...';
			
			var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
			listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Modificar"><button type="button" onclick="initModPlantilla(\''+i+'\')" class="btn btn-success btn-icon waves-effect waves-light" ><i class="icon wb-pencil " aria-hidden="true"></i></button></span>';
			listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Eliminar"><button type="button" onclick="eliminarPlantilla(\''+i+'\')" class="btn btn-warning btn-icon waves-effect waves-light" ><i class="icon wb-rubber " aria-hidden="true"></i></button></span>';
			listaBtn = listaBtn + '</div>';
			
			tPlantilla.row.add( [
				plantillaAct.tituloplantilla ,
				strTxtPlantilla,
				fechaString(plantillaAct.created_at),
				listaBtn
				
			] ).draw( false );
		} 
		$('[data-toggle="tooltip"]').tooltip();
	}
	
}


function prepararVentanaMisPlantillas(){
	
	var parametros = {
		'idmed' : usuarioSes.id
	};
	
	$.ajax({
		  data:  parametros,
          url:   '/private/listadoPlantillasUsuario',
          type:  'GET',
          dataType: 'json',
          success:  function (data) {
			console.info(data.listadoPlatillas);
			plantillasBusqueda = null;
			if(data.listadoPlatillas)
				prepararTablaPlantillas(data.listadoPlatillas);
          }
        });
}

function insertarModificarNuevaPlantilla(){
	
	
	if(!$('#txtplantilla').val() || !$('#titPlantilla').val()){
		swal('Campos Obligatorios', 'Todos los campos son obligatorios.', "error");
		return false;
	}
	
	var parametros = {
		'id' :  $('#idPlantilla').val(),
		'idmed' : usuarioSes.id,
		'tituloplantilla' : $('#titPlantilla').val(),
		'txtplantilla' : $('#txtplantilla').val()
	};
	
	$.ajax({
		  data:  parametros,
          url:   '/private/insertarModificarNuevaPlantilla',
          type:  'POST',
          dataType: 'json',
          success:  function (data) {

            if(data && data.msgOk){
				swal('Estupendo!', data.msgOk, "success");
				ocultarDivNuevo('divContPlan');
				if(data.listadoPlatillas)
				prepararTablaPlantillas(data.listadoPlatillas);
            }else if(data && data.msgErr){
				swal('Error', data.msgErr, "error");
            }
          }
        });
}

//Fin Plantillas

/************ FIN Mis Configuraciones *************/


function actualizarDatosUsuario(){
 		var parametros = {
 			 'id': $('#formId').val() 
			,'nomusr':$('#nomusr').val()
			,'apusr':$('#apusr').val()
            ,'emailusr':$('#emailusr').val()
            ,'dniusr':$('#dniusr').val()
            ,'numtel1':$('#numtel1').val()
            ,'numtel2':$('#numtel2').val()
            ,'numcoleg':$('#colegiado').val()

 		};
 		console.info(parametros);
 		
		$.ajax({
		  data:  parametros,
          url:   '/private/actualizarDatosUsuario',
          type:  'GET',
          dataType: 'json',
          success:  function (data) {

              if(data && data.msgOk){
                swal('Estupendo!', 'Datos actualizados', "success");
                obtenerDatosUsuarioSession();
              }else if(data && data.msgError){
                swal('Error', data.msgError, "error");
              }
          }
        });
}

obtenerDatosUsuarioSession();