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

use App\Ingreso;
use App\Paciente;
use App\Documento;
use App\RolUsuario;


class ingresoController extends Controller {
	
	public function opcionesDisponiblesByIdusr(){
		
		//solicitudes, evolutivos, medicacion, medicaPautada, alta;
		
		$usu =  app('App\Http\Controllers\SessionController')->obtenerUsuarioSes();
		
		$rolusr = RolUsuario::where('ideusr','=', $usu->id)->get();
		
		$arrReturn = (object) [
					'solicitudes' => 'N',
					'evolutivos' => 'N',
					'medicacion' => 'N',
					'medicaPautada' => 'N',
					'alta' => 'N'
			];
		
		foreach($rolusr as $r){
			
			if($r->codrol == 'ADMIN' || $r->codrol == 'MED'){
				$arrReturn->solicitudes = 'S';
				$arrReturn->evolutivos = 'S';
				$arrReturn->medicacion = 'S';
				$arrReturn->medicaPautada = 'S';
				$arrReturn->alta = 'S';
			}
			
			if($r->codrol == 'DUE' ){
				$arrReturn->solicitudes = 'S';
				$arrReturn->medicaPautada = 'S';
			}
			
			if($r->codrol == 'REC' ){
				$arrReturn->solicitudes = 'S';
			}
			
		}
		
		return $arrReturn;
	}
	
	public function paginaGeneral(){
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		$listaMedicos = app('App\Http\Controllers\usuariosController')->listaMedicosEspecialidad();
		$listaSeguros = app('App\Http\Controllers\configAdmController')->obtenerSeguros();
		$tiposSolicitud = app('App\Http\Controllers\solicitudIngresoController')->tiposSolicitudesIngreso('TIPO_SOLICITUD');
		
		return View::make('pages.private/tablonIngresos', array('version' => $v->desval, 'listaMedicos' => $listaMedicos, 'listaSeguros' => $listaSeguros, 'tiposSolicitud' => $tiposSolicitud));
	}
	
	public function obtenerIngresoById($id){
		return Ingreso::where('id', '=', $id)->get()->first();
	}
	
	public function obtenerIngresosByIdPac($idpac, $swiCod){
		if($swiCod == false)
			$idpac =  hash ( "md5" , $idpac);
		
		return Ingreso::where('idpac', '=', $idpac)
		->join('empresas', 'empresas.id', '=',  'ingresos.idempresa')
		->select('ingresos.desmed',
				'ingresos.desregpor',
				'ingresos.fecalta',
				'ingresos.fecingreso',
				'ingresos.id',
				'empresas.nombre')->get();
	}
	
	public function insertarIngreso(){
		
		$uSes = app('App\Http\Controllers\SessionController')->obtenerUsuarioSes();
		
		$ingreso = new Ingreso();
		
		$ingreso->idpac =  hash ( "md5" , Input::get('idpac'));
		$ingreso->redpor = Input::get('redpor');
		$ingreso->desregpor = Input::get('desregpor');
		$ingreso->idmed = Input::get('idmed');
		$ingreso->desmed = Input::get('desmed');
		$ingreso->idhab = Input::get('idhab');
		
		$ingreso->idempresa = Session::get('usuario.idempresa')[0];
		$ingreso->fecingreso = Carbon::now('Europe/Madrid');
		$ingreso->idusrcre = $uSes->id;
		
		$ingreso->save();
		
		$hab = $uSes = app('App\Http\Controllers\habitacionController')->actualizarEstadoHab($ingreso->idhab, 'S', $ingreso->idpac);
		
		return Response::json(array('ingreso' => $ingreso));
		
		
	}
	
	function obtenerIngresosActivos(){
		$litaIngresos = DB::table('ingresos')->whereNull('ingresos.fecalta')
		->where('ingresos.idempresa', '=', Session::get('usuario.idempresa')[0])
		->leftJoin('pacientes', DB::raw(" MD5( pacientes.id)"), '=', 'ingresos.idpac')
		->leftJoin('habitaciones', 'habitaciones.id', '=',  'ingresos.idhab')
		->select('ingresos.*',
				'habitaciones.deshab',
				'pacientes.nompac',
				'pacientes.ap1pac',
				'pacientes.ap2pac')
				->get();
		
		$opcDispo = $this->opcionesDisponiblesByIdusr();
	
		return Response::json(array('litaIngresos' => $litaIngresos, 'opcDispo' => $opcDispo));
	}
	
	function darAltaPaciente(){
		$ingreso = $this->obtenerIngresoById(Input::get('id'));
		if($ingreso){
			if(!$ingreso->infalta)
				return Response::json(array('msgErr' => 'Debe rellenar el informe de alta'));
			$uSes = app('App\Http\Controllers\SessionController')->obtenerUsuarioSes();
			
			$ingreso->fecalta = Carbon::now('Europe/Madrid');
			$ingreso->idusrallta = $uSes->id;
			$ingreso->save();
			if(Input::get('idhab') != '0')
				app('App\Http\Controllers\habitacionController')->actualizarEstadoHab(Input::get('idhab'), 'N', '');
			return Response::json(array('msgOK' => 'El paciente fue dado de alta correctamente', 'ingreso' => $ingreso));
		}
		
	}
	
	public function actualizarInformeAlta(){
		$ingreso = $this->obtenerIngresoById(Input::get('idingreso'));
		if($ingreso){
			$ingreso->infalta = Input::get('desevol');
			$ingreso->save();
			return Response::json(array('msgOK' => 'Informe de alta guardado correctamente'));
		}
	}
	
	public function obtenerInformeAlta(){
		$ingreso = $this->obtenerIngresoById(Input::get('idingreso'));
		return Response::json(array('ingreso' => $ingreso));
		
	}
	
	public function imprimirAlta(){
		$data = new Documento();
		
		$alta = $this->obtenerIngresoById(Input::get('impIdeAlta'));
		$pac = Paciente::where(DB::raw(" MD5( pacientes.id)"), '=', $alta->idpac)->get()->first();
		
		$infoPac = '<div  style=""> <h3> <strong>Paciente</strong> </h3> <div class="print50"> <strong>Nombre:</strong>'. 
		'<span class="sNombre" >'.$pac->nompac.'</span> '.		
		'<strong>Apellidos: </strong>'.$pac->ap1pac.' '.$pac->ap2pac.'</span> </div>'.
		'<div class="print50"> <strong>Doc. identidad: </strong> <span class="sDni">'.$pac->dniusr.'</span></div></div>';
		
		
		$nombreCompletoUsr = Session::get('usuario.nomusr')[0] . " " . Session::get('usuario.apusr')[0];
		
		
		
		$data->html = $infoPac.'<div class="col-sm-12 print100"><h2>Informe de alta</h2></div>'. $alta->infalta;
		$data->empresa = $alta->idempresa;
		$nombreCompletoUsr = null;
		$view =  View::make('PDF.docs', compact('data', 'nombreCompletoUsr'))->render();
		$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML($view);
		return $pdf->stream('documento.pdf');
	}
	
}
