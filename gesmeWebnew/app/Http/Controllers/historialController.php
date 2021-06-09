<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App;
use PDF;
use Session;
use Response;
use Input;
use View;
use DB;

use App\Paciente;
use App\Usuario;
use App\Antecedente;
use App\Ingreso;

class historialController extends Controller {
	
	public function obtenerCitasFechaTipo($idpac, $arrCod, $idMed, $fecDesde, $arrId, $swiHashId){
		$listaCitas = null;
		
		if($swiHashId)
			$listaCitas = DB::table('citas')->where('idpac', '=', $idpac)
			->where('citas.codestado', '=', 'FIN' );
		else
			$listaCitas = DB::table('citas')->where('idpac', '=', hash ( "md5" , $idpac))
			->where('citas.codestado', '=', 'FIN' );
		if($fecDesde){
			$listaCitas = $listaCitas->where('citas.created_at', '>=', $fecDesde);
		}
		if($arrId){
			$listaCitas = $listaCitas->whereIn('citas.id', $arrId );
		}else{
			$listaCitas = $listaCitas->whereIn('citas.codesp', $arrCod );
		}
		$listaCitas = $listaCitas->leftJoin('detalles_cita', 'detalles_cita.idecita', '=', 'citas.id')
		->leftJoin('usuarios', 'usuarios.id', '=', 'citas.idusr')
		->leftJoin('especialidades', 'citas.codesp', '=', 'especialidades.codesp')
		->select('citas.*',
				'usuarios.nomusr',
				'usuarios.apusr',
				'detalles_cita.fecinicita',
				'detalles_cita.tratamiento',
				DB::raw('(CASE WHEN citas.idusr = ' . $idMed . ' THEN detalles_cita.lineaConsulta ELSE "" END) AS lineaConsulta'),
				'detalles_cita.diagnostico',
				'especialidades.especialidad')->orderBy('citas.feccita', 'desc')->get();
		
		return $listaCitas;
	}

	public function initHistPac($id){
		
		$uSes = app('App\Http\Controllers\SessionController')->obtenerUsuarioSes();
		
		$citas = app('App\Http\Controllers\citaController')->obtenerCitasPacienteMedico($id, $uSes->id);	
		
		$swiEsp = "N";
		if(sizeof($citas) == 0){
			$swiEsp = "S";
		}
		
		
		app('App\Http\Controllers\accesoController')->inserTarAcceso($uSes->id, $id, 'VER_HISTORIAL', $swiEsp, null);
		
		$paciente = Paciente::where('id', '=', $id)->get()->first();
		
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		
		$listaCodesp = app('App\Http\Controllers\citaController')->obtenerEspecialidadesCitasPac($id, '', false);
		
		
		
		return View::make('pages.private/histMedPac',array('id' =>$id, 'paciente' => $paciente, 'listaCodesp' => $listaCodesp, "swiImpHist"=>'S', 'version' => $v->desval));
	}
	
	public function initHistPacEnf($id){
	
		$uSes = app('App\Http\Controllers\SessionController')->obtenerUsuarioSes();
	
		$citas = app('App\Http\Controllers\citaController')->obtenerCitasPacienteMedico($id, $uSes->id);
	
		$swiEsp = "N";
		if(sizeof($citas) == 0){
			$swiEsp = "S";
		}
	
	
		app('App\Http\Controllers\accesoController')->inserTarAcceso($uSes->id, $id, 'VER_HISTORIAL', $swiEsp, null);
	
		$paciente = Paciente::where('id', '=', $id)->get()->first();
	
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
	
		$listaCodesp = app('App\Http\Controllers\citaController')->obtenerEspecialidadesCitasPac($id, '', false);
	
		return View::make('pages.private/histMedPac',array('id' =>$id, 'paciente' => $paciente, 'listaCodesp' => $listaCodesp, "swiImpHist"=>'N', 'version' => $v->desval));
	}
	
	
	public function buscarHistorial(){
		
		$listAlergias = null;
		$listaCodesp = null;
		$listAnt = null;
		$listHab = null;
		$listMor = null;
		$listaCitas = null;
		$listIngresos = null;
		
		
		
		if(Input::get('verAlergias') == 'S'){
			$listAlergias = app('App\Http\Controllers\citaController')->obtenerAlergiasUsrActivas( Input::get('id'), false);
		}
		
		if(Input::get('verAntecede') == 'S'){
			$listAnt = app('App\Http\Controllers\citaController')->obtenerAntecedentes(Input::get('id'), 'ANT', false);
			$listHab = app('App\Http\Controllers\citaController')->obtenerAntecedentes(Input::get('id'), 'HAB', false);
			$listMor = app('App\Http\Controllers\citaController')->obtenerAntecedentes(Input::get('id'), 'MOR', false);
		}
		
		if(Input::get('verConsulta') == 'S'){
			$listaCitas = $this->obtenerCitasFechaTipo(Input::get('id'), Input::get('tipConsulta'), Input::get('idMed'), Input::get('fechaDesde'), null, null);
			
		}
		
		if(Input::get('verIngresos') == 'S'){
			$listIngresos = app('App\Http\Controllers\ingresoController')->obtenerIngresosByIdPac( Input::get('id'), false);
		}
		
		return Response::json(array('listAlergias'=>$listAlergias, 'listAnt'=>$listAnt, 'listHab'=>$listHab,
				'listMor'=>$listMor, 'listaCitas' => $listaCitas, 'listIngresos' => $listIngresos));
	}

