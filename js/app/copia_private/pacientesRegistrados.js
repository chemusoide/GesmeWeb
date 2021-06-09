$(document).ready(function(){
      
      setTimeout(
    		  function() 
    		  {
    			  $('[name="liMenuPac"]').addClass('active');
    		  }, 500);
      
      $('#tablaPacientes').DataTable( {
          "fnDrawCallback": function( oSettings ) {
          	$('[data-toggle="tooltip"]').tooltip(); 
            }
          });
      
      var table = $('#tablaHistoricoCitas').DataTable( {
    	  "ordering": false,
          "fnDrawCallback": function( oSettings ) {
          	$('[data-toggle="tooltip"]').tooltip(); 
            }
          });
      
      
      table.columns( [ 0 ] ).visible( false, false );
      
      $('#tablaCitasHoy').DataTable( {
          "fnDrawCallback": function( oSettings ) {
          	$('[data-toggle="tooltip"]').tooltip(); 
            }
          });
    
});
var pacientesActuales = null;
var combosCargados = false
var configMedico = null;
var idMedCal = null;
var pacMod = null;
var durCitaSel = null;
var fechaCalendario = null;
var usrSes = null;

var fechaInicioShow = null;
var fechaFinShow = null;
var totalCitasMon = null;
var totalCitasTue = null;
var totalCitasWen = null;
var totalCitasTh = null;
var totalCitasFri = null;
var totalCitasSat = null;
var totalCitasSun = null;

var horaCitaManual = null;
var minuCitaManual = null;
var citasVac = null;

var citasPacientesGrid = null;
var citaSelectGrid = null;

$('#gestionCitas').on('shown.bs.modal', function () {
	   $("#calendar").fullCalendar('render');
	});

$(document).on("click",".fc-prev-button",function(){
	actualizarVista();
});

$(document).on("click",".fc-next-button",function(){
	actualizarVista();
});

function diasVacaciones(){
	$('.fc-day.calendarVacaciones').css('background', '#BBD5EB');
}

function diasFestivos(){
	$('.fc-day.calendarFestivos').css('background', '#EB7F7C');
}

function obtenerCitasByFechas(){
	$('#calendar').fullCalendar('removeEvents');
	citasVac = null;
	$.ajax({
		data:{	'ideMed' : idMedCal,
				'fecIni' : fechaInicioShow,
				'fecFin' : fechaFinShow
			},
        url:   generarUrl('/private/obtenerCitasByFechas'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	
			if(data && data.totCitas){
				for(var i = 0; i < data.totCitas.length; i++){
					var d = new Date(data.totCitas[i].feccita);
					var newEvent = new Object();
					newEvent.title = data.totCitas[i].totalCita;
					newEvent.start = data.totCitas[i].feccita;
					newEvent.backgroundColor = '#FF8B07';
					
					if( (d.getDay() == 1 && data.totCitas[i].totalCita < totalCitasMon)
						|| ( d.getDay() == 2 && data.totCitas[i].totalCita < totalCitasTue)
						||( d.getDay() == 3 && data.totCitas[i].totalCita < totalCitasWen)
						||( d.getDay() == 4 && data.totCitas[i].totalCita < totalCitasTh)
						||( d.getDay() == 5 && data.totCitas[i].totalCita < totalCitasFri)
						||( d.getDay() == 6 && data.totCitas[i].totalCita < totalCitasSat)
						||( d.getDay() == 0 && data.totCitas[i].totalCita < totalCitasSun)
					){
						newEvent.backgroundColor = '#378006';
						
					}else{
						newEvent.title = newEvent.title + '- Completo';
					}
						
					
					newEvent.allDay = true;
					$('#calendar').fullCalendar( 'renderEvent', newEvent );	
				}
				
			}
			//Gestionamos las vacaiones
			$('.fc-day').removeClass('calendarVacaciones');
			if(data.listaVaciones && data.listaVaciones.length > 0){
				
				for(var i = 0; i < data.listaVaciones.length; i++){
					var regVac = data.listaVaciones[i];
					var diaIniVac = new Date(fechaInicioShow)>=new Date(regVac.fecini)?fechaInicioShow:regVac.fecini;
					var diaFinVac = new Date(fechaFinShow)<=new Date(regVac.fecfin)?fechaFinShow:regVac.fecfin;
					var fechaTratada = diaFinVac;
										
					var diaIni = new Date(diaIniVac).getDate();
					var diaFin = (new Date(diaIniVac).getMonth()+1)!=(new Date(diaFinVac).getMonth()+1)?31:new Date(diaFinVac).getDate();
					
					for(var j = diaIni; j <= diaFin; j++){
						
						//Añadimos la clase para despues añadirle fondo
						fechaTratada = new Date(diaIniVac).getFullYear() + "-" + (new Date(diaIniVac).getMonth()+1<10?'0'+(new Date(diaIniVac).getMonth()+1):new Date(diaIniVac).getMonth()+1) + "-" + (j <10? '0'+j:j);
						$( "td[data-date='"+ fechaTratada +"']" ).addClass('calendarVacaciones');
						
						var newEvent = new Object();
						newEvent.title = 'Ausencia';
						newEvent.start = fechaTratada;
						newEvent.backgroundColor = '#A0B6C9';
						newEvent.allDay = false;
						$('#calendar').fullCalendar( 'renderEvent', newEvent );
						
					}
					
					diaFinVac = new Date(diaFinVac).getFullYear() + "-" + (new Date(diaFinVac).getMonth()+1<10?'0'+(new Date(diaFinVac).getMonth()+1):new Date(diaFinVac).getMonth()+1) + "-" + (new Date(diaFinVac).getDate()<10?'0'+new Date(diaFinVac).getDate():new Date(diaFinVac).getDate());

					$( "td[data-date='"+ diaFinVac +"']" ).addClass('calendarVacaciones');
				}
				
			}
			diasVacaciones();
			
			//DIAS FESTIVOS
			
			$('.fc-day').removeClass('calendarFestivos');
			if(data.listaFestivos && data.listaFestivos.length > 0){
				
				for(var i = 0; i < data.listaFestivos.length; i++){
					var festivo = data.listaFestivos[i];
										
					var fechaTratada = new Date(festivo.fecha).getDate();

						
					//Añadimos la clase para despues añadirle fondo
					$( "td[data-date='"+ festivo.fecha +"']" ).addClass('calendarFestivos');
					
					var newEvent = new Object();
					newEvent.title = 'FESTIVO';
					newEvent.start = festivo.fecha;
					newEvent.backgroundColor = '#C96D6A';
					newEvent.allDay = false;
					$('#calendar').fullCalendar( 'renderEvent', newEvent );
				}
				
			}
			diasFestivos();
			
			if(data && data.citasVacaciones && data.citasVacaciones.length > 0){
				citasVac = data.citasVacaciones;
				$('#dGestionCitasSolVac').removeClass('hide');
			}else
				$('#dGestionCitasSolVac').addClass('hide');
			citasEnvacacionesInit();

        }
		});
}

