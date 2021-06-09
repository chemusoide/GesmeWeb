$(document).ready(function(){
      
      setTimeout(
    		  function() 
    		  {
    			  $('[name="liMenuPac"]').addClass('active');
    		  }, 500);
    
});
var pacientesActuales = null;
var combosCargados = false
var configMedico = null;
var idMedCal = null;
var pacMod = null;
var durCitaSel = null;
var fechaCalendario = null;
var usrSes = null;

$('#gestionCitas').on('shown.bs.modal', function () {
	   $("#calendar").fullCalendar('render');
	});

$(document).on("click",".fc-prev-button",function(){
	 configurarCalendarioMedico();
});

$(document).on("click",".fc-next-button",function(){
	 configurarCalendarioMedico();
});
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
	  		var day_int = date._d.getDay();
	  		fechaCalendario = date.format();
	  		  
	  		configurarCalendarioMedico();
	  		obtenerDisponivilidad(day_int);
	  		$('#divCal').addClass('col-sm-8');
	  		$('#divCal').removeClass('col-sm-12');
	  		$('#containerBtnCita').removeClass('hide');
	  		
		        // change the day's background color just for fun
		    $(this).css('background-color', '#FAE7C5');

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

function buscarPacienteNomAp(fTipo){ //añadir los filtros que se necesiten
   var parametros = {
		   'nompac': $('#nompac').val(),
			'ap1pac': $('#ap1pac').val(),
			'ap2pac': $('#ap2pac').val()
    };
    $('.loader-wrap').removeClass("hide");
    $.ajax({
            data:  parametros,
            url:   '/private/buscarPacienteNomAp',
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
                        var dni = '<span class="text-uppercase">'+pacActual.dniusr+'</span>'
                        var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
                        listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Editar Paciente"><button type="button" onclick="perfilPaciente(\''+pacActual.id+'\')" class="btn btn-icon btn-flat btn-default" data-target="#gestionPacientes" data-toggle="modal"><i class="icon wb-edit" aria-hidden="true"></i></button></span>';
                        listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Añadir Cita"><button type="button" onclick="citaPaciente(\''+pacActual.id+'\')" class="btn btn-icon btn-flat btn-default" data-target="#gestionCitas" data-toggle="modal"><i class="icon wb-inbox" aria-hidden="true"></i></button></span>'+
                         '</div>';
                        
                        var numtel = pacActual.numtel1 + ' - ' + pacActual.numtel2;
            			tPac.row.add( [
                            pacActual.nompac ,
                            pacActual.ap1pac,
                            pacActual.ap2pac,
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
function addError(c){
    c.addClass("has-error");
}
function removeError(c){
    c.removeClass("has-error");
}
function guardarPaciente(){

	//Campos Obligatorios
	var error = false;
	if(!$("#nompac").val()){error = true; addError($("#fgNombre"));} else removeError($("#fgNombre"));
	if(!$("#ap1pac").val()){error = true; addError($("#fgSurName1"));} else removeError($("#fgSurName1"));
	if(!$("#dniusr").val()){error = true; addError($("#fgDni"));} else removeError($("#fgDni"));
	if(!$("#sexpac").val()){error = true; addError($("#fgSexpac"));} else removeError($("#fgSexpac"));
	
	
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
			'dniusr': $('#dniusr').val(),
			'emailpac': $('#emailpac').val(),
			'dirpac': $('#dirpac').val(),
			'cppac': $('#cppac').val(),
			'idpais': $('#sIdpais').val(),
			'idseguro': $('#sIdseguro').val(),
			'numseg': $('#numseg').val(),
			'comentario': $('#comentario').val()
		};
	
	$.ajax({
		data: parametros,
        url:   '/private/guardarPaciente',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.paciente){
        		//obtenerUsuarios();
        		swal("Correcto", 'Paciente dado de alta', "success");
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
        url:   '/private/modificarEstadoCita',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.msgOk){
        		swal("Cita Modificada", data.msgOk, "success");
        		obtenerCitasActuales();
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
	}
	else
		accionModificarEstadoCita(idCita, newEst);
}

function obtenerCitasActuales(){
	 var tPac = $('#tablaCitasActuales').DataTable();
     tPac.clear().draw();
	$.ajax({
			data : {'id': pacMod.id},
	        url:   '/private/obtenerCitasActuales',
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	if(data && data.citas){
	        		for(var i = 0; i < data.citas.length; i++){
	        			 var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
	        			
	        			var hours = Math.floor( data.citas[i].hora / 60 );  
						var minutes =  (data.citas[i].hora % 60);
						var estCita = 'Sin Estado';
						if(data.citas[i].codestado && data.estadosCita){
							for(var j =0; j < data.estadosCita.length; j++ ){
								if(data.estadosCita[j].coddom == data.citas[i].codestado){
									if(data.citas[i].codestado == 'PLN'){
										estCita = '<span class="label  label-info">'+data.estadosCita[j].desval+'</span>';
										listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Paciente En Espera"><button type="button" onclick="modificarEstadoCita(\''+data.citas[i].id+'\',\'ESP\')" class="btn btn-warning btn-icon waves-effect waves-light" ><i class="icon wb-warning " aria-hidden="true"></i></button></span>';
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
	        			tPac.row.add( [
		                               data.citas[i].nomusr + ' ' + data.citas[i].apusr ,
		                               fechaString(data.citas[i].feccita),
		                               hours+':'+minutes,
		                               estCita,
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
	        url:   '/private/obtenerEspecialidades',
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
	        url:   '/private/obtenerEspecialistas',
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
		creaCalendario();
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
	for(var i = 0; i < configMedico.length; i++){
		var config = configMedico[i];
		if(config.diaseml == 'S') arr['L'] = 'S';
		if(config.diasemm == 'S') arr['M'] = 'S';
		if(config.diasemx == 'S') arr['X'] = 'S';
		if(config.diasemj == 'S') arr['J'] = 'S';
		if(config.diasemv == 'S') arr['V'] = 'S';
		if(config.diasems == 'S') arr['S'] = 'S';
		if(config.diasemd == 'S') arr['D'] = 'S';
	}
	//marcamos en gris los dias que no trabaja el médico
	if(!arr['L'])celdaNoTrabaja('mon' ,'#F1F4F5');else celdaNoTrabaja('mon' ,'#FFFFFF');
	if(!arr['M'])celdaNoTrabaja('tue' ,'#F1F4F5');else celdaNoTrabaja('tue' ,'#FFFFFF');
	if(!arr['X'])celdaNoTrabaja('wed' ,'#F1F4F5');else celdaNoTrabaja('wed' ,'#FFFFFF');
	if(!arr['J'])celdaNoTrabaja('thu' ,'#F1F4F5');else celdaNoTrabaja('thu' ,'#FFFFFF');
	if(!arr['V'])celdaNoTrabaja('fri' ,'#F1F4F5');else celdaNoTrabaja('fri' ,'#FFFFFF');
	if(!arr['S'])celdaNoTrabaja('sat' ,'#F1F4F5');else celdaNoTrabaja('sat' ,'#FFFFFF');
	if(!arr['D'])celdaNoTrabaja('sun' ,'#F1F4F5');else celdaNoTrabaja('sun' ,'#FFFFFF');
}


function prepararCalendario(idMed){
	$('.loader-wrap').removeClass("hide");
	configMedico = [];
	idMedCal = idMed;
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
	
	
	/******Pruebas ************/
	/* var delay=1000; //1 second
	 setTimeout(function() {
		 $('.loader-wrap').addClass("hide");
		 var start_date =  $('#calendar').fullCalendar('getView').start
	      console.log(start_date);
		 
		 //$('.fc-day.ui-widget-content.fc-sun').css('background-color', '#F1F4F5');
		 
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
	$.ajax({
		data:{'diaSem' : diaSem,
			  'idmed' : idMedCal,
			  'idusr' : usrSes.id,
			  'feccita' : fechaCalendario
		},
        url:   '/private/obtenerDisponivilidad',
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

function guardarCita(){	
	$.ajax({
		data:{
			  'idusr' : idMedCal,
			  'idpac' : pacMod.id,
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
			    		limpiarFormCitas();
			    	}
			    });
        	}
        }
	});
}

function cancelGuardado(){
	$('#divCal').removeClass('col-sm-8');
	$('#divCal').addClass('col-sm-12');
	$('#containerBtnCita').addClass('hide');
}

function citaPaciente(idpac){
	$('#calendar').addClass('hide');
	idMedCal = null;
	obtenerPacienteSelect(idpac);
	fechaCalendario = null;
	obtenerEspecialidades();
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

function limpiarFormCitas(){
	$("#gestionCitas").modal("toggle");
	$('#calendar').addClass('hide');
	idMedCal = null;
	$('#sEspecMed').empty();
	$('#sMedico').empty();
	fechaCalendario = null;
	cancelGuardado();
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


//obtenerUsuarios();
obtenerDatosUsuarioSession();
initPantallaAltaModPacientes();