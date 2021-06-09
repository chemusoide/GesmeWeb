<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Response;
use Carbon\Carbon;
use Session;
use Input;
use View;
use App;
use DB;

use App\BloqueoCita;
use App\ConfigAgenda;
use App\Paciente;
use App\Visita;
use App\Cita;
use App\Documento;
use App\Parametro;
use App\VacacionesUsuario;

class agendaController extends Controller {

	public function obtenerPrametrosInfo($tipo){
		$tip = Parametro::
		where('tipo','=', $tipo)->get();
	
		return $tip;
	
	}
	
	public function guardarConfigAgenda(){
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		$swiCambia = Input::get('swicambia');
		$msgExtraInfo = '';
		
		//Validaciones Formulario
		
		if((Input::get('horaini') > Input::get('horafin')) 
				|| (Input::get('horaini') == Input::get('horafin') && Input::get('minini') >= Input::get('minfin')  ) ){
					return Response::json(array('msgError'=>'La hora de inicio no puede ser anteiror a la hora de Fin'));
		}
		
		if(!Input::get('listaDias'))
			return Response::json(array('msgError'=>'Debe indicar los días que pasará consulta'));
		
		$inicioMins = (Input::get('horaini') * 60) + Input::get('minini');
		$finMins = (Input::get('horafin') * 60) + Input::get('minfin');
		
		$totMinTrabaja = $finMins - $inicioMins;
		
		if(Input::get('durcon') > 0){
			if($totMinTrabaja%Input::get('durcon') > 0)
				return Response::json(array('msgError'=>'No se pueden cuadrar las citas con la duración indicada'));
		}
		
		$vl = "N";
		$vm = "N";
		$vx = "N";
		$vj = "N";
		$vv = "N";
		$vs = "N";
		$vd = "N";
		
		foreach(Input::get('listaDias') as $v){
			if($v == "L")$vl = "S";
			if($v == "M")$vm = "S";
			if($v == "X")$vx = "S";
			if($v == "J")$vj = "S";
			if($v == "V")$vv = "S";
			if($v == "S")$vs = "S";
			if($v == "D")$vd = "S";
		}
		
		
		//Obtenesmos las cangiguraciones del usuario que no esten dadas de baja;
		
		$listaConfig = null;
		
		if(Input::get('id')){
			$listaConfig = ConfigAgenda::
			where('id', '<>', Input::get('id'))
			->where('idusr', '=', Input::get('idusr'))
			->where('fecbajadmin', '>' , Carbon::today()->toDateString() )->get();
		}else{
			$listaConfig = ConfigAgenda::
			where('idusr', '=', Input::get('idusr'))
			->where('fecbajadmin', '>' , Carbon::today()->toDateString() )->get();
		}
		
		
		//Si ya tiene configuraciones validamos que la nueva no entre en conflicto
		if(sizeof($listaConfig) > 0){
			foreach($listaConfig as $configActual){
				if($swiCambia && $swiCambia == 'S'){
					$msgExtraInfo = 'Recuerde que una agenda eventual tiene prioridad sobre la configurada como por defecto';
				}else{
					if(($configActual->diaseml == "S" && $vl == "S")
							|| ($configActual->diasemm == "S" && $vm == "S")
							|| ($configActual->diasemx == "S" && $vx == "S")
							|| ($configActual->diasemj == "S" && $vj == "S")
							|| ($configActual->diasemv == "S" && $vv == "S")
							|| ($configActual->diasems == "S" && $vs == "S")
							|| ($configActual->diasemd == "S" && $vd == "S")){
									
								$inicioMinsAct = ($configActual->horaini * 60) + $configActual->minini;
								$finMinsAct = ($configActual->horafin * 60) +$configActual->minfin;
								if((($inicioMins >= $inicioMinsAct && $inicioMins < $finMinsAct) ||
										($finMins > $inicioMinsAct && $finMins < $finMinsAct)) && $configActual->swicambia == $swiCambia){
											return Response::json(array('msgError'=>'Conflicto Con los días indicados y los horarios introducidos.'));
								}
					}
				}
				
			}
		}
		$config = null;
		
		if(Input::get('id')){
			$config = ConfigAgenda::
			where('id', '=', Input::get('id'))->get()->first();
		}else{
			$config = new ConfigAgenda();
		}
		
		$config->diaseml =$vl;
		$config->diasemm =$vm;
		$config->diasemx =$vx;
		$config->diasemj =$vj;
		$config->diasemv =$vv;
		$config->diasems =$vs;
		$config->diasemd =$vd;
		$config->idusr = Input::get('idusr');
		$config->idempresa = Input::get('idempresa');
		$config->horaini = Input::get('horaini');
		$config->minini = Input::get('minini');
		$config->horafin = Input::get('horafin');
		$config->minfin = Input::get('minfin');
		$config->durcon = Input::get('durcon');
		$config->fecbajadmin = $defaulfeccha->format('Y/m/d');
		
		$config->swicambia = $swiCambia;
		
		if($swiCambia && $swiCambia == 'S'){
			$config->fecfintemp = Carbon::createFromFormat('d/m/Y',Input::get('fecfintemp'))->format('Y/m/d');
			$config->fecinitemp = Carbon::createFromFormat('d/m/Y',Input::get('fecinitemp'))->format('Y/m/d');
		}
		
		$config->save();
		
		return Response::json(array('msgOk'=>'Configuración guardada.'.$msgExtraInfo, 'idusr'=>$config->idusr));
		
		
	}
	
