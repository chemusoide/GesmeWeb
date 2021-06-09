$(document).ready(function(){
      
      setTimeout(
    		  function() 
    		  {
    			  $('[name="liMenuUsr"]').addClass('active');
    		  }, 500);
      
      $('.datepicker').datepicker({
  	    weekStart: 1,
  	    format: "dd/mm/yyyy",
  	    language: 'es',
  	    startDate: "today",
  	    autoclose: true

  	});
    
});
var usuariosActuales = null;
var configUsrActual = null;

var usuariosRegitradosTOT = 0;
var usuariosPendientesTOT = 0;

var usuarioSes = null;
var esUsrAdm = false;
var swiDurConsulta = false;

function aceptarUsuario(p){
    $('.loader-wrap').removeClass("hide");
	$.ajax({
            data:  {'id': p},
            url:   generarUrl('/private/aceptarUsuario'),
            type:  'GET',
            dataType: 'json',
            success:  function (data) {
            	if(data && data.usuario){
                    usuariosRegitradosTOT = usuariosRegitradosTOT+1;       
                    obtenerUsuarios();

            	}     
            }
    });
}

function readmitirUsuario(p){
     $('.loader-wrap').removeClass("hide");
    $.ajax({
            data:  {'id': p},
            url:   generarUrl('/private/readmitirUsuario'),
            type:  'GET',
            dataType: 'json',
            success:  function (data) {
                if(data && data.usuario){
                    usuariosRegitradosTOT = usuariosRegitradosTOT+1;      
                    obtenerUsuarios();
                }     
            }
    });
}

function bajaUsuario(p){
     $('.loader-wrap').removeClass("hide");
    $.ajax({
            data:  {'id': p},
            url:   generarUrl('/private/bajaUsuario'),
            type:  'GET',
            dataType: 'json',
            success:  function (data) {
                if(data && data.usuario){
                     obtenerUsuarios();
                }     
            }
    });
}

function actualizarUsuario(){
     $('.loader-wrap').removeClass("hide");
    parametros = {
          'id':  $('#idusrEdit').val(),
          'igm': $('#igmEdit').val(), 
          'diadisconectusr': $('#limPago').val(), 
          'rolusr': $('#perfilEdit input:radio:checked').val(),
          'horprefusr': $('#horPrefEdit input:radio:checked').val()  
    }

    $.ajax({
            data:  parametros,
            url:   generarUrl('/private/actualizarUsuario'),
            type:  'GET',
            dataType: 'json',
            success:  function (data) {
                if(data && data.msgOK){
                     obtenerUsuarios();
                     //alert(data.msgOK);
                     swal('Estupendo!', data.msgOK, "success");

                }     
            }
    });
}



