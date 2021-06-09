<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Response;
use Carbon\Carbon;
use Input;
use Session;
use DB;
use View;
use App;

use App\PruebaComplementaria;
use App\EspecialidadUsuario;
use App\Especialidad;
use App\DetalleCita;
use App\Cita;
use App\Seguro;
use App\Usuario;
use App\Alergia;
use App\Parametro;
use App\Paciente;
use App\Festivos;
use App\Antecedente;
use App\Observacion;
use App\Documento;
use App\VacacionesUsuario;

class citaController extends Controller {
	
	public function obtenerCitasPacienteMedico($idePac, $ideMed){
		$citas = Cita::where('idusr', '=', $ideMed)
		->where('idpac', '=', hash ( "md5" , $idePac))
		->where('codestado', '=', 'FIN')->get();
		return $citas;
	}
	
	public function obtenerPrametrosInfo($tipo){
		$tip = Parametro::
		where('tipo','=', $tipo)->get();
	
		return $tip;
	
	}
	public function obtenerDesParam($tipo, $coddom){
		$tip = Parametro::
		where('tipo','=', $tipo)
		->where('coddom','=', $coddom)->get()->first();
	
		return $tip->desval;
	
	}
	
	public function obtenerCitaByid($id){
		return Cita::where('citas.id', '=', $id)
		->leftJoin('especialidades', 'especialidades.codesp', '=', 'citas.codesp')
		->leftJoin('aseguradoras', 'aseguradoras.id', '=', 'citas.idsegactual')
		->select('citas.*',
				'especialidades.especialidad',
				'aseguradoras.nomseguro')->get()->first();
	}
	
	public function obtenerDetalleCitaByid($id){
		return DetalleCita::where('idecita', '=',$id)->get()->first();
	}
	
	public function continuarIniciarCita($id){
		$cita = $this->obtenerCitaByid($id);
		$det = $this->obtenerDetalleCitaByid($id);
			if(sizeof($det) == 0){
				$det = new DetalleCita();
				$det->fecinicita  = Carbon::now('Europe/Madrid')->format('Y/m/d H:i:s');
				$det->idecita = $id;
				$det->save();
			}
		
		$listAlergias = $this->obtenerAlergiasUsrActivas($cita->idpac, false);
		
		$pacienteSelect = app('App\Http\Controllers\pacientesController')->buscarPacienteById($cita->idpac, true);
		
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		
		return View::make('pages.private/consulta',array('id' =>$id, 'cita' => $cita, 'detalleCita' => $det, 'listAlergias' => $listAlergias,'version' => $v->desval, 'pacienteSelect' => $pacienteSelect));
	}
	
	public function obtenerAlergiasUsrActivas($idPaciente, $swiEncryp){
		
		$idPac = $idPaciente;
		
		if(!$swiEncryp)
			$idPac = hash ( "md5" , $idPaciente);
	
		$listAlergias = Alergia::where('idecpac', '=', $idPac)
		->where('fecbaj', '>', Carbon::now('Europe/Madrid')->format('Y/m/d'))->get();
		
		return $listAlergias;
	}
	
	public function obtenerAlergiasEspUsrActivas($idPaciente){
		$listAlergias = Alergia::where('idecpac', '=', hash ( "md5" , $idPaciente))
		->whereNotNull('codalergia')
		->where('fecbaj', '>', Carbon::now('Europe/Madrid')->format('Y/m/d'))->get();
	
		return $listAlergias;
	}
	
	public function modificarEstadoCita(){
		$cita = Cita::where('id', '=', Input::get('id'))->get()->first();
		$cita->codestado = Input::get('codestado');
		if(Input::get('codestado') == "CAN")
			$cita->fecbaja =  Carbon::now('Europe/Madrid')->format('Y/m/d');
			$cita->save();
	
			return Response::json(array('msgOk'=>'Cita Modificada'));
	}
	
	
	public function guardarModificarCita(){
		
		$det = DetalleCita::where('idecita', '=',Input::get('idecita'))->get()->first();
		$cita = Cita::where('id', '=', Input::get('idecita'))->get()->first();
		if($cita->codestado == 'FIN'){
			return Response::json(array('msgErr'=>'La Consulta ya está cerrada no se pueden realizar modificaiones'));
		}
		if(sizeof($det) > 0){
			$det->lineaConsulta = Input::get('lineaConsulta');
			$det->diagnostico = Input::get('diagnostico');
			$det->tratamiento = Input::get('tratamiento');
			$det->save();
			
			
			if(Input::get('accion') == "GF" || Input::get('accion') == "GFIMP"){// (GF -> Guardar Finalizar)
				
				$det->fecfincita  = Carbon::now('Europe/Madrid')->format('Y/m/d H:i:s');
				$det->save();
				$this->modificarEstadoCita();
				return Response::json(array('msgOk'=>'Cita Finalizada'));
			}
		}
		return Response::json(array('msgOk'=>'Los Cambios Se han Guardado correctamente'));
	}
	
