<?php

Blade::setContentTags('<%', '%>');        // for variables and all things Blade
Blade::setEscapedContentTags('<%%', '%%>');   // for escaped data

use App\Alergia;
use App\Cita;
use App\Antecedente;
use App\PruebaComplementaria;
use App\HistorialViejo;
use App\LconsultaViejo;
use App\Usuario;
use App\Paciente;
use App\Especialidad;
use App\DetalleCita;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/************** Codificar IDPAC *****************/


	Route:: get('/private/insertarMedicosBorrar', 'migracionDbController@insertarMedicosBorrar');
	Route:: get('/private/insertarHistorialBorrar', 'migracionDbController@insertarHistorialBorrar');
	
	Route:: get('/private/citasMedOld', 'migracionDbController@citasMedOld');
	Route:: get('/private/insertarHistorialBorrar', 'migracionDbController@insertarHistorialBorrar');

Route::get('/volcadoHistorialViejo', function() {
	
	/*$listConsult = LconsultaViejo::where('ideusrnew', '>', 0 )
	->where('idpacnew', '>', 0)
	->where('idespnew', '>', 0)->get();
	
	
	foreach($listConsult as $item){
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		$fechaCita = Carbon::create(substr($item->lconsulta_fechaHora, 0, 4),substr($item->lconsulta_fechaHora, 5, 2), substr($item->lconsulta_fechaHora, 8, 2), 0, 0, 0);
		
		$cita = new Cita();
		$cita->idusr = $item->ideusrnew;
		$cita->idold = $item->id;
		$cita->idpac = hash ( "md5" , $item->idpacnew);
		$cita->hora = 0;
		$cita->feccita = $fechaCita->format('Y/m/d');
		$cita->codestado = "FIN";
		$cita->codesp = $item->idespnew;
		$cita->durcon = 0;
		$cita->fecbaja = $defaulfeccha->format('Y/m/d');
		$cita->save();
		
		$det = new DetalleCita();
		$det->fecinicita  = $fechaCita->format('Y/m/d');
		$det->idecita = $cita->id;
		$det->lineaConsulta = $item->lconsulta_texto;
		$det->save();
		
	
	}
	
	*/
	
	return'Hecho';
});

Route::get('/codspecialidadHistorialViejo', function() {
	/*$listEsp = Especialidad::all();
	
	
	foreach($listEsp as $item){
		$listCitas = Cita::where('codesp', '=', $item->id )->get();
		//return $listEsp;
		foreach($listCitas as $itemHist){
			$itemHist->codesp = $item->codesp;
			$itemHist->save();
			//return $itemHist;
		}
	
	}
	
	*/
	
	return'Hecho';
});

Route::get('/especialidadHistorialViejo', function() {
	/*$listEsp = Especialidad::all();
	
	
	foreach($listEsp as $item){
		$listConsult = LconsultaViejo::where('lconsulta_idespecialidad', '=', $item->idold )->get();
		//return $listEsp;
		foreach($listConsult as $itemHist){
			$itemHist->idespnew = $item->id;
			$itemHist->save();
			//return $itemHist;
		}
	
	}
	
	
	*/
	return'Hecho';
});

Route::get('/pacientesHistorialViejo', function() {
	/*$listPac = Paciente::where('idold', '>', 0)->get();
	
	
	foreach($listPac as $item){
		$listConsult = LconsultaViejo::where('historial_idpaciente', '=', $item->idold )->get();
		//return $listConsult;
		foreach($listConsult as $itemHist){
			$itemHist->idpacnew = $item->id;
			$itemHist->save();
			//return $itemHist;
		}
	
	}
	*/
	
	
	return'Hecho';
});

Route::get('/medicosHistorialViejo', function() {
	/*$listUsr = Usuario::where('idold', '>', 0)->get();
	
	//return $listUsr;
	foreach($listUsr as $item){
		$listConsult = LconsultaViejo::where('lconsulta_idusuario', '=', $item->idold )->get();
		foreach($listConsult as $itemHist){
			$itemHist->ideusrnew = $item->id;
			$itemHist->save();
			//return $itemHist;
		}
	
	}
	
	*/
	
	return'Hecho';
});