function actualizarVista(){
	var view = $('#calendar').fullCalendar('getView');
	var d = view.intervalStart._d;
	fechaInicioShow = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
	
	var d = view.intervalEnd._d;
	fechaFinShow = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
	
	obtenerCitasByFechas();
	
	configurarCalendarioMedico();
}

function creaCalendario(){
	$('#calendar').fullCalendar({
		height: 500,
		editable: true,										
		events: '',
		theme: true,
		header: {
			left: 'prev,next,today, title',
			center: '',
			right: 'month,basicWeek,basicDay'
		},
		firstDay : 1,
		eventDrop: function(event, delta) {
			alert(event.title + ' se movió ' + delta + ' días\n' +
				'(se actualizaría en la base de datos)');
			return false;
		},
		loading: function(bool) {
			if (bool) $('#loading').show();
			else $('#loading').hide();
		},
  	eventClick: function(event) {
			if (event.targetUrl) {
			    window.open(event.targetUrl);
			    return false;
			}
  	},
  	dayClick: function(date, jsEvent, view) {
  			if(date >= view.intervalEnd._d || date < view.intervalStart._d ){
				$('#divCal').removeClass('col-sm-8');
		  		$('#divCal').addClass('col-sm-12');
				$('#containerBtnCita').addClass('hide');
				$('#obscita').val('');
				$('.dObscita').addClass('hide');
				return true;
			}
	  		var day_int = date._d.getDay();
	  		fechaCalendario = date.format();
	  		  
	  		configurarCalendarioMedico();
	  		obtenerDisponivilidad(day_int);
	  		$('#divCal').addClass('col-sm-8');
	  		$('#divCal').removeClass('col-sm-12');
	  		$('#containerBtnCita').removeClass('hide');
	  		$('#obscita').val('');
	  		$('.dObscita').removeClass('hide');
	  		
		        // change the day's background color just for fun
		    $(this).css('background-color', '#FAE7C5');
		    diasVacaciones();

	    },
		monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		monthNamesShort:['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
		dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
		dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
		buttonText: {
			prev: '<',
			next: '>',
			today: 'Hoy',
			day: 'día',
			week:'semana',
			month:'mes'
		}
	});/*End Calendar*/
}

function obtenerDatosUsuarioSession(){
	usrSes = null;
	$.ajax({
      url:   generarUrl('/private/obtenerDatosUsuarioSession'),
      type:  'GET',
      dataType: 'json',
      success:  function (data) {

          if(data && data.usuario){
        	  usrSes = data.usuario;
          }
      }
    });
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

function initPantallaAltaModPacientes (){
	$('.loader-wrap').removeClass("hide");
	$('#fgIdHistorial').addClass("hide");
	limpiarForm();
	$.ajax({
	        url:   generarUrl('/private/initPantallaAltaModPacientes'),
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
	        		
	            }
	        }
	}); 
}

function obtenerPacienteSelect(p){	
	pacMod = pacientesActuales[p];	
}

function perfilPaciente(pos){
	limpiarForm();
	pacMod = null;
	if(!combosCargados){
		initPantallaAltaModPacientes();
	}
	
	if(pos && pacientesActuales){
		
		obtenerPacienteSelect(pos);
		
		if(pacMod){
			$('#id').val(pacMod.id);
			$('#fgIdHistorial').removeClass("hide");
			$('#infoidHistorial').val(pacMod.id);
			$('#nompac').val(pacMod.nompac);
			$('#ap1pac').val(pacMod.ap1pac);
			$('#ap2pac').val(pacMod.ap2pac);
			if(pacMod.fecnacpac){
				$('#fecnacpac').val(fechaString(pacMod.fecnacpac));
			}
			
			$( "#tipdoc" ).val(pacMod.tipdoc);
			
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
			if(pacMod.swilopd == 'S')
				$("#swilopd").prop('checked', true);
			else
				$("#swilopd").prop('checked', false);
			if(pacMod.swilopdcan == 'S')
				$("#swilopdcan").prop('checked', true);
			else
				$("#swilopdcan").prop('checked', false);
			
		}
	}
	
}


