$(document).ready(function(){
      
  setTimeout(function(){$('[name="liMenuIngresos"]').addClass('active');}, 500);
  
  $('.datepicker').datepicker({
	    weekStart: 1,
	    format: "dd/mm/yyyy",
	    language: 'es'

	});
  
  //$('#tablaMedPautadosEnf').DataTable();
  
  $('#sMedicamentos').select2({
	  minimumInputLength: 3,
	  maximumSelectionLength: 1,
	  language:{ inputTooShort: function (args) {
		      var remainingChars = args.minimum - args.input.length;
	
		      var message = 'Introduce ' + remainingChars + ' o más caracteres';
	
		      return message;
		    },
		    noResults: function () { return 'No se encontraron resultados';},
		    searching: function () {return 'Buscando…';},
		    maximumSelected: function (args) { 
		    	var message = 'Solo puede seleccionar ' + args.maximum + ' elemento';

			      if (args.maximum != 1) {
			        message += 's';
			      }
	
			      return message;
			}
	  },
	  ajax: {
	    url: generarUrl('/private/obtenerMedicamentos'),
        type:  'GET',
        dataType: 'json',
	    processResults: function (data) {
	      // Tranforms the top-level key of the response object from 'items' to 'results'
	      return {
	        results: data.listaMedicamentos
	      };
	    }
	  }
	});
  
    
});
var pacientesActuales = null;
var pacienteSelecc = null;
var ingresosActuales = null;
var ingresosSelecc = null;
var usrSes = null;
var listaSolAct = null;
var listaEvolAct = null;
var medActPautada = null;
var swiMostrarAplicar = false;
var swiInfoAlta = 'N';

function cargaComboOpt(l,c, o){
	
	if(o == 'N'){
		for(var i = 0; i < l.length; i++){
			
			  c.append(
				$('<option>', {
					value: l[i].id,
					text: l[i].apusr + ', ' + l[i].nomusr + ' - ' + l[i].especialidades
				}));
			}
	}else{
		var arrTipOtrosProf = new Array(
				{'codrol' : "RES", 'desc' : 'Resonancia magnética'}, 
				{'codrol' :"INY", 'desc' : 'Inyectables'}, 
				{'codrol' :"SCU", 'desc' : 'Sala de Curas'}, 
				{'codrol' :"QUI", 'desc' : 'Quirófano'}, 
				{'codrol' :"ECO", 'desc' : 'Ecografías'}, 
				{'codrol' : "RAX", 'desc' : 'Rayos X'},
				{'codrol' :"AEC", 'desc' : 'Laboratorio / Análisis'}/*, 
				{'codrol' :"API", 'desc' : 'Análisis Picornell'}, 
				{'codrol' :"AAC", 'desc' : 'Análisis Analiza / CAB (Centro de Análisis Biológico)'}, 
				{'codrol' :"APP", 'desc' : 'Análisis Palma patología'}*/
		);
		for(var i = 0; i < arrTipOtrosProf.length; i++){
			
			var resultFound = $.grep(l, 
					function(obj){
						return obj.codrol == arrTipOtrosProf[i].codrol; 
					});
			
			if(resultFound && resultFound.length > 0)
				$('#sMed').append('<optgroup id="sMedOpOtr'+arrTipOtrosProf[i].codrol+'" role="group" label="'+ arrTipOtrosProf[i].desc +'">');
			
			for(var j = 0; j < resultFound.length; j++){
				
				arrOtrProf[resultFound[j].id] = [];
				arrOtrProf[resultFound[j].id].codrol = resultFound[j].codrol
				arrOtrProf[resultFound[j].id].nomrol = resultFound[j].nomrol;

				$('#sMedOpOtr'+arrTipOtrosProf[i].codrol).append(
					$('<option>', {
						value: resultFound[j].id,
						text: resultFound[j].apusr + ', ' + resultFound[j].nomusr
					}));
				
				}
			
		}
	}	
}

function mostrarOcultarTipoDoc(terminacion){

	if($( "#tipdoc" + terminacion ).val()== 'DNI'){
		$( "#dniusr" + terminacion ).removeClass('hide');
		$( "#nieusr" + terminacion ).addClass('hide');
		$( "#passusr" + terminacion ).addClass('hide');
	}
	if($( "#tipdoc" + terminacion ).val()== 'NIE'){
		$( "#dniusr" + terminacion ).addClass('hide');
		$( "#nieusr" + terminacion ).removeClass('hide');
		$( "#passusr" + terminacion ).addClass('hide');
	}
	if($( "#tipdoc" + terminacion ).val()== 'PAS'){
		$( "#dniusr" + terminacion ).addClass('hide');
		$( "#nieusr" + terminacion ).addClass('hide');
		$( "#passusr" + terminacion ).removeClass('hide');
	}
}


$( "#tipdocBAlta" ).change(function(v) {
	$( "#dniusrBAlta" ).val('');
	$( "#nieusrBAlta" ).val('');
	$( "#passusrBAlta" ).val('');
	mostrarOcultarTipoDoc('BAlta');
});

$( "#tipdocAlta" ).change(function(v) {
	$( "#dniusrAlta" ).val('');
	$( "#nieusrAlta" ).val('');
	$( "#passusrAlta" ).val('');
	mostrarOcultarTipoDoc('Alta');
});

$( "#redporAlta" ).change(function(v) {
	$('#desRedPorAlta').val('');
	if($( "#redporAlta" ).val()== 'CAJ' || $( "#redporAlta" ).val()== 'MAJ'){
		$('.fgDesRedPorAlta').removeClass('hide');
	}else{
		$('.fgDesRedPorAlta').addClass('hide');
	}	
});