	public function guardarAlergia(){
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		
		$usu = Usuario:: where('emailusr', '=',  Session::get('usuario.email')[0])
		->where('fecbajadmin', '>' , Carbon::today()->toDateString())->get()->first();
		
		if(sizeof($usu) > 0){
			$alergia = new Alergia();
			$alergia->alergia = Input::get('alergia');
			$alergia->idecpac =  hash ( "md5" , Input::get('idecpac'));
			$alergia->idemedcrea = $usu->id;
			$alergia->feccrea = Carbon::now('Europe/Madrid')->format('Y/m/d H:i:s');
			$alergia->fecbaj =  $defaulfeccha->format('Y/m/d');
			$alergia->save();
		}else{
			return Response::json(array('msgErr'=>'No hay usuario en sesesión'));
		}
		$listAlergias = $this->obtenerAlergiasUsrActivas(Input::get('idecpac'), false);
		
		return Response::json(array('msgOk'=>'Alergia Creada', 'listAlergias'=>$listAlergias));
	}
	
	public function eliminarAlergia(){
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
	
		$alergia = Alergia:: where('id', '=', Input::get('id'))->get()->first();
	
		if(sizeof($alergia) > 0){
			$alergia->fecbaj = Carbon::now('Europe/Madrid')->format('Y/m/d');
			$alergia->save();
		}
		
		$listAlergias = $this->obtenerAlergiasUsrActivas($alergia->idecpac, true);
	
		return Response::json(array('msgOk'=>'Alergia Eliminada Correctamente', 'listAlergias'=>$listAlergias));
	}
	
	public function prepararVentanaAlergia(){
		$listAlergias = $this->obtenerAlergiasUsrActivas(Input::get('idecpac'), false);
		//$listAlergiasEsp = $this->obtenerPrametrosInfo('ALERG_ESPECIFICA');
		
		return Response::json(array('listAlergias'=>$listAlergias/*, 'listAlergiasEsp' => $listAlergiasEsp */));
	}
	
	
	public function saveconfigAlergia(){
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		$listAlergias = $this->obtenerAlergiasEspUsrActivas(Input::get('idecpac'));
		
		$usu = Usuario:: where('emailusr', '=',  Session::get('usuario.email')[0])
		->where('fecbajadmin', '>' , Carbon::today()->toDateString())->get()->first();
		
		if(sizeof($usu) > 0){
			if(sizeof($listAlergias) == 0 && sizeof(Input::get('alergiasSelect')) > 0 ){
				foreach(Input::get('alergiasSelect') as $t){
					$alergia = new Alergia();
					$alergia->codalergia = $t;
					$alergia->alergia = $this->obtenerDesParam('ALERG_ESPECIFICA', $t);
					$alergia->idecpac = hash ( "md5" , Input::get('idecpac'));
					$alergia->idemedcrea = $usu->id;
					$alergia->feccrea = Carbon::now('Europe/Madrid')->format('Y/m/d H:i:s');
					$alergia->fecbaj =  $defaulfeccha->format('Y/m/d');
					$alergia->save();
				}
			}
			if(sizeof($listAlergias) > 0 && sizeof(Input::get('alergiasSelect')) > 0 ){
				//Insertamos los que no estan ya dados de alta
				
				foreach(Input::get('alergiasSelect') as $t){
					$encontrado = false;
					
					foreach($listAlergias as $x){
						if($x->codalergia == $t){
							$encontrado = true;
							break;
						}
					}
					if(!$encontrado){
						$alergia = new Alergia();
						$alergia->codalergia = $t;
						$alergia->alergia = $this->obtenerDesParam('ALERG_ESPECIFICA', $t);
						$alergia->idecpac = hash ( "md5" , Input::get('idecpac'));
						$alergia->idemedcrea = $usu->id;
						$alergia->feccrea = Carbon::now('Europe/Madrid')->format('Y/m/d H:i:s');
						$alergia->fecbaj =  $defaulfeccha->format('Y/m/d');
						$alergia->save();
					}
				}
				
				foreach($listAlergias as $x){
					$encontrado = false;
						
					foreach(Input::get('alergiasSelect') as $t){
						if($x->codalergia == $t){
							$encontrado = true;
							break;
						}
					}
					if(!$encontrado){
						$x->fecbaj = Carbon::now('Europe/Madrid')->format('Y/m/d');
						$x->idemedbaj = $usu->id;
						$x->save();
					}
				}
				
				//Damos de baja los que no esten en la nueva configuracion
				
			}
			if(sizeof($listAlergias) > 0 && sizeof(Input::get('alergiasSelect')) == 0 ){
				foreach($listAlergias as $x){
					$x->fecbaj = Carbon::now('Europe/Madrid')->format('Y/m/d');
					$x->idemedbaj = $usu->id;
					$x->save();
				}
			}
		}
		$listAlergiasAct = $this->obtenerAlergiasUsrActivas(Input::get('idecpac'), false);
		return Response::json(array('msgOk'=>'Alergia Configuradas Correctamente', 'listAlergias'=>$listAlergiasAct));
	}
	
	
	function guardarAntecedente(){
		
		$ant = new Antecedente();
		$ant->codant = Input::get('codant');
		$ant->desant = Input::get('desant');
		$ant->obsant = Input::get('obsant');
		$ant->idpac = Input::get('idpac');
		$ant->idcita = Input::get('idcita');
		
		$ant->save();
		 
		return Response::json(array('msgOk'=>'Se guardado Correctamente'));
	}
	
