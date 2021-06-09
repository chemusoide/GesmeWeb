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
var listaSegurosAct = null;
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

var horaCitaManual = null;
var minuCitaManual = null;

var booCamMed = false;
var swiObtenerFin = true;
var dayClickDate = null;

var citasVac = null;
var listaCitasGrid = null;
var citaSelectGrid = null;

var citaCambio = null;

var finCargaCalendario = false;
var finCargaCitas = false;
var arrTrabajo = null;
var viewCalendar = null;
var citaRapEjec = false;
var objCitRap = null;
var fecPrimerDia = null;

var arrOtrProf = null;

function celdaNoTrabaja(diaSem, color){
	$('.fc-day.ui-widget-content.fc-'+diaSem).css('background', color);
	//$('.fc-day.fc-past').css('background', '#F1F4F5');
}

function diasVacaciones(){
	$('.fc-day.calendarVacaciones').css('background', '#BBD5EB');
}

function diasFestivos(){
	$('.fc-day.calendarFestivos').css('background', '#EB7F7C');
}

function diasEspeciales(){
	$('.fc-day.calendarHorEspCPQ').css('background', 'rgb(38, 179, 14)');
	$('.fc-day.calendarHorEspLUZ').css('background', 'rgb(226, 127, 20)');
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
        url:   generarUrl('/private/bloquearCita'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data){
        	}
        }
}); 
}

function continuarCita(){
	//si es un cambio de cita se guardará directamente
	if(citaCambio)
		guardarCita();
	else{
		
		if(citaRapEjec){
			$('#idEmpCita').val((objCitRap && objCitRap[0])? objCitRap[0].idempresa:'');
		}else{
			var vSelect = $("input[name=horaMedSelect]:checked")[0];
			if(vSelect)
				$('#idEmpCita').val($('#idempresa'+vSelect.id.substring(3, vSelect.id.length)).val());
		}
		
		
		$('#containerBtnCita').addClass('hide');
		$('#containerInfoCita').removeClass('hide');	
	}
	
}

function volverSelectHora(){
	$('#containerBtnCita').removeClass('hide');
	$('#containerInfoCita').addClass('hide');
}

function imprimirListadoPacientes(){
	$("#fechaCitaListado").val(fechaCalendario);
	$("#medListado").val(idMedSelect);
	
	$( "#fListPac" ).submit();
}

function guardarVisita(){
	$('.loader-wrap').removeClass("hide");
	var hora = '';
	var fecvisita = fechaCalendario;
	var swiCitRap = 'N';
	
	if($('#sEspecMed').val() == "DUV"){
		var dAux = new Date();
		hora =parseInt( dAux.getHours()*60 ) + parseInt(dAux.getMinutes());
		
		var vD = dAux.getDate() < 10? '0'+ dAux.getDate():dAux.getDate();
		var vM = (dAux.getMonth()+1) < 10? '0'+ (dAux.getMonth()+1):dAux.getMonth()+1;
		fecvisita =	dAux.getFullYear()+ "-" + vM + "-" + vD;
		swiCitRap = 'S';
	}
	else{
		hora = objCitRap && objCitRap[0] && objCitRap[0].horaCitaMis?objCitRap[0].horaCitaMis:$("input[name=horaMedSelect]:checked").val();
	}
	if(!hora)
		hora = parseInt( $('#sHoraIni').val()*60 ) + parseInt($('#sMinIni').val());
	$.ajax({
		data:{
			  'idusr' : idMedSelect,
			  'idpac' : pacienteSelect?pacienteSelect.id:'',
			  'fecvisita' : fecvisita,
			  'codestado' : 'OK',
			  'hora' : hora,
			  'codesp' : $('#sEspecMed').val(),
			  'obs': $('#obscita').val()?$('#obscita').val():'',
			  'swiCitRap': swiCitRap,
			  'idempresa': $('#idEmpCita').val()
		},
        url:   generarUrl('/private/guardarVisita'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.nombreCompleto){
        		var hours = Math.floor( data.hora / 60 );  
				var minutes =  (data.hora % 60);
				if(hours < 10) hours = '0'+hours;
				if(minutes < 10) minutes = '0'+minutes;
				pacienteSelect = null;
				var info = data.info?data.info:'';
				swal({
			      	title: "Visita creada Correctamente",
					text: data.nombreCompleto + ' el día ' + fechaString(data.feccita) + ' a las ' + hours + ':' + minutes + ' horas.' + info,
					type: "success",
					showCancelButton: false,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Cerrar',
					closeOnConfirm: true
			    },
			    function(isConfirm) {
			    	if (isConfirm) {
			    		volverPaso(true);
						cancelGuardado();
						
			    	}
			    });
        	}
        }
	});
}

function guardarCita(){	
	if(!$('#sEspecMed').val() && !citaCambio){
		swal("Error", 'Debe seleccionar una especialidad', "error");
		return false;
	}
	if(!pacienteSelect && !citaCambio){
		swal("Error", 'Debe seleccionar un paciente', "error");
		return false;
	}
	if(!$('#idEmpCita').val() && !citaCambio){
		swal("Error", 'Debe seleccionar una empresa', "error");
		return false;
	}
	
	if(arrOtrProf && arrOtrProf[idMedSelect]){
		
		/******************* VISITAS ***********************/
		guardarVisita();
		
	}else{
		/******************* CITAS ***********************/
		var horamed = objCitRap && objCitRap[0] && objCitRap[0].horaCitaMis?objCitRap[0].horaCitaMis:$("input[name=horaMedSelect]:checked").val();
		
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
				  'idusr' : idMedSelect,
				  'idpac' : pacienteSelect?pacienteSelect.id:'',
				  'hora' : horamed,
				  'feccita' : fechaCalendario,
				  'codestado' : 'OK',
				  'codesp' : $('#sEspecMed').val(),
				  'durcon' : durCitaSel,
				  'tipcita': horaCitaManual?'MAN':'',
				  'obscita': $('#obscita').val()?$('#obscita').val():'',
				  'swiCambio': citaCambio?'S':'N',
				  'citaCambio': citaCambio,
				  'swiCitRap': citaRapEjec?'S':'N',
				  'codUsr' : usrSes.id,
				  'idempresa': $('#idEmpCita').val()
			},
	        url:   generarUrl('/private/guardarCita'),
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	if(data && data.nombreCompleto){
	        		obtenerCitasByFechas();
	        		var hours = Math.floor( data.hora / 60 );  
					var minutes =  (data.hora % 60);
					if(hours < 10) hours = '0'+hours;
					if(minutes < 10) minutes = '0'+minutes;
					pacienteSelect = null;
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
				    		volverPaso(true);
							cancelGuardado();
							
				    	}
				    });
	        	}
	        }
		});
	}
	
}