function buscarPacienteNomAp(fTipo){ //añadir los filtros que se necesiten
   var parametros = {
		   'nompac': $('#nompacBusq').val(),
		   'ap1pac': $('#ap1pacBusq').val(),
		   'ap2pac': $('#ap2pacBusq').val(),
		   'dniusr' : $( "#tipdocBusq" ).val()== 'DNI'? $("#dniusrBusq").val(): $( "#tipdocBusq" ).val()== 'NIE'? $("#nieusrBusq").val():$( "#tipdocBusq" ).val()== 'PAS'? $("#passusrBusq").val():'',
		   'idHistorial' :$('#idHistorialBusq').val()
    };
    $('.loader-wrap').removeClass("hide");
    $.ajax({
            data:  parametros,
            url:   generarUrl('/private/buscarPacienteNomAp'),
            type:  'GET',
            dataType: 'json',
            success:  function (data) {
                pacientesActuales = null;
                var tPac = $('#tablaPacientes').DataTable();
                tPac.clear().draw();
            	
                if(data && data.listaPacientes){
                    pacientesActuales = data.listaPacientes;
            		for(var i = 0; i < data.listaPacientes.length; i++){
						var pacActual = data.listaPacientes[i];
                        var dni = '<span class="text-uppercase">'+(pacActual.dniusr?pacActual.dniusr:'Sin documento')+'</span>'
                        var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
                        listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Editar Paciente"><button type="button" onclick="perfilPaciente(\''+i+'\')" class="btn btn-icon btn-flat btn-default" data-target="#gestionPacientes" data-toggle="modal"><i class="icon wb-edit" aria-hidden="true"></i></button></span>';
                        if(pacActual.dniusr){
                        	listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Historico Citas"><button type="button" onclick="historicoPaciente(\''+i+'\')" class="btn btn-icon btn-flat btn-default" data-target="#historicoCitas" data-toggle="modal"><i class="icon wb-time" aria-hidden="true"></i></button></span>';
                            listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Gestionar Cita"><button type="button" onclick="citaPaciente(\''+i+'\')" class="btn btn-icon btn-flat btn-default" data-target="#gestionCitas" data-toggle="modal"><i class="icon wb-inbox" aria-hidden="true"></i></button></span>';
                            listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Doc.Firmados"><button type="button" onclick="docsPaciente(\''+i+'\')" class="btn btn-icon btn-flat btn-default" data-target="#gestionDocsFirmados" data-toggle="modal"><i class="icon wb-paperclip" aria-hidden="true"></i></button></span>';
                        }else{
                        	listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Paciente Incompleto: sin documento"><button type="button"  class="btn btn-icon btn-flat btn-default" ><i class="icon wb-info" aria-hidden="true"></i></button></span>';
                        }
                        
                        
                        listaBtn = listaBtn + '</div>';
                        var nSeguro = pacActual.nomseguro && pacActual.nomseguro.length > 20 ?  pacActual.nomseguro.substring(0, 20) + '...':  pacActual.nomseguro;
                        var numtel = pacActual.numtel1 + ' - ' + pacActual.numtel2;
            			tPac.row.add( [
                            pacActual.nompac ,
                            pacActual.ap1pac,
                            pacActual.ap2pac,
                            nSeguro,
							dni,
							pacActual.emailpac,
							numtel,
                            listaBtn
                            
                        ] ).draw( false );
                    }    
                    $('[data-toggle="tooltip"]').tooltip();     

                     $('.loader-wrap').addClass("hide");
                }
                 
            }//Fin success
    });//Fin Ajax
}

function mostrarNumDoc(extension){
	//var strCompTip = "#tipdoc";
	
	if($( "#tipdoc" + extension).val()== 'DNI'){
		$( "#dniusr" + extension ).removeClass('hide');
		$( "#nieusr" + extension ).addClass('hide');
		$( "#passusr" + extension ).addClass('hide');
	}
	if($( "#tipdoc" + extension ).val()== 'NIE'){
		$( "#dniusr" + extension ).addClass('hide');
		$( "#nieusr" + extension ).removeClass('hide');
		$( "#passusr" + extension ).addClass('hide');
	}
	if($( "#tipdoc" + extension ).val()== 'PAS'){
		$( "#dniusr" + extension ).addClass('hide');
		$( "#nieusr" + extension ).addClass('hide');
		$( "#passusr" + extension ).removeClass('hide');
	}
}

$( "#tipdoc" ).change(function(v) {
	$( "#dniusr" ).val('');
	$( "#nieusr" ).val('');
	$( "#passusr" ).val('');
	
	mostrarNumDoc('');
});

$( "#tipdocBusq" ).change(function(v) {
	$( "#dniusrBusq" ).val('');
	$( "#nieusrBusq" ).val('');
	$( "#passusrBusq" ).val('');
	
	mostrarNumDoc('Busq');
});

function addError(c){
    c.addClass("has-error");
}
function removeError(c){
    c.removeClass("has-error");
}
function guardarPaciente(){

	//Campos Obligatorios
	var error = false;
	var numdoc = $( "#tipdoc" ).val()== 'DNI'? $("#dniusr").val(): $( "#tipdoc" ).val()== 'NIE'? $("#nieusr").val():$( "#tipdoc" ).val()== 'PAS'? $("#passusr").val():'';
	if(!$("#nompac").val()){error = true; addError($("#fgNombre"));} else removeError($("#fgNombre"));
	if(!$("#ap1pac").val()){
		error = true; addError($("#fgSurName1"));
		} else removeError($("#fgSurName1"));
	//if(!numdoc){error = true; addError($("#fgDni"));} else removeError($("#fgDni"));
	if(!$("#sexpac").val()){error = true; addError($("#fgSexpac"));} else removeError($("#fgSexpac"));
	
	numdoc = (numdoc=='        - ')|| numdoc==''?null:numdoc;
	if(error){
		swal("Error", 'Debe de Rellenar los campos Obligatorios: Nombre, Primer apellido, DNI y Sexo', "error");
		return false;
	}
	$('.loader-wrap').removeClass("hide");
	
	var parametros = {
			'id': $('#id').val(),
			'nompac': $('#nompac').val(),
			'ap1pac': $('#ap1pac').val(),
			'ap2pac': $('#ap2pac').val(),
			'fecnacpac': $('#fecnacpac').val(),
			'sexpac': $('#sexpac').val(),
			'numtel1': $('#numtel1').val(),
			'numtel2': $('#numtel2').val(),
			'tipdoc': numdoc?$('#tipdoc').val():null,
			'dniusr': numdoc,
			'emailpac': $('#emailpac').val(),
			'dirpac': $('#dirpac').val(),
			'cppac': $('#cppac').val(),
			'idpais': $('#sIdpais').val(),
			'idseguro': $('#sIdseguro').val(),
			'numseg': $('#numseg').val(),
			'comentario': $('#comentario').val(),
			'codUsr': usrSes.id,
			'swilopd': $('#swilopd').is(":checked")|| !$('#id').val()? 'S': 'N',
			'swilopdcan': $('#swilopdcan').is(":checked")? 'S': 'N'
		};
	
	$.ajax({
		data: parametros,
        url:   generarUrl('/private/guardarPaciente'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.paciente){
        		//obtenerUsuarios();
        		swal({
        	      	title: "Correcto",
        			text: 'Paciente dado de alta',
        			type: "success",
        			showCancelButton: false,
        			confirmButtonClass: 'btn-success',
        			confirmButtonText: 'Continuar',
        			closeOnConfirm: true
        	    },
        	    function(isConfirm) {
        	    	if (isConfirm) {
        	    		if(pacientesActuales)
        	    			buscarPacienteNomAp();
        	    		$("#gestionPacientes").modal("toggle");
        	    		if(!$('#id').val())
        	    			imprimirDoc(data.paciente.id);
        	    	}
        	    });
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
        		if(newEst=='CAN')
        			obtenerCitasByFechas();
        		else
        			obtenerCitasActuales();
        	}
        }
});

}

function modificarMsgCitaInit(orden){
	citaSelectGrid = citasPacientesGrid[orden];
	$('#comentarioMod').val(citasPacientesGrid[orden].obscita);
}

function modificarMsgCita(){
	if(citaSelectGrid){
		$('.loader-wrap').removeClass("hide");
		$.ajax({
			data:{
				  'id' : citaSelectGrid.id,
				  'obscita': $('#comentarioMod').val()?$('#comentarioMod').val():''
			},
	        url:   generarUrl('/private/modificarMsgCita'),
	        type:  'POST',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	if(data && data.msgOk){
					swal({
				      	title: "Comentario Modificado",
						text: data.msgOk,
						type: "success",
						showCancelButton: false,
						confirmButtonColor: '#DD6B55',
						confirmButtonText: 'Cerrar',
						closeOnConfirm: true
				    },
				    function(isConfirm) {
				    	if (isConfirm) {
				    		$('#insModComentarios').modal('toggle');
				    		obtenerCitasActuales();
				    	}
				    });
	        	}
	        }
		});
	}
	
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
	}
	else
		accionModificarEstadoCita(idCita, newEst);
}

function obtenerCitasActuales(){
	citasPacientesGrid = null;
	var tPac = $('#tablaCitasActuales').DataTable();
    tPac.clear().draw();
	$.ajax({
			data : {'id': pacMod.id},
	        url:   generarUrl('/private/obtenerCitasActuales'),
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	if(data && data.citas){
	        		citasPacientesGrid = data.citas;
	        		for(var i = 0; i < data.citas.length; i++){
	        			 var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">'
	        				 + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Modificar Comentario"><button type="button" data-toggle="modal" data-target="#insModComentarios" onclick="modificarMsgCitaInit(\''+i+'\',\'CAN\')" class="btn btn-info btn-icon waves-effect waves-light"><i class="icon wb-info-circle " aria-hidden="true"></i></button></span>';	        			
	        			var hours = Math.floor( data.citas[i].hora / 60 );  
						var minutes =  (data.citas[i].hora % 60);
						var estCita = 'Sin Estado';
						if(data.citas[i].codestado && data.estadosCita){
							for(var j =0; j < data.estadosCita.length; j++ ){
								if(data.estadosCita[j].coddom == data.citas[i].codestado){
									if(data.citas[i].codestado == 'PLN'){
										estCita = '<span class="label  label-info">'+data.estadosCita[j].desval+'</span>';
										listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Poner Paciente En Espera"><button type="button" onclick="modificarEstadoCita(\''+data.citas[i].id+'\',\'ESP\')" class="btn btn-warning btn-icon waves-effect waves-light" ><i class="icon wb-warning " aria-hidden="true"></i></button></span>';
										listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Cancelar Cita"><button type="button" onclick="modificarEstadoCita(\''+data.citas[i].id+'\',\'CAN\')" class="btn btn-danger btn-icon waves-effect waves-light"><i class="icon wb-minus-circle " aria-hidden="true"></i></button></span>';
									}
										
									if(data.citas[i].codestado == 'CAN')
										estCita = '<span class="label  label-danger">'+data.estadosCita[j].desval+'</span>';
									if(data.citas[i].codestado == 'ESP'){
										estCita = '<span class="label  label-warning">'+data.estadosCita[j].desval+'</span>';
										listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Cita Planificada"><button type="button" onclick="modificarEstadoCita(\''+data.citas[i].id+'\',\'PLN\')" class="btn btn-info btn-icon waves-effect waves-light" ><i class="icon wb-reply " aria-hidden="true"></i></button></span>';
										listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Cancelar Cita"><button type="button" onclick="modificarEstadoCita(\''+data.citas[i].id+'\',\'CAN\')" class="btn btn-danger btn-icon waves-effect waves-light"><i class="icon wb-minus-circle " aria-hidden="true"></i></button></span>';
									}
										
									if(data.citas[i].codestado == 'ABR')
										estCita = '<span class="label  label-success">'+data.estadosCita[j].desval+'</span>';
									if(data.citas[i].codestado == 'FIN')
										estCita = '<span class="label  label-primary">'+data.estadosCita[j].desval+'</span>';
									break;
								}
							}
						}
						 listaBtn = listaBtn + '</div>';
						
						if(hours<10) hours = '0'+hours;
						if(minutes<10) minutes = '0'+minutes;
						var strObs = data.citas[i].obscita;
						if(strObs && strObs.length > 50)
							strObs = strObs.substring(0,50) + '...';
	        			tPac.row.add( [
		                               data.citas[i].nomusr + ' ' + data.citas[i].apusr ,
		                               fechaString(data.citas[i].feccita),
		                               hours+':'+minutes,
		                               estCita + (data.citas[i].obscita?' <span class="label  label-primary" data-toggle="tooltip" data-original-title="'+strObs+'"><li class="icon wb-info-circle"></li></span>' :'') ,
		                               listaBtn
		                               
		                           ] ).draw( false );
	        		}
	        		$('[data-toggle="tooltip"]').tooltip();
	        	}
	        }
	});
}

function obtenerEspecialidades(){
	$('#sEspecMed').empty();
	$('#sMedico').empty();
	$('#sEspecMed').append( $('<option>', { value: '', text: 'Especialidades' }));
	
	$('.loader-wrap').removeClass("hide");
	$.ajax({
	        url:   generarUrl('/private/obtenerEspecialidades'),
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	if(data && data.listEspec){
	        		obtenerCitasActuales();
	        		for(var i = 0; i < data.listEspec.length; i++){
	        			$('#sEspecMed').append( $('<option>', { value: data.listEspec[i].codesp , text: data.listEspec[i].especialidad }));
	        		}
	        	}
	        }
	});
}

$( "#sEspecMed" ).change(function() {
	$('#sMedico').empty();
	cancelGuardado();
	$('#sMedico').append( $('<option>', { value: '', text: 'Especialista' }));
	if($('#sEspecMed').val()){
		$.ajax({
			data:{'codesp' : $('#sEspecMed').val()},
	        url:   generarUrl('/private/obtenerEspecialistas'),
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	if(data && data.listMedicos){
	        		for(var i = 0; i < data.listMedicos.length; i++){
	        			var nombre =  data.listMedicos[i].apusr + ', ' + data.listMedicos[i].nomusr;
	        			$('#sMedico').append( $('<option>', { value: data.listMedicos[i].ideusr , text: nombre }));
	        		}
	        	}
	        }
		});
	}
});

$( "#sMedico" ).change(function() {
	cancelGuardado();
	$('#calendar').fullCalendar('removeEvents');
	if($('#sMedico').val()){
		$('#calendar').removeClass('hide');
		
		prepararCalendario($('#sMedico').val());
		
	}else{
		$('#calendar').addClass('hide');
	}
});

function celdaNoTrabaja(diaSem, color){
	$('.fc-day.ui-widget-content.fc-'+diaSem).css('background-color', color);
}

function configurarCalendarioMedico(){
	var arr = new Array();
	totalCitasMon = 0;
	totalCitasTue = 0;
	totalCitasWen = 0;
	totalCitasTh = 0;
	totalCitasFri = 0;
	totalCitasSat = 0;
	totalCitasSun = 0;
	for(var i = 0; i < configMedico.length; i++){
		var config = configMedico[i];
		
		var dur = config.durcon;
		var iniMins = (config.horaini *60) + config.minini;
		var finMins = (config.horafin *60) + config.minfin;
		var totalMins = finMins - iniMins;
		var totalCitas = totalMins/dur;

		
		if(config.diaseml == 'S'){
			totalCitasMon = totalCitasMon + totalCitas;
			arr['L'] = 'S';
		} 
		if(config.diasemm == 'S'){
			totalCitasTue = totalCitasTue + totalCitas;
			arr['M'] = 'S';
		} 
		if(config.diasemx == 'S'){
			totalCitasWen = totalCitasWen + totalCitas;
			arr['X'] = 'S';
		} 
		if(config.diasemj == 'S'){
			totalCitasTh = totalCitasTh + totalCitas;
			arr['J'] = 'S';
		} 
		if(config.diasemv == 'S'){
			totalCitasFri = totalCitasFri + totalCitas;
			arr['V'] = 'S';
		} 
		if(config.diasems == 'S'){
			totalCitasSat = totalCitasSat + totalCitas;
			arr['S'] = 'S';
		} 
		if(config.diasemd == 'S'){
			totalCitasSun = totalCitasSun + totalCitas;
			arr['D'] = 'S';
		} 
		
	}
	//marcamos en gris los dias que no trabaja el médico
	if(!arr['L'])celdaNoTrabaja('mon' ,'#DFE2E3');else celdaNoTrabaja('mon' ,'#F3EDB1');
	if(!arr['M'])celdaNoTrabaja('tue' ,'#DFE2E3');else celdaNoTrabaja('tue' ,'#F3EDB1');
	if(!arr['X'])celdaNoTrabaja('wed' ,'#DFE2E3');else celdaNoTrabaja('wed' ,'#F3EDB1');
	if(!arr['J'])celdaNoTrabaja('thu' ,'#DFE2E3');else celdaNoTrabaja('thu' ,'#F3EDB1');
	if(!arr['V'])celdaNoTrabaja('fri' ,'#DFE2E3');else celdaNoTrabaja('fri' ,'#F3EDB1');
	if(!arr['S'])celdaNoTrabaja('sat' ,'#DFE2E3');else celdaNoTrabaja('sat' ,'#F3EDB1');
	if(!arr['D'])celdaNoTrabaja('sun' ,'#DFE2E3');else celdaNoTrabaja('sun' ,'#F3EDB1');
	
	$('.fc-day.fc-today').css('background', '#BCE3B8');
	$('.fc-day.fc-past').css('background', '#F1F4F5');
}


function prepararCalendario(idMed){
	$('.loader-wrap').removeClass("hide");
	configMedico = [];
	idMedCal = idMed;
	$.ajax({
		data:{'idusr' : idMed},
        url:   generarUrl('/private/obtenerAgendaCitasMed'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	
        	if(data && data.listaConfig){
        		
        		configMedico = data.listaConfig;
        		creaCalendario();
        		actualizarVista();
        	}
        }
	});
	
	
	/******Pruebas ************/
	/* var delay=1000; //1 second
	 setTimeout(function() {
		 $('.loader-wrap').addClass("hide");
		 var start_date =  $('#calendar').fullCalendar('getView').start
	      console.log(start_date);
		 
		 //$('.fc-day.ui-widget-content.fc-sun').css('background-color', '#DFE2E3');
		 
		 if(start_date){
			  var newEvent = new Object();

			  newEvent.title = "Nombre del paciente";
			  newEvent.start = new Date(start_date._d);
			  newEvent.allDay = false;
			  $('#calendar').fullCalendar( 'renderEvent', newEvent , true);
		 }
	 }, delay);*/
	 /******************/
}

function obtenerDisponivilidad( diaSem ){
	$('#horasDisp').empty();
	horaCitaManual = null;
	minuCitaManual = null;
	$.ajax({
		data:{'diaSem' : diaSem,
			  'idmed' : idMedCal,
			  'idusr' : usrSes.id,
			  'feccita' : fechaCalendario
		},
        url:   generarUrl('/private/obtenerDisponivilidad'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	console.info(data);
        	if(data){
        		if(data.listaConfig){
        			durCitaSel = null;
        			for(var i = 0; i < data.listaConfig.length; i++ ){
        				var configTratada = data.listaConfig[i];
        				var dur = configTratada.durcon;
        				var iniMins = (configTratada.horaini *60) + configTratada.minini;
        				var finMins = (configTratada.horafin *60) + configTratada.minfin;
        				var totalMins = finMins - iniMins;
        				var totalCitas = totalMins/dur;
        				
        				for(var x = 0; x < totalCitas; x++){
        					var existe = false;
        					var horaCitaMis = iniMins + (dur * x);
        					var hours = Math.floor( horaCitaMis / 60 );  
        					var minutes =  (horaCitaMis % 60);
        					
        					if(data.listaCitas){
        						for(j = 0; j < data.listaCitas.length; j++){
        							if(data.listaCitas[j].hora == horaCitaMis){
        								existe = true;
        								break;
        							}
        						}
        					}
        					var strDiv = '';
        					if(existe == false){
        						
            					strDiv = '<div class="form-group"><div class="radio-custom radio-primary">'
             	               +'<input onclick="bloquearSeleccion('+dur+', '+horaCitaMis+')" type="radio" id="hor'+ i +'-'+ x +'" value= "'+horaCitaMis+'" name="horaMedSelect">'
             	                +'<label for="hor'+ i +'-'+ x +'">'+ (hours<10? '0'+hours: hours )+':'+(minutes<10? '0'+minutes: minutes )+'</label>'
             	             + '</div></div>';
        					}else{
        						strDiv = '<div class="form-group" ><div class="radio-custom radio-primary">'
                   	               +'<input disabled type="radio" id="hor'+ i +'-'+ x +'" value= "'+horaCitaMis+'" name="horaMedSelect">'
                   	                +'<label style="color: red;" for="hor'+ i +'-'+ x +'">'+ (hours<10? '0'+hours: hours )+':'+(minutes<10? '0'+minutes: minutes )+'</label>'
                   	             + '</div></div>';
         					}
         					$('#horasDisp').append(strDiv);
        					
        					
        				}
        				
        			}
        			for(j = 0; j < data.listaCitas.length; j++){
						if(data.listaCitas[j].tipcita == 'MAN'){
							var hours = Math.floor(data.listaCitas[j].hora / 60 );  
        					var minutes =  (data.listaCitas[j].hora % 60);
        					
							pacienteStr = data.listaCitas[j].nompac + ' ' + data.listaCitas[j].ap1pac + ' ' + data.listaCitas[j].ap2pac;
							idPacLista = data.listaCitas[j].idpac;
							citaFind = data.listaCitas[j];

    						var strDiv = '<div class="form-group text-left" >'
              	                + '<button type="button" data-toggle="modal" data-target="#gestionPacientes" onclick="abrirInfoUsuario(\''+idPacLista+'\')" class="btn btn-danger btn-icon waves-effect waves-light"><i class="icon wb-info-circle " aria-hidden="true"></i></button>';
    						
    						if(citaFind.codestado == 'PLN' )
    							strDiv = strDiv + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Poner Paciente En Espera"><button type="button" onclick="modificarEstadoCita(\''+citaFind.id+'\',\'ESP\', \''+ diaSem +'\')" class="btn btn-warning btn-icon waves-effect waves-light" ><i class="icon wb-warning " aria-hidden="true"></i></button></span>'
    						strDiv = strDiv + '<label style="color: red;" for="hor'+ i +'-'+ x +'">(MANUAL) '+ (hours<10? '0'+hours: hours )+':'+(minutes<10? '0'+minutes: minutes )
              	                + ' - ' + pacienteStr.toUpperCase()
              	                +'</label>'
              	             + '</div>';
    						$('#horasDisp').append(strDiv);
						}
					}
    				
    				$('#horasDisp').append('<div class="form-group text-left"><span id="sCitaManual"></span></div>');
        		}
        	}
        }
	});
}

function guardarCita(){	
	var horamed = $("input[name=horaMedSelect]:checked").val();
	if(!horamed){
		if(horaCitaManual && minuCitaManual){
			horamed = parseInt( horaCitaManual * 60 ) + parseInt(minuCitaManual);
		}
		else{
			swal("Error", 'Debe seleccionar una cita o crearla manualmente', "error");
			return false;
		}
			
	}
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		data:{
			  'idusr' : idMedCal,
			  'idpac' : pacMod.id,
			  'hora' : horamed,
			  'feccita' : fechaCalendario,
			  'codestado' : 'OK',
			  'codesp' : $('#sEspecMed').val(),
			  'durcon' : durCitaSel,
			  'tipcita': horaCitaManual?'MAN':'',
			  'obscita': $('#obscita').val()?$('#obscita').val():'',
			  'codUsr' : usrSes.id		  
		},
        url:   generarUrl('/private/guardarCita'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.nombreCompleto){
        		var hours = Math.floor( data.hora / 60 );  
				var minutes =  (data.hora % 60);
				if(hours < 10) hours = '0'+hours;
				if(minutes < 10) minutes = '0'+minutes;
				var info = data.info?data.info:'';
				swal({
			      	title: "Cita creada Correctamente",
					text: data.nombreCompleto + ' el día ' + fechaString(data.feccita) + ' a las ' + hours + ':' + minutes + ' horas.' + info,
					type: "success",
					showCancelButton: false,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Cerrar',
					closeOnConfirm: true
			    },
			    function(isConfirm) {
			    	if (isConfirm) {
			    		limpiarFormCitas();
			    	}
			    });
        	}
        }
	});
}