	public function obtenerAntecedentes($idPac, $codAnt,  $swiEncryp){
		$idPaciente = $idPac;
		if(!$swiEncryp)
			$idPaciente = hash ( "md5" , $idPac);
		
		
		$ant = Antecedente::where('codant', $codAnt)
		->where('idpac', '=', $idPaciente)
		->whereNull('fechabaja')
		->orderBy('id', 'desc')->get();
		
		return $ant;
	}
	
	function prepararVentanaAntecedentes(){
	
		$listAnt = $this->obtenerAntecedentes(Input::get('idpac'), 'ANT', true);
		$listHab = $this->obtenerAntecedentes(Input::get('idpac'), 'HAB', true);
		$listMor = $this->obtenerAntecedentes(Input::get('idpac'), 'MOR', true);
	
		return Response::json(array('listAnt'=>$listAnt, 'listHab'=>$listHab, 'listMor'=>$listMor));
	}
	
	function eliminarAntecedente(){
		$antecedente = Antecedente::where('id', Input::get('id'))->get()->first();
		$antecedente->fechabaja = Carbon::now('Europe/Madrid')->format('Y/m/d H:i:s');
		$antecedente->save();
		return Response::json(array('msgOk'=>'Se elimino correctamente'));
	}
	
	public function obtenerEspecialidadesCitasPac($idpac, $cita, $swiEncryp){
		
		
		$idPaciente = $idpac;
		
		if($swiEncryp == false)
			$idPaciente = hash ( "md5" , $idpac);

		$listaCodesp =  DB::table('citas')->where('idpac', '=', $idPaciente);
		
		if($cita){
			$listaCodesp = $listaCodesp->where('citas.id', '<>', $cita );
		}
		
		$listaCodesp = $listaCodesp->where('citas.codestado', '=', 'FIN' )
		->leftJoin('especialidades', 'citas.codesp', '=', 'especialidades.codesp')
		->select('citas.codesp',
				'especialidades.especialidad')
				->distinct()->get();
		
		return $listaCodesp;
	}
	
	public function obtenerEspecialidadesPac(){
		$listaCodesp = $this->obtenerEspecialidadesCitasPac(Input::get('idpac'), null, false); 
		
		return Response::json(array('listaCodesp' => $listaCodesp, 'prueba' => count(Input::get('idcita'))));
	}
	
	function prepararVentanaHistConsultas(){
		
		
		$listaCodesp = $this->obtenerEspecialidadesCitasPac(Input::get('idpac'), Input::get('idcita'), true); 
		
		return Response::json(array('listaCodesp' => $listaCodesp, 'prueba' => count(Input::get('idcita'))));
	}
	