function citasEnvacacionesInit(){
	citasVac = null;
	var tCitVac = $('#tablaCitasVacaciones').DataTable();
	tCitVac.clear().draw();
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		data:{ 'ideMed' : idMedSelect },
        url:   generarUrl('/private/obtenerCitasVacacionesUsr'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data){
        		citasVac = data.citasVacaciones;
            	
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

function gestionHorEspCalendar(horariosEsp){
	$('.fc-day').removeClass('calendarHorEsp');
	
	if(horariosEsp && horariosEsp.length > 0){
		
		for(var i = 0; i < horariosEsp.length; i++){
			var regHoEsp = horariosEsp[i];
			var diaIniHor = new Date(fechaInicioShow)>=new Date(regHoEsp.fecinitemp)?fechaInicioShow:regHoEsp.fecinitemp;
			var diaFinHor = new Date(fechaFinShow)<=new Date(regHoEsp.fecfintemp)?fechaFinShow:regHoEsp.fecfintemp;
			var fechaTratada = diaFinHor;
			var arr = new Array();
			var days = ["D", "L", "M", "X", "J", "V", "S"];
			
			if(regHoEsp.diaseml == 'S'){
				arr['L'] = !regHoEsp.idempresa?'S':regHoEsp.idempresa == 1?'CPQ':'LUZ';
			} 
			if(regHoEsp.diasemm == 'S'){
				arr['M'] = !regHoEsp.idempresa?'S':regHoEsp.idempresa == 1?'CPQ':'LUZ';
			} 
			if(regHoEsp.diasemx == 'S'){
				arr['X'] = !regHoEsp.idempresa?'S':regHoEsp.idempresa == 1?'CPQ':'LUZ';
			} 
			if(regHoEsp.diasemj == 'S'){
				arr['J'] = !regHoEsp.idempresa?'S':regHoEsp.idempresa == 1?'CPQ':'LUZ';
			} 
			if(regHoEsp.diasemv == 'S'){
				arr['V'] = !regHoEsp.idempresa?'S':regHoEsp.idempresa == 1?'CPQ':'LUZ';
			} 
			if(regHoEsp.diasems == 'S'){
				arr['S'] = !regHoEsp.idempresa?'S':regHoEsp.idempresa == 1?'CPQ':'LUZ';
			} 
			if(regHoEsp.diasemd == 'S'){
				arr['D'] = !regHoEsp.idempresa?'S':regHoEsp.idempresa == 1?'CPQ':'LUZ';
			} 
								
			var diaIni = new Date(diaIniHor).getDate();
			var diaFin = (new Date(diaIniHor).getMonth()+1)!=(new Date(diaFinHor).getMonth()+1)?31:new Date(diaFinHor).getDate();
			
			for(var j = diaIni; j <= diaFin; j++){
				
				//Añadimos la clase para despues añadirle fondo
				fechaTratada = new Date(diaIniHor).getFullYear() + "-" + (new Date(diaIniHor).getMonth()+1<10?'0'+(new Date(diaIniHor).getMonth()+1):new Date(diaIniHor).getMonth()+1) + "-" + (j <10? '0'+j:j);
				console.info('fechaTratada: ' + fechaTratada + ' - ' + days[new Date(fechaTratada).getDay()]);
				if(arr[days[new Date(fechaTratada).getDay()]]){
					//$( "td[data-date='"+ fechaTratada +"']" ).css('background', returnColorFondo());
					$( "td[data-date='"+ fechaTratada +"']" ).addClass('calendarHorEsp'+arr[days[new Date(fechaTratada).getDay()]]);
					
				}
				
				
			}
			
		}
		diasEspeciales();
	}
}

function gestionVacacionesCalendar(listaVaciones){
	//Gestionamos las vacaiones
	$('.fc-day').removeClass('calendarVacaciones');
	if(listaVaciones && listaVaciones.length > 0){
		
		for(var i = 0; i < listaVaciones.length; i++){
			var regVac = listaVaciones[i];
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
}

function obtenerVisitasByFechas(accion){
	$('#calendar').fullCalendar('removeEvents');
	if(accion == 'changeMed'){
		if(finCargaCalendario)
			seleccionarPrimerDiaTrabajo();
	}else{
		citasVac = null;
		$('#dGestionCitasSolVac').addClass('hide');
		$('.loader-wrap').removeClass("hide");
		$.ajax({
			data:{	'ideMed' : idMedSelect,
					'fecIni' : fechaInicioShow,
					'fecFin' : fechaFinShow
				},
	        url:   generarUrl('/private/obtenerVisitasByFechas'),
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	
	        	//Cambio al pulsar siguiente
				//;
				/*var moment = $('#calendar').fullCalendar('getDate');
				    alert("The current date of the calendar is " + moment.format());*/
	        	//debugger;
	        	
				if(data && data.totCitas){
					for(var i = 0; i < data.totCitas.length; i++){
						var d = new Date(data.totCitas[i].fecvisita);
						var newEvent = new Object();
						newEvent.title = String( data.totCitas[i].totalCita);
						newEvent.start = data.totCitas[i].fecvisita;
						
						if(data.totCitas[i].idempresa == 1)
							newEvent.backgroundColor = '#203E39';
						if(data.totCitas[i].idempresa == 2)
							newEvent.backgroundColor = '#F28E02';
						
						if( (d.getDay() == 1 && data.totCitas[i].totalCita < totalCitasMon)
							|| ( d.getDay() == 2 && data.totCitas[i].totalCita < totalCitasTue)
							||( d.getDay() == 3 && data.totCitas[i].totalCita < totalCitasWen)
							||( d.getDay() == 4 && data.totCitas[i].totalCita < totalCitasTh)
							||( d.getDay() == 5 && data.totCitas[i].totalCita < totalCitasFri)
							||( d.getDay() == 6 && data.totCitas[i].totalCita < totalCitasSat)
							||( d.getDay() == 0 && data.totCitas[i].totalCita < totalCitasSun)
							|| $('#sEspecMed').val() == "DUV"
						){
							newEvent.backgroundColor = '#378006';
							
						}else{
							newEvent.title = newEvent.title + '- Completo';
						}
							
						
						newEvent.allDay = false;
						$('#calendar').fullCalendar( 'renderEvent', newEvent );	
					}
					
				}
				
				gestionHorEspCalendar(data.horariosEsp);
				
				gestionVacacionesCalendar(data.listaVaciones);
				
				//gestionamos las citas en vacaciones
				
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
				
				//DIAS FESTIVOS
				
				if(data && data.citasVac && data.citasVac== true)
					$('#dGestionCitasSolVac').removeClass('hide');
				
				finCargaCitas = true;
				
				
				//SEGUROS
				listaSegurosAct = data.listaSeguros;
				segurosLista(data.listaSeguros, 'sIdseguro');
				
				
				
	        }
			});
	}
	
}

function obtenerCitasByFechas(accion){
	$('#calendar').fullCalendar('removeEvents');
	
	if(accion == 'changeMed' && finCargaCalendario){
		finCargaCitas = true;
		seleccionarPrimerDiaTrabajo();
	}else{
		citasVac = null;
		$('#dGestionCitasSolVac').addClass('hide');
		$('.loader-wrap').removeClass("hide");
		$.ajax({
			data:{	'ideMed' : idMedSelect,
					'fecIni' : fechaInicioShow,
					'fecFin' : fechaFinShow
				},
	        url:   generarUrl('/private/obtenerCitasByFechas'),
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	
	        	//Cambio al pulsar siguiente
				//;
				/*var moment = $('#calendar').fullCalendar('getDate');
				    alert("The current date of the calendar is " + moment.format());*/
	        	//debugger;
	        	
				if(data && data.totCitas){
					for(var i = 0; i < data.totCitas.length; i++){
						var d = new Date(data.totCitas[i].feccita);
						var newEvent = new Object();
						newEvent.title = String( data.totCitas[i].totalCita);
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
							if(data.totCitas[i].idempresa == 1)
								newEvent.backgroundColor = '#C5D6C3';
							if(data.totCitas[i].idempresa == 2)
								newEvent.backgroundColor = '#FADCB3';
							
						}else{
							newEvent.title = newEvent.title;
						}
							
						
						newEvent.allDay = false;
						$('#calendar').fullCalendar( 'renderEvent', newEvent );	
					}
					
				}
				gestionHorEspCalendar(data.horariosEsp);
				
				gestionVacacionesCalendar(data.listaVaciones);	
				
				
				//gestionamos las citas en vacaciones
				
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
				
				//DIAS FESTIVOS
				
				if(data && data.citasVac && data.citasVac== true)
					$('#dGestionCitasSolVac').removeClass('hide');
				
				finCargaCitas = true;
				
				//SEGUROS
				listaSegurosAct = data.listaSeguros;
				segurosLista(data.listaSeguros, 'sIdseguro');
				
				
				
	        }
			});
	}
}

function seleccionarPrimerDiaTrabajo(){
	if(arrTrabajo){
		var encontrado = false;
		var dateAtratar = new Date();
		var mes = dateAtratar.getMonth();
		var anyo= dateAtratar.getFullYear();
		
		var firstTime = true;
		while (!encontrado){
			if(!firstTime)
				dateAtratar = new Date(anyo, mes, dateAtratar.getDate()+1);
			
			if(dateAtratar >= viewCalendar.intervalEnd._d || dateAtratar < viewCalendar.intervalStart._d ){
				encontrado = true;
			}else{
				var m = (dateAtratar.getMonth()+1)<10? ('0' + (dateAtratar.getMonth()+1).toString()): (dateAtratar.getMonth()+1);
				var d = (dateAtratar.getDate())<10? ('0' + (dateAtratar.getDate()).toString()): (dateAtratar.getDate());
				var fechaSQL = dateAtratar.getFullYear()+ "-" + m + "-" + d;
				var classObj = $( "td[data-date='"+ fechaSQL +"']" ).attr("class");
				
				var trabajaDia = false;
				if(arrTrabajo['L'] && classObj && classObj.indexOf('fc-mon') >=0 && classObj.indexOf('calendarVacaciones') < 0)
					trabajaDia = true;
				else if(arrTrabajo['M'] && classObj && classObj.indexOf('fc-tue') >=0 && classObj.indexOf('calendarVacaciones') < 0)
					trabajaDia = true;
				else if(arrTrabajo['X'] && classObj && classObj.indexOf('fc-wed') >=0 && classObj.indexOf('calendarVacaciones') < 0)
					trabajaDia = true;
				else if(arrTrabajo['J'] && classObj && classObj.indexOf('fc-thu') >=0 && classObj.indexOf('calendarVacaciones') < 0)
					trabajaDia = true;
				else if(arrTrabajo['V'] && classObj && classObj.indexOf('fc-fri') >=0 && classObj.indexOf('calendarVacaciones') < 0)
					trabajaDia = true;
				else if(arrTrabajo['S'] && classObj && classObj.indexOf('fc-sat') >=0 && classObj.indexOf('calendarVacaciones') < 0)
					trabajaDia = true;
				else if(arrTrabajo['D'] && classObj && classObj.indexOf('fc-sun') >=0 && classObj.indexOf('calendarVacaciones') < 0)
					trabajaDia = true;
				
				if(trabajaDia){
					$('td.fc-today div').trigger('click');
					
					//$('#calendar').fullCalendar( 'select', fechaSQL);
					fecPrimerDia = new Date(anyo, mes, dateAtratar.getDate());
					
					dayClickCalendar(new Date(anyo, mes, dateAtratar.getDate()));
					encontrado = true;
				}
			}
			
			firstTime = false;
		}
		
		
		
	}
}

function returnColorFondo(arrElemet){
	if(arrElemet == 'S')
	return '#F3EDB1';
	if(arrElemet == 'CPQ')
		return '#347529';
	if(arrElemet == 'LUZ')
		return '#F4B066';
	
}
function configurarCalendarioMedico(accion){
	var arr = new Array();
	totalCitasMon = 0;
	for(var i = 0; i < configMedico.length; i++){
		var config = configMedico[i];
		
		if(config.swicambia == 'S')
			continue;
		
		var dur = config.durcon;
		var iniMins = (config.horaini *60) + config.minini;
		var finMins = (config.horafin *60) + config.minfin;
		var totalMins = finMins - iniMins;
		var totalCitas = totalMins/dur;

		
		if(config.diaseml == 'S'){
			totalCitasMon = totalCitasMon + totalCitas;
			arr['L'] = !config.idempresa?'S':config.idempresa == 1?'CPQ':'LUZ';
		} 
		if(config.diasemm == 'S'){
			totalCitasTue = totalCitasTue + totalCitas;
			arr['M'] = !config.idempresa?'S':config.idempresa == 1?'CPQ':'LUZ';
		} 
		if(config.diasemx == 'S'){
			totalCitasWen = totalCitasWen + totalCitas;
			arr['X'] = !config.idempresa?'S':config.idempresa == 1?'CPQ':'LUZ';
		} 
		if(config.diasemj == 'S'){
			totalCitasTh = totalCitasTh + totalCitas;
			arr['J'] = !config.idempresa?'S':config.idempresa == 1?'CPQ':'LUZ';
		} 
		if(config.diasemv == 'S'){
			totalCitasFri = totalCitasFri + totalCitas;
			arr['V'] = !config.idempresa?'S':config.idempresa == 1?'CPQ':'LUZ';
		} 
		if(config.diasems == 'S'){
			totalCitasSat = totalCitasSat + totalCitas;
			arr['S'] = !config.idempresa?'S':config.idempresa == 1?'CPQ':'LUZ';
		} 
		if(config.diasemd == 'S'){
			totalCitasSun = totalCitasSun + totalCitas;
			arr['D'] = !config.idempresa?'S':config.idempresa == 1?'CPQ':'LUZ';
		} 
		
		arrTrabajo = arr;
	}

	
	//marcamos en gris los dias que no trabaja el médico
	if(!arr['L'])celdaNoTrabaja('mon' ,'#DFE2E3');else celdaNoTrabaja('mon' ,returnColorFondo(arr['L']));
	if(!arr['M'])celdaNoTrabaja('tue' ,'#DFE2E3');else celdaNoTrabaja('tue' ,returnColorFondo(arr['M']));
	if(!arr['X'])celdaNoTrabaja('wed' ,'#DFE2E3');else celdaNoTrabaja('wed' ,returnColorFondo(arr['X']));
	if(!arr['J'])celdaNoTrabaja('thu' ,'#DFE2E3');else celdaNoTrabaja('thu' ,returnColorFondo(arr['J']));
	if(!arr['V'])celdaNoTrabaja('fri' ,'#DFE2E3');else celdaNoTrabaja('fri' ,returnColorFondo(arr['V']));
	if(!arr['S'])celdaNoTrabaja('sat' ,'#DFE2E3');else celdaNoTrabaja('sat' ,returnColorFondo(arr['S']));
	if(!arr['D'])celdaNoTrabaja('sun' ,'#DFE2E3');else celdaNoTrabaja('sun' ,returnColorFondo(arr['D']));
	
	$('.fc-day.fc-today').css('background', '#BCE3B8');
	$('.fc-day.fc-past').css('background', '#F1F4F5');
	
	finCargaCalendario = true;
	if(accion == 'changeMed'){
		seleccionarPrimerDiaTrabajo();
	}
	
}

function abrirInfoUsuario(id){
	cancelarModPac();
	if(id){
		 $('.loader-wrap').removeClass("hide");
		$.ajax({
            data:  {'idPaciente': id},
            url:   generarUrl('/private/buscarPacienteNomAp'),
            type:  'GET',
            dataType: 'json',
            success:  function (data) {
            	
                if(data && data.listaPacientes){
                	var pacMod = data.listaPacientes[0];
                	if(pacMod){
            			$('#infoidHistorial').val(pacMod.id);
            			$('#infonompac').val(pacMod.nompac);
            			$('#infoap1pac').val(pacMod.ap1pac);
            			$('#infoap2pac').val(pacMod.ap2pac);
            			if(pacMod.fecnacpac){
            				$('#infofecnacpac').val(fechaString(pacMod.fecnacpac));
            			}
            			$('#infosexpac').val(pacMod.sexpac);
            			$('#infonumtel1').val(pacMod.numtel1);
            			$('#infonumtel2').val(pacMod.numtel2);
            			$('#infoemailpac').val(pacMod.emailpac);
            			$('#infodirpac').val(pacMod.dirpac);
            			$('#infocppac').val(pacMod.cppac);
            			$('#sIdseguro').val(pacMod.idseguro);
            			$('#numseg').val(pacMod.numseg);
            			$('#infocomentario').val(pacMod.comentario);
            			$('#tipdocinfo').val(pacMod.tipdoc);
            			if(pacMod.tipdoc == 'DNI'){
            				$( "#infodniusr" ).val(pacMod.dniusr);
            				$( "#infonieusr" ).val('');
            				$( "#infopassusr" ).val('');
            			}
            			if(pacMod.tipdoc == 'NIE'){
            				$( "#infonieusr" ).val(pacMod.dniusr);
            				$( "#infodniusr" ).val('');
            				$( "#infopassusr" ).val('');
            			}
            			if(pacMod.tipdoc == 'PAS'){
            				$( "#infodniusr" ).val('');
            				$( "#infonieusr" ).val('');
            				$( "#infopassusr" ).val(pacMod.dniusr);
            			}
            			mostrarOcultarTipoDoc();
            		}   

                     $('.loader-wrap').addClass("hide");
                }
                 
            }//Fin success
		});//Fin Ajax
		
	}
	
}

function accionModificarEstadoVisita(idCita, newEst){
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		data: {'id' : idCita, 'codestado' : newEst},
        url:   generarUrl('/private/modificarEstadoVisita'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.msgOk){
        		swal("Cita Modificada", data.msgOk, "success");
        		volverPaso();
				cancelGuardado();
        	}
        }
});

}

