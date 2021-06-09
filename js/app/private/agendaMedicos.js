$(document).ready(function(){
      
      setTimeout(
    		  function() 
    		  {
    			  $('[name="liMenuAgendaMed"]').addClass('active');
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

var horaCitaManual = null;
var minuCitaManual = null;

var booCamMed = false;
var swiObtenerFin = true;
var dayClickDate = null;

var citasVac = null;
var listaCitasGrid = null;
var citaSelectGrid = null;

var finCargaCalendario = false;
var finCargaCitas = false;
var arrTrabajo = null;
var viewCalendar = null;

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
	$('.fc-day.calendarFestivos').css('background', '#B4A32E');
}


function volverSelectHora(){
	$('#containerBtnCita').removeClass('hide');
	$('#containerInfoCita').addClass('hide');
}

function gestionHorEspCalendar(horariosEsp){
	$('.fc-day').removeClass('calendarHorEsp');
	
	if(horariosEsp && horariosEsp.length > 0){
		
		for(var i = 0; i < horariosEsp.length; i++){
			var regHoEsp = horariosEsp[i];
			var diaIniHor = new Date(fechaInicioShow)>=new Date(regHoEsp.fecinitemp)?fechaInicioShow:regHoEsp.fecinitemp;
			var diaFinHor = new Date(fechaFinShow)<=new Date(regHoEsp.fecfintemp)?fechaFinShow:regHoEsp.fecfintemp;
			var fechaTratada = diaFinHor;
								
			var diaIni = new Date(diaIniHor).getDate();
			var diaFin = (new Date(diaIniHor).getMonth()+1)!=(new Date(diaFinHor).getMonth()+1)?31:new Date(diaFinHor).getDate();
			
			for(var j = diaIni; j <= diaFin; j++){
				
				//Añadimos la clase para despues añadirle fondo
				fechaTratada = new Date(diaIniHor).getFullYear() + "-" + (new Date(diaIniHor).getMonth()+1<10?'0'+(new Date(diaIniHor).getMonth()+1):new Date(diaIniHor).getMonth()+1) + "-" + (j <10? '0'+j:j);
				$( "td[data-date='"+ fechaTratada +"']" ).addClass('calendarHorEsp');
				
				var newEvent = new Object();
				newEvent.title = 'Ausencia';
				newEvent.start = fechaTratada;
				newEvent.backgroundColor = '#A0B6C9';
				newEvent.allDay = false;
				$('#calendar').fullCalendar( 'renderEvent', newEvent );
				
			}
			
			diaFinHor = new Date(diaFinHor).getFullYear() + "-" + (new Date(diaFinHor).getMonth()+1<10?'0'+(new Date(diaFinHor).getMonth()+1):new Date(diaFinHor).getMonth()+1) + "-" + (new Date(diaFinHor).getDate()<10?'0'+new Date(diaFinHor).getDate():new Date(diaFinHor).getDate());

			$( "td[data-date='"+ diaFinHor +"']" ).addClass('calendarHorEsp');
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

function obtenerCitasByFechas(){
	$('#calendar').fullCalendar('removeEvents');
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
		
		arrTrabajo = arr;
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
	
	finCargaCalendario = true;
	
}



function obtenerDisponivilidad( diaSem ){
	$('.loader-wrap').removeClass("hide");
	$('#horasDisp').empty();
	horaCitaManual = null;
	minuCitaManual = null;
	listaCitasGrid = null;
	citaSelectGrid = null;
	
	$.ajax({
		data:{'diaSem' : diaSem,
			  'idmed' : idMedSelect,
			  'idusr' : usrSes.id,
			  'feccita' : fechaCalendario
		},
        url:   generarUrl('/private/obtenerDisponivilidad'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data){
        		if(data.listaConfig){
        			durCitaSel = null;
        			$('#horasDisp').append('<h4><strong>' + fechaString(fechaCalendario) + '</strong></h4>' );
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
        					var pacienteStr = '';
        					var idPacLista = null;
        					var citaFind = null;
        					var ordenLCita = null;
        					if(data.listaCitas){
        						listaCitasGrid = data.listaCitas;
        						for(j = 0; j < data.listaCitas.length; j++){
        							ordenLCita = j;
        							if(data.listaCitas[j].hora == horaCitaMis){
        								existe = true;
        								pacienteStr = data.listaCitas[j].nompac + ' ' + data.listaCitas[j].ap1pac + ' ' + data.listaCitas[j].ap2pac;
        								idPacLista = data.listaCitas[j].idpac;
        								citaFind = data.listaCitas[j];
        								break;
        							}
        						}
        					}
        					
        					var strDiv = '';
        					if(existe == false){
        						
            					var strDiv = '<div class="form-group text-left" style="margin-bottom:0px; ">'
            						+'<div style="margin-bottom:0px; margin-top:0px;">'
             	                +'<label for="hor'+ i +'-'+ x +'">'+ (hours<10? '0'+hours: hours )+':'+(minutes<10? '0'+minutes: minutes )+' - Libre</label>'
             	             + '</div></div>';
        					}else{
        						var strDiv = '<div class="form-group text-left" style="margin-bottom:0px;">';
        						if(citaFind.codestado == 'PLN' )
        							var texto = '';
        							if(hours<10) texto = '0'+hours; else texto =hours;
        							texto = texto + ':'+(minutes<10? '0'+minutes: minutes );
        							texto = texto + ' - ' + pacienteStr.toUpperCase();
        							var colorCita= citaFind.idempresa==1?'#4A6849':'#C88509';
        							strDiv = strDiv + '<label style="color: '+colorCita+'; vertical-align: middle; margin-bottom:0px;" for="hor'+ i +'-'+ x +'">'+  texto
        								+'</label>'
        							+ '</div>';
        					}
        					$('#horasDisp').append(strDiv);
        					
        					
        				}
        				
        				
        			}
        			
        			//Mostramos las citas manuales (estan fuera de la logica)
        			if(data.listaCitas){
        				for(j = 0; j < data.listaCitas.length; j++){
    						if(data.listaCitas[j].tipcita == 'MAN'){
    							var hours = Math.floor(data.listaCitas[j].hora / 60 );  
            					var minutes =  (data.listaCitas[j].hora % 60);
            					
    							pacienteStr = data.listaCitas[j].nompac + ' ' + data.listaCitas[j].ap1pac + ' ' + data.listaCitas[j].ap2pac;
    							idPacLista = data.listaCitas[j].idpac;
    							citaFind = data.listaCitas[j];

        						var strDiv = '<div class="form-group text-left" >'
                  	                + '<button type="button" data-toggle="modal" data-target="#gestionPacientes" onclick="abrirInfoUsuario(\''+idPacLista+'\')" class="btn btn-info btn-icon waves-effect waves-light"><i class="icon wb-info-circle " aria-hidden="true"></i></button>';
        						
        						if(citaFind.codestado == 'PLN' )
        							strDiv = strDiv + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Poner Paciente En Espera"><button type="button" onclick="modificarEstadoCita(\''+citaFind.id+'\',\'ESP\', \''+ diaSem +'\')" class="btn btn-warning btn-icon waves-effect waves-light" ><i class="icon wb-warning " aria-hidden="true"></i></button></span>'
       							if(citaFind.codestado == 'ESP' )
        							strDiv = strDiv + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Cita Planificada"><button type="button" onclick="modificarEstadoCita(\''+citaFind.id+'\',\'PLN\', \''+ diaSem +'\')" class="btn btn-info btn-icon waves-effect waves-light" ><i class="icon wb-reply " aria-hidden="true"></i></button></span>';

        						strDiv = strDiv + '<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Cancelar Cita"><button type="button" onclick="modificarEstadoCita(\''+citaFind.id+'\',\'CAN\', \''+ diaSem +'\')" class="btn btn-danger btn-icon waves-effect waves-light" ><i class="icon wb-minus " aria-hidden="true"></i></button></span>';        						strDiv = strDiv + '<label style="color: red;" for="hor'+ i +'-'+ x +'">'+ (hours<10? '0'+hours: hours )+':'+(minutes<10? '0'+minutes: minutes )

        						strDiv = strDiv + '<label style="color: red;" for="hor'+ i +'-'+ x +'">(MANUAL) '+ (hours<10? '0'+hours: hours )+':'+(minutes<10? '0'+minutes: minutes )
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
        	}
        	swiObtenerFin = true;
        }
	});
}

function initCarga(idMed){
	finCargaCalendario = false;
	finCargaCitas = false;
	arrTrabajo = null;
	booCamMed = true;
	idMedSelect = idMed;
	prepararCalendario(idMed);
	creaCalendario();
	obtenerCitasByFechas();
	if(idMedSelect)
		$('#dGestionVac').removeClass('hide');
	else
		$('#dGestionVac').addClass('hide');
	
};

function prepararCalendario(idMed){
	
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
        		configurarCalendarioMedico();
        	}
        }
	});
}


function dayClickCalendar(date){
	
	
	var dMoment = moment(date);
	
	var view = viewCalendar;
	if(viewCalendar.type != 'basicDay' && (date >= view.intervalEnd._d || !idMedSelect || date < view.intervalStart._d) ){
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
		if(!booCamMed && viewCalendar && viewCalendar.type == 'basicDay'){
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
		if(!booCamMed)
			obtenerCitasByFechas();
		else 
			booCamMed = false;
		
		configurarCalendarioMedico();
    },
  	dayClick: function(date, jsEvent, view) {
  		viewCalendar = view;
  		//console.info('dayClick');
  		dayClickDate = date;
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


function ampliaCalendario(){
	$('#divCal').removeClass('col-sm-3');
	$('#divCal').addClass('col-sm-12');
	$('#containerBtnCita').addClass('hide');
	$('#calendar').fullCalendar( 'changeView', 'month');
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
        	  initCarga(usrSes.id);
        	  console.info()
        	  $('.nombreMed').html( (usrSes.nomusr+ ' ' + usrSes.apusr).toUpperCase());
          }
      }
    });
}

function initPantalla(){
	obtenerDatosUsuarioSession();
}