function citasEnvacacionesInit(){
	
	
	var tCitVac = $('#tablaCitasVacaciones').DataTable();
	tCitVac.clear().draw();
	 if(citasVac){
		 for(var i = 0; i< citasVac.length; i++){
				var dCita = citasVac[i];
				if(dCita.length > 0){
					for(var j = 0; j < dCita.length; j++ ){
						var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
		                listaBtn= listaBtn + '<span><button type="button" class="btn btn-icon btn-flat btn-default" data-toggle="tooltip" data-placement="top" data-trigger="hover" onclick="modificarEstadoCita(\''+dCita[j].id+'\',\'CAN\')" data-original-title="Cancelar Cita"><i class="icon wb-close text-danger" aria-hidden="true"></i></button></span>';
		                listaBtn = listaBtn + '</div>';
						
						tCitVac.row.add( [
				   			dCita[j].nompac ,
				   			dCita[j].ap1pac + ' ' + dCita[j].ap2pac,
				   			dCita[j].numtel1 + ' - ' + dCita[j].numtel2,
				   			fechaString(dCita[j].feccita),
				   			dCita[j].especialidad,
				   			listaBtn
				   			
				   		] ).draw( false );
					}
				}
			}
	 }

	$('[data-toggle="tooltip"]').tooltip();

}