function perfilUsuario(p){
	limpiarPrivate('MOD');
	$.ajax({
	        url:   generarUrl('/private/obtenerDatosInitAlta'),
	        type:  'GET',
	        data: {'id': p},
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	if(data){
	        		
	        	
	        		if(!tieneDatos){
	        			tieneDatos = true;
	        			if(data.roles){
		            		for(var i = 0; i < data.roles.length; i++){
								$('#roles').append(
									$('<option>', {
								    value: data.roles[i].codrol,
								    text: data.roles[i].nomrol
								}));
		            		}
		            		
		            	}
	        			if(listaEmpresa){
		            		for(var i = 0; i < listaEmpresa.length; i++){
								$('#empresas').append(
									$('<option>', {
								    value: listaEmpresa[i].id,
								    text: listaEmpresa[i].nombre
								}));
		            		}
		            		
		            	}
		            	if(data.especialidades){
		            		for(var i = 0; i < data.especialidades.length; i++){
								$('#especialidad').append(
									$('<option>', {
								    value: data.especialidades[i].codesp,
								    text: data.especialidades[i].especialidad
								}));
		            		}
		            		
		            	}
	        		}
	            	
	            	
	            	for(var i = 0; i < usuariosActuales.length; i++){
	        	        var usrActual = usuariosActuales[i];
	        	        if( p == usrActual.id){
	        	        	
	        	        	$('#nomusr').val(usrActual.nomusr);
	        				$('#apusr').val(usrActual.apusr);
	        				$('#dniusr').val(usrActual.dniusr);
	        				$('#tipdoc').val(usrActual.tipdoc);
	        				
	        				$('#numtel1').val(usrActual.numtel1);
	        				$('#numtel2').val(usrActual.numtel2);
	        				$('#emailusr').val(usrActual.emailusr);
	        				$('#id').val(usrActual.id);
	        				
	        				//'rolesSelec' :rolSelect,
	        				var selectedValues = new Array();
	        				
	        				if(usrActual.rolesusr){
	        					for(var j = 0; j < usrActual.rolesusr.length; j++ ){
	        						selectedValues[j] = usrActual.rolesusr[j].codrol;
	        						if(usrActual.rolesusr[j].codrol == 'MED' || usrActual.rolesusr[j].codrol == 'FIS' || usrActual.rolesusr[j].codrol == 'OTE'){
        								$('#fgColeg').removeClass("hide");
        								$('#fgEsp').removeClass("hide");
	        						}
	        					}
	        				}
	        				$("#sRoles").select2('val', selectedValues);
	        				
	        				
	        				$('#colegiado').val(usrActual.numcoleg);
	        				//'espeSelect' : espSelect,
	        				//'esPrivate' : esPrivate           
	        	            
	        	            break;
	        	        }
	        	    }
	            	var selectedValues = new Array();
	            	if(data.especialidadesUsuario){
    					for(var j = 0; j < data.especialidadesUsuario.length; j++ ){
    						selectedValues[j] = data.especialidadesUsuario[j].codesp;
    						
    					}
    				}
	            	$("#sEspecialidad").select2('val', selectedValues);
	            	
	            	selectedValues = new Array();
    				
	            	if(data.empresasUsu){
    					for(var j = 0; j < data.empresasUsu.length; j++ ){
    						selectedValues[j] = data.empresasUsu[j].idempresa;
    					}
    				}
    				$("#sEmpresas").select2('val', selectedValues);
	            	
	            }
	        }
	}); 
	
     

}