	public function obtenerHoriosEspeciales($usr, $fecini, $fecfin){
				
		$listaConfig = ConfigAgenda::
		leftJoin('empresas', 'empresas.id', '=', 'confi_agenda.idempresa')
		->where('idusr', '=', $usr)
		->where('fecbajadmin', '>' , Carbon::today()->toDateString() )
		->where('swicambia', '=', 'S')
		
		->where('fecfintemp', '>=', $fecini)
		->where('fecinitemp', '<=', $fecfin)
		/*->where('fecinitemp', '>=', Carbon::createFromFormat('Y/m/d',$fecini)->format('Y-M-d'))
		->where('fecfintemp', '<=', Carbon::createFromFormat('Y/m/d',$fecini)->format('Y-M-d'))*/
		/*->where(function($query) use ($fecini, $fecfin)
		{
			$query->orWhere('fecinitemp', '<=', Carbon::createFromFormat('Y/m/d',$fecini)->format('Y-m-d'))
			->orWhere('fecfintemp', '>=', Carbon::createFromFormat('Y/m/d',$fecini)->format('Y-m-d'))
			;
		})*/
		->select('confi_agenda.*', 'empresas.nombre')
		->get();
		
		return $listaConfig;
	}
	
	private function obtenerConfigMedVigente($idusr){
		$listaConfig = ConfigAgenda::
		leftJoin('empresas', 'empresas.id', '=', 'confi_agenda.idempresa')
		->where('idusr', '=', $idusr)
		->where('fecbajadmin', '>' , Carbon::today()->toDateString() )
		//->where('swicambia', '=', 'N') 
		->select('confi_agenda.*', 'empresas.nombre')
		->get();
		return $listaConfig;
	}
	
	public function obtenerConfigAgendaUsuario(){
		$listaConfig =$this->obtenerConfigMedVigente(Input::get('idusr'));		
		return Response::json(array('listaConfig'=>$listaConfig));
	}
	
	public function deleteConfig(){
		$listaConfig = ConfigAgenda::
		where('id', '=', Input::get('id'))->get()->first();
		
		$listaConfig->fecbajadmin = Carbon::today()->toDateString();
		
		$listaConfig ->save();
	
		return Response::json(array('msgOk'=>'Configuración Eliminada Correctamente', 'idusr'=>$listaConfig->idusr));
	}
	
	public function obtenerAgendaCitasMed(){
		$listaConfig =$this->obtenerConfigMedVigente(Input::get('idusr'));	
		
		return Response::json(array('listaConfig'=>$listaConfig, 'med' => Input::get('idusr')));
	}
	