function cancelGuardado(){
	$('#divCal').removeClass('col-sm-8');
	$('#divCal').addClass('col-sm-12');
	$('#containerBtnCita').addClass('hide');
	$('#obscita').val('');
	$('.dObscita').addClass('hide');
}

function citaPaciente(pos){
	$('#calendar').addClass('hide');
	idMedCal = null;
	obtenerPacienteSelect(pos);
	fechaCalendario = null;
	obtenerEspecialidades();
}

function verDocumentoFirma(iddoc){
	$("#iddoc").val(iddoc);
	$( "#fverFirma" ).submit();
}

function docsPaciente(pos){
	obtenerPacienteSelect(pos);
	 var tDoc = $('#tablaFirmaDoc').DataTable();
	 tDoc.clear().draw();
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		data:{
			'idpac' : pacMod.id
		},
        url:   generarUrl('/firma/obtenerDocFirmaPac'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.listaDocs){
        		for(var i = 0; i < data.listaDocs.length; i++){
        			var btn = '<span><button type="button" class="btn btn-icon btn-flat btn-default" data-toggle="tooltip" data-placement="top" data-trigger="hover" onclick="verDocumentoFirma(\''+data.listaDocs[i].id+'\',\'CAN\')" data-original-title="Cancelar Cita"><i class="icon wb-eye text-danger" aria-hidden="true"></i></button></span>';
        			tDoc.row.add( [
        		                   data.listaDocs[i].nombre,
        		                   fechaString(data.listaDocs[i].fecha),
        		                   btn	                               
        		               ] ).draw( false );	
        		}
        	}
        }
	});
}