function modificarEstadoVisita(idCita, newEst, diaSem){
	if(newEst == 'CAN'){
		swal({
	      	title: "Alerta",
			text: 'Se eliminará la visita.¿Desea Continuar?',
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
	    		accionModificarEstadoVisita(idCita, newEst);
	    	}
	    });
	}
	else
		accionModificarEstadoVisita(idCita, newEst);
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
        		volverPaso();
				cancelGuardado();
        	}
        }
});

}

function modificarEstadoCita(idCita, newEst, diaSem){
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


function accionModificarEstadoVisita(idVisita, newEst){
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		data: {'id' : idVisita, 'codestado' : newEst},
        url:   generarUrl('/private/modificarEstadoVisita'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.msgOk){
        		swal("Cita Modificada", data.msgOk, "success");
        		volverPaso();
				cancelGuardado();
        	}
        }
});

}

function modificarEstadoVisita(idVisita, newEst, diaSem){
	if(newEst == 'CAN'){
		swal({
	      	title: "Alerta",
			text: 'Se eliminará la visita.¿Desea Continuar?',
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
	    		accionModificarEstadoVisita(idVisita, newEst);
	    	}
	    });
	}
	else
		accionModificarEstadoCita(idCita, newEst);
}

function modificarMsgCitaInit(orden){
	citaSelectGrid = listaCitasGrid[orden];
	$('#comentarioMod').val(listaCitasGrid[orden].obscita);
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
				    		citaSelectGrid.obscita = $('#comentarioMod').val()?$('#comentarioMod').val():'';
				    	}
				    });
	        	}
	        }
		});
	}
	
}

