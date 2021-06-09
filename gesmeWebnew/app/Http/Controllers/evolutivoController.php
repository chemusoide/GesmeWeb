<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Response;
use Carbon\Carbon;
use Input;

use App;

use App\Evolutivo;


class evolutivoController extends Controller {
	
	public function obtenerEvolutivoByid($id){
		return Evolutivo::where('id', '=', $id)->get()->first();
	}
	
	public function insertNuevoEvolutivo(){
		
		$uSes = app('App\Http\Controllers\SessionController')->obtenerUsuarioSes();
		
		$solicitud = new Evolutivo();
		
		$solicitud->idingreso = Input::get('idingreso');
		$solicitud->tipevol = Input::get('tipevol');
		$solicitud->desevol = Input::get('desevol');
		$solicitud->idusrcre = $uSes->id;
		
		$solicitud->save();
		
		return Response::json(array('msgOk'=>'Evolutivo guardado'));
	}
	
	public function obtenerEvolutivosIngreso(){
		$listaEvoutivos = Evolutivo::where("idingreso", "=", Input::get('idingreso'))
		->join('usuarios', 'usuarios.id', '=',  'evolutivos_ingreso.idusrcre')
		->where('evolutivos_ingreso.fecbaj', '>', Carbon::now('Europe/Madrid')->format('Y/m/d'))
		->where('tipevol', '=', Input::get('tipevol'))
		->select('evolutivos_ingreso.*',
				'usuarios.nomusr',
				'usuarios.apusr')
		->get();
		
		return Response::json(array('listaEvoutivos'=>$listaEvoutivos));
	}
	
	public function bajaEvolutivoIngreso(){
		$evo = $this->obtenerEvolutivoByid(Input::get('id'));
		if($evo){
			$evo->fecbaj = Carbon::now('Europe/Madrid')->format('Y/m/d');
			$evo->save();
			return Response::json(array('msgOk'=>'Se eliminó correctamente'));
		}
		return Response::json(array('msgErr'=>'No existe esta solicitud'));
		
	}
	
}
