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

use App\Habitacion;


class habitacionController extends Controller {
	
	public function obtenerHabitacionById($id){
		return Habitacion::where('id', '=', $id)->get()->first();
	}
	
	public function obtenerhabitacionesDisponibles(){
		$listaHabitaciones = Habitacion::
		where('idempresa', '=', Session::get('usuario.idempresa')[0])
		->where('swiocupada', '=', 'N')->get();
		
		return Response::json(array('listaHabitaciones'=>$listaHabitaciones));
	}
	
	public function actualizarEstadoHab($id, $swiocupada, $idPac){
		
		$hab = $this->obtenerHabitacionById($id);
		
		if($hab){
			$hab->swiocupada = $swiocupada;
			$hab->idpac = $idPac;
			$hab->save();
		}
		
		return $hab;
		
	}
	
	public function liberaHab(){
		$hab = $this->actualizarEstadoHab(Input::get('idhab'), 'N', '');
		$ingreso = $hab = $uSes = app('App\Http\Controllers\ingresoController')->obtenerIngresoById(Input::get('id'));
		$ingreso->idhab = 0; 
		$ingreso->save();
		
		return Response::json(array('msgOk'=>'Habitación liberada', 'habitacion' => $hab));
	}
	
}