	public function obtenerDisponivilidad(){
		$this->eliminarBloqueos(Input::get('idusr'));
		$fecini = Carbon::parse(Input::get('feccita'))->format('Y-m-d');
		
		$swiSelect = 'diasem';
		if(Input::get('diaSem') == 0) $swiSelect = $swiSelect."d";
		if(Input::get('diaSem') == 1) $swiSelect = $swiSelect."l";
		if(Input::get('diaSem') == 2) $swiSelect = $swiSelect."m";
		if(Input::get('diaSem') == 3) $swiSelect = $swiSelect."x";
		if(Input::get('diaSem') == 4) $swiSelect = $swiSelect."j";
		if(Input::get('diaSem') == 5) $swiSelect = $swiSelect."v";
		if(Input::get('diaSem') == 6) $swiSelect = $swiSelect."s";
		
		$listaConfig = ConfigAgenda::
		where('idusr', '=', Input::get('idmed'))
		->where($swiSelect, '=', 'S')
		->where('swicambia', '=', 'S')
		->where('fecfintemp', '>=', $fecini)
		->where('fecinitemp', '<=', $fecini)
		->where('fecbajadmin', '>' , Carbon::today()->format('Y-m-d') )->orderBy('horaini', 'asc')->get();
		
		
		
		//return Response::json(array('listaConfig'=>$listaConfig, 'fecini'=>$fecini ));
		
		if(!$listaConfig || sizeof($listaConfig) == 0){
			$listaConfig = ConfigAgenda::
			where('idusr', '=', Input::get('idmed'))
			->where($swiSelect, '=', 'S')
			->where('swicambia', '=', 'N')
			->where('fecbajadmin', '>' , Carbon::today()->toDateString() )->orderBy('horaini', 'asc')->get();
		}
		
		
		//obtenemos las citas que tiene el medico ese día
		
		$listaCitas = null;
		$listaVisitas = null;
		
		
		if(strlen(Input::get('rolOtrProf')) > 0){
			$listaVisitas = Visita::
			where('idusr', '=', Input::get('idmed'))
			->where('fecvisita', '=', Carbon::parse(Input::get('feccita'))->format('Y-m-d'))
			->where('fecbaj', '>' , Carbon::today()->toDateString() )
			->leftJoin('pacientes', DB::raw(" MD5( pacientes.id)"), '=', 'visitas.idpac')
			->leftJoin('aseguradoras', 'aseguradoras.id', '=', 'pacientes.idseguro')
			->select('visitas.*',
					'pacientes.nompac',
					'pacientes.ap1pac',
					'pacientes.ap2pac',
					'pacientes.numtel1',
					'pacientes.numtel2',
					'pacientes.dniusr',
					'aseguradoras.nomseguro')
					->orderBy('hora', 'asc')
					->get();
		}else{
			$listaCitas = Cita::
			where('idusr', '=', Input::get('idmed'))
			->where('feccita', '=',  Carbon::parse(Input::get('feccita'))->format('Y-m-d'))
			->where('fecbaja', '>' , Carbon::today()->toDateString() )
			->leftJoin('pacientes', DB::raw(" MD5( pacientes.id)"), '=', 'citas.idpac')
			->leftJoin('aseguradoras', 'aseguradoras.id', '=', 'pacientes.idseguro')
			->select('citas.*',
					'pacientes.nompac',
					'pacientes.ap1pac',
					'pacientes.ap2pac',
					'pacientes.numtel1',
					'pacientes.numtel2',
					'pacientes.dniusr',
					'aseguradoras.nomseguro')
					->get();
		}
		
		
		
		$bloq = BloqueoCita::where('idmed', '=', Input::get('idmed'))
		->where('feccita', '=', Input::get('feccita'))->get();
		
		return Response::json(array('listaConfig'=>$listaConfig,'listaCitas'=>$listaCitas,'listaVisitas'=>$listaVisitas ,'listaBloqueos'=>$bloq, 'feccita' => Carbon::parse(Input::get('feccita'))->format('Y-m-d')));
		
	}
	