function cambiaCitaInit(id, texto){
	citaCambio = id; 
	$('#divCal').removeClass('col-sm-3');
	$('#divCal').addClass('col-sm-12');
	$('#calendar').fullCalendar( 'changeView', 'month');
	$('#containerBtnCita').addClass('hide');
	$('#sMed').prop("disabled", true);
	$('#dGestionCitasSolVac').children().attr("disabled","disabled");	
	$('#dGestionVac').children().attr("disabled","disabled");
	
	$('#dCambioCita').removeClass('hide');
	$('#txtCambioCita').html('<strong>Cita que se modificará: </strong>'+texto);
}

function cancelarCambio(){
	citaCambio = null;
	
	$('#sMed').prop("disabled", false);
	$('#dGestionCitasSolVac').children().attr("disabled",false);	
	$('#dGestionVac').children().attr("disabled",false);
	
	$('#dCambioCita').addClass('hide');
	$('#txtCambioCita').html('');
	
	cancelGuardado();
}

function addDivRadio(listaConfig, arrVisCit, diaSem){
	for(var i = 0; i < listaConfig.length; i++ ){
		var configTratada = listaConfig[i];
		var dur = configTratada.durcon;
		var iniMins = (configTratada.horaini *60) + configTratada.minini;
		var finMins = (configTratada.horafin *60) + configTratada.minfin;
		var totalMins = finMins - iniMins;
		var totalCitas = totalMins/dur;
		var esOtrPrf = false;
		if(arrOtrProf && arrOtrProf[idMedSelect])
			esOtrPrf = true;
		
		var resultEmpresa = $.grep(listadoEmpresas, function(e){ return e.id == configTratada.idempresa; });
		var idempresaFor = '';
		if(resultEmpresa[0]){
			$('#horasDisp').append('<h4><strong>' + resultEmpresa[0].nombre + '</strong></h4>' );
			idempresaFor = resultEmpresa[0].id;
		}
			
		
		for(var x = 0; x < totalCitas; x++){
			var existe = false;
			var horaCitaMis = iniMins + (dur * x);
			var hours = Math.floor( horaCitaMis / 60 );  
			var minutes =  (horaCitaMis % 60);
			var pacienteStr = '';
			var telStr = '';
			var seguroStr = '';
			var idPacLista = null;
			var citaFind = null;
			var obsCita = '';
			var ordenLCita = null;
			var pacConDNI = false;
			if(arrVisCit){
				listaVisitasGrid = arrVisCit;
				for(j = 0; j < arrVisCit.length; j++){
					obsCita = '';
					ordenLCita = j;
					if(arrVisCit[j].hora == horaCitaMis){
						obsCita = arrVisCit[j].obscita;
						existe = true;
						pacienteStr = '<b>' +arrVisCit[j].nompac + ' ' + arrVisCit[j].ap1pac + ' ' + arrVisCit[j].ap2pac + '</b>';

						if(!arrVisCit[j].dniusr)
							pacienteStr = pacienteStr + ' (PACIENTE SIN DOCUMENTO DE IDENTIDAD)';
						else
							pacConDNI = true;
						
						if(arrVisCit[j].numtel1 && arrVisCit[j].numtel1 > 0 )
							telStr = String(arrVisCit[j].numtel1);
						if(arrVisCit[j].numtel2 && arrVisCit[j].numtel2 > 0 ){
							if(telStr.length > 0)
								telStr = telStr + ' - ';
							telStr = telStr + String(arrVisCit[j].numtel2);
						}
						seguroStr = arrVisCit[j].nomseguro;
						if(seguroStr && seguroStr.length > 20)
							seguroStr = seguroStr.substring(0, 20) + '...';
						idPacLista = arrVisCit[j].idpac;
						citaFind = arrVisCit[j];
						break;
					}
				}
			}
			
			var strDiv = '';
			if(existe == false){
				if(!citaRapEjec){
					var strDiv = '<div class="form-group text-left" style="margin-bottom:0px; ">'
						+'<div class="radio-custom radio-primary" style="margin-bottom:0px; margin-top:0px;">'
 	               +'<input onclick="bloquearSeleccion('+dur+', '+horaCitaMis+')" type="radio" id="hor'+ i +'-'+ x +'" value= "'+horaCitaMis+'" name="horaMedSelect">'
 	                +'<label for="hor'+ i +'-'+ x +'">'+ (hours<10? '0'+hours: hours )+':'+(minutes<10? '0'+minutes: minutes )+'</label>'
 	                +'<input type="hidden" id="idempresa'+ i +'-'+ x +'" value="'+ idempresaFor +'">'
 	             + '</div></div>';
				}else{
					
					objCitRap = new Array();
					var objValue = {'dur' : dur, 'horaCitaMis': horaCitaMis, 'idempresa': idempresaFor};
					objCitRap.push(objValue);
					
					bloquearSeleccion(dur, horaCitaMis);
					continuarCita();
					break;
					
				}
			}else if((!citaCambio || (citaCambio && citaCambio != citaFind.id) ) && !citaRapEjec){
				var auxObs = (obsCita && obsCita.length > 30?'Obser.: ' + obsCita.substring(0,30)+'...': 'Obser.: ' + obsCita);
				var strDiv = '<div class="form-group text-left" style="margin-bottom:0px;">'
  	                + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Datos del Paciente"><button type="button" data-toggle="modal" data-target="#gestionPacientes" onclick="abrirInfoUsuario(\''+idPacLista+'\')" class="btn btn-success btn-icon waves-effect waves-light"><i class="icon wb-user " aria-hidden="true"></i></button></span>'
  	                + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="'+ auxObs +'"><button type="button" data-toggle="modal" data-target="#insModComentarios" onclick="modificarMsgCitaInit(\''+ordenLCita+'\')" class="btn btn-info btn-icon waves-effect waves-light"><i class="icon wb-info " aria-hidden="true"></i></button></span>';
				if(citaFind.codestado == 'PLN' )
					var texto = '';
					if(hours<10) texto = '0'+hours; else texto =hours;
					texto = texto + ':'+(minutes<10? '0'+minutes: minutes );
					texto = texto + ' - ' + pacienteStr.toUpperCase()+(seguroStr || telStr?'<br>': '');
					texto = texto + (seguroStr?'SEGURO: '+ seguroStr:'') + (telStr?' | TEL.: '+ telStr:'');
					
					if(esOtrPrf){
						strDiv = strDiv + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Cancelar Cita"><button type="button" onclick="modificarEstadoVisita(\''+citaFind.id+'\',\'CAN\', \''+ diaSem +'\')" class="btn btn-danger btn-icon waves-effect waves-light" ><i class="icon wb-minus " aria-hidden="true"></i></button></span>';
					}else{
						if(citaFind.codestado == 'PLN' && pacConDNI)
							strDiv = strDiv + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Poner Paciente En Espera"><button type="button" onclick="modificarEstadoCita(\''+citaFind.id+'\',\'ESP\', \''+ diaSem +'\')" class="btn btn-warning btn-icon waves-effect waves-light" ><i class="icon wb-warning " aria-hidden="true"></i></button></span>';
						if(citaFind.codestado == 'ESP' && pacConDNI)
							strDiv = strDiv + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Cita Planificada"><button type="button" onclick="modificarEstadoCita(\''+citaFind.id+'\',\'PLN\', \''+ diaSem +'\')" class="btn btn-info btn-icon waves-effect waves-light" ><i class="icon wb-reply " aria-hidden="true"></i></button></span>';

						strDiv = strDiv + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Cambiar Cita"><button type="button" onclick="cambiaCitaInit(\''+citaFind.id+'\',\''+texto+'\')" class="btn btn-primary btn-icon waves-effect waves-light" ><i class="icon wb-calendar " aria-hidden="true"></i></button></span>';
						strDiv = strDiv + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Cancelar Cita"><button type="button" onclick="modificarEstadoCita(\''+citaFind.id+'\',\'CAN\', \''+ diaSem +'\')" class="btn btn-danger btn-icon waves-effect waves-light" ><i class="icon wb-minus " aria-hidden="true"></i></button></span>';        						
					}
					var colorCita= citaFind.idempresa==1?'#4A6849':'#C88509';
					
					strDiv = strDiv + '<label style="color: '+colorCita+'; vertical-align: middle; margin-bottom:0px;" for="hor'+ i +'-'+ x +'">'+  texto
						+'</label>'
					+ '</div>';
			}
			if(!citaRapEjec)
				$('#horasDisp').append(strDiv);
			
			
		}
		
		if(citaRapEjec && !objCitRap){
			swal("Error", 'No existen más citas para hoy, deberá realizarlas manualmente', "error");
			break;
		}
			
			
	}
}

function gestionCitasFunc(data, diaSem){
	addDivRadio(data.listaConfig, data.listaCitas, diaSem);
	
	//Mostramos las citas manuales (estan fuera de la logica)
	if(data.listaCitas){
		for(j = 0; j < data.listaCitas.length; j++){
			if(data.listaCitas[j].tipcita == 'MAN'){
				var hours = Math.floor(data.listaCitas[j].hora / 60 );  
				var minutes =  (data.listaCitas[j].hora % 60);
				
				pacienteStr = data.listaCitas[j].nompac + ' ' + data.listaCitas[j].ap1pac + ' ' + data.listaCitas[j].ap2pac;
				
				if(!data.listaCitas[j].dniusr)
					pacienteStr = pacienteStr + ' (PACIENTE SIN DOCUMENTO DE IDENTIDAD)';
				
				idPacLista = data.listaCitas[j].idpac;
				citaFind = data.listaCitas[j];

				var strDiv = '<div class="form-group text-left" >'
  	                + '<button type="button" data-toggle="modal" data-target="#gestionPacientes" onclick="abrirInfoUsuario(\''+idPacLista+'\')" class="btn btn-info btn-icon waves-effect waves-light"><i class="icon wb-info-circle " aria-hidden="true"></i></button>';
				
				if(citaFind.codestado == 'PLN' )
					strDiv = strDiv + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Poner Paciente En Espera"><button type="button" onclick="modificarEstadoCita(\''+citaFind.id+'\',\'ESP\', \''+ diaSem +'\')" class="btn btn-warning btn-icon waves-effect waves-light" ><i class="icon wb-warning " aria-hidden="true"></i></button></span>'
					if(citaFind.codestado == 'ESP' )
					strDiv = strDiv + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Cita Planificada"><button type="button" onclick="modificarEstadoCita(\''+citaFind.id+'\',\'PLN\', \''+ diaSem +'\')" class="btn btn-info btn-icon waves-effect waves-light" ><i class="icon wb-reply " aria-hidden="true"></i></button></span>';

				var colorCita= citaFind.idempresa==1?'#4A6849':'#C88509';
				
				strDiv = strDiv + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Cancelar Cita"><button type="button" onclick="modificarEstadoCita(\''+citaFind.id+'\',\'CAN\', \''+ diaSem +'\')" class="btn btn-danger btn-icon waves-effect waves-light" ><i class="icon wb-minus " aria-hidden="true"></i></button></span>';        						
				strDiv = strDiv + '<label style="color: '+colorCita+';"'+ (hours<10? '0'+hours: hours )+':'+(minutes<10? '0'+minutes: minutes )

				strDiv = strDiv + '<label style="color: '+colorCita+';">(MANUAL) '+ (hours<10? '0'+hours: hours )+':'+(minutes<10? '0'+minutes: minutes )
  	                + ' - ' + pacienteStr.toUpperCase()
  	                +'</label>'
  	             + '</div>';
				$('#horasDisp').append(strDiv);
			}
		}
	}
	
	
	$('#horasDisp').append('<div class="form-group text-left"><span id="sCitaManual"></span></div>');
	$('[data-toggle="tooltip"]').tooltip();

}
function gestionVisitasFunc(data, diaSem){

	//para los Roles que NO necesitan especificar horario de visitas
	if(arrOtrProf && arrOtrProf[idMedSelect] && arrOtrProf[idMedSelect].codrol =='DEV'){
		if(data.listaVisitas){
			
			
			for(j = 0; j < data.listaVisitas.length; j++){
				var hours = Math.floor(data.listaVisitas[j].hora / 60 );  
				var minutes =  (data.listaVisitas[j].hora % 60);
				
				pacienteStr = data.listaVisitas[j].nompac + ' ' + data.listaVisitas[j].ap1pac + ' ' + data.listaVisitas[j].ap2pac;
				idPacLista = data.listaVisitas[j].idpac;
				citaFind = data.listaVisitas[j];

				var strDiv = '<div class="form-group text-left" >'
	                + '<button type="button" data-toggle="modal" data-target="#gestionPacientes" onclick="abrirInfoUsuario(\''+idPacLista+'\')" class="btn btn-info btn-icon waves-effect waves-light"><i class="icon wb-info-circle " aria-hidden="true"></i></button>';
				
				var colorCita= citaFind.idempresa==1?'#4A6849':'#C88509';
				
				strDiv = strDiv + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Cancelar Visita"><button type="button" onclick="modificarEstadoVisita(\''+citaFind.id+'\',\'CAN\', \''+ diaSem +'\')" class="btn btn-danger btn-icon waves-effect waves-light" ><i class="icon wb-minus " aria-hidden="true"></i></button></span>';        						
				strDiv = strDiv + '<label style="color: '+colorCita+';" for="hor'+ j +'">'+ (hours<10? '0'+hours: hours )+':'+(minutes<10? '0'+minutes: minutes )

	                + ' - ' + pacienteStr.toUpperCase()
	                +'</label>'
	             + '</div>';
				$('#horasDisp').append(strDiv);
			}
			
		}
	}else{//para los Roles que SI necesitan especificar horario de visitas
		addDivRadio(data.listaConfig, data.listaVisitas, diaSem);	
	}
	
	
	
	
	
	$('#horasDisp').append('<div class="form-group text-left"><span id="sCitaManual"></span></div>');
	$('[data-toggle="tooltip"]').tooltip();
}

function obtenerDisponivilidad( diaSem ){
	$('.loader-wrap').removeClass("hide");
	$('#horasDisp').empty();
	horaCitaManual = null;
	minuCitaManual = null;
	listaCitasGrid = null;
	citaSelectGrid = null;
	var rolOtrProf = arrOtrProf && arrOtrProf[idMedSelect]?arrOtrProf[idMedSelect].codrol:'';
	
	if(fecPrimerDia){
		var dMoment = moment(fecPrimerDia);
		fechaCalendario = dMoment._d?dMoment.format(): dMoment.getFullYear()+ "-" + vM + "-" + vD;
		diaSem = fecPrimerDia.getDay();
	}
	
	$.ajax({
		data:{'diaSem' : diaSem,
			  'idmed' : idMedSelect,
			  'idusr' : usrSes.id,
			  'feccita' : fechaCalendario,
			  'rolOtrProf' : rolOtrProf
		},
        url:   generarUrl('/private/obtenerDisponivilidad'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	durCitaSel = null;
        	$('#horasDisp').append('<h4><strong>' + fechaString(fechaCalendario) + '</strong></h4>' );
        	if(data){
        		if(data.listaConfig && !rolOtrProf){
        			listaCitasGrid = data.listaCitas;
        			gestionCitasFunc(data, diaSem);
        		}
        			
        		if(data.listaVisitas){
        			gestionVisitasFunc(data, diaSem);
        		}
        			
        		
        		
        	}
        	swiObtenerFin = true;
        	fecPrimerDia = null;
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
	        url:   generarUrl('/private/obtenerEspecialidadMedico'),
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	if(data && data.listaEspecialidades){
	        		for(var i = 0; i < data.listaEspecialidades.length; i++){
	        			$('#sEspecMed').append( $('<option>', { value: data.listaEspecialidades[i].codesp , text: data.listaEspecialidades[i].especialidad }));
	        		}
	        	}
	        	if(data.listaEspecialidades.length == 1 )
	        		$('#sEspecMed').prop('selectedIndex', 1);
	        }
	});
}

function obtenerOtroProf(o){
	$('#sEspecMed').empty();
	$('#sMedico').empty();
	$('#sEspecMed').append( $('<option>', { value: o.codrol, text: o.nomrol}));
	
}

$( "#sMed" ).change(function() {
	finCargaCalendario = false;
	finCargaCitas = false;
	citaRapEjec = false;
	objCitRap = null;
	arrTrabajo = null;
	booCamMed = true;
	idMedSelect = $( "#sMed" ).val();
	prepararCalendario($( "#sMed" ).val(), 'changeMed');
	creaCalendario();
	
	
	$('#aGestionCitasSolVac').removeClass('hide');
	$('#aCitManual').removeClass('hide');
	
	if(arrOtrProf && arrOtrProf[idMedSelect]){
		obtenerOtroProf(arrOtrProf[idMedSelect]);
		obtenerVisitasByFechas('changeMed');
		if(arrOtrProf[idMedSelect].codrol == 'DEV'){
			$('#aGestionCitasSolVac').addClass('hide');
			$('#aCitManual').addClass('hide');
		}
		
	}else{
		obtenerCitasByFechas('changeMed');
		obtenerEspecialidadMedico();
	}
	
	
	volverPaso();
	cancelGuardado();
	if(idMedSelect){
		$('#dGestionVac').removeClass('hide');
		$('#dBtnCitRap').removeClass('hide');
	}else{
		$('#dGestionVac').addClass('hide');
		$('#dBtnCitRap').addClass('hide');
	}
		
	
});

function prepararCalendario(idMed, accion){
	
	$('.loader-wrap').removeClass("hide");
	configMedico = [];
	$.ajax({
		data:{'idusr' : idMed},
        url:   generarUrl('/private/obtenerAgendaCitasMed'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.listaConfig){
        		
        		configMedico = data.listaConfig;
        		configurarCalendarioMedico(accion);
        	}
        }
	});
}

function obtenerUsuariosConCita(fecha){
	$('.loader-wrap').removeClass("hide");
	$('#fechaCitaListadoUsr').val(fecha);
	$.ajax({
		data: {
			'fechaCitaListado': fecha
		},
		url:   generarUrl('/private/obtenerUsuariosConCita'),
		type:  'GET',
		dataType: 'json',
		success:  function (data) {
			if(data && data.listaUsusarios){
				var listaUsusarios = data.listaUsusarios;
				
				var tMed = $('#tablaMedDia').DataTable();
				tMed.clear().draw();
				for(var i = 0; i < listaUsusarios.length; i++){
					var usuActual = listaUsusarios[i];
					
					tMed.row.add( [
						usuActual.nomusr ,
						usuActual.apusr,
						usuActual.numcoleg
						
					] ).draw( false );
				}
				$('[data-toggle="tooltip"]').tooltip();  
			
            }
			 $('.loader-wrap').addClass("hide");
			$('#panelMedDia').modal();
      }
    });
}

function dayClickCalendar(date){
	
	
	var dMoment = moment(date);
	
	var view = viewCalendar;
	if(viewCalendar.type != 'basicDay' && (date >= view.intervalEnd._d || !idMedSelect || date < moment(view.intervalStart._d).startOf('day')) ){
		$('#divCal').removeClass('col-sm-3');
  		$('#divCal').addClass('col-sm-12');
		$('#containerBtnCita').addClass('hide');
		
		
		
		if(!idMedSelect){
			
			var fechaSelect = dMoment._d.getDate() + "/" + (dMoment._d.getMonth()+1) + "/" + dMoment._d.getFullYear();
			var fechaSQL =  dMoment._d.getFullYear()+ "-" + (dMoment._d.getMonth()+1) + "-" + dMoment._d.getDate();
			
			swal({
		      	title: "",
				text: '¿Desea ver la lista de medicos del día '+fechaSelect+'?',
				type: "info",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: 'Si',
				cancelButtonText: 'No',
				closeOnConfirm: true
		    },
		    function(isConfirm) {
		    	if (isConfirm) {
		    		$('#fechaPanelMedDia').html(fechaSelect);
		    		obtenerUsuariosConCita(fechaSQL);
		    	}
		    });
		}
		
		return true;
	}
	if(dayClickDate && moment(dayClickDate._d).format('DDMMYYYY') != moment( dMoment._d).format('DDMMYYYY'))
		return true;
	volverPaso();
	var day_int = dMoment._d? dMoment._d.getDay():dMoment.getDay();
	var vD = !dMoment._d?dMoment.getDate():'';
	if(!dMoment._d && vD < 10)
		vD = '0' + vD;
	var vM = !dMoment._d?(dMoment.getMonth()+1):'';
	if(!dMoment._d && vM < 10)
		vM = '0' + vM;
	
	if(viewCalendar.type != 'basicDay'){
		$('#calendar').fullCalendar( 'changeView', 'basicDay');
		$('#calendar').fullCalendar( 'gotoDate', date );
		$('#calendar').fullCalendar( 'select', date );
	}
	
	
	  
	configurarCalendarioMedico();
	if(swiObtenerFin == true){
		swiObtenerFin = false;
		dayClickDate = null;
		fechaCalendario = dMoment._d?dMoment.format(): dMoment.getFullYear()+ "-" + vM + "-" + vD;
		
		
		/*console.info(date);
		console.info(dMoment._d);
		console.info(fechaCalendario);
		console.info('----------------------------');*/
		obtenerDisponivilidad(day_int);
		
	}
	
	
	$('#divCal').addClass('col-sm-3');
	$('#divCal').removeClass('col-sm-12');
	$('#containerBtnCita').removeClass('hide');
	
	
	diasVacaciones();
	
		
    // change the day's background color just for fun
   // $(this).css('background-color', '#FAE7C5');

	
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
			if (start.add(1,'days').date() != end.date() ){
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
  	dayRender : function(){
  		viewCalendar = $('#calendar').fullCalendar('getView');
		if(!booCamMed && !citaRapEjec && viewCalendar && viewCalendar.type == 'basicDay'){
			var f = $('#calendar').fullCalendar('getDate');
			var fecha = new Date(moment(f).format('YYYY'), moment(f).format('M'), moment(f).format('D'))
			//console.info('Metodo: dayRender' )
			dayClickCalendar(moment($('#calendar').fullCalendar('getDate')));
		}
  	},
	viewRender: function(view, element){
        //var fechaInicioShow = view.intervalStart._d.format();
		viewCalendar = view;
		var d = view.intervalStart._d;
		fechaInicioShow = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
		
		var d = view.intervalEnd._d;
		fechaFinShow = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
		if(!booCamMed){
			if(arrOtrProf && arrOtrProf[idMedSelect])
				obtenerVisitasByFechas();
			else
				obtenerCitasByFechas();	
		}else 
			booCamMed = false;
		
		configurarCalendarioMedico();
    },
  	dayClick: function(date, jsEvent, view) {
  		viewCalendar = view;
  		//console.info('dayClick');
  		dayClickDate = date;
  		if(!citaRapEjec)
  			dayClickCalendar(date);
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

function volverPaso(valCitRap){
	if(citaRapEjec && valCitRap){
		objCitRap = null;
		citaRapEjec = false;
		cancelGuardado();
	}
	seleccionarPaciente();
	volverSelectHora();
}



function cancelGuardado(){
	objCitRap = null;
	citaRapEjec = false;
	
	$('#divCal').removeClass('col-sm-3');
	$('#divCal').addClass('col-sm-12');
	$('#containerBtnCita').addClass('hide');
	
	$('#calendar').fullCalendar( 'changeView', 'month');
	$('#calendar').fullCalendar( 'select', '' );
	
	$('#idEmpCita').val('');
	
	if(citaCambio)
		cancelarCambio();
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
		$('#fgDniBusq').addClass('hide');
		$('#fgtipDocBusq').addClass('hide');
		$('#fgIdHistorialBusq').addClass('hide');
		
		$('#btnLimp').removeClass('hide');
		$('.dObscita').removeClass('hide');
		
		$('#btnBusPac').addClass('hide');
		
	}else{
		pacienteSelect = null;
		$('#snompac').html('');
		$('#sap1pac').html('');
		$('#sap2pac').html('');
		
		$('#snompac').addClass('hide');
		$('#sap1pac').addClass('hide');
		$('#sap2pac').addClass('hide');
		
		$('#nompac').val('');
		$('#ap1pac').val('');
		$('#ap2pac').val('');
		document.getElementById('tipdocBusq').value  = 'DNI';
		mostrarNumDoc('Busq');
		$( "#dniusrBusq" ).val('');
		$( "#nieusrBusq" ).val('');
		$( "#passusrBusq" ).val('');
		$('#idHistorialBusq').val('');
		$('#obscita').val('');
		
		$('#nompac').removeClass('hide');
		$('#ap1pac').removeClass('hide');
		$('#ap2pac').removeClass('hide');
		$('#fgDniBusq').removeClass('hide');
		$('#fgtipDocBusq').removeClass('hide');
		$('#fgIdHistorialBusq').removeClass('hide');
		$('.dObscita').addClass('hide');
		
		$('#btnLimp').addClass('hide');
		$('#btnBusPac').removeClass('hide');
	}
	
}

function seleccionarPacienteTabla(orden){
	seleccionarPaciente(pacientesActuales[orden]);
	$("#panelBusq").modal("hide");
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

$( "#tipdocBusq" ).change(function(v) {
	$( "#dniusrBusq" ).val('');
	$( "#nieusrBusq" ).val('');
	$( "#passusrBusq" ).val('');
	
	mostrarNumDoc('Busq');
});


function buscarPacienteNomAp(){
	var numdoc = $( "#tipdocBusq" ).val()== 'DNI'? $("#dniusrBusq").val(): $( "#tipdocBusq" ).val()== 'NIE'? $("#nieusrBusq").val():$( "#tipdocBusq" ).val()== 'PAS'? $("#passusrBusq").val():'';
	numdoc = numdoc.replace(/\s/g, "") ;
	
	if(!$('#nompac').val() && !$('#ap1pac').val() && !$('#ap2pac').val() && (!numdoc || numdoc == '-') && !$('#idHistorialBusq').val()){
		swal("Error", 'debe especificar almenos un elemento en la busqueda', "error");
		return false;
	}
	pacientesActuales = null;
	
	$.ajax({
			data: {
				'nompac': $('#nompac').val(),
				'ap1pac': $('#ap1pac').val(),
				'ap2pac': $('#ap2pac').val(),
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
						    },
						    function(isConfirm) {
						    	if (isConfirm) {

									$('#nompacAlta').val($('#nompac').val());
									$('#ap1pacAlta').val($('#ap1pac').val());
									$('#ap2pacAlta').val($('#ap2pac').val());
									$('#sexpacAlta').val('');
									$('#dniusrAlta').val('');
									segurosLista(listaSegurosAct, 'sIdseguroAlta');
									$('#sIdseguroAlta').val('');
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


/*********** GESTION PACIENTES ****************/

function cancelaAltaRapida(){
	$("#panelAltRap").modal("hide");
}

function addError(c){
    c.addClass("has-error");
}
function removeError(c){
    c.removeClass("has-error");
}

$( "#tipdocAlta" ).change(function(v) {
	$( "#dniusrAlta" ).val('');
	$( "#nieusrAlta" ).val('');
	$( "#passusrAlta" ).val('');
	
	if($( "#tipdocAlta" ).val()== 'DNI'){
		$( "#dniusrAlta" ).removeClass('hide');
		$( "#nieusrAlta" ).addClass('hide');
		$( "#passusrAlta" ).addClass('hide');
	}
	if($( "#tipdocAlta" ).val()== 'NIE'){
		$( "#dniusrAlta" ).addClass('hide');
		$( "#nieusrAlta" ).removeClass('hide');
		$( "#passusrAlta" ).addClass('hide');
	}
	if($( "#tipdocAlta" ).val()== 'PAS'){
		$( "#dniusrAlta" ).addClass('hide');
		$( "#nieusrAlta" ).addClass('hide');
		$( "#passusrAlta" ).removeClass('hide');
	}
});

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


function initModPac(){
	
	$('#infonompac').prop('disabled', false);
	$('#infoap1pac').prop('disabled', false);
	$('#infoap2pac').prop('disabled', false);
	$('#infofecnacpac').prop('disabled', false);
	$('#infosexpac').prop('disabled', false);
	$('#infonumtel1').prop('disabled', false);
	$('#infonumtel2').prop('disabled', false);
	$('#infodniusr').prop('disabled', false);
	$('#infoemailpac').prop('disabled', false);
	$('#infodirpac').prop('disabled', false);
	$('#infocppac').prop('disabled', false);
	$('#infocomentario').prop('disabled', false);
	$('#tipdocinfo').prop('disabled', false);
	$('#infodniusr').prop('disabled', false);
	$('#infonieusr').prop('disabled', false);
	$('#infopassusr').prop('disabled', false);
	$('#sIdseguro').prop('disabled', false);
	$('#numseg').prop('disabled', false);	
	
	$('#bCancelModPac').removeClass('hide');
	$('#bGuardarModPac').removeClass('hide');
	$('#bInitModPac').addClass('hide');

}

function cancelarModPac(){
	
	$('#infonompac').prop('disabled', true);
	$('#infoap1pac').prop('disabled', true);
	$('#infoap2pac').prop('disabled', true);
	$('#infofecnacpac').prop('disabled', true);
	$('#infosexpac').prop('disabled', true);
	$('#infonumtel1').prop('disabled', true);
	$('#infonumtel2').prop('disabled', true);
	$('#infodniusr').prop('disabled', true);
	$('#infoemailpac').prop('disabled', true);
	$('#infodirpac').prop('disabled', true);
	$('#infocppac').prop('disabled', true);
	$('#infocomentario').prop('disabled', true);
	$('#tipdocinfo').prop('disabled', true);
	$('#infodniusr').prop('disabled', true);
	$('#infonieusr').prop('disabled', true);
	$('#infopassusr').prop('disabled', true);
	$('#sIdseguro').prop('disabled', true);
	$('#numseg').prop('disabled', true);
	
	$('#bCancelModPac').addClass('hide')
	$('#bGuardarModPac').addClass('hide');;
	$('#bInitModPac').removeClass('hide');

}


function guardarModPac(){

	//Campos Obligatorios
	var error = false;
	var numdoc = $( "#tipdocinfo" ).val()== 'DNI'? $("#infodniusr").val(): $( "#tipdocinfo" ).val()== 'NIE'? $("#infonieusr").val():$( "#tipdocinfo" ).val()== 'PAS'? $("#infopassusr").val():'';
	if(!$("#infonompac").val()){error = true; addError($("#fgNombreMod"));} else removeError($("#fgNombreMod"));
	if(!$("#infoap1pac").val()){error = true; addError($("#fgSurName1Mod"));} else removeError($("#fgSurName1Mod"));
	if(!numdoc || numdoc == '        - ' || $("#infodniusr").val() == ' - '){error = true; addError($("#fgDniMod"));} else removeError($("#fgDniMod"));
	if(!$("#infosexpac").val()){error = true; addError($("#fgSexpacMod"));} else removeError($("#fgSexpacMod"));
	
	if(error){
		swal("Error", 'Debe de Rellenar los campos Obligatorios: Nombre, Primer apellido, DNI y Sexo', "error");
		return false;
	}
	$('.loader-wrap').removeClass("hide");
	
	
	var parametros = {
			'id': $('#infoidHistorial').val(),
			'nompac': $('#infonompac').val(),
			'ap1pac': $('#infoap1pac').val(),
			'ap2pac': $('#infoap2pac').val(),
			'sexpac': $('#infosexpac').val(),
			'tipdoc': $('#tipdocinfo').val(),
			'dniusr': numdoc,
			'fecnacpac': $('#infofecnacpac').val(),
			'numtel1': $('#infonumtel1').val(),
			'numtel2': $('#infonumtel2').val(),
			'emailpac': $('#infoemailpac').val(),
			'dirpac': $('#infodirpac').val(),
			'cppac': $('#infocppac').val(),
			'comentario': $('#infocomentario').val(),
			'idseguro': $('#sIdseguro').val(),
			'numseg': $('#numseg').val(),
			'tipMod' : 'AGENDA',
			'codUsr': usrSes.id
	};
	
	$.ajax({
		data: parametros,
        url:   generarUrl('/private/guardarPaciente'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.paciente){
        		swal("Correcto", 'Paciente modificado', "success");
        		cancelarModPac();
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

function mostrarOcultarTipoDoc(){

	if($( "#tipdocinfo" ).val()== 'DNI'){
		$( "#infodniusr" ).removeClass('hide');
		$( "#infonieusr" ).addClass('hide');
		$( "#infopassusr" ).addClass('hide');
	}
	if($( "#tipdocinfo" ).val()== 'NIE'){
		$( "#infodniusr" ).addClass('hide');
		$( "#infonieusr" ).removeClass('hide');
		$( "#infopassusr" ).addClass('hide');
	}
	if($( "#tipdocinfo" ).val()== 'PAS'){
		$( "#infodniusr" ).addClass('hide');
		$( "#infonieusr" ).addClass('hide');
		$( "#infopassusr" ).removeClass('hide');
	}
}


$( "#tipdocinfo" ).change(function(v) {
	$( "#infodniusr" ).val('');
	$( "#infonieusr" ).val('');
	$( "#infopassusr" ).val('');
	mostrarOcultarTipoDoc();
});

/*********** FIN GESTION PACIENTES ****************/

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
	$("#gestionCitas").modal("toggle");
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
/************ GESTION VACACIONES ********************/
function eliminarVacaciones(id){
	swal({
      	title: "Atención",
		text: 'Se va a proceder a eliminar el registro, ¿Desea Continuar?',
		type: "info",
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: 'Continuar',
		cancelButtonText: 'Cancelar',
		closeOnConfirm: true
    },
    function(isConfirm) {
    	if (isConfirm) {
    		$('.loader-wrap').removeClass("hide");
    		$.ajax({
    	        url:   generarUrl('/private/eliminarVacionesByid'),
    	        data: {
    	        	'id' : id
    	        },
    	        type:  'GET',
    	        dataType: 'json',
    	        success:  function (data) {
    	        	  $('.loader-wrap').addClass("hide");
    	          	  if(data && data.msgOk){
    	          		swal("Vacaciones Eliminadas", data.msgOk, "success");
    	          		initPantallaVacaiones();
    	          	  }
    	            }
    	     });
    	}
    });
}

function initPantallaVacaiones(){
	$('.loader-wrap').removeClass("hide");
	$('#dpIni').val('');
	$('#dpFin').val('');
	
	$.ajax({
        url:   generarUrl('/private/obtenerVacacionesMed'),
        data: {
        	'idusr' : idMedSelect
        },
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
      	  $('.loader-wrap').addClass("hide");
      	  if(data && data.listaVaciones){
	      		var listaVaciones = data.listaVaciones;
				
				var tVac = $('#tablaVacaciones').DataTable();
				tVac.clear().draw();
				for(var i = 0; i < listaVaciones.length; i++){
					var regVac = listaVaciones[i];
					var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
					listaBtn = listaBtn + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Eliminar"><button type="button" onclick="eliminarVacaciones(\''+regVac.id+'\')" class="btn btn-danger btn-icon waves-effect waves-light"><i class="icon wb-minus-circle " aria-hidden="true"></i></button></span>';
					listaBtn = listaBtn + '</div>';
					tVac.row.add( [
						fechaString(regVac.fecini),
						fechaString(regVac.fecfin),
						listaBtn
						
					] ).draw( false );
				}
				$('[data-toggle="tooltip"]').tooltip();
      	  }
        }
     });
}

function guardarVacaciones(){
	
	 $('.loader-wrap').removeClass("hide");
	
	$.ajax({
        url:   generarUrl('/private/guardarVacaciones'),
        data: {
        	'fecini': fechaStringEncode($('#dpIni').val()),
        	'fecfin': fechaStringEncode($('#dpFin').val()),
        	'idusr' : idMedSelect
        },
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
      	  $('.loader-wrap').addClass("hide");
      	  if(data && data.msgOk){
      		swal("Vacaciones creadas", 'Debe seleccionar una especialidad', "success");
      		initPantallaVacaiones();
      		obtenerCitasByFechas();
      	  }
        }
      });
}
/************ FIN GESTION VACACIONES ********************/


/*********CITA RAPADI*********/
function citaRapida(){
	
	citaRapEjec = true;
	objCitRap = null;
	var dateAtratar = new Date();
	var mes = dateAtratar.getMonth();
	var anyo= dateAtratar.getFullYear();
	var m = (dateAtratar.getMonth()+1)<10? ('0' + (dateAtratar.getMonth()+1).toString()): (dateAtratar.getMonth()+1);
	var d = (dateAtratar.getDate())<10? ('0' + (dateAtratar.getDate()).toString()): (dateAtratar.getDate());
	var fechaSQL = dateAtratar.getFullYear()+ "-" + m + "-" + d;
	var classObj = $( "td[data-date='"+ fechaSQL +"']" ).attr("class");
			
	
	$('#calendar').fullCalendar( 'today');
	
   	dayClickCalendar(new Date(anyo, mes, dateAtratar.getDate()));

	
	//seleccionarPrimerDiaTrabajo('CITRAP');
}

/*********CITA RAPADI*********/
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

function verListaDocsPendFirma(){
	var tDocFirma = $('#tablaMedDocsFirma').DataTable();
	tDocFirma.clear().draw();
	$('.loader-wrap').removeClass("hide");
	$.ajax({
        url:   generarUrl('/private/verListaDocsPendFirma'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data){
        		listaDocsPend = data.listaDocsPend;

	       		 for(var i = 0; i< listaDocsPend.length; i++){
	   				var lDoc = listaDocsPend[i];
	   					tDocFirma.row.add( [
				   			lDoc.nombremed ,
				   			lDoc.totDocs           				   			
				   		] ).draw( false );
	       			}

           	$('[data-toggle="tooltip"]').tooltip();
        	}
        }
		});
}


function initPantalla(){
	obtenerDatosUsuarioSession();
	if(listaMedicos){
		cargaComboOpt(listaMedicos,$('#sMedOpMed'), 'N' );
	}
	if(listaOtrProf){
		arrOtrProf = new Array();
		cargaComboOpt(listaOtrProf,'', 'S' );
	}
	if(listadoEmpresas){
		$('#idEmpCita').append(
				$('<option>', {
					value: '',
					text: 'Empresa'
				}));
		for(var i =0; i < listadoEmpresas.length; i++){
			$('#idEmpCita').append(
				$('<option>', {
					value: listadoEmpresas[i].id,
					text: listadoEmpresas[i].nombre
				}));
		}
	}
	
	if(listaDocsPend && listaDocsPend.length>0){
		$('#btnDocPendFirm').removeClass('hide');
	}
}