Route::get('/medicosHistorialViejo', function() {
	/*$listUsr = Usuario::where('idold', '>', 0)->get();
	
	//return $listUsr;
	foreach($listUsr as $item){
		$listConsult = LconsultaViejo::where('lconsulta_idusuario', '=', $item->idold )->get();
		foreach($listConsult as $itemHist){
			$itemHist->ideusrnew = $item->id;
			$itemHist->save();
			//return $itemHist;
		}
	
	}
	
	
	*/
	return'Hecho';
});


Route::get('/historialViejo', function() {
	$listHist = HistorialViejo::all();
	
	
	foreach($listHist as $item){
		$listConsult = LconsultaViejo::where('lconsulta_idhistorial', '=', $item->id )->get();
		foreach($listConsult as $itemHist){
			$itemHist->historial_idpaciente = $item->historial_idpaciente;
			$itemHist->save();
			//return $itemHist;
		}
	
	}
	
	
	
	return'Hecho';
});

Route::get('/alergiasIdepacChange', function() {
	$listAlergias = Alergia::all();
	return null;
	//$listAlergias = Alergia::first();
		
	foreach($listAlergias as $item){
		$idPac = hash ( "md5" , $item->idecpac);
		//$idPac = Crypt::encrypt($item->idecpac);
		$item->idecpac = $idPac;
		//$idPac = Crypt::decrypt($item->idecpac);
		//return $idPac;	
		$item->save();
	
	}
		
		
	return $listAlergias;
});


Route::get('/antecedentesIdepacChange', function() {
	$listAntecedentes = Antecedente::all();
	return null;
	//$listAlergias = Alergia::first();
		
	foreach($listAntecedentes as $item){
		$idPac = hash ( "md5" , $item->idpac);
		//$idPac = Crypt::encrypt($item->idecpac);
		$item->idpac = $idPac;
		//$idPac = Crypt::decrypt($item->idecpac);
		//return $idPac;	
		$item->save();
	
	}
		
		
	return $listAntecedentes;
});

Route::get('/pruebaCompIdepacChange', function() {
	$listPrueba = PruebaComplementaria::all();
	return null;
	//$listAlergias = Alergia::first();
		
	foreach($listPrueba as $item){
		$idPac = hash ( "md5" , $item->idpac);
		//$idPac = Crypt::encrypt($item->idecpac);
		$item->idpac = $idPac;
		//$idPac = Crypt::decrypt($item->idecpac);
		//return $idPac;	
		$item->save();
	
	}
		
		
	return $listPrueba;
});

Route::get('/citasIdepacChange', function() {
	$listCitas = Cita::all();
	return null;
	//$listAlergias = Alergia::first();

	foreach($listCitas as $item){
		$idPac = hash ( "md5" , $item->idpac);
		//$idPac = Crypt::encrypt($item->idecpac);
		$item->idpac = $idPac;
		//$idPac = Crypt::decrypt($item->idecpac);
		//return $idPac;
		$item->save();

	}


	return $listCitas;
});


/*Usado por Policlinico*/
//SESSION
Route:: get('/private/loginUsuario', 'SessionController@loginUsuario');
Route:: get('/private/obtenerDatosUsuarioSession', 'SessionController@obtenerDatosUsuarioSession');
Route:: get('/private/mataSesiones', 'SessionController@mataSesiones');
Route:: get('/private/menuConfig', 'SessionController@menuConfig');
Route:: get('/private/accederEmpresa', 'SessionController@accederEmpresa');