	function verHistoricoCitas(){
	
	
		$listaCitas = DB::table('citas')->where('idpac', '=', Input::get('idpac'))
		->where('citas.id', '<>', Input::get('idcita') )
		->where('citas.codestado', '=', 'FIN' )
		->where('citas.codesp', '=', Input::get('codesp') )
		->leftJoin('detalles_cita', 'detalles_cita.idecita', '=', 'citas.id')
		->leftJoin('usuarios', 'usuarios.id', '=', 'citas.idusr')
		->leftJoin('especialidades', 'citas.codesp', '=', 'especialidades.codesp')
		->select('citas.*',
				'usuarios.nomusr',
				'usuarios.apusr',
				'detalles_cita.fecinicita',
				'detalles_cita.tratamiento',
				DB::raw('(CASE WHEN citas.idusr = ' . Input::get('idusr') . ' THEN detalles_cita.lineaConsulta ELSE "" END) AS lineaConsulta'),
				'detalles_cita.diagnostico',
				'especialidades.especialidad')->get();
				//->orderBy('test_usu.id', 'asc')->get();
	
	
		return Response::json(array('listaCitas'=> $listaCitas));
	}
	
	public  function guardarPruebaComplem(){
		
		$idPac =  Input::get('idpac');
		
		if(!Input::get('swiEncryp') || Input::get('swiEncryp') == 'false')
			$idPac = hash ( "md5" , Input::get('idpac'));
		
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		$prueba = new PruebaComplementaria();
		$prueba->idpac = $idPac;
		$prueba->tipprueba = Input::get('tipprueba');
		$prueba->tipo = Input::get('tipo');
		$prueba->archivo = Input::get('archivo');
		$prueba->observacion = Input::get('observacion');
		$prueba->fechabaja = $defaulfeccha->format('Y/m/d');
		$prueba->idvisita = Input::get('idvisita');
		$prueba->idact = Input::get('idactqui');
		
		$prueba->save();
	
		return Response::json(array('msgOk'=>'Prueba Creada' ));
	}
	

	public function prepararVentanaPrueCompl(){
		
		$idPac =  Input::get('idpac');
		
		if(!Input::get('swiEncryp') || Input::get('swiEncryp') == 'false')
			$idPac = hash ( "md5" , Input::get('idpac'));

		$listaPruebas =  DB::table('prueba_complemtaria')->where('fechabaja','>', Carbon::now('Europe/Madrid')->format('Y/m/d'))
		->where('prueba_complemtaria.idpac', '=', $idPac)
		->leftJoin('parametros_gesmeweb', 'parametros_gesmeweb.coddom', '=', 'prueba_complemtaria.tipprueba')
		->where('parametros_gesmeweb.tipo', '=', 'TIPO_PRUEBA')
		->select('prueba_complemtaria.*',
				'parametros_gesmeweb.desval');
		
		if(Input::get('idvisita')){
			$listaPruebas = $listaPruebas->join('visitas', 'visitas.id', '=', 'prueba_complemtaria.idvisita');
		}
		
		if(Input::get('idactqui')){
			$listaPruebas = $listaPruebas->where('idact', '=', Input::get('idactqui'));
		}
		
		$listaPruebas = $listaPruebas->get();
		
		$listTipoPrueba = $this->obtenerPrametrosInfo('TIPO_PRUEBA');
		
		$listaActQui = app('App\Http\Controllers\actQuiController')->obtenerListaActqui($idPac, null, TRUE);
		
		return Response::json(array('listaPruebas'=> $listaPruebas, 'listTipoPrueba' => $listTipoPrueba, 'listaActQui' => $listaActQui));
	}
	
	public function eliminarPruebaCompl(){
		
		$prueba = PruebaComplementaria:: where('id', '=', Input::get('id'))->get()->first();
	
		if(sizeof($prueba) > 0){
			$prueba->fechabaja = Carbon::now('Europe/Madrid')->format('Y/m/d');
			$prueba->save();
		}
			
		return Response::json(array('msgOk'=>'Prueba Complementaria eliminada'));
	}
	
	public function prepararVentanaObs(){
		$listaObs = Observacion::where('fechabaja', '>',  Carbon::now('Europe/Madrid')->format('Y/m/d H:i:s'))->get();
		return Response::json(array('listaObs'=> $listaObs));
	}
	
	public function guardarObs(){
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		$obs = new Observacion();
		$obs->idcita = Input::get('idcita');
		$obs->observacion = Input::get('observacion');
		$obs->fechabaja = $defaulfeccha->format('Y/m/d');
		
		$obs->save();
		
		return Response::json(array('msgOk'=>'Observación guardada'));
	}
	