	public function guardarCita(){
		$pac = null;
		$cita = null;
		$info = null;
		if(Input::get('swiCambio') == "S"){
			
			$cita = Cita::where('id', '=', Input::get('citaCambio'))->get()->first();
			$cita->hora = Input::get('hora');
			$cita->feccita = Input::get('feccita');
			$cita->tipcita = Input::get('tipcita');
			$cita->save();
			
			$pac = Paciente::where(DB::raw('md5(id)'), '=',$cita->idpac)->get()->first();

		}else{
			$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
			$pac = Paciente::where('id', '=', Input::get('idpac'))->get()->first();
			$info = null;
			
			$listaCitas = Cita::
			where('idpac', '=', hash ( "md5" , Input::get('idpac')))
			->where('feccita', '=', Input::get('feccita'))
			->where('fecbaja', '>' , Carbon::today()->toDateString() )->get();
			
			if($listaCitas && sizeof($listaCitas) > 0)
				$info = 'Ya existe una cita para este día, revise si es correcto';
			
				$cita = new Cita();
				$cita->idusr = Input::get('idusr');
				$cita->idpac = hash ( "md5" , Input::get('idpac'));
				$cita->hora = Input::get('hora');
				$cita->feccita = Input::get('feccita');
				$cita->codestado = "PLN";
				if(Input::get('swiCitRap') && strlen(Input::get('swiCitRap')) > 0 && Input::get('swiCitRap') == "S"){
					$cita->codestado = "ESP";
				}
				
				$cita->idsegactual = $pac->idseguro;
				$cita->numseg = $pac->numseg;
				$cita->codesp = Input::get('codesp');
				$cita->durcon = Input::get('durcon');
				$cita->tipcita = Input::get('tipcita');
				$cita->obscita = Input::get('obscita');
				if(Input::get('idempresa')){
					$cita->idempresa = Input::get('idempresa');
				}else{
					$cita->idempresa = Session::get('usuario.idempresa')[0];
				}
				
				$cita->codusrcre = Input::get('codUsr');
				$cita->fecbaja = $defaulfeccha->format('Y/m/d');
				$cita->save();
			
		}
		
		$nombreCompleto = $pac->nompac.' '. $pac->ap1pac.' '. $pac->ap2pac;
		return Response::json(array('nombreCompleto'=>$nombreCompleto, 'feccita' => $cita->feccita, 'hora' => $cita->hora, 'info' => $info ));
	
	}
	
	public function eliminarBloqueos($idusr){
		BloqueoCita::where('created_at', '<', Carbon::now('Europe/Madrid')->subMinutes(3))
		->orWhere('idusr', '=', $idusr)->delete();
	}
	
	public function bloquearCita(){
		$this->eliminarBloqueos(Input::get('idusr'));
		
		$bloq = BloqueoCita::where('idmed', '=', Input::get('idmed'))
		->where('hora', '=', Input::get('hora'))
		->where('feccita', '=', Input::get('feccita'))
		->get()->first();
		
		if(sizeof($bloq) > 0){
			return Response::json(array('msgErr'=>'Esta cita ya esta bloqueada, intentelo de nuevo más tarde o seleccione otra.' ));
		}else{
			$nbloq = new BloqueoCita();
			$nbloq->idusr = Input::get('idusr');
			$nbloq->idmed = Input::get('idmed');
			$nbloq->idpac = hash ( "md5" , Input::get('idpac'));
			$nbloq->hora = Input::get('hora');
			$nbloq->feccita = Input::get('feccita');
			$nbloq->save();
		}
		return Response::json(array('msgOk'=>'Bloqueo Creado' ));
	}
	
	public function obtenerCitasUsr(){
		
		$citas = DB::table('citas')->where('idusr', '=', Input::get('idmed'))
		->where('citas.fecbaja', '>',  Carbon::now('Europe/Madrid')->format('Y/m/d'))
		->where('citas.feccita', '=', Carbon::now('Europe/Madrid')->format('Y/m/d'))
		->orWhere(function($query)
		{
			$query->where('idusr', '=', Input::get('idmed'))
			->where('citas.codestado', 'ABR');
		})
		->leftJoin('pacientes', DB::raw(" MD5( pacientes.id)"), '=', 'citas.idpac')
		->leftJoin('parametros_gesmeweb', 'parametros_gesmeweb.coddom', '=',  'citas.codestado')
		->leftJoin('aseguradoras', 'aseguradoras.id', '=', 'pacientes.idseguro')
		->where('tipo','=', 'ESTADO_CITA')
		->select('citas.*',
				'parametros_gesmeweb.desval',
				'pacientes.nompac',
				'pacientes.ap1pac',
				'pacientes.ap2pac',
				'pacientes.dniusr',
				'pacientes.fecnacpac',
				'aseguradoras.nomseguro')
		->get();
		
		$dateFiltro = Carbon::now('Europe/Madrid');
		$dateFiltro = $dateFiltro->subDays(7);
		
		/*
		 *Contenido eliminado
		 * 
		 * $histCitas = DB::table('citas')->where('idusr', '=', Input::get('idmed'))
		->where('citas.fecbaja', '>',  Carbon::now('Europe/Madrid')->format('Y/m/d'))
		->where('citas.feccita', '>=', $dateFiltro->format('Y/m/d'))
		->orWhere(function($query)
		{
			$query->where('idusr', '=', Input::get('idmed'))
			->where('citas.codestado', 'FIN');
		})
		->leftJoin('pacientes',  DB::raw(" MD5( pacientes.id)"), '=', 'citas.idpac')
		->select('citas.*',
				'pacientes.nompac',
				'pacientes.ap1pac',
				'pacientes.ap2pac')
				->skip(0)->take(10)->get();*/
		
		$est = $this->obtenerPrametrosInfo('ESTADO_CITA');
		
		return Response::json(array('citas'=>$citas, 'estadosCita' => $est/*, 'histCitas' =>$histCitas*/));
	}
	