//USUARIOS
Route:: get('/private/obtenerDatosInitAlta', 'usuariosController@obtenerDatosInitAlta');
Route:: get('/private/altaUsuario', 'usuariosController@altaUsuario');
Route:: get('/private/obtenerUsuarios', 'usuariosController@obtenerUsuarios');
Route:: get('/private/aceptarUsuario', 'usuariosController@aceptarUsuario');
Route:: get('/private/bajaUsuario', 'usuariosController@bajaUsuario');
Route:: get('/private/readmitirUsuario', 'usuariosController@readmitirUsuario');
Route:: get('/private/actualizarDatosUsuario', 'usuariosController@actualizarDatosUsuario');
Route:: get('/private/actualizarPass', 'usuariosController@actualizarPass');
Route:: get('/private/restablecerContrasena', 'usuariosController@restablecerContrasena');
Route:: get('/private/buscarMedNomApCol', 'usuariosController@buscarMedNomApCol');
Route:: get('/private/obtenerEspecialidadMedico', 'usuariosController@obtenerEspecialidadMedico');
Route:: get('/private/altaManualUsr', 'usuariosController@altaManualUsr');
Route:: get('/private/obtenerListaMed', 'usuariosController@obtenerListaMed');
Route:: get('/private/listaMedicosEspecialidad', 'usuariosController@listaMedicosEspecialidad');


//PACIENTES
Route:: get('/private/initPantallaAltaModPacientes', 'pacientesController@initPantallaAltaModPacientes');
Route:: get('/private/guardarPaciente', 'pacientesController@guardarPaciente');
Route:: get('/private/obtenerPacientes', 'pacientesController@obtenerPacientes');
Route:: get('/private/obtenerEspecialistas', 'pacientesController@obtenerEspecialistas');
Route:: get('/private/buscarPacienteNomAp', 'pacientesController@buscarPacienteNomAp');

//AGENDA
Route:: get('/private/guardarConfigAgenda', 'agendaController@guardarConfigAgenda');
Route:: get('/private/obtenerConfigAgendaUsuario', 'agendaController@obtenerConfigAgendaUsuario');
Route:: get('/private/deleteConfig', 'agendaController@deleteConfig');
Route:: get('/private/obtenerAgendaCitasMed', 'agendaController@obtenerAgendaCitasMed');
Route:: get('/private/obtenerDisponivilidad', 'agendaController@obtenerDisponivilidad');
Route:: get('/private/guardarCita', 'agendaController@guardarCita');
Route:: get('/private/bloquearCita', 'agendaController@bloquearCita');
Route:: get('/private/obtenerCitasUsr', 'agendaController@obtenerCitasUsr');
Route:: get('/private/obtenerCitasActuales', 'agendaController@obtenerCitasActuales');
Route:: get('/private/obtenerMisPacientes', 'agendaController@obtenerMisPacientes');
Route:: get('/private/accesoListadoPacientes', 'agendaController@accesoListadoPacientes');
Route:: get('/private/obtenerCitasHistorico', 'agendaController@obtenerCitasHistorico');
Route:: get('/private/guardarVacaciones', 'agendaController@guardarVacaciones');
Route:: get('/private/obtenerVacacionesMed', 'agendaController@obtenerVacacionesMed');
Route:: get('/private/eliminarVacionesByid', 'agendaController@eliminarVacionesByid');
Route:: get('/private/imprimirHistCita', 'agendaController@imprimirHistCita');