function cancelarAltaRapida(){
	$('.registro-usr').addClass('hide');
	$('.selecPac').removeClass('hide');
	$('.infoIngreso').addClass('hide');
}

function addError(c){
    c.addClass("has-error");
}
function removeError(c){
    c.removeClass("has-error");
}

function confirmarAltaRapida(){
	swal({
		title: "Guardar paciente",
		text: '¿Desea guardar el paciente?',
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: 'Confirmar',
		cancelButtonText: 'Cancelar',
		closeOnConfirm: true
	},function(isConfirm) {
		if (isConfirm) {
			altaRapidaPacientes();
		}
	});
}

function altaRapidaPacientes(){

	//Campos Obligatorios
	var error = false;
	var warningDNI = false;
	var numdoc = $( "#tipdocAlta" ).val()== 'DNI'? $("#dniusrAlta").val(): $( "#tipdocAlta" ).val()== 'NIE'? $("#nieusrAlta").val():$( "#tipdocAlta" ).val()== 'PAS'? $("#passusrAlta").val():'';
	if(!$("#nompacAlta").val()){error = true; addError($("#fgNombreAlta"));} else removeError($("#fgNombreAlta"));
	if(!$("#ap1pacAlta").val()){error = true; addError($("#fgSurName1Alta"));} else removeError($("#fgSurName1Alta"));
	if(!numdoc || numdoc == '        - ' || $("#dniusrAlta").val() == ' - '){warningDNI = true;}
	if(!$("#sexpacAlta").val()){error = true; addError($("#fgSexpacAlta"));} else removeError($("#fgSexpacAlta"));
	
	if(error){
		swal("Error", 'Debe de Rellenar los campos Obligatorios: Nombre, Primer apellido, DNI y Sexo', "error");
		return false;
	}
	$('.loader-wrap').removeClass("hide");
	
	var parametros = {
			'id': $('#id').val(),
			'nompac': $('#nompacAlta').val(),
			'ap1pac': $('#ap1pacAlta').val(),
			'ap2pac': $('#ap2pacAlta').val(),
			'sexpac': $('#sexpacAlta').val(),
			'tipdoc': $('#tipdocAlta').val(),
			'numtel1':$('#numtel1Alta').val(),
			'dniusr': numdoc,
			'codUsr': usrSes.id,
			'idseguro': $('#sIdseguroAlta').val()
		};
	
	$.ajax({
		data: parametros,
        url:   generarUrl('/private/guardarPaciente'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.paciente){
        		if(warningDNI)
        			swal("Alerta", 'Paciente dado de alta Sin DNI para iniciar las visitas es obligatorio el DNI', "warning");
        		else
        			swal("Correcto", 'Paciente dado de alta', "success");
        		pacienteSelecc = data.paciente;
        		$('.registro-usr').addClass('hide');
        		$('.infoIngreso').removeClass('hide');
        		return true;
        	}
        	else if(data.msgError){
        		//usuariosRegistrados.js
        		swal("Error", data.msgError, "error");
        		return false;
        	}
        }
	});
}

function segurosLista(listaSeguros,combo){
	$('#'+combo).empty();
	$('#'+combo).append(
			$('<option>', {
		    value: '',
		    text: 'Seguros'
		}));
	if(listaSeguros){
		for(var i = 0; i < listaSeguros.length; i++){
			$('#'+combo).append(
				$('<option>', {
			    value: listaSeguros[i].id,
			    text: listaSeguros[i].nomseguro
			}));
		}
		
	}
}

function cancelGuardado(){
	$("#gestionNuevoIngreso").modal('toggle');
}

function prepararRegistroUsr(){
	segurosLista(listaSegurosAct, 'sIdseguroAlta');
	$('.registro-usr').removeClass('hide');
	$('.selecPac').addClass('hide');
	$('.infoIngreso').addClass('hide');
}

function seleccionarPaciente(pos){
	pacienteSelecc = pacientesActuales[pos];
	$('.selecPac').addClass('hide');
	$('.infoIngreso').removeClass('hide');
}

