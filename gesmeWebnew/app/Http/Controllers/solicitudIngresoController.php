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

use App\SolicitudServicio;


class solicitudIngresoController extends Controller {
	
	public function tiposSolicitudesIngreso(){
		return app('App\Http\Controllers\citaController')->obtenerPrametrosInfo('TIPO_SOLICITUD');
	}
	
	public function obtenerSolicitudByid($id){
		return SolicitudServicio::where('id', '=', $id)->get()->first();
	}
	
	public function insertNuevaSolicitud(){
		
		$uSes = app('App\Http\Controllers\SessionController')->obtenerUsuarioSes();
		
		$solicitud = new SolicitudServicio();
		
		$solicitud->idingreso = Input::get('idingreso');
		$solicitud->codtipsol = Input::get('codtipsol');
		$solicitud->dessol = Input::get('dessol');
		$solicitud->idusrcre = $uSes->id;
		
		$solicitud->save();
		
		return Response::json(array('msgOk'=>'Solicitud guardada'));
	}
	
	public function obtenerSolicitudesIngreso(){
		$listaSolicitudes = SolicitudServicio::where("idingreso", "=", Input::get('idingreso'))
		->leftJoin('parametros_gesmeweb', 'parametros_gesmeweb.coddom', '=', 'solicitudes_ingreso.codtipsol')
		->where('solicitudes_ingreso.fecbaj', '>', Carbon::now('Europe/Madrid')->format('Y/m/d'))
		->where('parametros_gesmeweb.tipo', '=', 'TIPO_SOLICITUD')
		->select('solicitudes_ingreso.*',
				'parametros_gesmeweb.desval')
		->get();
		
		return Response::json(array('listaSolicitudes'=>$listaSolicitudes));
	}
	
	public function bajaSolicitudIngreso(){
		$solicitud = $this->obtenerSolicitudByid(Input::get('id'));
		if($solicitud){
			$solicitud->fecbaj = Carbon::now('Europe/Madrid')->format('Y/m/d');
			$solicitud->save();
			return Response::json(array('msgOk'=>'Se eliminó correctamente'));
		}
		return Response::json(array('msgErr'=>'No existe esta solicitud'));
		
	}
	
}