//CITAS
Route:: get('/private/modificarEstadoCita', 'citaController@modificarEstadoCita');
Route:: post('/private/guardarModificarCita', 'citaController@guardarModificarCita');
Route:: get('/private/guardarAlergia', 'citaController@guardarAlergia');
Route:: get('/private/prepararVentanaAlergia', 'citaController@prepararVentanaAlergia');
Route:: get('/private/eliminarAlergia', 'citaController@eliminarAlergia');
Route:: get('/private/saveconfigAlergia', 'citaController@saveconfigAlergia');
Route:: get('/private/guardarAntecedente', 'citaController@guardarAntecedente');
Route:: get('/private/prepararVentanaAntecedentes', 'citaController@prepararVentanaAntecedentes');
Route:: get('/private/eliminarAntecedente', 'citaController@eliminarAntecedente');
Route:: get('/private/prepararVentanaHistConsultas', 'citaController@prepararVentanaHistConsultas');
Route:: get('/private/verHistoricoCitas', 'citaController@verHistoricoCitas');
Route:: post('/private/guardarPruebaComplem', 'citaController@guardarPruebaComplem');
Route:: get('/private/prepararVentanaPrueCompl', 'citaController@prepararVentanaPrueCompl');
Route:: get('/private/eliminarPruebaCompl', 'citaController@eliminarPruebaCompl');
Route:: get('/private/prepararVentanaObs', 'citaController@prepararVentanaObs');
Route:: get('/private/guardarObs', 'citaController@guardarObs');
Route:: get('/private/eliminarObservacion', 'citaController@eliminarObservacion');
Route:: get('/private/obtenerEspecialidadesPac', 'citaController@obtenerEspecialidadesPac');
Route:: get('/private/obtenerCitasByFechas', 'citaController@obtenerCitasByFechas');
Route:: get('/private/crearConsultaSinCita', 'citaController@crearConsultaSinCita');
Route:: get('/private/obtenerUsuariosConCita', 'citaController@obtenerUsuariosConCita');
Route:: get('/private/imprimirListadoUsr', 'citaController@imprimirListadoUsr');
Route:: get('/private/obtenerUltimasCitasPac', 'citaController@obtenerUltimasCitasPac');
Route:: get('/private/obtenerCitasVacacionesUsr', 'citaController@obtenerCitasVacacionesUsr');
Route:: post('/private/modificarMsgCita', 'citaController@modificarMsgCita');
Route:: get('/private/initPantallaCitasFinHoy', 'citaController@initPantallaCitasFinHoy');
Route:: get('/private/verHistoricoCitasPaginado', 'citaController@verHistoricoCitasPaginado');
Route:: get('/private/reportarCitaOldById', 'citaController@reportarCitaOldById');

//Historial
Route:: get('/private/buscarHistorial', 'historialController@buscarHistorial');
Route:: get('/private/imprimirHistorial', 'historialController@imprimirHistorial');

//Plantillas
Route:: post('/private/insertarModificarNuevaPlantilla', 'plantillaController@insertarModificarNuevaPlantilla');
Route:: get('/private/listadoPlantillasUsuario', 'plantillaController@listadoPlantillasUsuario');
Route:: get('/private/eliminarPlantillaUsuario', 'plantillaController@eliminarPlantillaUsuario');

//Documentos
Route:: post('/private/imprimirDoc', 'documentoController@imprimirDoc');
Route:: get('/private/buscarDocumentos', 'documentoController@buscarDocumentos');
Route:: get('/private/buscarConfiguracionesDoc', 'documentoController@buscarConfiguracionesDoc');
Route:: get('/private/buscarConfiguracionesDocGrupo', 'documentoController@buscarConfiguracionesDocGrupo');

//configuraciones
Route:: get('/private/obtenerEspecialidades', 'configAdmController@obtenerEspecialidades');
Route:: get('/private/guardarEspecialidad', 'configAdmController@guardarEspecialidad');
Route:: get('/private/eliminarEspecialidad', 'configAdmController@eliminarEspecialidad');
Route:: get('/private/obtenerSegurosAdm', 'configAdmController@obtenerSegurosAdm');
Route:: get('/private/guardarSeguro', 'configAdmController@guardarSeguro');
Route:: get('/private/eliminarSeguro', 'configAdmController@eliminarSeguro');
Route:: get('/private/obtenerFestivosAnual', 'configAdmController@obtenerFestivosAnual');
Route:: get('/private/eliminarFestivo', 'configAdmController@eliminarFestivo');
Route:: get('/private/guardarFestivo', 'configAdmController@guardarFestivo');
Route:: get('/private/buscarCieByClaseDesc', 'configAdmController@buscarCieByClaseDesc');
Route:: get('/private/guardarCie', 'configAdmController@guardarCie');
Route:: get('/private/buscarCieProByCodDesc', 'configAdmController@buscarCieProByCodDesc');

//Firma
Route::get('/firma/firma-documentos/{id}', 'firmaController@initFirmarDocumentos');
Route::get('/firma/firma-documentos-profesional/{id}', 'firmaController@initFirmarDocumentosProf');
Route:: get('/firma/obtenerDocAFirmar', 'firmaController@obtenerDocAFirmar');
Route:: post('/firma/guardarDocFirmado', 'firmaController@guardarDocFirmado');
Route:: get('/firma/obtenerDocFirmaPac', 'firmaController@obtenerDocFirmaPac');
Route:: get('/firma/obtenerDocFirmaMed', 'firmaController@obtenerDocFirmaMed');
Route:: get('/private/verDocFirmado', 'firmaController@verDocFirmado');
Route:: get('/private/verListaDocsPendFirma', 'firmaController@verListaDocsPendFirma');