function buscarPacienteNomAp(){
	var numdoc = $( "#tipdocBAlta" ).val()== 'DNI'? $("#dniusrBAlta").val(): $( "#tipdocBAlta" ).val()== 'NIE'? $("#nieusrBAlta").val():$( "#tipdocBAlta" ).val()== 'PAS'? $("#passusrBAlta").val():'';
	numdoc = numdoc.replace(/\s/g, "") ;
	if(!$('#nompacBAlta').val() && !$('#ap1pacBAlta').val() && !$('#ap2pacBAlta').val() && (!numdoc || numdoc == '-') && !$('#idHistorialBusq').val()){
		swal("Error", 'debe especificar almenos un elemento en la busqueda', "error");
		return false;
	}
	pacientesActuales = null;
	pacienteSelecc = null;
	$.ajax({
		data: {
			'nompac': $('#nompacBAlta').val(),
			'ap1pac': $('#ap1pacBAlta').val(),
			'ap2pac': $('#ap2pacBAlta').val(),
			'dniusr' : numdoc,
			'idHistorial' :$('#idHistorialBusq').val(),
			'tipBusq': 'ADDCITA'
		},
		url:   generarUrl('/private/buscarPacienteNomAp'),
		type:  'GET',
		dataType: 'json',
		success:  function (data) {
			if(data && data.listaPacientes){
				pacientesActuales = data.listaPacientes;
				if(pacientesActuales){
					if(pacientesActuales.length == 0){
						swal({
							title: "Alerta!",
							text: 'No existe el paciente, ¿desea darlo de alta?',
							type: "warning",
							showCancelButton: true,
							confirmButtonColor: '#DD6B55',
							confirmButtonText: 'Alta Rápida',
							cancelButtonText: 'Cancelar',
							closeOnConfirm: true
						},function(isConfirm) {
							if (isConfirm) {
								$('#nompacAlta').val($('#nompacBAlta').val());
								$('#ap1pacAlta').val($('#ap1pacBAlta').val());
								$('#ap2pacAlta').val($('#ap2pacBAlta').val());
								$('#sexpacAlta').val('');
								$('#dniusrAlta').val('');
								$('#numtel1Alta').val('');
								$('#sIdseguroAlta').val('');
								prepararRegistroUsr();
								//$("#panelAltRap").modal();
							}
						});
					}else if(pacientesActuales.length == 1){
						seleccionarPaciente(0);
					}else{
						var tPac = $('#tablaPacientes').DataTable();
						tPac.clear().draw();
						for(var i = 0; i < data.listaPacientes.length; i++){
						var pacActual = data.listaPacientes[i];
						var dni = '<span class="text-uppercase">'+pacActual.dniusr+'</span>'
						var listaBtn =`
						<div class="btn-group" aria-label="Acciones Usuario" role="group">
						   <span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Seleccionar Paciente"><button type="button" onclick="seleccionarPaciente('`+i+`')" class="btn btn-icon btn-flat btn-default"><i class="icon wb-check-circle" aria-hidden="true"></i></button></span>
						   
						</div>
						`;
						tPac.row.add( [
						pacActual.nompac ,
						pacActual.ap1pac,
						pacActual.ap2pac,
						dni,
						listaBtn
						] ).draw( false );
						}
						$('[data-toggle="tooltip"]').tooltip();  
						$("#panelBusq").modal();
					}
				}else{
				//Mandar MSG Error
				}
				$('.loader-wrap').addClass("hide");
			}
		}
	});
}

function limpiarBusquedaPac(){
	$('#tablaPacientes').DataTable().clear().draw();
	$('#nompacBAlta').val('');
	$('#ap1pacBAlta').val('');
	$('#ap2pacBAlta').val('');
	$('#idHistorialBusq').val('');
	$("#dniusrBAlta").val('');
	$("#nieusrBAlta").val('');
	$("#passusrBAlta").val('');
	$("#redporAlta").val('');
	$("#desRedPorAlta").val('');
	$("#sMedAlta").val('');
	$("#sHabAlta").val('');
	$('.fgDesRedPorAlta').addClass('hide');
}

function initGestionNuevoIngreso(){
	var pacientesActuales = null;
	var pacienteSelecc = null;
	
	$('.selecPac').removeClass('hide');
	$('.infoIngreso').addClass('hide');
	$('.registro-usr').addClass('hide');
	obtenerhabitacionesDisponibles();
	limpiarBusquedaPac();
	
}

function insertarIngreso(){
	
	  
	var desRedPor = $( "#redporAlta option:selected" ).text();
	if($( "#redporAlta" ).val()== 'CAJ' || $( "#redporAlta" ).val()== 'MAJ'){
		desRedPor = $( "#desRedPorAlta" ).val();
	}
	$('.loader-wrap').removeClass("hide");
  
	$.ajax({
		data: {
			'idpac': pacienteSelecc.id,
			'redpor': $("#redporAlta" ).val(),
			'desregpor': desRedPor,
			'idmed' : $("#sMedAlta" ).val() == 'URG'?'':$("#sMedAlta" ).val(),
			'desmed' :$( "#sMedAlta option:selected" ).text(),
			'idhab': $('#sHabAlta').val()
		},
		url:   generarUrl('/private/insertarIngreso'),
		type:  'GET',
		dataType: 'json',
		success:  function (data) {
			$('.loader-wrap').addClass("hide");
			if(data.ingreso){
				$('#gestionNuevoIngreso').modal('toggle');
				swal("Ingreso Registrado", 'La información quedó registrada correctamente.', "success");
				obtenerIngresosActivos();
			}
			
		}
	});
}

function obtenerhabitacionesDisponibles(){
	$('.loader-wrap').removeClass("hide");
	$('#sHabAlta').empty();
	$('#sHabAlta').append(
			$('<option>', {
				value: '',
				text: 'Habitaciones'
			}));
	$.ajax({
		  url: generarUrl('/private/obtenerhabitacionesDisponibles'),
		  type:  'GET',
		  dataType: 'json',
		  success:  function (data) {
			  $('.loader-wrap').addClass("hide");
		      if(data){
		    	var l = data.listaHabitaciones;
		    	for(var i = 0; i < l.length; i++)
		    	
		    	$('#sHabAlta').append(
						$('<option>', {
							value: l[i].id,
							text: l[i].deshab
						}));
		      }
		  }
		});
}

function imprimirAlta(ing){
	
	$('#impIdeAlta').val(ing.id);
	$( "#fImpAlta" ).submit();
	obtenerIngresosActivos();
	
}