function historicoPaciente(pos){
	obtenerPacienteSelect(pos);
	fechaCalendario = null;
	

	 var tPac = $('#tablaHistoricoCitas').DataTable();
    tPac.clear().draw();
	$.ajax({
			data : {'id': pacMod.id},
	        url:   generarUrl('/private/obtenerCitasHistorico'),
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	if(data && data.citas){
	        		for(var i = 0; i < data.citas.length; i++){
	        			
	        			var hours = Math.floor( data.citas[i].hora / 60 );  
						var minutes =  (data.citas[i].hora % 60);
						var estCita = 'Sin Estado';
						if(data.citas[i].codestado && data.estadosCita){
							for(var j =0; j < data.estadosCita.length; j++ ){
								if(data.estadosCita[j].coddom == data.citas[i].codestado){
									if(data.citas[i].codestado == 'PLN'){
										estCita = '<span class="label  label-info">'+data.estadosCita[j].desval+'</span>';
									}
										
									if(data.citas[i].codestado == 'CAN')
										estCita = '<span class="label  label-danger">'+data.estadosCita[j].desval+'</span>';
									if(data.citas[i].codestado == 'ESP'){
										estCita = '<span class="label  label-warning">'+data.estadosCita[j].desval+'</span>';
									}
										
									if(data.citas[i].codestado == 'ABR')
										estCita = '<span class="label  label-success">'+data.estadosCita[j].desval+'</span>';
									if(data.citas[i].codestado == 'FIN')
										estCita = '<span class="label  label-primary">'+data.estadosCita[j].desval+'</span>';
									break;
								}
							}
						}
						
						if(hours<10) hours = '0'+hours;
						if(minutes<10) minutes = '0'+minutes;
	        			tPac.row.add( [ 
	        			               data.citas[i].feccita,
		                               data.citas[i].nomusr + ' ' + data.citas[i].apusr ,
		                               fechaString(data.citas[i].feccita),
		                               hours+':'+minutes,
		                               data.citas[i].obscita,
		                               estCita		                               
		                           ] ).draw( false );
	        		}
	        		$('[data-toggle="tooltip"]').tooltip();
	        		
	        	}
	        }
	});

	
	
}

