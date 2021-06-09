<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Response;
use Input;

use App\PlantillaUsuario;


class plantillaController extends Controller {
	
	public function obtenerPlantillasUsuario($id){
		
		$listadoPlatillas = PlantillaUsuario::where('idusr', '=', $id)
		->where('fechabaja', '>', Carbon::now('Europe/Madrid')->format('Y/m/d'))->orderBy('tituloplantilla', 'asc')->get();
		
		return $listadoPlatillas;
		
	}
	
	public function validacionesPlantillas($id, $idusr, $tituloplantilla){
		
		$listadoPlatillas = PlantillaUsuario::where('idusr', '=', $idusr);
		if($id){
			$listadoPlatillas = $listadoPlatillas->where('id', '<>', $id);
		}
		$listadoPlatillas = $listadoPlatillas->where('tituloplantilla', '=', $tituloplantilla )
		->where('fechabaja', '>', Carbon::now('Europe/Madrid')->format('Y/m/d'))->orderBy('tituloplantilla', 'asc')->get();
		
		if(sizeof($listadoPlatillas) > 0){
			return 'Ya existe un una plantilla con este nombre.';
		}else{
			return null;
		}
		
	}
	
	
	public function insertarModificarNuevaPlantilla(){
		
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		
		$plantilla = null;
		
		$msgErr = $this->validacionesPlantillas(Input::get('id'), Input::get('idmed'), Input::get('tituloplantilla'));
		if($msgErr){
			return Response::json(array('msgErr' => $msgErr));
		}
		
		if(Input::get('id')){
			$plantilla = PlantillaUsuario::where('id', '=', Input::get('id'))->get()->first();
		}else{
			$plantilla = new PlantillaUsuario();
		}
		
		$plantilla->idusr = Input::get('idmed');
		$plantilla->tituloplantilla = Input::get('tituloplantilla');
		$plantilla->txtplantilla = Input::get('txtplantilla');
		$plantilla->fechabaja = $defaulfeccha->format('Y/m/d');
		
		$plantilla->save();
		
		$listadoPlatillas = $this->obtenerPlantillasUsuario(Input::get('idmed'));
		
		return Response::json(array('msgOk'=>'Se ha creado la plantilla correctamente.', 'listadoPlatillas' => $listadoPlatillas));
		
	}
	
	public function listadoPlantillasUsuario(){
		
		$listadoPlatillas = $this->obtenerPlantillasUsuario(Input::get('idmed'));
		
		return Response::json(array('listadoPlatillas' => $listadoPlatillas));
		
	}
	
	public function eliminarPlantillaUsuario(){
		
		$plantilla = PlantillaUsuario::where('id', '=', Input::get('id'))->get()->first();
		
		$plantilla->fechabaja = Carbon::now('Europe/Madrid')->format('Y/m/d');
		$plantilla->save();
		
		$listadoPlatillas = $this->obtenerPlantillasUsuario(Input::get('idmed'));
		
		return Response::json(array('listadoPlatillas' => $listadoPlatillas));
		
	}
	
}

?>