function darAltaPaciente(ingresoActual){
	$('.loader-wrap').removeClass("hide");
	ingresosSelecc = ingresoActual;
	$.ajax({
		data: {
			'id': ingresoActual.id,
			'idhab': ingresoActual.idhab,
			'ap2pac': $('#ap2pacBAlta').val()
		},
		url: generarUrl('/private/darAltaPaciente'),
		type: 'GET',
		dataType: 'json',
		success:  function (data) {
			$('.loader-wrap').addClass("hide");
			if(data){
				if(data.msgOK){
					swal({
						title: "Alta Correcta",
						text: data.msgOK,
						type: "success",
						showCancelButton: true,
						confirmButtonColor: '#DD6B55',
						confirmButtonText: 'Imprimir Alta',
						cancelButtonText: 'Cerrar',
						closeOnConfirm: true
					},function(isConfirm) {
				    	if (isConfirm) {
				    		imprimirAlta(data.ingreso);
				    	}else
				    		obtenerIngresosActivos();
				    });
				}else if(data.msgErr)
						swal({
							title: "Error al dar de alta",
							text: data.msgErr,
							type: "info",
							showCancelButton: true,
							confirmButtonColor: '#DD6B55',
							confirmButtonText: 'Crear Informe de alta',
							cancelButtonText: 'Cancelar',
							closeOnConfirm: true
						},function(isConfirm) {
					    	if (isConfirm) {
					    		initVentanaEvolutivos();
					    		initNewEvo('S');
					    	}else
					    		obtenerIngresosActivos();
					    });
				
				
		    }
		}
	});
}

function confirmacionalta(pos){
	
	var ingresoActual = ingresosActuales[pos];
	swal({
      	title: "",
		text: '¿Desea dar de alta al paciente '+ ingresoActual.nompac + ' ' + ingresoActual.ap1pac + ' ' + ingresoActual.ap2pac +'?',
		type: "info",
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: 'Dar de alta',
		cancelButtonText: 'Cancelar',
		closeOnConfirm: true
    },
    function(isConfirm) {
    	if (isConfirm) {
    		darAltaPaciente(ingresoActual);
    	}
    });
}

function insertNuevaSolicitud(){
	$.ajax({
		  url: generarUrl('/private/insertNuevaSolicitud'),
		  data:{
			  'idingreso' : ingresosSelecc.id,
			  'codtipsol': $('#tipSolAlta').val(),
			  'dessol' : $('#desSolAlta').val()
		  },
		  type:  'GET',
		  dataType: 'json',
		  success:  function (data) {
			  $('.loader-wrap').addClass("hide");
		      if(data){
		    	  
		    	  volverGestionSolucitudes();
		    	  gestionSolucitudes();
		      }
		  }
	});
	
	
}

function volverGestionSolucitudes(){
	$('.resumenSol').removeClass('hide');
	$('.nuevaSolForm').addClass('hide');
}

function tipoSolLista(combo){
	$('#'+combo).empty();
	$('#'+combo).append(
			$('<option>', {
		    value: '',
		    text: 'Tip. Solicitud'
		}));
	if(tiposSolicitud){
		for(var i = 0; i < tiposSolicitud.length; i++){
			$('#'+combo).append(
				$('<option>', {
			    value: tiposSolicitud[i].coddom,
			    text: tiposSolicitud[i].desval
			}));
		}
		
	}
}

function initNewSol(){
	
	tipoSolLista('tipSolAlta');
	$('.nuevaSolForm').removeClass('hide');
	$('.resumenSol').addClass('hide');
	
	$('#tipSolAlta').val('');
	$('#desSolAlta').val('');
}

function bajaSolicitudIngreso(pos){
	var solEliminar = listaSolAct[pos];
	swal({
		title: "Eliminar Solicitud",
		text: '¿Desea eliminar la solicitud seleccionada?',
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: 'Confirmar',
		cancelButtonText: 'Cancelar',
		closeOnConfirm: true
	},function(isConfirm) {
		if (isConfirm) {
			$('.loader-wrap').removeClass("hide");
			$.ajax({
				  url: generarUrl('/private/bajaSolicitudIngreso'),
				  type:  'GET',
				  data:{'id': solEliminar.id},
				  dataType: 'json',
				  success:  function (data) {
					  if(data && data.msgOk){
						  swal("Solicitud Ingreso", data.msgOK, "success");
						  gestionSolucitudes();
					  }
				  }
			});
		}
	});
}

function gestionSolucitudes(pos){
	if(pos)
		ingresosSelecc = ingresosActuales[pos];
	volverGestionSolucitudes();
	
	var tSol = $('#tablaSolicitudesAct').DataTable();
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		  url: generarUrl('/private/obtenerSolicitudesIngreso'),
		  type:  'GET',
		  data:{'idingreso': ingresosSelecc.id},
		  dataType: 'json',
		  success:  function (data) {
			  $('.loader-wrap').addClass("hide");
		      if(data){
		    	  listaSolAct = data.listaSolicitudes;
		    	  tSol.clear().draw();
		    	  for(var i = 0; i < data.listaSolicitudes.length; i++){
		    		  var solicitud = data.listaSolicitudes[i];
						
		    		  var listaBtn =`
		    		  	<div class="btn-group" aria-label="Acciones Usuario" role="group">
						   	<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Eliminar Solicitud"><button type="button" onclick="bajaSolicitudIngreso('`+i+`')" class="btn btn-icon  btn-danger"><i class="icon wb-close" aria-hidden="true"></i></button></span>
						</div>
						`;
						
						tSol.row.add( [
			               solicitud.desval,
			               solicitud.dessol,
			               fechaString(solicitud.created_at) + solicitud.created_at.substring(10, solicitud.created_at.length -3),
			               listaBtn
						] ).draw( false );
					}
					$('[data-toggle="tooltip"]').tooltip();
					if(pos)
						$("#gestionSolicitudes").modal();
		      }
		  }
	});
	
	
}

function obtenerInformeAlta(){
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		  url: generarUrl('/private/obtenerInformeAlta'),
		  data:{
			  'idingreso' : ingresosSelecc.id
		  },
		  type:  'GET',
		  dataType: 'json',
		  success:  function (data) {
			  $('.loader-wrap').addClass("hide");
		      if(data && data.ingreso){
		    	  $('#descEvoNew-wrap .note-editable').html(data.ingreso.infalta);
		    	  
		      }
		  }
	});
}