	public function obtenerCitasActuales(){
		$citas = DB::table('citas')->where('idpac', '=', hash ( "md5" , Input::get('id')))
		->where('citas.fecbaja', '>',  Carbon::now('Europe/Madrid')->format('Y/m/d'))
		->where('citas.feccita', '>=', Carbon::now('Europe/Madrid')->format('Y/m/d'))
		->leftJoin('usuarios', 'usuarios.id', '=', 'citas.idusr')
		->select('citas.*',
				'usuarios.nomusr',
				'usuarios.apusr')
				->orderBy('feccita', 'desc')
				->get();
		
		$est = $this->obtenerPrametrosInfo('ESTADO_CITA');
		
		return Response::json(array('citas'=>$citas, 'estadosCita' => $est  ));
	}
	
	public function obtenerCitasHistorico(){
		$citas = DB::table('citas')->where('idpac', '=', hash ( "md5" , Input::get('id')))
		->leftJoin('usuarios', 'usuarios.id', '=', 'citas.idusr')
		->select('citas.*',
				'usuarios.nomusr',
				'usuarios.apusr')
				->orderBy('feccita', 'desc')
				->get();
	
				$est = $this->obtenerPrametrosInfo('ESTADO_CITA');
	
				return Response::json(array('citas'=>$citas, 'estadosCita' => $est  ));
	}
	
	
	public function obtenerMisPacientes(){
		$documento = str_replace(' ', '', Input::get('dniusr'));
		$med = Input::get('idmed');
		$pacientes = DB::table('pacientes')
		->leftJoin('citas', DB::raw(" MD5( pacientes.id)"), '=', 'citas.idpac')
		->leftJoin('usr_aseguradoras', "pacientes.idseguro", '=', 'usr_aseguradoras.idseg')
		->leftJoin('usr_pac_compartidos', "citas.idusr", '=', 'usr_pac_compartidos.idusr')
		
		
		
		->where(function($q) use ($med){
			$q->where(function($q1) use ($med){
				$q1->where('citas.idusr', '=', $med )
				->where('citas.fecbaja', '>',  Carbon::now('Europe/Madrid')->format('Y/m/d'));
			});
			
			$q->orWhere(function($q2)  use ($med) {
				$q2->where('usr_aseguradoras.idusr', '=', $med );
			});
			
			$q->orWhere(function($q3)  use ($med) {
				
				$q3->where('usr_pac_compartidos.idusrben', '=', $med );
			});
			
		});
		
		
		if(Input::get('nompac'))
			$pacientes = $pacientes->where('nompac', 'LIKE', Input::get('nompac')."%");
		if(Input::get('ap1pac'))
			$pacientes = $pacientes->where('ap1pac', 'LIKE', Input::get('ap1pac')."%");
		if(Input::get('ap2pac'))
			$pacientes = $pacientes->where('ap2pac', 'LIKE', Input::get('ap2pac')."%");
		if($documento && $documento!= "-")
			$pacientes = $pacientes->whereIn('dniusr', array( Input::get('dniusr'), str_replace('-', '', Input::get('dniusr')) ));
		if(Input::get('idHistorial'))
			$pacientes = $pacientes->where('pacientes.id', '=', Input::get('idHistorial'));
				
		$pacientes = $pacientes->select('pacientes.*')->distinct()
		->get();
		//->toSql();
		
		return Response::json(array('pacientes'=>$pacientes));
	}
	
	public function accesoListadoPacientes(){
		
		$swiEsp = "S";
		
		if(Input::get('swienfermera') && Input::get('swienfermera') == "S"){
			$swiEsp = "N";
		}
		
		app('App\Http\Controllers\accesoController')->inserTarAcceso(Input::get('idmed'), null, 'VER_TODOS_PACIENTES', $swiEsp , Input::get('comentario'));
		
		$pacientes = Paciente::all();
		
		return Response::json(array('pacientes'=>$pacientes));
	}
	
	/************** Lista Medicos ******************/
	