function bloquearSeleccionTxt(v,h){
	bloquearSeleccion()
}

function bloquearSeleccion(v,h){
	durCitaSel = v;
	$.ajax({
		data:{
			'idusr' : usrSes.id,
			'idmed' : idMedCal,
			'idpac' : pacMod.id,
			'hora' : h,
			'feccita' : fechaCalendario
		},
        url:   generarUrl('/private/bloquearCita'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data){
        		console.info(data);
        	}
        }
}); 
}

function limpiarFormCitas(){
	$("#gestionCitas").modal("toggle");
	$('#calendar').addClass('hide');
	idMedCal = null;
	$('#sEspecMed').empty();
	$('#sMedico').empty();
	fechaCalendario = null;
	cancelGuardado();
}


function limpiarForm(swiClose){
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
	$('#tipdoc').val('DNI');
	if(swiClose)
		$("#gestionPacientes").modal("toggle");
}

function cancelCitaManual(){
	horaCitaManual = null;
	minuCitaManual = null;
	$('#sCitaManual').html('');
	$("#gestionCitas").modal("toggle");
}

function continuarCitaManual(){
	if(!$('#sHoraIni').val() || !$('#sMinIni').val()){	
		swal("Error", 'Debe seleccionar hora y minutos', "error");
		return false;
	}
	
	horaCitaManual = $('#sHoraIni').val();
	minuCitaManual = $('#sMinIni').val();
	$("input[name=horaMedSelect]:checked").prop('checked', false);
	var strHora = (($('#sHoraIni').val() < 10)? '0'+$('#sHoraIni').val():$('#sHoraIni').val()) + ' : '
	+ (($('#sMinIni').val() < 10)? '0'+$('#sMinIni').val():$('#sMinIni').val());
	
	$('#sCitaManual').html('CITA MANUAL SELECCIONADA: <strong>' + strHora + '<strong>' );
	$("#gestionCitasManu").modal("toggle");
}