function initNewEvo(swAlta){
	$('.resumenEvo').addClass('hide');
	$('.newEvo').removeClass('hide');
	$('.detNewEvol').empty();
	$('#descEvoNew-wrap .note-editable').html('');
	swiInfoAlta = swAlta;
	if(swiInfoAlta == 'S'){
		$('.detNewEvol').append('Informe de alta');
		obtenerInformeAlta();
		
	}else{
		$('.detNewEvol').append('Nuevo Evolitivo');
	}
}


function volverGestionEvolutivo(){
	obtenerEvolutivosIngreso();
	$('.resumenEvo').removeClass('hide');
	$('.newEvo').addClass('hide');
}

function insertNuevoEvolutivo(){
	$('.loader-wrap').removeClass("hide");
	var url = generarUrl('/private/insertNuevoEvolutivo');
	if(swiInfoAlta == 'S')
		url = generarUrl('/private/actualizarInformeAlta');
	$.ajax({
		  url: url,
		  data:{
			  'idingreso' : ingresosSelecc.id,
			  'tipevol': 'EVOL_MED',
			  'desevol' : $('#descEvoNew-wrap .note-editable').html() == '<p><br></p>'? '':$('#descEvoNew-wrap .note-editable').html()
		  },
		  type:  'POST',
		  dataType: 'json',
		  success:  function (data) {
			  $('.loader-wrap').addClass("hide");
		      if(data){
		    	  swal("Correcto", data.msgOk, "success");
		    	  volverGestionEvolutivo();
		    	  //gestionSolucitudes();
		      }
		  }
	});
}

function bajaEvolutivoIngreso(pos){
	var evoEliminar = listaEvolAct[pos];
	swal({
		title: "Eliminar Solicitud",
		text: '¿Desea eliminar el evolutivo seleccionado?',
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: 'Confirmar',
		cancelButtonText: 'Cancelar',
		closeOnConfirm: true
	},function(isConfirm) {
		if (isConfirm) {
			$('.loader-wrap').removeClass("hide");
			$.ajax({
				  url: generarUrl('/private/bajaEvolutivoIngreso'),
				  type:  'GET',
				  data:{'id': evoEliminar.id},
				  dataType: 'json',
				  success:  function (data) {
					  if(data && data.msgOk){
						  swal("Evolutivo Ingreso", data.msgOk, "success");
						  obtenerEvolutivosIngreso();
					  }
				  }
			});
		}
	});
}


function initVentanaEvolutivos(pos){
	if(pos)
		ingresosSelecc = ingresosActuales[pos];
	volverGestionEvolutivo();
	$('#gestionEvolutivos').modal();
}

function obtenerEvolutivosIngreso(){
	listaEvolAct = null;
	var tEvol = $('#tablaEvolutivosAct').DataTable();
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		  url: generarUrl('/private/obtenerEvolutivosIngreso'),
		  type:  'GET',
		  data:{'idingreso': ingresosSelecc.id,
			  'tipevol': 'EVOL_MED'},
		  dataType: 'json',
		  success:  function (data) {
			  $('.loader-wrap').addClass("hide");
		      if(data){
		    	  listaEvolAct = data.listaEvoutivos;
		    	  tEvol.clear().draw();
		    	  for(var i = 0; i < listaEvolAct.length; i++){
		    		  var evol = listaEvolAct[i];
						
		    		  var listaBtn =`
		    		  	<div class="btn-group" aria-label="Acciones Usuario" role="group">
						   	<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Eliminar evolutivo"><button type="button" onclick="bajaEvolutivoIngreso('`+i+`')" class="btn btn-icon  btn-danger"><i class="icon wb-close" aria-hidden="true"></i></button></span>
						</div>
						`;
						
						tEvol.row.add( [
			               evol.nomusr + ' ' + evol.apusr,
			               evol.desevol,
			               fechaString(evol.created_at) + evol.created_at.substring(10, evol.created_at.length -3),
			               listaBtn
						] ).draw( false );
					}
					$('[data-toggle="tooltip"]').tooltip();
		      }
		  }
	});

}

function initNewMedi(){
	$('.resumenMedi').addClass('hide');
	$('.newMedi').removeClass('hide');
	//$('#descEvoNew-wrap .note-editable').html('');
}

function volverGestionMedicacion(){
	obtenerEvolutivosIngreso();
	$('.resumenMedi').removeClass('hide');
	$('.newMedi').addClass('hide');
}

function bajaMedPauta(pos){
	
	swal({
		title: "Eliminar",
		text: '¿Desea eliminar el medicamento pautado?',
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: 'Confirmar',
		cancelButtonText: 'Cancelar',
		closeOnConfirm: true
	},function(isConfirm) {
		if (isConfirm) {
			$('.loader-wrap').removeClass("hide");
			
			$.ajax({
				  url: generarUrl('/private/eliminarMedicacionIng'),
				  type:  'GET',
				  data:{'id' : medActPautada[pos].id},
				  dataType: 'json',
				  success:  function (data) {
					  $('.loader-wrap').addClass("hide");
				      if(data){
				    	if(data.msgOK){
				    		swal("Correcto", data.msgOK, "success");
				    		initVentanaMedicacion();
				    	}
				      }
				  }
			});
		}
	});
}

