<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use App;
use View;
use Input;
use Response;

use App\Seguro;
use App\Paciente;
use App\CieDiagnostico;
use App\CieProcedimiento;
use Carbon\Carbon;
use App\Especialidad;
use App\Festivos;
use App\EspecialidadUsuario;


class configAdmController extends Controller {
	
	public function initConfiguraciones(){
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		return View::make('pages.private/configAdm', array('version' => $v->desval));
	}
	
	
	public function obtenerEspecialidades(){
		$esp = Especialidad::all();
		return Response::json(array('listEspec'=> $esp));
	}
	
	public function guardarEspecialidad(){
		if(Input::get('id')){
			$esp = Especialidad::where('id', '=', Input::get('id') )->get()->first();
			$esp->especialidad =  Input::get('especialidad');
			$esp-> save();
		}else{
			
			
			$existeEsp = Especialidad::where('especialidad','=',Input::get('especialidad'))->get()->first();
			
			if($existeEsp && sizeof($existeEsp) > 0)
				return Response::json(array('msgErr'=> 'Ya existe una especialidad con el mismo nombre'));
			
			$maxesp = Especialidad::whereRaw('id = (select max(`id`) from especialidades)')->get()->first();
			$esp = new Especialidad();
			$esp->especialidad =  Input::get('especialidad');
			$esp->codesp = 'GEN'.($maxesp->id+1);
			$esp->save();
		}
		
		
		
		return Response::json(array('msgOk'=> 'Especialidad guardada correctamente'));
	}
	
	public function eliminarEspecialidad(){
		$espeUsu = EspecialidadUsuario::where('codesp', '=', Input::get('codesp'))->get()->first();
		if($espeUsu)
			return Response::json(array('msgErr'=> 'Esta especialiada tiene usuarios asignados, no se puede eliminar'));
		
		$especialidad = Especialidad::find(Input::get('id'));
		$especialidad->delete();
		
		return Response::json(array('msgOk'=> 'Especialidad Eliminada'));
	}
	
	
	//SEGUROS
	
	public function obtenerSeguros(){
		return Seguro::where('activo', '=', '1')->orderBy('nomseguro')->get();
	}
	
	public function obtenerSegurosAdm(){
		$lisSeguros = $this->obtenerSeguros();
		return Response::json(array('lisSeguros'=> $lisSeguros));
	}
	
	public function guardarSeguro(){
		if(Input::get('id')){
			$seg = Seguro::where('id', '=', Input::get('id') )->get()->first();
			$seg->nomseguro =  Input::get('nomseguro');
			$seg-> save();
		}else{
				
				
			$existeSeg = Seguro::where('nomseguro','=',Input::get('nomseguro'))->get()->first();
				
			if($existeSeg && sizeof($existeSeg) > 0)
				return Response::json(array('msgErr'=> 'Ya existe un seguro con el mismo nombre'));
					
				$seg = new Seguro();
				$seg->nomseguro =  Input::get('nomseguro');
				$seg->save();
		}
	
	
	
		return Response::json(array('msgOk'=> 'Seguro guardado correctamente'));
	}
	
	public function eliminarSeguro(){
		
		$listaPaciente = Paciente::where('idseguro', '=', Input::get('id'))->get();
		foreach($listaPaciente as $p){
			$p->idseguro = '';
			$p->numseg = '';
			$p->save();
		}
		$seg = Seguro::where('id', '=', Input::get('id') )->get()->first();
		$seg->activo = 2;
		$seg-> save();
		
		return Response::json(array('msgOk'=> 'Seguro eliminado correctamente'));
	}
	
	public function obtenerFestivosAnual(){
		$desde = Carbon::create(Input::get('anyoFest'), 01, 01, 0, 0, 0);
		$hasta = Carbon::create(Input::get('anyoFest'), 12, 31, 0, 0, 0);
		$listFestivos = Festivos::where('fecha', '>=', $desde)
		->where('fecha', '<=', $hasta)->get();
		return Response::json(array('listFestivos'=> $listFestivos));
	}
	
	public function eliminarFestivo(){
		
		$festivo = Festivos::where('id', '=',  Input::get('id'))->first();
		$festivo->delete();
		
		return Response::json(array('msgOk'=> 'Se ha eliminado Correctamente'));
	}
	
	public function guardarFestivo(){
		$festivo = new Festivos();
		$festivo->fecha = Input::get('fecha');
		$festivo->save();
		
		return Response::json(array('msgOk'=> 'Festivo creado'));
		
	}
	
	public function buscarCieByClaseDesc(){
		$listaCie = null;
		
		if(Input::get('clase'))
			$listaCie = CieDiagnostico::where('clase', 'like', Input::get('clase').'%' );
		
		if(Input::get('descie') && strlen ( Input::get('descie') ) > 3){
			if(Input::get('clase'))
				$listaCie = $listaCie->where( DB::raw(" UPPER( descie)"), 'like', '%'.strtoupper(Input::get('descie')).'%' );
			else
				$listaCie = CieDiagnostico::where(DB::raw(" UPPER( descie)"), 'like', '%'.strtoupper(Input::get('descie')).'%' );
		}
			
		
		$listaCie = $listaCie->get();
		return Response::json(array('listaCie'=> $listaCie));
		
	}
	
	public function guardarCie(){
	
		$cieBBD = CieDiagnostico::where('clase', '=', Input::get('clase') )->get()->first();
		
		if($cieBBD){
			return Response::json(array('msgErr'=> 'Error, el cie ya existe'));
		}
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		$newCie = new CieDiagnostico();
		$newCie->clase = Input::get('clase');
		$newCie->descie = Input::get('descie');
		$newCie->fecbaj = $defaulfeccha->format('Y/m/d');
		$newCie->save();
		return Response::json(array('msgOk'=> 'Se añadió correctamente', 'cieBBD'=> $cieBBD, 'newCie'=> $newCie));
	
	}
	
	public function buscarCieProByCodDesc(){
		$listaCiePro = null;
		
		if(Input::get('codigo'))
			$listaCiePro = CieProcedimiento::where('codigo', 'like', Input::get('codigo').'%' );
		
		if(Input::get('desciepro') && strlen ( Input::get('desciepro') ) > 3){
			if(Input::get('codigo'))
				$listaCiePro = $listaCiePro->where( DB::raw(" UPPER( desciepro)"), 'like', '%'.strtoupper(Input::get('desciepro')).'%' );
				else
					$listaCiePro = CieDiagnostico::where(DB::raw(" UPPER( desciepro)"), 'like', '%'.strtoupper(Input::get('desciepro')).'%' );
		}
		
		$listaCiePro = $listaCiePro->get();
		return Response::json(array('listaCiePro'=> $listaCiePro));
	}
	
	
}