	public function initListaMed(){
		
		
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		
		$listaMedicos = app('App\Http\Controllers\usuariosController')->listaMedicosEspecialidad();
		$listaOtrProf = app('App\Http\Controllers\usuariosController')->listaOtrosUsr();
		$listaDocsPend = app('App\Http\Controllers\firmaController')->numdocsMedFirmaPend(null);
		$listadoEmpresas = app('App\Http\Controllers\empresasController')->obtenerEmpresas();
		
		return View::make('pages.private/busqMed', array('version' => $v->desval, 'listaMedicos' => $listaMedicos, 'listaOtrProf' => $listaOtrProf, 'listaDocsPend' => $listaDocsPend, 'listadoEmpresas' => $listadoEmpresas));
	}
	
	/************** FIN Lista Medicos ******************/

	/************** Vacaciones **********************/
	
	public function guardarVacaciones(){
		$regVac = new VacacionesUsuario();
		$regVac->fecini = Input::get('fecini');
		$regVac->fecfin = Input::get('fecfin');
		$regVac->idusr = Input::get('idusr');
		$regVac->save();
		return Response::json(array('msgOk'=>'El registro se guardo correctamente'));
	}
	
	public function obtenerVacacionesMed(){
		$listaVaciones = VacacionesUsuario::where('idusr', '=', Input::get('idusr'))->get();

		return Response::json(array('listaVaciones'=> $listaVaciones));
	}
	
	public function eliminarVacionesByid(){
		$regVac = VacacionesUsuario::find(Input::get('id'));
		$regVac->delete();
		
		return Response::json(array('msgOk'=>'El registro se elimino correctamente'));
		
	}
	
	
	/************** FIN vacaciones *********************/
	
	
	public function imprimirHistCita(){
		$data = new Documento();
		$pac = Paciente::where('id', '=', Input::get('idPacList'))->get()->first();

		$citas = DB::table('citas')->where('idpac', '=', hash ( "md5" , Input::get('idPacList')))
		->leftJoin('usuarios', 'usuarios.id', '=', 'citas.idusr')
		->leftJoin('parametros_gesmeweb', 'parametros_gesmeweb.coddom', '=', 'citas.codestado')
		->leftJoin('especialidades', 'especialidades.codesp', '=', 'citas.codesp')
		->where('tipo','=', 'ESTADO_CITA')
		->select('citas.*',
				'usuarios.nomusr',
				'usuarios.apusr',
				'parametros_gesmeweb.desval',
				'especialidad')
				->orderBy('feccita', 'desc')
				->get();
	
				$est = $this->obtenerPrametrosInfo('ESTADO_CITA');
	
	
		$data->html = $data->html.'<div class="col-sm-12 print100"><h2>Historico Citas</h2> <h4>PACIENTE: '.mb_strtoupper($pac->nompac).' '.mb_strtoupper($pac->ap1pac).' '.mb_strtoupper($pac->ap2pac).'</h4></div>';
		$cont=1;
		foreach($citas as $item){
			
			$hora = 0;
			$min = 0;
			
			if($item->hora > 0){
				$hora = intval($item->hora/60);
				$min = $item->hora%60;
			}
			if($hora < 10)
				$hora = "0".$hora;
			
			if($min < 10)
				$min = "0".$min;
			
			$data->html = $data->html."<div>  <strong>".$cont." - </strong>". 
			"<strong>FECHA:</strong> ".Carbon::createFromFormat('Y-m-d',$item->feccita)->format('d/m/Y'). " ".$hora.":".$min.
			" <strong>DOCTOR:</strong> ".mb_strtoupper($item->nomusr). " ". mb_strtoupper($item->apusr).
			"<br>".
			"<strong>ESPECIALIDAD:</strong> ".mb_strtoupper($item->especialidad).
			" <strong>ESTADO:</strong> ".mb_strtoupper($item->desval).
			"</div> <hr>";
			$cont = $cont+1;
		}
		$data->empresa = Session::get('usuario.idempresa')[0];
		$nombreCompletoUsr = null;
		$view =  View::make('PDF.docs', compact('data', 'nombreCompletoUsr'))->render();
		$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML($view);
		return $pdf->stream('documento.pdf');
	}
	
	/********** mi-agenda ******************/
	function initAgendaMed(){
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		
		$listaMedicos = app('App\Http\Controllers\usuariosController')->listaMedicos();
		
		return View::make('pages.private/agendaMed', array('version' => $v->desval, 'listaMedicos' => $listaMedicos));
	}
	
}