	public function eliminarObservacion(){
		$obs = Observacion:: where('id', '=', Input::get('id'))->get()->first();
		
		if(sizeof($obs) > 0){
			$obs->fechabaja = Carbon::now('Europe/Madrid')->format('Y/m/d H:i:s');
			$obs->save();
		}
			
		return Response::json(array('msgOk'=>'Observación eliminada'));
	}
	
	public function obtenerCitasVacacionesUsr(){

		$listaCitVac = VacacionesUsuario::where('idusr', '=', Input::get('ideMed'))->get();
		
		$citasVacaciones = array();
		foreach($listaCitVac as $item){
			$citasVacacionesAux = Cita::where('idusr', '=', Input::get('ideMed'))
			->where('feccita', '>=',$item->fecini)
			->where('feccita', '<=', $item->fecfin)
			->where('fecbaja', '>' , Carbon::now('Europe/Madrid')->format('Y/m/d') )
			->join('pacientes', DB::raw(" MD5( pacientes.id)"), '=', 'citas.idpac')
			->leftJoin('especialidades', 'citas.codesp', '=', 'especialidades.codesp')
			->select('citas.*',
					'pacientes.ap1pac',
					'pacientes.ap2pac',
					'pacientes.nompac',
					'pacientes.numtel1',
					'pacientes.numtel2',
					'pacientes.tipdoc',
					'especialidad')
					->get();
		
						
					//	$newMediaProjects->add($filmProject);
					if($citasVacacionesAux && sizeof($citasVacacionesAux) > 0)
						array_push ($citasVacaciones, $citasVacacionesAux);
		}
		return Response::json(array('citasVacaciones'=>$citasVacaciones));
	}
	
	public function obtenerCitasByFechas(){
		
		$citas = Cita::where('idusr', '=', Input::get('ideMed'))
		->where('feccita', '>=', Input::get('fecIni'))
		->where('feccita', '<', Input::get('fecFin'))
		->where('fecbaja', '>' , Carbon::now('Europe/Madrid')->format('Y/m/d') )
		->where('hora', '>', '0')
		->select('feccita', DB::raw('count(*) as totalCita'), 'idempresa')
        ->groupBy('feccita', 'idempresa')
		->get();

		
		$listaVaciones = VacacionesUsuario::where(function($query)
		{
			$query->where('idusr', '=', Input::get('ideMed')) ->where('fecini', '>=', Input::get('fecIni'))
			->where('fecini', '<', Input::get('fecFin'));
		})
		->orWhere(function($query)
            {
                $query->where('idusr', '=', Input::get('ideMed'))->where('fecfin', '<=', Input::get('fecFin'))
                      ->where('fecfin', '>', Input::get('fecIni'));
            })
		->get();
            
		
		$listaCitVac = VacacionesUsuario::where('idusr', '=', Input::get('ideMed'))->get();
		
		$citasVac = false;
		foreach($listaVaciones as $item){
			$citasVacacionesAux = Cita::where('idusr', '=', Input::get('ideMed'))
			->where('feccita', '>=',$item->fecini)
			->where('feccita', '<=', $item->fecfin)
			->where('fecbaja', '>' , Carbon::now('Europe/Madrid')->format('Y/m/d') )->get();
				
			
			//	$newMediaProjects->add($filmProject);
			if($citasVacacionesAux && sizeof($citasVacacionesAux) > 0){
				$citasVac = true;
				break;
			}
			
		}
		
		
		$listaFestivos = Festivos::where('fecha', '>=', Input::get('fecIni'))
			->where('fecha', '<', Input::get('fecFin'))->get();
		
		$listaSeguros = app('App\Http\Controllers\configAdmController')->obtenerSeguros();
		
		$horariosEsp = app('App\Http\Controllers\agendaController')->obtenerHoriosEspeciales(Input::get('ideMed'), Input::get('fecIni') , Input::get('fecFin'));
		//return Response::json(array('horariosEsp' =>$horariosEsp, 'ini' => Carbon::createFromFormat('Y/m/d',Input::get('fecIni'))->format('Y-m-d'), 'fin'=> Carbon::createFromFormat('Y/m/d',Input::get('fecFin'))->format('Y-m-d'), 'usr'=> Input::get('ideMed') ));
		return Response::json(array('totCitas'=>$citas, 'listaVaciones' => $listaVaciones, 'citasVac'=>$citasVac, 'listaFestivos' => $listaFestivos, 'listaSeguros' => $listaSeguros, 'horariosEsp' =>$horariosEsp));
	}
	