function initPantallaGestionCita(){
	$('#sHoraIni').empty();
	$('#sMinIni').empty();
	
	for(var i = 1; i <= 24; i++){
		var horaStr = '';
		if(i < 10 ){
			if(i == 1)
			$('#sHoraIni').append(
					$('<option>', {
						value: '',
						text: 'Selecciona la hora'
					}));
			horaStr = '0'+ i.toString();
		}
			
		else
			horaStr = i.toString();
		
		$('#sHoraIni').append(
				$('<option>', {
					value: i,
					text: horaStr.toString()
				}));
	}
	
	for(var i = 0; i < 60; i++){
		var horaStr = '';
		if(i < 10 ){
			if(i == 0)
			$('#sMinIni').append(
					$('<option>', {
						value: '',
						text: 'Selecciona los minuros'
					}));
			horaStr = '0'+ i.toString();
		}
			
		else
			horaStr = i.toString();
		
		$('#sMinIni').append(
				$('<option>', {
					value: i,
					text: horaStr.toString()
				}));
	}
	if(horaCitaManual)
		$('#sHoraIni').val(horaCitaManual);
	if(minuCitaManual)
		$('#sMinIni').val(minuCitaManual);
}	

function imprimirHistCita(){
	$("#idPacList").val(pacMod.id);
	$( "#fHistPacPac" ).submit();
}

/*********************** IMPRIMIR DOC ****************/
function imprimirDoc(idPacCre){
	
	
	$("#impIdePacDat").val(idPacCre);
	$("#impIdeDoc").val('21');
	$( "#fImpDocDatos" ).submit();
		
}

/*********************** IMPRIMIR DOC ****************/

function initPantallaCitasFinHoy(){
	$('.loader-wrap').removeClass("hide");
	$.ajax({
	      url:   generarUrl('/private/initPantallaCitasFinHoy'),
	      type:  'GET',
	      dataType: 'json',
	      success:  function (data) {
	    	  var tHoy = $('#tablaCitasHoy').DataTable();
	    	  tHoy.clear().draw();
              
              if(data && data.listaCitas){
            	  for(var i = 0; i < data.listaCitas.length; i++){
            		  var item =  data.listaCitas[i];
            		  tHoy.row.add( [
                                     (item.ap1pac + ' ' + item.ap2pac + ', ' +item.nompac).toUpperCase() ,
                                     (item.apusr + ', ' + item.nomusr).toUpperCase() ,
                                     item.especialidad.toUpperCase()
                                     
                                 ] ).draw( false );
            	  }
              }
              
              $('.loader-wrap').addClass("hide");
             
	      }
	    });
}


//obtenerUsuarios();
obtenerDatosUsuarioSession();
initPantallaAltaModPacientes();