function obtenerUsuarios(fTipo){ //añadir los filtros que se necesiten
   var parametros = {
          'verusrbaja' : $('#verusrbaja').is(":checked"),
          'esUsrAdm': esUsrAdm
    };
    $('.loader-wrap').removeClass("hide");
    $.ajax({
            data:  parametros,
            url:   generarUrl('/private/obtenerUsuarios'),
            type:  'GET',
            dataType: 'json',
            success:  function (data) {
                usuariosActuales = null;
                var tUsr = $('#tablaUsuarios').DataTable();
                tUsr.clear().draw();
                
                if(!$('#verusrbaja').is(":checked")){
                   usuariosRegitradosTOT = 0;
                   usuariosPendientesTOT = 0;              
                }
                
            	
                if(data && data.usuario){
                    usuariosActuales = data.usuario
            		for(var i = 0; i < data.usuario.length; i++){
						var usrActual = data.usuario[i];
                        var usrFoto = "<img src='data:image/jpeg;base64,"+usrActual.urlFot+"' alt='Foto Usuario'>";
						var estado = '<span class="label  label-success">Aceptado</span>';
                        var dni = '<span class="text-uppercase">'+usrActual.dniusr+'</span>'
                        var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';

                        if(new Date(usrActual.fecbajadmin) > new Date() && new Date(usrActual.fecaceptado) > new Date()){
                          estado = '<span class="label  label-warning">Pendiente</span>';  
                          if(esUsrAdm)
                        	  listaBtn= listaBtn + '<span><button type="button" class="btn btn-icon btn-flat btn-default" data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Aceptar Usuario" onclick="aceptarUsuario(\''+usrActual.id+'\')"><i class="icon wb-check text-success" aria-hidden="true"></i></button></span>'+
                                '<span><button type="button" class="btn btn-icon btn-flat btn-default" data-toggle="tooltip" data-placement="top" data-trigger="hover" onclick="bajaUsuario(\''+usrActual.id+'\')" data-original-title="Denegar Usuario"><i class="icon wb-close text-danger" aria-hidden="true"></i></button></span>'
                                if(!$('#verusrbaja').is(":checked")){
                               usuariosPendientesTOT = usuariosPendientesTOT+1;                    
                            }
                        } else if(new Date(usrActual.fecbajadmin) <= new Date()){
                            estado = '<span class="label  label-danger">Baja</span>';
                            if(esUsrAdm){
                            	 if(new Date(usrActual.fecaceptado) > new Date()) 
                                     listaBtn = listaBtn + '<span><button type="button" class="btn btn-icon btn-flat btn-default" data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Aceptar Usuario" onclick="aceptarUsuario(\''+usrActual.id+'\')"><i class="icon wb-check text-success" aria-hidden="true"></i></button></span>';
                                 else
                                     listaBtn= listaBtn + '<span><button type="button" class="btn btn-icon btn-flat btn-default" data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Readmitir" onclick="readmitirUsuario(\''+usrActual.id+'\')"><i class="icon wb-check text-success" aria-hidden="true"></i></button></span>';
                            }
                           
                                
                        } else if(new Date(usrActual.feccaduca) <= new Date()){
                            estado = '<span class="label  label-default">Caducado</span>';
                            if(esUsrAdm)
                            	listaBtn= listaBtn + '<span><button type="button" class="btn btn-icon btn-flat btn-default" data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Readmitir" onclick="readmitirUsuario(\''+usrActual.id+'\')"><i class="icon wb-check text-success" aria-hidden="true"></i></button></span>';
                        }else{
                        	if(esUsrAdm)
                        		listaBtn= listaBtn + '<span><button type="button" class="btn btn-icon btn-flat btn-default" data-toggle="tooltip" data-placement="top" data-trigger="hover" onclick="bajaUsuario(\''+usrActual.id+'\')" data-original-title="Denegar Usuario"><i class="icon wb-close text-danger" aria-hidden="true"></i></button></span>';
                            if(!$('#verusrbaja').is(":checked")){
                               usuariosRegitradosTOT = usuariosRegitradosTOT+1;                    
                            }  
                        }
                        if(esUsrAdm)
                        	listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Editar Usuario"><button type="button" onclick="perfilUsuario(\''+usrActual.id+'\')" class="btn btn-icon btn-flat btn-default" data-target="#gestionUsuarios" data-toggle="modal"><i class="icon wb-edit" aria-hidden="true"></i></button></span>';
                        data.usuario[i].rolesusr = [];
                        var existeMed = false;
                        var existeDuv = false;
                        var roles = '';
                        var arrTipOtrosProf = new Array(
                				{'codrol' : "RES", 'desc' : 'Resonancia magnética'}, 
                				{'codrol' :"INY", 'desc' : 'Inyectables'}, 
                				{'codrol' :"SCU", 'desc' : 'Sala de Curas'}, 
                				{'codrol' :"QUI", 'desc' : 'Quirófano'}, 
                				{'codrol' :"ECO", 'desc' : 'Ecografías'}, 
                				{'codrol' : "RAX", 'desc' : 'Rayos X'},
                				{'codrol' :"AEC", 'desc' : 'Análisis Echevarne'}, 
                				{'codrol' :"API", 'desc' : 'Análisis Picornell'}, 
                				{'codrol' :"AAC", 'desc' : 'Análisis Analiza / CAB (Centro de Análisis Biológico)'}, 
                				{'codrol' :"APP", 'desc' : 'Análisis Palma patología'}
                		);
                        
                        for(var j = 0; j < data.rolesusr.length; j ++){
                        	
                        	if(data.rolesusr[j].ideusr == usrActual.id){
                        		var desrol = '';
                        		data.usuario[i].rolesusr.push(data.rolesusr[j]);
                        		
                        		for(var x = 0; x < data.roles.length; x++){
                        			if(data.roles[x].codrol == data.rolesusr[j].codrol){
                        				if(data.rolesusr[j].codrol == 'MED' || data.rolesusr[j].codrol == 'FIS' || data.rolesusr[j].codrol == 'OTE' || data.rolesusr[j].codrol == 'RES')
                        					existeMed = true;
                        				else{
                        					var resultFound = $.grep(arrTipOtrosProf, function(obj){ 
                        						return obj.codrol == data.rolesusr[j].codrol; 
                        					});
                        					if(resultFound && resultFound.length > 0)
                        						existeDuv = true;
                        				}
                        					
                        				desrol = data.roles[x].nomrol;
                        				break;
                        			}
                        		}
                        		if(roles)
                        			roles = roles+', ';
                        		roles = roles + desrol;
                        	}
                        	
                        }
                        
                        if(existeMed || existeDuv){
                        	if(existeMed)
                        		listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Configurar Agenda"><button type="button" onclick="initAgenda(\''+usrActual.id+'\', '+!existeDuv+')" class="btn btn-icon btn-flat btn-default" data-target="#gestionAgenda" data-toggle="modal"><i class="icon wb-book" aria-hidden="true"></i></button></span>';
                        	else
                        		listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Configurar Agenda"><button type="button" onclick="initAgenda(\''+usrActual.id+'\', true)" class="btn btn-icon btn-flat btn-default" data-target="#gestionAgenda" data-toggle="modal"><i class="icon wb-book" aria-hidden="true"></i></button></span>';
                        }
                        	 
                        
                        listaBtn = listaBtn +'</div>';
							
            			tUsr.row.add( [
                            usrActual.nomusr ,
							usrActual.apusr,
							dni,
    						usrActual.emailusr,
    						roles,
			                estado,
                            listaBtn
                            
                        ] ).draw( false );
            			
            			
                    }    
                    $('[data-toggle="tooltip"]').tooltip();     

                     $('.loader-wrap').addClass("hide");
                }     
                 $('#numUsrAct').html(usuariosRegitradosTOT);
                 $('#numUsrPen').html(usuariosPendientesTOT);
                 
            }//Fin success
    });//Fin Ajax
}

function initPantallaRegUsuarios(){
	initPantalla();
	limpiarPrivate('MOD');
}

function limpiarPrivate(accion){
	
	$('#nomusr').val('');
	$('#apusr').val('');
	$('#dniusr').val('');
	$('#numtel1').val('');
	$('#numtel2').val('');
	$('#emailusr').val('');
	$('#colegiado').val('');
	$('#id').val('');
	$('#tipdoc').val('DNI');
	mostrarNumDoc('');
	$("#sRoles").select2('val', null);
	$("#sEmpresas").select2('val', null);
	$('#fgColeg').addClass("hide");
	$('#fgEsp').addClass("hide");
	$("#sEspecialidad").select2('val', null);
	if(!accion || accion != 'MOD'){
		obtenerUsuarios();
		$("#gestionUsuarios").modal("toggle");
	}
		
}



/***** GESTIÓN AGENDA ******************/


function gestionFormSwiCambia(input){
	console.info( input.checked);
	$('#fecIniCambia').val('');
	$('#fecFinCambia').val('');
	if(input.checked){
		$('#datepicker-container').addClass('hide');
	
	}else{
		$('#datepicker-container').removeClass('hide');
	}
}

function generarCombosAgenda(){
	var horMaxAgenda = 24;
	var intervaloMins = 5;
	$('.sHora').empty();
	$('.sMinutos').empty();
	$('.sEmpresa').empty();
	
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
	
	$('.sEmpresa').append(
			$('<option>', {
		    value: '',
		    text: 'Empresa'
		}));
	for(var i = 1; i <= horMaxAgenda; i++){
		$('.sHora').append(
				$('<option>', {
			    value: i,
			    text: i<10? '0'+i:i
			}));
	}
	
	for(var i = 0; i < 60; i+=intervaloMins){
		$('.sMinutos').append(
				$('<option>', {
			    value: i,
			    text: i<10? '0'+i:i
			}));
	}
	
	for(var i=0; i < listaEmpresa.length; i++){
		$('.sEmpresa').append(
				$('<option>', {
			    value: listaEmpresa[i].id,
			    text: listaEmpresa[i].nombre
			}));
	}
	
	
	
}

function obtenerConfigAgendaUsuario(idusuario){
	
	$.ajax({
        data:  {'idusr' : idusuario},
        url:   generarUrl('/private/obtenerConfigAgendaUsuario'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	configUsrActual = null;
        	if(data && data.listaConfig){
        		configUsrActual = data.listaConfig;
        		var tAgenda = $('#tablaAgenda').DataTable();
        	    tAgenda.clear().draw();
        	    
        	    for(var i = 0; i < data.listaConfig.length; i++){
        	    	var configActual = data.listaConfig[i];
        	    	var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
        	    	listaBtn = listaBtn + '<span><button type="button" class="btn btn-icon btn-flat btn-default" data-toggle="tooltip" data-placement="top" data-trigger="hover" onclick="prepararModConfig(\''+configActual.id+'\')" data-original-title="Modificar Configuración"><i class="icon wb-pencil text-warning" aria-hidden="true"></i></button></span>';
        	    	listaBtn = listaBtn + '<span><button type="button" class="btn btn-icon btn-flat btn-default" data-toggle="tooltip" data-placement="top" data-trigger="hover" onclick="deleteConfig(\''+configActual.id+'\')" data-original-title="Eliminar Configuración"><i class="icon wb-trash text-danger" aria-hidden="true"></i></button></span>';
        	    	listaBtn = listaBtn + '</div>'
        	    	var strDias = '';
        	    	if( configActual.diaseml == 'S')
        	    		strDias = 'Lunes';
	    			if( configActual.diasemm == 'S'){
	    				if(strDias.length > 0)
	    					strDias = strDias + ' - ';
	    				strDias = strDias + ' Martes ';
	    			}
	    			if( configActual.diasemx == 'S'){
	    				if(strDias.length > 0)
	    					strDias = strDias + ' - ';
	    				strDias = strDias + ' Miercoles ';
	    			}
	    			if( configActual.diasemj == 'S'){
	    				if(strDias.length > 0)
	    					strDias = strDias + ' - ';
	    				strDias = strDias + ' Jueves ';
	    			}
	    			if( configActual.diasemv == 'S'){
	    				if(strDias.length > 0)
	    					strDias = strDias + ' - ';
	    				strDias = strDias + ' Viernes ';
	    			}
	    			if( configActual.diasems == 'S'){
	    				if(strDias.length > 0)
	    					strDias = strDias + ' - ';
	    				strDias = strDias + ' Sábado ';
	    			}
	    			if( configActual.diasemd == 'S'){
	    				if(strDias.length > 0)
	    					strDias = strDias + ' - ';
	    				strDias = strDias + ' Domingo ';
	    			}
        	    		var hIni = configActual.horaini < 10? '0'+ configActual.horaini:configActual.horaini;
        	    			hIni = configActual.minini < 10?  hIni + ' : ' + '0'+ configActual.minini: hIni + ' : ' +configActual.minini;
    	    			var hFin = configActual.horafin < 10? '0'+ configActual.horaini:configActual.horafin;
    	    			hFin = configActual.minfin < 10?  hFin + ' : ' + '0'+ configActual.minfin: hFin + ' : ' +configActual.minfin;
    	    			
    	    			var vigencia = 'Indefinido';
    	    			if(configActual.swicambia && configActual.swicambia == 'S')
    	    				vigencia = fechaString(configActual.fecinitemp) + ' - ' + fechaString(configActual.fecfintemp);
    	    			 tAgenda.row.add( [
    	       	                        strDias ,
    	       	                        vigencia,
    	       	                        configActual.nombre,
    	       	                        hIni,
    	       	                    	hFin,
    	       	                        listaBtn
    	       	                    ] ).draw( false );
        	    	
        	    }
        	}
        	
        }
	});
}

function prepararModConfig(id){
	if(configUsrActual){
		for(var i = 0; i < configUsrActual.length; i++){
			if(id == configUsrActual[i].id){
				var selectedValues = new Array();
				var cont = 0;
				if(configUsrActual[i].diaseml == 'S'){ selectedValues[cont] = 'L'; cont = cont+1}
				if(configUsrActual[i].diasemm == 'S'){ selectedValues[cont] = 'M'; cont = cont+1}
				if(configUsrActual[i].diasemx == 'S'){ selectedValues[cont] = 'X'; cont = cont+1}
				if(configUsrActual[i].diasemj == 'S'){ selectedValues[cont] = 'J'; cont = cont+1}
				if(configUsrActual[i].diasemv == 'S'){ selectedValues[cont] = 'V'; cont = cont+1}
				if(configUsrActual[i].diasems == 'S'){ selectedValues[cont] = 'S'; cont = cont+1}
				if(configUsrActual[i].diasemd == 'S'){ selectedValues[cont] = 'D'; cont = cont+1}
				
				$("#sDias").select2('val', selectedValues);
				
				$('#idConfig').val(configUsrActual[i].id);
				
				$('#sHoraIni').val(configUsrActual[i].horaini);
				$('#sMinIni').val(configUsrActual[i].minini);
				$('#sHoraFin').val(configUsrActual[i].horafin);
				$('#sMinFin').val(configUsrActual[i].minfin);
				$('#sDurCon').val(configUsrActual[i].durcon);
				$('#sEmpresa').val(configUsrActual[i].idempresa);
				
				$('#btnAddAgenda').addClass('hide');
				$('#btnModAgenda').removeClass('hide');
				
				if( configUsrActual[i].swicambia== 'S'){
					$('#swiCambia').prop('checked', false);
					//$('#fecIniCambia').val(fechaString(configUsrActual[i].fecinitemp));
					//document.getElementById("fecIniCambia").value  = fechaString(configUsrActual[i].fecinitemp);
					//$('#fecFinCambia').val(fechaString(configUsrActual[i].fecfintemp));
					var fS = new Date(configUsrActual[i].fecinitemp );
					var fE = new Date(configUsrActual[i].fecfintemp );
					
					$('#fecIniCambia').datepicker("setDate", new Date(fS.getFullYear(), fS.getMonth(), fS.getDate()));
					$('#fecFinCambia').datepicker("setDate", new Date(fE.getFullYear(), fE.getMonth(), fE.getDate()));
					$('#datepicker-container').removeClass('hide');
				}else{
					$('#swiCambia').prop('checked', true);
					$('#datepicker-container').addClass('hide');
				}
				
				
				break;
			}
		}
	}
}

function deleteConfig(id){
	$.ajax({
        data:  {'id' : id},
        url:   generarUrl('/private/deleteConfig'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	if(data && data.msgOk){
        		swal('Correcto', data.msgOk, "success");
        		if(data.idusr)
        			obtenerConfigAgendaUsuario(data.idusr);
        	}
        		
        	if(data && data.msgError)
        		swal('Error', data.msgError, "error");
        }
	});
}

function initAgenda (id, swMostrarDur){
	$('#swiCambia').prop('checked', true);
	$('#datepicker-container').addClass('hide');
	
	swiDurConsulta = swMostrarDur;
	generarCombosAgenda();
	obtenerConfigAgendaUsuario(id);
	$('#idusrConfig').val(id);
	$('#btnAddAgenda').removeClass('hide');
	$('#btnModAgenda').addClass('hide');
	if(!swMostrarDur)
		$('#fDurCon').addClass('hide');
	else
		$('#fDurCon').removeClass('hide');
}

function guardarAgenda(){
	
	if(swiDurConsulta && !$('#sDurCon').val()){
		swal('Error', 'Debe indicar la duración de la consulta', "error");
		return false;
	}
	var diasSelect = [];
    var swimed = false;
	$('#sDias :selected').each(function(i, selected){ 
		diasSelect.push(selected.value);
	});
	
	var parametros = {
			'listaDias' : diasSelect,
			'idusr' : $('#idusrConfig').val(),
			'id' : $('#idConfig').val(),
			'horaini': $('#sHoraIni').val() ,
			'minini': $('#sMinIni').val(),
			'horafin': $('#sHoraFin').val(),
			'minfin': $('#sMinFin').val(),
			'durcon': $('#sDurCon').val()?$('#sDurCon').val():1,
			'idempresa': $('#sEmpresa').val(),
			'swicambia': $('#swiCambia')[0].checked?'N':'S',
			'fecinitemp': $('#fecIniCambia').val()? $('#fecIniCambia').val():'',
			'fecfintemp': $('#fecFinCambia').val()? $('#fecFinCambia').val():''
					
	}
	
	
	$.ajax({
        data:  parametros,
        url:   generarUrl('/private/guardarConfigAgenda'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	if(data && data.msgOk){
        		swal('Correcto', data.msgOk, "success");
        		cancelaEdicion();
        		if(data.idusr)
        			obtenerConfigAgendaUsuario(data.idusr);
        	}
        	
        	if(data && data.msgError)
        		swal('Error', data.msgError, "error");
        }
	});
}

function limpiarFormAgenda(){
	cancelaEdicion();
	$("#gestionAgenda").modal("toggle");
}

function cancelaEdicion(){
	$('#idConfig').val('');
	$("#sDias").select2('val', new Array());
	$('#sHoraIni').val('');
	$('#sMinIni').val('');
	$('#sHoraFin').val('');
	$('#sMinFin').val('');
	$('#sDurCon').val('');
	$('#sEmpresa').val('');
	
	$('#fecIniCambia').val('');
	$('#fecFinCambia').val('');
	
	$('#swiCambia').prop('checked', true);
	
	$('#datepicker-container').addClass('hide');
	
	$('#btnAddAgenda').removeClass('hide');
	$('#btnModAgenda').addClass('hide');
}

/***** FIN GESTIÓN AGENDA ******************/

function obtenerDatosUsuarioSession (){
	$.ajax({
      url:   generarUrl('/private/obtenerDatosUsuarioSession'),
      type:  'GET',
      dataType: 'json',
      success:  function (data) {
    	  if(data && data.rolusr){
    		  var encontrado = false;
    		  for(var i = 0; i < data.rolusr.length; i++){
    			  var rUsu = data.rolusr[i];
    			  if(rUsu.codrol == 'ADMIN'){
    				  encontrado = true;
    				  break;
    			  }
    		  }
    		  if(encontrado){
    			  esUsrAdm = true;
    			  $('#btnAltaUsr').removeClass('hide');
    		  }else{
    			  esUsrAdm = false;
    			  $('#btnAltaUsr').addClass('hide');
    		  }
   				  
    	  }
          if(data && data.usuario){
        	usuarioSes = data.usuario;
        	obtenerUsuarios();
          }
      }
    });
}

obtenerDatosUsuarioSession();