	public function crearConsultaSinCita(){
		//privisionalmente se creara con la primera especialiadd del usuario.
		$espeUsu = EspecialidadUsuario::where('ideusr', '=', Input::get('idmed'))->get()->first();
		$pacActual = Paciente::where('id', '=', Input::get('idpac'))->get()->first();
		
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		$fecActual = new Carbon();
		$cita = new Cita();
		$cita->idusr = Input::get('idmed');
		$cita->idpac = hash ( "md5" , Input::get('idpac'));
		$cita->idsegactual = $pacActual->idseguro;
		$cita->numseg = $pacActual->numseg;
		$cita->hora = 0;
		$cita->feccita = $fecActual->format('Y/m/d');
		$cita->codestado = 'ABR';
		$cita->codesp = $espeUsu->codesp;
		$cita->durcon = 0;
		$cita->fecbaja = $defaulfeccha->format('Y/m/d');
		$cita->codusrcre = Input::get('codUsr');
		$cita->idempresa = Session::get('usuario.idempresa')[0];
		$cita->save();
		
		return Response::json(array('cita' => $cita));
	}
	
	public function obtCitasMedDiario(){
		$fecActual = new Carbon();
		$data = new Documento(); 
		$fechaListado = null;
		if(Input::get('fechaCitaListado')){
			
			$newDate = date("Y-m-d", strtotime(Input::get('fechaCitaListado')));
			$fechaListado = Carbon::createFromFormat('Y-m-d', $newDate);
			
		}
		
		$listaUsusarios = DB::table('citas')
			->whereIn('citas.codestado', array('FIN','ABR', 'PLN', 'ESP') );
		
		if(Input::get('medListado')){
			$data->html = $data->html.'<div class="col-sm-12 print100"><h2>Listado Pacientes - Fecha: '.$fechaListado->format('d/m/Y').'</h2></div>';
			$listaUsusarios = $listaUsusarios->where('feccita', '=',$fechaListado->format('Y/m/d'))
			->where('citas.idusr', '=', Input::get('medListado'));
		}else{
			$listaUsusarios = $listaUsusarios->where('feccita', '=', $fecActual->format('Y/m/d'));
			$data->html = $data->html.'<div class="col-sm-12 print100"><h2>Listado Pacientes Diarios - Fecha: '.$fecActual->format('d/m/Y').'</h2></div>';
		}
			
		$listaUsusarios = $listaUsusarios->join('usuarios', 'usuarios.id', '=', 'citas.idusr')
		->select('usuarios.*')->distinct()
		->orderBy('usuarios.nomusr', 'desc')->get();
		
		
		
		foreach($listaUsusarios as $item){
			$data->html = $data->html."<div> <h3>MEDICO: ".  mb_strtoupper($item->nomusr). " ". mb_strtoupper($item->apusr)  ." </h3> </div> <hr>";
			$listaCit = DB::table('citas')
			->whereIn('citas.codestado', array('FIN','ABR', 'PLN', 'ESP') )
			->where('citas.idusr', '=', $item->id );
			if(Input::get('fechaCitaListado')){
				$listaCit = $listaCit->where('feccita', '=', $fechaListado->format('Y/m/d'));
			}else{
				$listaCit = $listaCit->where('feccita', '=', $fecActual->format('Y/m/d'));
			}
			$listaCit = $listaCit->where('citas.durcon', '>=', 0)
			->orderBy('citas.hora', 'asc')
			->distinct()
			->get();
			$cont = 1;
			
			foreach($listaCit as $cita){
				
				$paciente = Paciente::where( DB::raw(" MD5( pacientes.id)"), '=', $cita->idpac)->get()->first();
				$nombreSeguro = "";
				$numeroSeguro = $paciente->numseg;
				$idSeguroIt = $paciente->idseguro;
					
				if($cita->idsegactual &&  strlen($cita->idsegactual) > 0){
					$idSeguroIt = $cita->idsegactual;
					$numeroSeguro = $cita->numseg;
					
					if($idSeguroIt && strlen($idSeguroIt) > 0){
						$s = Seguro::where('id','=',$idSeguroIt)->get()->first();
						if($s)
							$nombreSeguro = $s-> nomseguro;
						if($paciente->idseguro && $paciente->idseguro!=$cita->idsegactual){
							$s = Seguro::where('id','=',$paciente->idseguro)->get()->first();
							if($s)
								$nombreSeguro = $nombreSeguro.'- SEGURO ACTUAL -'.$s-> nomseguro;
						}
					}
				}elseif($idSeguroIt && strlen($idSeguroIt) > 0){
					$s = Seguro::where('id','=',$idSeguroIt)->get()->first();
						if($s)
							$nombreSeguro = 'SEGURO ACTUAL - '.$s-> nomseguro;
				}
					
				
				
				$hours = floor($cita->hora/60) ;
				$minutes =  $cita->hora % 60;
				if($hours < 10)
					$hours = '0'.strval($hours);
				
				if($minutes < 10)
					$minutes = '0'.strval($minutes);
				
				$strTl = '';
				if($paciente->numtel1)
					$strTl = $paciente->numtel1;
				if($paciente->numtel2){
					if($paciente->numtel1)
						$strTl = $strTl. ' - ' .$paciente->numtel2;
					else{
						$strTl = $paciente->numtel2;
					}
				}
					
				
				$data->html = $data->html."<div> <span style='margin-top: 8px; text-align: justify; font-size: 13px;'>".$cont. '- Hora: '
										. $hours .':'. $minutes . ' '
										 . mb_strtoupper($paciente->nompac).' ' . mb_strtoupper($paciente->ap1pac). ' ' . mb_strtoupper($paciente->ap2pac). '   TLF: '. $strTl; 
				if($cita->durcon == 0)
					$data->html = $data->html. ' (CITA MANUAL)';
				if($nombreSeguro && strlen($nombreSeguro) > 0)
					$data->html = $data->html. '<br>'. $nombreSeguro. ' : '. mb_strtoupper($numeroSeguro);
				$data->html = $data->html." </span> </div> <hr>";
				$cont = $cont + 1;
			}
			
		}
		$data->empresa = Session::get('usuario.idempresa')[0];
		
		$nombreCompletoUsr = null;
		$view =  View::make('PDF.docs', compact('data', 'nombreCompletoUsr'))->render();
		$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML($view);
		return $pdf->stream('documento.pdf');
		
	}
	