//empresas
Route:: get('/private/volcadoClientePoliclinico', 'empresasController@volcadoClientePoliclinico');

//Visitas
Route:: get('/private/guardarVisita', 'visitaController@guardarVisita');
Route:: get('/private/obtenerVisitasByFechas', 'visitaController@obtenerVisitasByFechas');
Route:: get('/private/modificarEstadoVisita', 'visitaController@modificarEstadoVisita');
Route:: get('/private/obtenerVisitasUsr', 'visitaController@obtenerVisitasUsr');
Route:: get('/private/finalizarVisita', 'visitaController@finalizarVisita');

//Actos quirurjicos
Route:: get('/private/guardarActQui', 'actQuiController@guardarActQui');
Route:: get('/private/obtenerActQuiAbiertosByUsr', 'actQuiController@obtenerActQuiAbiertosByUsr');
Route:: get('/private/getDatosInfoActQui', 'actQuiController@getDatosInfoActQui');
Route:: get('/private/obtenerActQuiAbiertosByIdpac', 'actQuiController@obtenerActQuiAbiertosByIdpac');
Route:: get('/private/guardarInfoPreoperatorio', 'actQuiController@guardarInfoPreoperatorio');
Route:: get('/private/obtenerPreoperatorio', 'actQuiController@obtenerPreoperatorio');
Route:: get('/private/finalizarActQui', 'actQuiController@finalizarActQui');
Route:: get('/private/obtenerActQuiHistByUsr', 'actQuiController@obtenerActQuiHistByUsr');
Route:: get('/private/guardarEdicionCieFin', 'actQuiController@guardarEdicionCieFin');
Route:: get('/private/guardarEdicionCieProFin', 'actQuiController@guardarEdicionCieProFin');
Route:: get('/private/hojaQuiroRedirect', 'actQuiController@hojaQuiroRedirect');


//INGRESOS
Route:: get('/private/insertarIngreso', 'ingresoController@insertarIngreso');
Route:: get('/private/obtenerIngresosActivos', 'ingresoController@obtenerIngresosActivos');
Route:: get('/private/darAltaPaciente', 'ingresoController@darAltaPaciente');
Route:: get('/private/obtenerInformeAlta', 'ingresoController@obtenerInformeAlta');
Route:: post('/private/actualizarInformeAlta', 'ingresoController@actualizarInformeAlta');
Route:: get('/private/imprimirAlta', 'ingresoController@imprimirAlta');
//HABITACIONES
Route:: get('/private/obtenerhabitacionesDisponibles', 'habitacionController@obtenerhabitacionesDisponibles');
Route:: get('/private/liberaHab', 'habitacionController@liberaHab');
//SOLICITUDES INGRESOS
Route:: get('/private/tiposSolicitudesIngreso', 'solicitudIngresoController@tiposSolicitudesIngreso');
Route:: get('/private/insertNuevaSolicitud', 'solicitudIngresoController@insertNuevaSolicitud');
Route:: get('/private/obtenerSolicitudesIngreso', 'solicitudIngresoController@obtenerSolicitudesIngreso');
Route:: get('/private/bajaSolicitudIngreso', 'solicitudIngresoController@bajaSolicitudIngreso');
//EVOLUTIVOS INGRESOS
Route:: post('/private/insertNuevoEvolutivo', 'evolutivoController@insertNuevoEvolutivo');
Route:: get('/private/obtenerEvolutivosIngreso', 'evolutivoController@obtenerEvolutivosIngreso');
Route:: get('/private/bajaEvolutivoIngreso', 'evolutivoController@bajaEvolutivoIngreso');
//GESTION MEDICAMERNTOS
Route:: get('/private/eliminarMedicacionIng', 'medicacionController@eliminarMedicacionIng');
Route:: get('/private/obtenerMedicacionIngActivos', 'medicacionController@obtenerMedicacionIngActivos');
Route:: get('/private/obtenerMedicamentos', 'medicacionController@obtenerMedicamentos');
Route:: get('/private/guardarMedicamentosIngreso', 'medicacionController@guardarMedicamentosIngreso');
Route:: get('/private/medicacionSuministradaByIdIngreso', 'medicacionController@medicacionSuministradaByIdIngreso');
Route:: get('/private/medicacionSuministradaByIdIngresoHistorico', 'medicacionController@medicacionSuministradaByIdIngresoHistorico');
Route:: get('/private/registrarFirmaMedicacion', 'medicacionController@registrarFirmaMedicacion');


