$(document).ready(function(){
      
      setTimeout(
    		  function() 
    		  {
    			  $('[name="liMenuBusqMed"]').addClass('active');
    		  }, 500);
			  
    initPantalla();
	$("#calendar").fullCalendar('render');	
	creaCalendario();
});

var configMedico = [];
var usrSes = null;
var durCitaSel = null;
var idMedSelect = null;
var pacienteSelect = null;
var fechaCalendario = null;
var fechaInicioShow = null;
var pacientesActuales = null;
var fechaFinShow = null;
var totalCitasMon = null;
var totalCitasTue = null;
var totalCitasWen = null;
var totalCitasTh = null;
var totalCitasFri = null;
var totalCitasSat = null;
var totalCitasSun = null;

function fechaString(date){
	year = date.substring(0, 4);
    mes = date.substring(5, 7);
    dia = date.substring(8, 10);
    if(year.indexOf("/") >= 0 || mes.indexOf("/") >= 0 || dia.indexOf("/") >= 0  )
    	return null;
    return dia + '/'+ mes + '/' + year;
}

function celdaNoTrabaja(diaSem, color){
	$('.fc-day.ui-widget-content.fc-'+diaSem).css('background', color);
	//$('.fc-day.fc-past').css('background', '#F1F4F5');
}

function bloquearSeleccion(v,h){
	durCitaSel = v;
	$.ajax({
		data:{
			'idusr' : usrSes.id,
			'idmed' : idMedSelect,
			'hora' : h,
			'feccita' : fechaCalendario
		},
        url:   '/private/bloquearCita',
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

function continuarCita(){
	$('#containerBtnCita').addClass('hide');
	$('#containerInfoCita').removeClass('hide');
}

function volverSelectHora(){
	$('#containerBtnCita').removeClass('hide');
	$('#containerInfoCita').addClass('hide');
}

function guardarCita(){	
	$.ajax({
		data:{
			  'idusr' : idMedSelect,
			  'idpac' : pacienteSelect.id,
			  'hora' : $("input[name=horaMedSelect]:checked").val(),
			  'feccita' : fechaCalendario,
			  'codestado' : 'OK',
			  'codesp' : $('#sEspecMed').val(),
			  'durcon' : durCitaSel
		},
        url:   '/private/guardarCita',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.nombreCompleto){
        		var hours = Math.floor( data.hora / 60 );  
				var minutes =  (data.hora % 60);
				if(hours < 10) hours = '0'+hours;
				if(minutes < 10) minutes = '0'+minutes;
				swal({
			      	title: "Cita creada Correctamente",
					text: data.nombreCompleto + ' el día ' + fechaString(data.feccita) + ' a las ' + hours + ':' + minutes + ' horas',
					type: "success",
					showCancelButton: false,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Cerrar',
					closeOnConfirm: true
			    },
			    function(isConfirm) {
			    	if (isConfirm) {
			    		volverPaso();
						cancelGuardado();
						obtenerCitasByFechas();
			    	}
			    });
        	}
        }
	});
}

function obtenerCitasByFechas(){
	$('#calendar').fullCalendar('removeEvents');
	$.ajax({
		data:{	'ideMed' : idMedSelect,
				'fecIni' : fechaInicioShow,
				'fecFin' : fechaFinShow
			},
        url:   '/private/obtenerCitasByFechas',
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


        }
		});
}

function configurarCalendarioMedico(){
	var arr = new Array();
	totalCitasMon = 0;
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
	if(!arr['L'])celdaNoTrabaja('mon' ,'#F1F4F5');else celdaNoTrabaja('mon' ,'#FFFFFF');
	if(!arr['M'])celdaNoTrabaja('tue' ,'#F1F4F5');else celdaNoTrabaja('tue' ,'#FFFFFF');
	if(!arr['X'])celdaNoTrabaja('wed' ,'#F1F4F5');else celdaNoTrabaja('wed' ,'#FFFFFF');
	if(!arr['J'])celdaNoTrabaja('thu' ,'#F1F4F5');else celdaNoTrabaja('thu' ,'#FFFFFF');
	if(!arr['V'])celdaNoTrabaja('fri' ,'#F1F4F5');else celdaNoTrabaja('fri' ,'#FFFFFF');
	if(!arr['S'])celdaNoTrabaja('sat' ,'#F1F4F5');else celdaNoTrabaja('sat' ,'#FFFFFF');
	if(!arr['D'])celdaNoTrabaja('sun' ,'#F1F4F5');else celdaNoTrabaja('sun' ,'#FFFFFF');
	
	$('.fc-day.fc-today').css('background', '#fcf8e3');
	$('.fc-day.fc-past').css('background', '#F1F4F5');
}