	public function obteneruUsuCitasFINABRPLN($fecha){
		$fechaListado = null;
		if($fecha){
			$fechaListado = Carbon::createFromFormat('Y-m-d', $fecha);
		}
		$listaUsusarios = DB::table('citas')
		->whereIn('citas.codestado', array('FIN','ABR', 'PLN', 'ESP') )
		->where('citas.feccita','=',$fechaListado->format('Y/m/d') );
			
		$listaUsusarios = $listaUsusarios->join('usuarios', 'usuarios.id', '=', 'citas.idusr')
		->select('usuarios.*')->distinct()
		->orderBy('usuarios.nomusr', 'asc')->get();
		
		return $listaUsusarios;
	}
	
	public function obtenerUsuariosConCita(){
 
		$listaUsusarios = $this->obteneruUsuCitasFINABRPLN(Input::get('fechaCitaListado'));
		
		return Response::json(array('listaUsusarios' => $listaUsusarios));
	}
	
	public function imprimirListadoUsr(){
		$fechaListado = Carbon::createFromFormat('Y-m-d', Input::get('fechaCitaListado'));
		$data = new Documento();
		
		$listaUsusarios = $this->obteneruUsuCitasFINABRPLN(Input::get('fechaCitaListado'));
		
		
		$data->html = $data->html.'<div class="col-sm-12 print100"><h2>Listado médicos con cita - Fecha: '.$fechaListado->format('d/m/Y').'</h2></div>';
		$cont=1;
		foreach($listaUsusarios as $item){
			$data->html = $data->html."<div>  <strong>".$cont." - </strong>".  mb_strtoupper($item->nomusr). " ". mb_strtoupper($item->apusr)  ."  </div> <hr>";
			$cont = $cont+1;
		}
		$data->empresa = Session::get('usuario.idempresa')[0];
		
		$nombreCompletoUsr = null;
		$view =  View::make('PDF.docs', compact('data', 'nombreCompletoUsr'))->render();
		$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML($view);
		return $pdf->stream('documento.pdf');
	}
	

	
	public function obtenerUltimasCitasPac(){
		$listaCitas = DB::table('citas')->where('idpac', '=',  hash ( "md5" ,Input::get('idpac')))
		->where('citas.codestado', '<>', 'CAN' )
		->leftJoin('detalles_cita', 'detalles_cita.idecita', '=', 'citas.id')
		->leftJoin('especialidades', 'citas.codesp', '=', 'especialidades.codesp')
		->leftJoin('parametros_gesmeweb', 'parametros_gesmeweb.coddom', '=', 'citas.codestado')
		->where('parametros_gesmeweb.tipo' , '=', 'ESTADO_CITA')
		->select('citas.*',
				'detalles_cita.fecinicita',
				'detalles_cita.fecfincita',
				'parametros_gesmeweb.desval',
				'especialidades.especialidad')->orderBy('citas.feccita', 'desc')->skip(0)->take(15)->get();
		
		$listaVisitas = app('App\Http\Controllers\visitaController')->obtenerUltimasVisitasPac(Input::get('idpac'));
		
				return Response::json(array('listaCitas'=> $listaCitas, 'listaVisitas'=> $listaVisitas));
	}
	