//PUBLIC
Route::get('/', function() {
	$v = app('App\Http\Controllers\SessionController')->versionArchivos();
	return View::make('pages.home', array('version' => $v->desval)); 
});

Route::get('/registro', function() {
	$v = app('App\Http\Controllers\SessionController')->versionArchivos();
	return View::make('pages.private/registro', array('version' => $v->desval));
});

Route::get('/registro-completado', function() {
	$v = app('App\Http\Controllers\SessionController')->versionArchivos();
	return View::make('pages.private/registro-completado', array('version' => $v->desval));
});

Route::get('/recordar-password', function() {
	$v = app('App\Http\Controllers\SessionController')->versionArchivos();
	return View::make('pages.private/recordar', array('version' => $v->desval));
});


//PRIVADO
Route::group(['middleware' => 'sessionAdmMid'], function () {
	Route::get('/private/perfil', function() {
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		return View::make('pages.private/profile', array('version' => $v->desval));
	});
	
});
	
//PRIVADO
Route::group(['middleware' => 'sessionMid'], function () {
	Route:: get('/private/obtCitasMedDiario', 'citaController@obtCitasMedDiario');
	Route::get('/private/listado-usuarios', function() {
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		$listaEmpresa = app('App\Http\Controllers\empresasController')->obtenerEmpresas();
		return View::make('pages.private/user-list', array('version' => $v->desval, 'listaEmpresa' =>$listaEmpresa));
	});
	
	Route::get('/private/listado-pacientes', function() {
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		return View::make('pages.private/pacientes-list', array('version' => $v->desval));
	});
	
	Route::get('/private/tablon-medico', function() {
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		return View::make('pages.private/tablonMed', array('version' => $v->desval));
	});
	
	Route::get('/private/tablon-enfermeria', function() {
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		return View::make('pages.private/tablonEnf', array('version' => $v->desval));
	});
	
	Route::get('/private/visitas', function() {
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		return View::make('pages.private/tablonVisita', array('version' => $v->desval));
	});
	
	Route::get('/private/actos-quirurgicos', function() {
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		return View::make('pages.private/tablonActQui', array('version' => $v->desval));
	});
	
	Route:: get('/private/lista-documentos', 'documentoController@listadocumentos');

	Route::get('/private/perfil', function() {
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		return View::make('pages.private/profile', array('version' => $v->desval));
	});
	
	Route:: get('/private/gestion_cita/{id}', 'citaController@continuarIniciarCita');
	
	Route:: get('/private/historial/{id}', 'historialController@initHistPac');
	Route:: get('/private/historial-enfermeria/{id}', 'historialController@initHistPacEnf');
	
	Route::get('/private/busqueda-paciente', function() {
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		$listaOpciones = app('App\Http\Controllers\SessionController')->gestionOpcionesFichaPaciente();
		
		return View::make('pages.private/busqFichaPac', array('version' => $v->desval, 'listaOpciones' => $listaOpciones));
	});
	
	Route:: get('/private/lista-medicos', 'agendaController@initListaMed');
	Route:: get('/private/mi-agenda', 'agendaController@initAgendaMed');
	
	Route:: get('/private/configuraciones', 'configAdmController@initConfiguraciones');
	Route:: get('/private/ingresos', 'ingresoController@paginaGeneral');
	
	//Firma
	Route::get('/private/firma-documentos', function() {
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		return View::make('pages.firma/homeFirma', array('version' => $v->desval));
	});
	
	Route:: get('/private/Registro-quirofano', 'actQuiController@registroquirofano');
	

});




	