	public function obtenerAntecedentesByID($arrId){
		$ant = Antecedente::whereIn('id', $arrId)
		->orderBy('id', 'desc')->get();
		
		return $ant;
	}
	
	public function obtenerIngresosByID($arrId){
		$ant = Ingreso::whereIn('id', $arrId)
		->orderBy('id', 'desc')->get();
	
		return $ant;
	}
	
	public function imprimirHistorial(){
		
		$id = Input::get('impIdePac');
		$impTipConsulta = Input::get('impTipConsulta');
		$impIdConsulta = Input::get('impIdConsulta');
		$impIdMed = Input::get('impIdMed');
		$impFechaDesde = Input::get('impFechaDesde');
		
		$usu =  Usuario::find($impIdMed);
		$nombreCompletoUsr = $usu->nomusr . " " . $usu->apusr;
		
		//DECLARAMOS VARIABLES PDF
		$listAlergias = null;
		$listAnt = null;
		$listHab = null;
		$listMor = null;
		$listaCitas = null;
		$listIngreso = null;
		$data = null;
		
	
		if(Input::get('swiCodId'))
			$data = Paciente::where(DB::raw(" MD5( pacientes.id)"), '=', $id)->get()->first();
		else
			$data = Paciente::where('id', '=', $id)->get()->first();
		
		//Alergia
		
		$listAlergias = app('App\Http\Controllers\citaController')->obtenerAlergiasUsrActivas( $id, false);
		
		//Antecedentes
		if(Input::get('impAntecedente') == 'S'){
			$listAnt = app('App\Http\Controllers\citaController')->obtenerAntecedentes($id, 'ANT', false);
		}elseif(Input::get('impListANT') && sizeof(Input::get('impListANT')) > 0){
			$listAnt = $this->obtenerAntecedentesByID(Input::get('impListANT'));
		}

		if(Input::get('impHabito') == 'S'){
			$listHab = app('App\Http\Controllers\citaController')->obtenerAntecedentes($id, 'HAB', false);
		}elseif(Input::get('impListHAB') && sizeof(Input::get('impListHAB')) > 0){
			$listHab = $this->obtenerAntecedentesByID(Input::get('impListHAB'));
		}

		if(Input::get('impMorfologia') == 'S'){
			$listMor = app('App\Http\Controllers\citaController')->obtenerAntecedentes($id, 'MOR', false);
		}elseif(Input::get('impListMOR') && sizeof(Input::get('impListMOR')) > 0){
			$listMor = $this->obtenerAntecedentesByID(Input::get('impListMOR'));
		}
		
		//ingresos
		if(Input::get('impingreso') == 'S'){
			$listIngreso = app('App\Http\Controllers\ingresoController')->obtenerIngresosByIdPac($id, false);
		}elseif(Input::get('impListingreso') && sizeof(Input::get('impListingreso')) > 0){
			$listIngreso = $this->obtenerIngresosByID(Input::get('impListingreso'));
		}
		
		
		//Citas
		if(Input::get('impListCitas') == 'S'){
			$listaCitas = $this->obtenerCitasFechaTipo($id, $impTipConsulta, $impIdMed, $impFechaDesde, $impIdConsulta, Input::get('swiCodId'));
		}
		$data->empresa = Session::get('usuario.idempresa')[0];
		
		$view =  View::make('PDF.historialPaciente', compact('data', 'listAlergias', 'listAnt', 'listHab', 'listMor', 'listaCitas', 'impTipConsulta', 'nombreCompletoUsr', 'listIngreso'))->render();
		
		$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML($view);
		return $pdf->stream('gesmeweb-historial.pdf');
	}
	

}