function initVentanaMedicacion(pos){
	if(pos)
		ingresosSelecc = ingresosActuales[pos];
	volverGestionMedicacion();
	
	$('#sNumHpauta').empty();
	$('#sNumMpauta').empty();
	
	for(var i = 1; i <= 24; i++){
		var horaStr = '';
		if(i < 10 ){
			if(i == 1)
			$('#sNumHpauta').append(
					$('<option>', {
						value: '',
						text: 'Horas'
					}));
			horaStr = '0'+ i.toString();
		}
			
		else
			horaStr = i.toString();
		
		$('#sNumHpauta').append(
				$('<option>', {
					value: i,
					text: horaStr.toString()
				}));
	}
	
	for(var i = 0; i < (60/15); i++){
		var horaStr = '';
		if( (i*15) < 10 ){
			if(i == 0)
			$('#sNumMpauta').append(
					$('<option>', {
						value: '',
						text: 'Minutos'
					}));
			horaStr = '0'+ (i*15).toString();
		}
			
		else
			horaStr = (i*15).toString();
		
		$('#sNumMpauta').append(
				$('<option>', {
					value: (i*15),
					text: horaStr.toString()
				}));
	}
	
	$('.loader-wrap').removeClass("hide");
	var tMedAct= $('#tablaMedicacionAct').DataTable();
	medActPautada = null;
	
	$.ajax({
		  url: generarUrl('/private/obtenerMedicacionIngActivos'),
		  type:  'GET',
		  data:{'idingreso' : ingresosSelecc.id},
		  dataType: 'json',
		  success:  function (data) {
			  $('.loader-wrap').addClass("hide");
		      if(data){
		    	if(data.listaMedPau){
		    		
		    		medActPautada = data.listaMedPau;
		    		tMedAct.clear().draw();
					for(var i = 0; i < medActPautada.length; i++){
						
						var listaBtn =`
						<div class="btn-group" aria-label="Acciones Usuario" role="group">
						   
						   <span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Dar de baja"><button type="button" onclick="bajaMedPauta('`+i+`')" class="btn btn-icon  btn-danger"><i class="icon wb-close" aria-hidden="true"></i></button></span>
						</div>
						`;
						
						tMedAct.row.add( [
						               medActPautada[i].descripcion,
						               medActPautada[i].dosis,
						               fechaString(medActPautada[i].fecini),
						               fechaString(medActPautada[i].fecfin),
						               medActPautada[i].medico,
						               listaBtn
						               
						] ).draw( false );
					}
					$('[data-toggle="tooltip"]').tooltip();
		    	}
		      }
		  }
	});
	
	
	$('#gestionMedicacion').modal();
}

function guardarMedicamentosIngreso(){
	$('.loader-wrap').removeClass("hide");
	
	var arExcp={'descripcion':'S'};
	var vf = convertirFormEnJson( $('#nuevoMedForm').serializeArray(), 'S', arExcp );
	if(!vf || vf['err']){
		$('.loader-wrap').addClass("hide");
		swal("Error", vf['err'] , "error");
		return false;
	}
	
	vf['idmed'] = usrSes.id;
	vf['periodominutos'] = parseInt(vf['sNumHpauta']*60) + parseInt(vf['sNumMpauta']);
	vf['idingreso'] = ingresosSelecc.id;
	vf['fecfin'] = fechaStringEncode(vf['fecfin']);
	vf['fecini'] = fechaStringEncode(vf['fecini']);
	
	
	
	$.ajax({
		  url: generarUrl('/private/guardarMedicamentosIngreso'),
		  type:  'GET',
		  data: vf,
		  dataType: 'json',
		  success:  function (data) {
			  $('.loader-wrap').addClass("hide");
		      if(data && data.msgOK){
		    	  initVentanaMedicacion();
		    	  swal("Correcto", data.msgOK, "success");
		    	  $('#nuevoMedForm')[0].reset();
		    	  $("#sMedicamentos").select2("val", "");
		      }
		  }
	});
	
	
	
	
}

function firmarMedicacion(id, mm){
	$('.loader-wrap').removeClass("hide");
	
	$.ajax({
		  url: generarUrl('/private/registrarFirmaMedicacion'),
		  type:  'GET',
		  data:{ 'idingreso' : ingresosSelecc.id,
			  'fecsum' : mm,
			  'idmedpautado': id,
			  'idusrsum': usrSes.id
			  },
		  dataType: 'json',
		  success:  function (data) {
			  $('.loader-wrap').addClass("hide");
		      if(data && data.msgOK){
		    	  swal("Correcto", data.msgOK, "success");
		    	  medicacionSuministradaByIdIngreso();
		      }
		  }
	});
}

function generarStrUsrMedSum(mstr, obj, listaMedSumini){
	var strReturn = '';
	if(listaMedSumini){
		for(var i = 0; i < listaMedSumini.length; i++){
			if(listaMedSumini[i].fecsum == (mstr+':00') && obj.idMedPautado == listaMedSumini[i].idmedpautado)
				return listaMedSumini[i].usrAdm + '<br>';
		}
	}
	
	
	return 'No suministrada';
}

function generarContenidoInfoCelda(obj, mm, listaMedSumini){
	var mstr = moment(mm).set('minutes', 0).set('seconds', 0).format('YYYY-MM-DD HH:mm');
	var mact = moment().set('minutes', 0).set('seconds', 0).format('YYYY-MM-DD HH:mm');
	if( obj.ultdosis && moment(mm).set('minutes', 0).set('seconds', 0)._d < moment(obj.ultdosis).set('seconds', 0)._d){
		return generarStrUsrMedSum(mstr, obj, listaMedSumini);
	}
		
	var strReturn = `<div>
		<span><strong>` + obj.dosis+`</strong>  </span>
		<br>Último dado:<br>`;
		
	if(!obj.ultdosis)
		strReturn = strReturn + `<strong>Sin Documentar</strong>`;
	else
		strReturn = strReturn + generarStrUsrMedSum(mstr, obj, listaMedSumini)+ `<strong>`+obj.fecultsum+`</strong>`;
		
		if( !obj.ultdosis ||  moment(mm).set('minutes', 0).set('seconds', 0)._d > moment(obj.ultdosis).set('seconds', 0)._d){
			if(swiMostrarAplicar == true){
				strReturn = strReturn + `	<div class="col-sm-12">
					<button type="button" class="btn btn-success nSolicitud" onclick = "firmarMedicacion(`+ obj.idMedPautado +`, '`+mstr+`')">Aplicar</button>
				</div>`;
				swiMostrarAplicar = false;
			}
		}
		
		
	
	
	strReturn = strReturn + `</div>`;
	
	return strReturn;
}