function obtenerDisponivilidad( diaSem ){
	$('#horasDisp').empty();
	$.ajax({
		data:{'diaSem' : diaSem,
			  'idmed' : idMedSelect,
			  'idusr' : usrSes.id,
			  'feccita' : fechaCalendario
		},
        url:   '/private/obtenerDisponivilidad',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
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
        					if(existe == false){
        						
            					var strDiv = '<div class="form-group"><div class="radio-custom radio-primary">'
             	               +'<input onclick="bloquearSeleccion('+dur+', '+horaCitaMis+')" type="radio" id="hor'+ i +'-'+ x +'" value= "'+horaCitaMis+'" name="horaMedSelect">'
             	                +'<label for="hor'+ i +'-'+ x +'">'+ (hours<10? '0'+hours: hours )+':'+(minutes<10? '0'+minutes: minutes )+'</label>'
             	             + '</div></div>';
            					$('#horasDisp').append(strDiv);
        					}
        					
        					
        				}
        				
        			}
        		}
        	}
        }
	});
}

function obtenerEspecialidadMedico(){
	$('#sEspecMed').empty();
	$('#sMedico').empty();
	$('#sEspecMed').append( $('<option>', { value: '', text: 'Especialidades' }));
	
	$('.loader-wrap').removeClass("hide");
	$.ajax({
			data:{'ideusr' : idMedSelect},
	        url:   '/private/obtenerEspecialidadMedico',
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	if(data && data.listaEspecialidades){
	        		for(var i = 0; i < data.listaEspecialidades.length; i++){
	        			$('#sEspecMed').append( $('<option>', { value: data.listaEspecialidades[i].codesp , text: data.listaEspecialidades[i].especialidad }));
	        		}
	        	}
	        }
	});
}

$( "#sMed" ).change(function() {
	idMedSelect = $( "#sMed" ).val();
	prepararCalendario($( "#sMed" ).val());
	creaCalendario();
	obtenerCitasByFechas();
	obtenerEspecialidadMedico();
	volverPaso();
	cancelGuardado();
	
});

function prepararCalendario(idMed){
	$('.loader-wrap').removeClass("hide");
	configMedico = [];
	$.ajax({
		data:{'idusr' : idMed},
        url:   '/private/obtenerAgendaCitasMed',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.listaConfig){
        		
        		configMedico = data.listaConfig;
        		configurarCalendarioMedico();
        	}
        }
	});
}

