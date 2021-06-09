<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Response;
use Carbon\Carbon;
use Input;
use DB;

use App\Pais;
use App\Seguro;
use App\Paciente;
use App\Especialidad;

class pacientesController extends Controller {

	public function initPantallaAltaModPacientes(){
	
		$pais = Pais::all();
		$seguro = app('App\Http\Controllers\configAdmController')->obtenerSeguros();
		
		return Response::json(array('listaPaises'=> $pais, 'listaSeguros'=> $seguro));
	}
	
	public function guardarPaciente(){
		$datefecnac = '';
		
		if(Input::get('fecnacpac') && Input::get('fecnacpac') != "  /  /    ")
			$datefecnac = Carbon::createFromFormat('d/m/Y', Input::get('fecnacpac'));
		$paciente = '';
		if(!Input::get('id')){
			
			if(Input::get('dniusr') && Input::get('dniusr')!=''){
				$pacAux = Paciente::where('dniusr', '=', Input::get('dniusr'))->get()->first();
				if(sizeof($pacAux) > 0){
					return Response::json(array('msgError'=>'Ya existe un paciente dado de alta con el DNI introducido'));
				}
			}
			
			$paciente = new Paciente();
			$paciente->codusrcre = Input::get('codUsr');
			$paciente->codusrmod = Input::get('codUsr');
		}else{
			if(Input::get('dniusr') && Input::get('dniusr')!=''){
				$pacAux = Paciente::where('id', '<>', Input::get('id'))
				->where('dniusr', '=', Input::get('dniusr'))->get()->first();
				if(sizeof($pacAux) > 0){
					return Response::json(array('msgError'=>'Ya existe un paciente dado de alta con el DNI introducido'));
				}
			}
			$paciente = Paciente::where('id', '=', Input::get('id'))->get()->first();
			$paciente->codusrmod = Input::get('codUsr');
		}
		
		$paciente->nompac = Input::get('nompac');
		$paciente->ap1pac = Input::get('ap1pac');
		$paciente->ap2pac = Input::get('ap2pac');
		$paciente->fecnacpac = $datefecnac;
		$paciente->sexpac = Input::get('sexpac');
		$paciente->numtel1 = Input::get('numtel1');
		$paciente->numtel2 = Input::get('numtel2');
		$paciente->tipdoc = Input::get('tipdoc');
		$paciente->dniusr = Input::get('dniusr');
		$paciente->emailpac = Input::get('emailpac');
		$paciente->dirpac = Input::get('dirpac');
		$paciente->cppac = Input::get('cppac');
		$paciente->idseguro = Input::get('idseguro');
		$paciente->numseg = Input::get('numseg');
		$paciente->swilopd = Input::get('swilopd');
		$paciente->swilopdcan = Input::get('swilopdcan');
		if(!Input::get('tipMod') || Input::get('tipMod') != "AGENDA"  ){
			$paciente->idpais = Input::get('idpais');
		}
		
		$paciente->comentario = Input::get('comentario');
		
		$paciente->save();
	
		return Response::json(array('paciente'=> $paciente));
	}
	
	public function obtenerPacientes(){
	
		$listaPacientes = Paciente::all();
		
		return Response::json(array('listaPacientes'=> $listaPacientes));
	}
	
	public function obtenerEspecialidades(){
		$esp = Especialidad::all();
		return Response::json(array('listEspec'=> $esp));
	}
	
	public function obtenerEspecialistas(){
		$listaUsr = DB::table('usuarios')
		->leftJoin('especialidades_usuario', 'usuarios.id', '=', 'especialidades_usuario.ideusr')
		->where('especialidades_usuario.codesp', '=', Input::get('codesp'))
		->where('usuarios.fecbajadmin', '>' , Carbon::today()->toDateString())
		->get();
		
		return Response::json(array('listMedicos'=> $listaUsr));
	}
	
	public function buscarPacienteById($idpac, $swiCodificado){
		$paciente = null;
		if($swiCodificado == false)
			$paciente = Paciente::where('id', '=', $idpac);
		else
			$paciente = Paciente::where(DB::raw(" MD5(id)"), '=', $idpac);
		$paciente = $paciente->get()->first();
		
		if($paciente)
			$paciente->id = hash ( "md5" , $paciente->id);
		
		return $paciente;
	}
	
	public function buscarPacienteNomAp(){
		$documento = str_replace(' ', '', Input::get('dniusr'));
		$listaPacientes = DB::table('pacientes')
		->where('pacientes.id', '>' , 0);
		
		if(Input::get('nompac'))
			$listaPacientes = $listaPacientes->where('nompac', 'LIKE', "%".Input::get('nompac')."%");
		if(Input::get('ap1pac'))
			$listaPacientes = $listaPacientes->where('ap1pac', 'LIKE', Input::get('ap1pac')."%");
		/*if(Input::get('tipBusq') && Input::get('tipBusq') == "ADDCITA")
			$listaPacientes = $listaPacientes->where('dniusr', '<>', '')->whereNotNull('dniusr');*/
		if(Input::get('ap2pac'))
			$listaPacientes = $listaPacientes->where('ap2pac', 'LIKE', Input::get('ap2pac')."%");
		if(Input::get('idPaciente'))
			$listaPacientes = $listaPacientes->where( DB::raw(" MD5( pacientes.id)"), '=', Input::get('idPaciente'));
		if($documento && $documento!= "-")
			$listaPacientes = $listaPacientes->whereIn('dniusr', array( Input::get('dniusr'), str_replace('-', '', Input::get('dniusr')) ));
		if(Input::get('idHistorial'))
			$listaPacientes = $listaPacientes->where('pacientes.id', '=', Input::get('idHistorial'));
		
		$listaPacientes = $listaPacientes
		->leftJoin('aseguradoras', 'aseguradoras.id', '=', 'pacientes.idseguro')
		->select('pacientes.*','aseguradoras.nomseguro')
		->get();
		
		return Response::json(array('listaPacientes'=> $listaPacientes));
	}

}