function medicacionSuministradaByIdIngresoHistorico(){
	$('.loader-wrap').removeClass("hide");
	$('#tablaHistMedicacion').DataTable().clear().draw();
	
	$.ajax({
		  url: generarUrl('/private/medicacionSuministradaByIdIngresoHistorico'),
		  type:  'GET',
		  data:{ 'idingreso' : ingresosSelecc.id},
		  dataType: 'json',
		  success:  function (data) {
			  $('.loader-wrap').addClass("hide");
		      if(data){
		    	if(data.listaMedSumini){
		    		var tHistMed = $('#tablaHistMedicacion').DataTable();
		    		for(var i = 0; i < data.listaMedSumini.length; i++){
		    			var medAct = data.listaMedSumini[i];
		    			tHistMed.row.add( [
							fechaString(medAct.created_at) + medAct.created_at.substring(10, medAct.created_at.length -3) ,
							fechaString(medAct.fecsum) + medAct.fecsum.substring(10, medAct.fecsum.length -3),
							medAct.descripcion,
							medAct.usrAdm
						] ).draw( false );
		    		}
		    		
					
					$('[data-toggle="tooltip"]').tooltip();
		    		console.info(data.listaMedSumini);
		    	}
		      }
		  }
	});
}


function medicacionSuministradaByIdIngreso(){
	$('.loader-wrap').removeClass("hide");
	$('#tablaMedPautadosEnf').DataTable().clear().draw();
	
	var fecIniBusq = moment(new Date()).subtract(1,'hours').format('YYYY-MM-DD HH:mm');
	var fecFinBusq = moment(new Date()).add(8,'hours').format('YYYY-MM-DD HH:mm');
	$.ajax({
		  url: generarUrl('/private/medicacionSuministradaByIdIngreso'),
		  type:  'GET',
		  data:{ 'idingreso' : ingresosSelecc.id,
		  		 'fecIniBusq': fecIniBusq,
		  		 'fecFinBusq': fecFinBusq},
		  dataType: 'json',
		  success:  function (data) {
			  $('.loader-wrap').addClass("hide");
		      if(data){
		    	if(data.listaMedPau){
		    		var diaAct = new Date();
		    		
		    		var tmedSum = $('#tablaMedPautadosEnf').DataTable();
		    		for(var i = 0; i < data.listaMedPau.length; i++){
		    			var medPauAct = data.listaMedPau[i];
		    			var dateSumaMins = moment(medPauAct.fecini);
		    			var objEncontrado = [];
			    		while (dateSumaMins < moment(diaAct).add(8,'hours')) {
			    			dateSumaMins = dateSumaMins.add(medPauAct.periodominutos, 'minutes');
			    			
			    			if(dateSumaMins > moment(new Date()))
			    				objEncontrado.push(moment(dateSumaMins.format()))
			    				
			    			
			    		}
			    		
			    		
			    		var objAdd = [];
			    		objAdd.push(medPauAct.swiprecisa);
			    		objAdd.push(medPauAct.descripcion);
			    		
			    		swiMostrarAplicar = true;
			    		for(var j = 0; j < 9; j++){
			    			var encontrado = false;
			    			var mm = null;
			    			for(var z = 0; z < objEncontrado.length; z ++){
				    			var objAddAct = objEncontrado[z];
				    			if(moment(objAddAct).hours() == moment(new Date()).add(j, 'hours').hours()){
				    				mm = moment(new Date()).add(j, 'hours');
				    				encontrado = true;
				    				break;
				    			}
				    			
				    		}
			    			if(encontrado)
			    				objAdd.push(generarContenidoInfoCelda(medPauAct, mm.format(), data.listaMedSumini));
			    			else 
			    				objAdd.push("");
			    				
			    		}
			    		
			    		tmedSum.row.add(objAdd).draw( );
		    			
		    		}
		    		$('[data-toggle="tooltip"]').tooltip();
		    		$('.loader-wrap').addClass("hide");
		    	}
		      }
		  }
	});
}


function generarTablaMedicamentoPautados(){
	$('.resumenMediPau').empty();
	var dia = new Date();
	var horaIni = dia.getHours();
	var numhoras = 9;
	var tablaGen = `<table id="tablaMedPautadosEnf" class="table table-hover dataTable table-striped width-full">
					    <thead>
					       <tr>
					       	  <th>swiprecisa</th>
					          <th>Medicación</th>`;
					          
					          for(var i = 0; i < numhoras; i ++){
					        	  var mm = moment().add(i,'hours');
					        	  var fechaespacio = mm.format('DD/MM/YYYY') + '<br>';
					        	  var hmuestra = mm.hours();
					        	  tablaGen = tablaGen + '<th>'+ fechaespacio + hmuestra+':00</th>';
					          }
					          
					          
					          tablaGen = tablaGen  + `</tr>
					    </thead>
					    <tbody></tbody>
					    <tfoot>
					    	<tr>
					    	  <th>swiprecisa</th>
					          <th>Medicación</th>`;
					          for(var i = 0; i < numhoras; i ++){
					        	  var hmuestra = (horaIni + i) > 24? ((horaIni + i) -24): (horaIni + i);
					        	  tablaGen = tablaGen + '<th>'+ hmuestra+':00</th>';
					          }
					          tablaGen = tablaGen  + `</tr>
					    </tfoot>
					 </table>`;
	$('.resumenMediPau').append(tablaGen);
	$('#tablaMedPautadosEnf').DataTable({
		"columnDefs": [
		               { "visible": false, "targets": 0 }
		             ],
		"rowCallback": function( row, data, index ) {
			    if ( data[0] == "N" )
			    {
			        $('td', row).addClass('bg-blue-200');
			    }
			    else if ( data[0] == "S" )
			    {
			        $('td', row).addClass('bg-green-200');
			    }
			}
	  });
}

