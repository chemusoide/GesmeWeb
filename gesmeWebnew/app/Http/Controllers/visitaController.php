<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App;
use DB;
use Carbon\Carbon;
use Input;
use Response;
use Session;

use App\Paciente;
use App\Usuario;
use App\Visita;
use App\VacacionesUsuario;
use App\Festivos;

class visitaController extends Controller {
	
	public function guardarVisita(){
		$pac = null;
		$cita = null;
		$info = null;
		
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		$pac = Paciente::where('id', '=', Input::get('idpac'))->get()->first();
		$info = null;
			
		$listaCitas = Visita::
		where('idpac', '=', hash ( "md5" , Input::get('idpac')))
		->where('fecvisita', '=', Input::get('fecvisita'))
		->where('fecbaj', '>' , Carbon::today()->toDateString() )->get();
			
		if($listaCitas && sizeof($listaCitas) > 0)
			$info = 'Ya existe una cita para este día, revise si es correcto';
				
		$visita = new Visita();
		$visita->idusr = Input::get('idusr');
		$visita->idpac = hash ( "md5" , Input::get('idpac'));
		
		$visita->hora = Input::get('hora');
		$visita->fecvisita = Input::get('fecvisita');
		
		$visita->codestado = "PLN";
		if(Input::get('swiCitRap') && strlen(Input::get('swiCitRap')) > 0 && Input::get('swiCitRap') == "S"){
			$visita->codestado = "ESP";
		}
		
		$arrEsp = array("RES", "INY", "SCU", "QUI", "ECO", "AEC", "API", "AAC", "APP", "RAX");
		if (in_array(Input::get('codesp'), $arrEsp)) {
			$visita->codestado = "ATC"; //ATC ->Auto cierre
			$visita->obs = Input::get('obs').' - CIERRE AUTOMATICO POR SER '.Input::get('codesp');
			$visita->fecfin = Carbon::now('Europe/Madrid');
		}else{
			$visita->obs = Input::get('obs');
		}

		$visita->idsegactual = $pac->idseguro;
		$visita->numseg = $pac->numseg;
		
		$visita->codesp = Input::get('codesp');
		if(Input::get('idempresa')){
			$visita->idempresa = Input::get('idempresa');
		}else{
			$visita->idempresa = Session::get('usuario.idempresa')[0];
		}
		$visita->fecbaj = $defaulfeccha->format('Y/m/d');
		$visita->save();
					
		
	
		$nombreCompleto = $pac->nompac.' '. $pac->ap1pac.' '. $pac->ap2pac;
		return Response::json(array('nombreCompleto'=>$nombreCompleto, 'feccita' => $visita->fecvisita, 'hora' => $visita->hora, 'info' => $info ));
	
	}
	
	public function obtenerVisitasByFechas(){
		
		$citas = Visita::where('idusr', '=', Input::get('ideMed'))
		->where('fecvisita', '>=', Input::get('fecIni'))
		->where('fecvisita', '<', Input::get('fecFin'))
		->where('fecbaj', '>' , Carbon::now('Europe/Madrid')->format('Y/m/d') )
		->where('hora', '>', '0')
		->select('fecvisita', DB::raw('count(*) as totalCita'))
        ->groupBy('fecvisita')
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
			$citasVacacionesAux = Visita::where('idusr', '=', Input::get('ideMed'))
			->where('fecvisita', '>=',$item->fecini)
			->where('fecvisita', '<=', $item->fecfin)
			->where('fecbaj', '>' , Carbon::now('Europe/Madrid')->format('Y/m/d') )->get();
				
			
			//	$newMediaProjects->add($filmProject);
			if($citasVacacionesAux && sizeof($citasVacacionesAux) > 0){
				$citasVac = true;
				break;
			}
			
		}
		
		
		$listaFestivos = Festivos::where('fecha', '>=', Input::get('fecIni'))
			->where('fecha', '<', Input::get('fecFin'))->get();
		
		$listaSeguros = app('App\Http\Controllers\configAdmController')->obtenerSeguros();
				
		return Response::json(array('totCitas'=>$citas, 'listaVaciones' => $listaVaciones, 'citasVac'=>$citasVac, 'listaFestivos' => $listaFestivos, 'listaSeguros' => $listaSeguros));
	}
	
	public function modificarEstadoVisita(){
		$visita = Visita::where('id', '=', Input::get('id'))->get()->first();
		//return $visita;	
		$visita->codestado = Input::get('codestado');
		if(Input::get('codestado') == "CAN")
			$visita->fecbaj =  Carbon::now('Europe/Madrid')->format('Y/m/d');
		
		$visita->save();
	
		return Response::json(array('msgOk'=>'Visita Modificada'));
	}
	
	public function obtenerVisitasUsr(){
		$listaVisitas = DB::table('visitas')->where('idusr', '=', Input::get('idusr'))
		->where('visitas.fecbaj', '>',  Carbon::now('Europe/Madrid')->format('Y/m/d'))
		->where('visitas.fecvisita', '=', Carbon::now('Europe/Madrid')->format('Y/m/d'))
		->leftJoin('pacientes', DB::raw(" MD5( pacientes.id)"), '=', 'visitas.idpac')
		->leftJoin('parametros_gesmeweb', 'parametros_gesmeweb.coddom', '=',  'visitas.codestado')
		->leftJoin('aseguradoras', 'aseguradoras.id', '=', 'pacientes.idseguro')
		->where('tipo','=', 'ESTADO_CITA')
		->select('visitas.*',
				'parametros_gesmeweb.desval',
				'pacientes.nompac',
				'pacientes.ap1pac',
				'pacientes.ap2pac',
				'pacientes.fecnacpac',
				'aseguradoras.nomseguro')
				->get();
		
		return Response::json(array('listaVisitas'=>$listaVisitas));
		
	}
	
	public function finalizarVisita() {
		$visita = Visita::where('id', '=', Input::get('id'))->get()->first();
		$visita->codestado = 'FIN';
		$visita->obs = Input::get('obs');
		$visita->fecfin = Carbon::now('Europe/Madrid');
		$visita->save();
		return Response::json(array('msgOk'=>'Visita Finalizada'));
	}
	
	public function obtenerVisitaByid($id){
		return Visita::where('id', '=', $id)->get()->first();
	}
	
	public function obtenerUltimasVisitasPac( $idPacParam){
		$listaVisitas = DB::table('visitas')->where('idpac', '=',  hash ( "md5" ,$idPacParam))
		->where('visitas.codestado', '<>', 'CAN' )
		->leftJoin('roles', 'visitas.codesp', '=', 'roles.codrol')
		->leftJoin('parametros_gesmeweb', 'parametros_gesmeweb.coddom', '=', 'visitas.codestado')
		->where('parametros_gesmeweb.tipo' , '=', 'ESTADO_CITA')
		->select('visitas.*',
				'parametros_gesmeweb.desval',
				'roles.nomrol')->orderBy('visitas.fecvisita', 'desc')->skip(0)->take(15)->get();
		
		
				return $listaVisitas;
	}
	
}