	public function modificarMsgCita(){
		
		$citas = Cita::where('id', '=', Input::get('id'))->get()->first();
		$citas->obscita = Input::get('obscita');
		$citas->save();
		
	
		return Response::json(array('msgOk'=> 'Comentario modificado correctamente.'));
	}
	
	public function initPantallaCitasFinHoy(){
	
		$listaCitas = Cita::where('feccita', '=', Carbon::now('Europe/Madrid')->format('Y/m/d'))
		->where('codestado', '=', 'FIN')		
		->get();
		
		foreach($listaCitas as $item){
			$esp = Especialidad::where('codesp', '=', $item->codesp)->get()->first();
			$item->especialidad = $esp->especialidad;
			$esp = Usuario::where('id', '=', $item->idusr)->get()->first();
			$item->nomusr = $esp->nomusr;
			$item->apusr = $esp->apusr;
			$esp = Paciente::where( DB::raw(" MD5( pacientes.id)"), '=', $item->idpac)->get()->first();
			$item->ap1pac = $esp->ap1pac;
			$item->ap2pac = $esp->ap2pac;
			$item->nompac = $esp->nompac;
		}
		
	
		return Response::json(array('listaCitas'=>$listaCitas));
	}
	
	
	function verHistoricoCitasPaginado(){
		
		$incremento = 5;
		$pagSelect = Input::get('numPag') - 1;
		$totSkip = $incremento * $pagSelect;
		
		$total = 0;
		
		$total = DB::table('citas')->where('idpac', '=', Input::get('idpac'))
		->where('citas.id', '<>', Input::get('idcita') )
		->where('citas.codestado', '=', 'FIN' )
		->where('citas.codesp', '=', Input::get('codesp') )
		->leftJoin('detalles_cita', 'detalles_cita.idecita', '=', 'citas.id')
		->leftJoin('usuarios', 'usuarios.id', '=', 'citas.idusr')
		->leftJoin('especialidades', 'citas.codesp', '=', 'especialidades.codesp')
		->select('citas.*',
				'usuarios.nomusr',
				'usuarios.apusr',
				'detalles_cita.fecinicita',
				'detalles_cita.tratamiento',
				DB::raw('(CASE WHEN citas.idusr = ' . Input::get('idusr') . ' THEN detalles_cita.lineaConsulta ELSE "" END) AS lineaConsulta'),
				'detalles_cita.diagnostico',
				'especialidades.especialidad')->count();
	
		$listaCitas = DB::table('citas')->where('idpac', '=', Input::get('idpac'))
		->where('citas.id', '<>', Input::get('idcita') )
		->where('citas.codestado', '=', 'FIN' )
		->where('citas.codesp', '=', Input::get('codesp') )
		->leftJoin('detalles_cita', 'detalles_cita.idecita', '=', 'citas.id')
		->leftJoin('usuarios', 'usuarios.id', '=', 'citas.idusr')
		->leftJoin('especialidades', 'citas.codesp', '=', 'especialidades.codesp')
		->select('citas.*',
				'usuarios.nomusr',
				'usuarios.apusr',
				'detalles_cita.fecinicita',
				'detalles_cita.tratamiento',
				DB::raw('(CASE WHEN citas.idusr = ' . Input::get('idusr') . ' THEN detalles_cita.lineaConsulta ELSE "" END) AS lineaConsulta'),
				'detalles_cita.diagnostico',
				'especialidades.especialidad')
				->orderBy('detalles_cita.fecinicita', 'desc')->skip($totSkip)->take($incremento)->get();
	
	
				return Response::json(array('listaCitas'=> $listaCitas,'total'=>$total, 'paginador' => $incremento));
	}
	
	function reportarCitaOldById(){
		
		$cita = Cita::where('citas.id', '=', Input::get('idcita'))->get()->first();
		$cita->swireportada = 'S';
		$cita->save();
		
		return Response::json(array('msgOk'=> 'OK'));
	}
	
}