function initVtnMedHistorico(pos){
	
	medicacionSuministradaByIdIngresoHistorico();
	$('.mcHistMed').removeClass('hide');
	$('.mcActMed').addClass('hide');
	$('#gestionMedicacionEnf').modal();
}

function liberaHab(pos){
	swal({
		title: "Liberar Habitación",
		text: '¿Desea liberar la habitación?',
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: 'Confirmar',
		cancelButtonText: 'Cancelar',
		closeOnConfirm: true
	},function(isConfirm) {
		if (isConfirm) {
			$('.loader-wrap').removeClass("hide");
			$.ajax({
				  url: generarUrl('/private/liberaHab'),
				  type:  'GET',
				  data:{ 'idhab' : ingresosActuales[pos].idhab,
					  'id' : ingresosActuales[pos].id},
				  dataType: 'json',
				  success:  function (data) {
					  $('.loader-wrap').addClass("hide");
				      if(data){
				    	  swal('Correcto', data.msgOk, "success");
				    	  obtenerIngresosActivos();
				      }
				  }
			});
		}
	});
	
}

function initVtnMedSuministrada(pos){
	if(pos)
		ingresosSelecc = ingresosActuales[pos];
	generarTablaMedicamentoPautados();
	medicacionSuministradaByIdIngreso();
	$('.mcHistMed').addClass('hide');
	$('.mcActMed').removeClass('hide');
	$('#gestionMedicacionEnf').modal();
}

function obtenerIngresosActivos(){
	$('.loader-wrap').removeClass("hide");
	var tIng = $('#tablaIngresos').DataTable();
	ingresosActuales = null;
	$.ajax({
		  url: generarUrl('/private/obtenerIngresosActivos'),
		  type:  'GET',
		  dataType: 'json',
		  success:  function (data) {
			  $('.loader-wrap').addClass("hide");
		      if(data){
		    	if(data.litaIngresos){
		    		ingresosActuales = data.litaIngresos;
		    		tIng.clear().draw();
					for(var i = 0; i < data.litaIngresos.length; i++){
						var ingresoActual = data.litaIngresos[i];
						
						var listaBtn =`
						<div class="btn-group" aria-label="Acciones Usuario" role="group">`;
						
						if(data.opcDispo['solicitudes'] == 'S')
							listaBtn = listaBtn + `<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Solicitudes"><button type="button" onclick="gestionSolucitudes('`+i+`')" class="btn btn-icon  btn-success"><i class="icon wb-folder" aria-hidden="true"></i></button></span>`;
						if(data.opcDispo['evolutivos'] == 'S')
							listaBtn = listaBtn + `<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Evolutivo"><button type="button" onclick="initVentanaEvolutivos('`+i+`')" class="btn btn-icon  btn-success"><i class="icon wb-graph-up" aria-hidden="true"></i></button></span>`;
					   if(data.opcDispo['medicacion'] == 'S')
						   listaBtn = listaBtn + `<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Medicación"><button type="button" onclick="initVentanaMedicacion('`+i+`')" class="btn btn-icon  btn-success"><i class="icon wb-eye" aria-hidden="true"></i></button></span>`;
					   if(data.opcDispo['medicaPautada'] == 'S')
						   listaBtn = listaBtn + `<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Medicación"><button type="button" onclick="initVtnMedSuministrada('`+i+`')" class="btn btn-icon  btn-success"><i class="icon wb-tag" aria-hidden="true"></i></button></span>`;
					   if(data.opcDispo['alta'] == 'S')
						   listaBtn = listaBtn + `<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Dar de alta"><button type="button" onclick="confirmacionalta('`+i+`')" class="btn btn-icon  btn-success"><i class="icon wb-emoticon" aria-hidden="true"></i></button></span>`;
					   if(ingresoActual.idhab > 0)
						   listaBtn = listaBtn + `<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Liberar Habitación"><button type="button" onclick="liberaHab('`+i+`')" class="btn btn-icon  btn-success"><i class="icon wb-close" aria-hidden="true"></i></button></span>`;	   
					
						listaBtn = listaBtn + `</div>`;
						
						tIng.row.add( [
						               ingresoActual.deshab,
						               ingresoActual.nompac + ' ' + ingresoActual.ap1pac + ' ' + ingresoActual.ap2pac,
						               ingresoActual.desmed,
						               listaBtn
						] ).draw( false );
					}
					$('[data-toggle="tooltip"]').tooltip();
		    	}
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
	    	  usrSes = data.usuario;	    	
	      }
	  }
	});
}

function initTablonIngresos(){
	obtenerDatosUsuarioSession();
	obtenerIngresosActivos();
	
	if(listaMedicos){
		cargaComboOpt(listaMedicos,$('#sMedOpMed'), 'N' );
	}
}

initTablonIngresos();
