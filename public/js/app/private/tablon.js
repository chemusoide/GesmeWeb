$(document).ready(function(){
      
      setTimeout(
    		  function() 
    		  {
    			  $('[name="liMenuTabEnf"]').addClass('active');
    		  }, 500);
    
});

var usuarioSes = null;
var pacientesActuales = null;
var combosCargados = false

function fechaString(date){
	year = date.substring(0, 4);
    mes = date.substring(5, 7);
    dia = date.substring(8, 10);
    if(year.indexOf("/") >= 0 || mes.indexOf("/") >= 0 || dia.indexOf("/") >= 0  )
    	return null;
    return dia + '/'+ mes + '/' + year;
}

function valoresDefaultoCombos (){
	$('#sIdpais').append(
			$('<option>', {
		    value: '',
		    text: 'Paises'
		}));
	$('#sIdseguro').append(
			$('<option>', {
		    value: '',
		    text: 'Seguros'
		}));
}

function limpiarForm(){
	$('#id').val('');
	$('#nompac').val('');
	$('#ap1pac').val('');
	$('#ap2pac').val('');
	$('#fecnacpac').val('');
	$('#sexpac').val('');
	$('#numtel1').val('');
	$('#numtel2').val('');
	$('#dniusr').val('');
	$('#emailpac').val('');
	$('#dirpac').val('');
	$('#cppac').val('');
	$('#sIdpais').val('');
	$('#sIdseguro').val('');
	$('#numseg').val('');
	$('#comentario').val('');
	
	$("#gestionPacientes").modal("toggle");
}


function initHistPac(idPacFunc){
	window.location.href = '/private/historial-enfermeria/'+idPacFunc;
} 

function initPantallaAltaModPacientes (){
	$('.loader-wrap').removeClass("hide");
	$.ajax({
	        url:   '/private/initPantallaAltaModPacientes',
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	if(data){
	        		if(!combosCargados){
	        			combosCargados = true;
	        			valoresDefaultoCombos();
		            	if(data.listaPaises){
		            		for(var i = 0; i < data.listaPaises.length; i++){
								$('#sIdpais').append(
									$('<option>', {
								    value: data.listaPaises[i].id,
								    text: data.listaPaises[i].nompais
								}));
		            		}
		            		
		            	}
	        		}
	        		if(data.listaSeguros){
	            		for(var i = 0; i < data.listaSeguros.length; i++){
							$('#sIdseguro').append(
								$('<option>', {
							    value: data.listaSeguros[i].id,
							    text: data.listaSeguros[i].nomseguro
							}));
	            		}
	            		
	            	}
	            }
	        }
	}); 
}

function obtenerPacienteSelect(id){
	for(var i = 0; i < pacientesActuales.length; i++){
		if(id == pacientesActuales[i].id){
			pacMod = pacientesActuales[i];
			break;
		}
		
	}
}

function perfilPaciente(id){
	pacMod = null;
	if(!combosCargados){
		initPantallaAltaModPacientes();
	}
	
	if(id && pacientesActuales){
		
		obtenerPacienteSelect(id);
		
		if(pacMod){
			$('#id').val(pacMod.id);
			$('#nompac').val(pacMod.nompac);
			$('#ap1pac').val(pacMod.ap1pac);
			$('#ap2pac').val(pacMod.ap2pac);
			if(pacMod.fecnacpac){
				$('#fecnacpac').val(fechaString(pacMod.fecnacpac));
			}
			$('#sexpac').val(pacMod.sexpac);
			$('#numtel1').val(pacMod.numtel1);
			$('#numtel2').val(pacMod.numtel2);
			$('#dniusr').val(pacMod.dniusr);
			$('#emailpac').val(pacMod.emailpac);
			$('#dirpac').val(pacMod.dirpac);
			$('#cppac').val(pacMod.cppac);
			$('#sIdpais').val(pacMod.idpais);
			$('#sIdseguro').val(pacMod.idseguro);
			$('#numseg').val(pacMod.numseg);
			$('#comentario').val(pacMod.comentario);
		}
	}
	
}

function accesoListadoPacientes(){
	$.ajax({
		data :{'idmed' : usuarioSes.id,
			   'swienfermera' : 'S'},
        url:   '/private/accesoListadoPacientes',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
            
            var tPac = $('#tablaPac').DataTable();
            tPac.clear().draw();
            pacientesActuales = null;
        	
            if(data && data.pacientes){
            	pacientesActuales = data.pacientes;
            	for(var i = 0; i < data.pacientes.length; i++){
            		var pacAct =  data.pacientes[i];
        			
            		var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
            		listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Datos Paciente"><button type="button" onclick="perfilPaciente(\''+pacAct.id+'\')" class="btn btn-icon btn-flat btn-default" data-target="#gestionPacientes" data-toggle="modal"><i class="icon wb-edit" aria-hidden="true"></i></button></span>';
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

function obtenerDatosUsuarioSession (){
		$.ajax({
          url:   '/private/obtenerDatosUsuarioSession',
          type:  'GET',
          dataType: 'json',
          success:  function (data) {
              if(data && data.usuario){
            	usuarioSes = data.usuario;
            	accesoListadoPacientes();
              }
          }
        });
}

obtenerDatosUsuarioSession();
