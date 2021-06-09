$(document).ready(function(){
      
      setTimeout(
    		  function() 
    		  {
    			  $('[name="liMenuVisitas"]').addClass('active');
    			  obtenerDatosUsuarioSession();
    		  }, 500);
      
      $('#tablaVisitas').DataTable( {
          "order": [[ 5, "asc" ]]
      } );
    
});

var usuarioSes = null;
var lCObtenidas = null;
var visitaMod = null;
var paciente = null;


function modificarEstadoVisita(pos, newEst){
	$('.loader-wrap').removeClass("hide");
	
	visitaMod = lCObtenidas[pos];
	
	console.info(visitaMod);
	$.ajax({
		data :{	'id' : visitaMod.id,
				'codestado': newEst},
        url:   generarUrl('/private/modificarEstadoVisita'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	continuarVisita(pos);
        }
	});
	
}

function continuarVisita(pos){
	$('#gestionVisitas').modal('toggle');
	visitaMod = lCObtenidas[pos];
	$('#sNomApPac').html(visitaMod.nompac + ' ' + visitaMod.ap1pac + ' ' + visitaMod.ap2pac);
	$('#obsVisita').val('');
	console.info('');
	if( visitaMod.codesp =='RES'){
		paciente ={};
		paciente.id=visitaMod.idepac;
		prepararVentanaPrueCompl();
		$('#oPruCom').removeClass('hide');
	}else
		$('#oPruCom').addClass('hide');
}

function cancelarModVisita(){
	$('#gestionVisitas').modal('toggle');
	obtenerVisitasUsr();
}

function finalizarVisita(pos){
	console.info(pos);
	
	
	swal({
      	title: "Atención",
		text: 'Se finaliza no podrá realizar más cambios, ¿Desea Continuar?',
		type: "info",
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: 'Continuar',
		cancelButtonText: 'Cancelar',
		closeOnConfirm: true
    },
    function(isConfirm) {
    	if (isConfirm) {
    		if(pos || pos == 0){
    			visitaMod = lCObtenidas[pos];
    			$('#obsVisita').val('');		
    		}
    		
    		$.ajax({
    			data :{	'id' : visitaMod.id,
    					'obs': $('#obsVisita').val()},
    	        url:   generarUrl('/private/finalizarVisita'),
    	        type:  'GET',
    	        dataType: 'json',
    	        success:  function (data) {
    	        	$('.loader-wrap').addClass("hide");
    	        	if(data && data.msgOk){
    	        		if(!pos && pos != 0)
    	            		$('#gestionVisitas').modal('toggle');
    	            	swal("Correcto", data.msgOk, "success");
    	            	obtenerVisitasUsr();
    	        	}        	
    	        }
    		});
    	}
    });
		
}

function obtenerVisitasUsr(){
	visitaMod = null;
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		data :{'idusr' : usuarioSes.id},
        url:   generarUrl('/private/obtenerVisitasUsr'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	var tCit = $('#tablaVisitas').DataTable();
        	tCit.clear().draw();
        	
            if(data && data.listaVisitas){
            	lCObtenidas = data.listaVisitas;
            	for(var i = 0; i < data.listaVisitas.length; i++){
        			
        			visitaAct =  data.listaVisitas[i];
        			
        			
					var hours = Math.floor( visitaAct.hora / 60 );  
					var minutes =  (visitaAct.hora % 60);
					
					if(hours<10) hours = '0'+hours;
					if(minutes<10) minutes = '0'+minutes;
					 var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
					var estVisita = 'Sin Estado';
					if(visitaAct.codestado){
						
						if(visitaAct.codestado == 'PLN'){
							estVisita = '<span class="label  label-info">'+visitaAct.desval+'</span>';
							listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Forzar Inicio visita"><button type="button" onclick="modificarEstadoVisita(\''+i+'\',\'ABR\')" class="btn btn-warning btn-icon waves-effect waves-light" ><i class="icon wb-heart " aria-hidden="true"></i></button></span>';
						}
							
						if(visitaAct.codestado == 'CAN')
							estVisita = '<span class="label  label-danger">'+visitaAct.desval+'</span>';
						if(visitaAct.codestado == 'ESP'){
							estVisita = '<span class="label  label-warning">'+visitaAct.desval+'</span>';
							listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Iniciar visita"><button type="button" onclick="modificarEstadoVisita(\''+i+'\',\'ABR\')" class="btn btn-success btn-icon waves-effect waves-light" ><i class="icon wb-heart " aria-hidden="true"></i></button></span>';
						}
							
						if(visitaAct.codestado == 'ABR'){
							estVisita = '<span class="label  label-success">'+visitaAct.desval+'</span>';
							listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Continuar visita"><button type="button" onclick="continuarVisita(\''+i+'\')" class="btn btn-success btn-icon waves-effect waves-light" ><i class="icon wb-layout " aria-hidden="true"></i></button></span>';
						}
						
						if(visitaAct.codestado == 'ABR' || visitaAct.codestado == 'ESP' || visitaAct.codestado == 'PLN')
							listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Finilizar visita"><button type="button" onclick="finalizarVisita('+i+')" class="btn btn-warning btn-icon waves-effect waves-light" ><i class="icon wb-thumb-up " aria-hidden="true"></i> Finalizar</button></span>';
							
						
						if(visitaAct.codestado == 'FIN')
							estVisita = '<span class="label  label-primary">'+visitaAct.desval+'</span>';
						
					}
					 listaBtn = listaBtn + '</div>';
        			
        			tCit.row.add( [
                        visitaAct.nompac ,
                        visitaAct.ap1pac + ' ' + visitaAct.ap2pac,
						fechaString(visitaAct.fecnacpac),
						visitaAct.nomseguro,
                        visitaAct.obs,
                        hours+':'+minutes,
                        estVisita,
                        listaBtn
                        
                    ] ).draw( false );
                } 
        		$('[data-toggle="tooltip"]').tooltip();
            }
            
        }
      });
}

function obtenerDatosUsuarioSession (){
		$.ajax({
          url:   './obtenerDatosUsuarioSession',
          type:  'GET',
          dataType: 'json',
          success:  function (data) {
              if(data && data.usuario){
            	usuarioSes = data.usuario;
            	obtenerVisitasUsr();
              }
          }
        });
}