function creaCalendario(){
	$('#calendar').fullCalendar({
		height: 500,
		editable: false,										
		events: '',
		selectable:true,
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
		select : function(start, end){
			if (start.add('days', 1).date() != end.date() ){
				$('#horasDisp').empty();
				$('#calendar').fullCalendar('unselect');
			}
				
		},
  	eventClick: function(event) {
			if (event.targetUrl) {
			    window.open(event.targetUrl);
			    return false;
			}
  	},
	viewRender: function(view, element){
        //var fechaInicioShow = view.intervalStart._d.format();
		
		var d = view.intervalStart._d;
		fechaInicioShow = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
		
		var d = view.intervalEnd._d;
		fechaFinShow = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
		
		obtenerCitasByFechas();
		
		configurarCalendarioMedico();
    },
  	dayClick: function(date, jsEvent, view) {
  			volverPaso();
	  		var day_int = date._d.getDay();
	  		fechaCalendario = date.format();
	  		  
	  		configurarCalendarioMedico();
			obtenerDisponivilidad(day_int);
			
			$('#divCal').addClass('col-sm-8');
	  		$('#divCal').removeClass('col-sm-12');
			$('#containerBtnCita').removeClass('hide');
	  		
		    // change the day's background color just for fun
		   // $(this).css('background-color', '#FAE7C5');

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

function volverPaso(){
	seleccionarPaciente();
	volverSelectHora();
}

function cancelGuardado(){
	$('#divCal').removeClass('col-sm-8');
	$('#divCal').addClass('col-sm-12');
	$('#containerBtnCita').addClass('hide');
}

function seleccionarPaciente(pSelect){
	if(pSelect){
		pacienteSelect = pSelect;
		$('#snompac').html(pSelect.nompac);
		$('#sap1pac').html(pSelect.ap1pac);
		$('#sap2pac').html(pSelect.ap2pac);
		
		$('#snompac').removeClass('hide');
		$('#sap1pac').removeClass('hide');
		$('#sap2pac').removeClass('hide');
		
		$('#nompac').addClass('hide');
		$('#ap1pac').addClass('hide');
		$('#ap2pac').addClass('hide');
		
		$('#btnLimp').removeClass('hide');
		$('#btnBusPac').addClass('hide');
		
	}else{
		pSelect = null;
		$('#snompac').html('');
		$('#sap1pac').html('');
		$('#sap2pac').html('');
		
		$('#snompac').addClass('hide');
		$('#sap1pac').addClass('hide');
		$('#sap2pac').addClass('hide');
		
		$('#nompac').val('');
		$('#ap1pac').val('');
		$('#ap2pac').val('');
		
		$('#nompac').removeClass('hide');
		$('#ap1pac').removeClass('hide');
		$('#ap2pac').removeClass('hide');
		
		$('#btnLimp').addClass('hide');
		$('#btnBusPac').removeClass('hide');
	}
	
}

function seleccionarPacienteTabla(orden){
	seleccionarPaciente(pacientesActuales[orden]);
	$("#panelBusq").modal("hide");
}

function buscarPacienteNomAp(){
	if(!$('#nompac').val() && !$('#ap1pac').val() && !$('#ap2pac').val()){
		swal("Error", 'debe especificar almenos un elemento en la busqueda', "error");
		return false;
	}
	pacientesActuales = null;
	
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
						    },
						    function(isConfirm) {
						    	if (isConfirm) {

									$('#nompacAlta').val($('#nompac').val());
									$('#ap1pacAlta').val($('#ap1pac').val());
									$('#ap2pacAlta').val($('#ap2pac').val());
									$('#sexpacAlta').val('');
									$('#dniusrAlta').val('');
						    		$("#panelAltRap").modal();
						    	}
						    });
						}else if(pacientesActuales.length == 1){
							seleccionarPaciente(pacientesActuales[0]);
						}else{
							var tPac = $('#tablaPacientes').DataTable();
							tPac.clear().draw();
							for(var i = 0; i < data.listaPacientes.length; i++){
								var pacActual = data.listaPacientes[i];
								var dni = '<span class="text-uppercase">'+pacActual.dniusr+'</span>'
								var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
								listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Seleccionar Paciente"><button type="button" onclick="seleccionarPacienteTabla(\''+i+'\')" class="btn btn-icon btn-flat btn-default"><i class="icon wb-check-circle" aria-hidden="true"></i></button></span>';
								'</div>';
								
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

function cancelaAltaRapida(){
	$("#panelAltRap").modal("hide");
}

function addError(c){
    c.addClass("has-error");
}
function removeError(c){
    c.removeClass("has-error");
}

function altaRapidaPacientes(){

	//Campos Obligatorios
	var error = false;
	if(!$("#nompacAlta").val()){error = true; addError($("#fgNombreAlta"));} else removeError($("#fgNombreAlta"));
	if(!$("#ap1pacAlta").val()){error = true; addError($("#fgSurName1Alta"));} else removeError($("#fgSurName1Alta"));
	if(!$("#dniusrAlta").val() || $("#dniusrAlta").val() == '        - ' || $("#dniusrAlta").val() == ' - '){error = true; addError($("#fgDniAlta"));} else removeError($("#fgDniAlta"));
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
			'dniusr': $('#dniusrAlta').val()
		};
	
	$.ajax({
		data: parametros,
        url:   '/private/guardarPaciente',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.paciente){
        		swal("Correcto", 'Paciente dado de alta', "success");
        		seleccionarPaciente(data.paciente);
        		$("#panelAltRap").modal("hide");
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


function obtenerDatosUsuarioSession(){
	usrSes = null;
	$.ajax({
      url:   '/private/obtenerDatosUsuarioSession',
      type:  'GET',
      dataType: 'json',
      success:  function (data) {

          if(data && data.usuario){
        	  usrSes = data.usuario;
          }
      }
    });
}

function initPantalla(){
	obtenerDatosUsuarioSession();
	if(listaMedicos){
		for(var i = 0; i < listaMedicos.length; i++){
		  $('#sMed').append(
			$('<option>', {
				value: listaMedicos[i].id,
				text: listaMedicos[i].apusr + ', ' + listaMedicos[i].nomusr
			}));
		}
	}
}


