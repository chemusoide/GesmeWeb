<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Usuario;
use App\Parametro;
use App\Empresa;
use App\EmpresaUsu;
use Carbon\Carbon;
use Input;

use Session;
use App\RolUsuario;
use Hash;
use Response;

class SessionController extends Controller {

	public function versionArchivos(){
		$v = Parametro::
		where('tipo','=', 'ADMIN')
		->where('coddom','=', 'VJS')->get()->first();
		return $v;
	
	}

	public function loginUsuario(Request $request){

		$usu = Usuario::where('emailusr','=',Input::get('emailusr'))
		->where('fecbajadmin', '>' , Carbon::today()->toDateString())->get()->first();
		
		//VALIDACIONES
		if((sizeof($usu) > 0) && $usu->fecaceptado > Carbon::today()->toDateString())
			return Response::json(array('msgError'=>'El administrador no valido su cuenta'));

		if((sizeof($usu) > 0) && Hash::check(Input::get('password'), $usu->password)){
		 	if(Session::get('usuario.email')[0] !=  $usu->emailusr ){
		 		Session::flush();
		 		Session::push('usuario.email', $usu->emailusr); 
		 		Session::push('usuario.nomusr', $usu->nomusr); 
		 		Session::push('usuario.apusr', $usu->apusr); 
		 	}
		 	Session::forget('usuario.idempresa');
		 	$listEmpresa = EmpresaUsu::where('idusu', '=', $usu->id)
		 					->where('empresa_usu.fecbaj', '>', Carbon::now('Europe/Madrid')->format('Y/m/d'))
		 					->join('empresas', 'empresas.id', '=', 'empresa_usu.idempresa')->get();
		 	if(sizeof($listEmpresa) == 0)
		 		return Response::json(array('msgError'=>'No tiene empresa asignada, contacte con el administrador'));
		 	elseif (sizeof($listEmpresa) == 1){
		 		Session::push('usuario.idempresa', $listEmpresa{0}->idempresa);
		 		Session::push('usuario.nombreEmpresa', $listEmpresa{0}->nombre);
		 	}
		 	
		 		
		 	return  Response::json(array('msgOk'=>'Loguin Correcto', 'listEmpresa' => $listEmpresa, 'si'=>Session::get('usuario.idempresa')[0]));
		}else {
			return Response::json(array('msgError'=>'Credenciales no válidas'));
		} 
	}
	
	public function obtenerUsuarioSes(){
		$usu = Usuario:: where('emailusr', '=',  Session::get('usuario.email')[0])
		->where('fecbajadmin', '>' , Carbon::today()->toDateString())->get()->first();
		
		return $usu;
	}

	public function obtenerDatosUsuarioSession(){
		$usu = $this->obtenerUsuarioSes();
		
		$rolusr = RolUsuario::where('ideusr','=', $usu->id)->get();		
		
		return Response::json(array('usuario'=>$usu, 'rolusr'=>$rolusr));
	}
	
	public function menuConfig(){
		$usu = $this->obtenerUsuarioSes();
		$empresa = $this->obtenerEmpresaSes();
		$rolusr = RolUsuario::where('ideusr','=', $usu->id)->get();
		
		$listaRoles = array();
		
		$verUsuarios = 'N';
		$verPacientes = 'N';
		$verTablon = 'N';
		$verTablonEnfermeria = 'N';
		$verListaDocs = 'N';
		$FichaPac = "N";
		$verListaMed = "N";
		$verMiAgenda = "N";
		$verVisitas = "N";
		$verActQuirurgico = "N";
		$verIngresos = "N";
		
		$verConfiAdm = "N";
		
		foreach($rolusr as $r){
			if($r->codrol == 'MED' || $r->codrol == 'FIS'|| $r->codrol == 'OTE'){
				$verTablon = 'S';
				$verMiAgenda = 'S';
				$verIngresos = "S";
			}
			
			if($r->codrol == 'REC'){
				$verPacientes = 'S';
				$verListaDocs = 'S';
				$verListaMed = "S";
				$verUsuarios = "S";
				$verIngresos = "S";
			}
			
			if($r->codrol == 'DUE' || $r->codrol == 'AUX'){
				$verPacientes = 'S';
				$FichaPac = 'S';
				$verIngresos = "S";
			}
			
			if($r->codrol == 'DUV' || $r->codrol == 'RES'){
				$verPacientes = 'S';
				$FichaPac = 'S';
				$verVisitas = "S";
			}
			
			if($r->codrol == 'ADM'){
				$verPacientes = 'S';
			}
			
			if($r->codrol == 'ADE'){
				$FichaPac = 'S';
			}
			
			if($r->codrol == 'ADMIN'){
				$verUsuarios = 'S';
				$verPacientes = 'S';
				$verTablon = 'S';
				//$verTablonEnfermeria = 'S';
				$verListaDocs = 'S';
				$FichaPac = 'S';
				$verListaMed = "S";
				$verConfiAdm = "S";
				$verActQuirurgico = "S";
				$verIngresos = "S";
			}
		}
		
		if($verUsuarios == 'S'){
			$object = (object) [
					'name' => 'liMenuUsr',
					'href' => '/private/listado-usuarios',
					'icon' => 'wb-user',
					'title' => 'Médicos'
			];
				
			array_push($listaRoles, $object);
		}
		
		if($verPacientes == 'S'){
			$object = (object) [
					'name' => 'liMenuPac',
					'href' => '/private/listado-pacientes',
					'icon' => 'wb-users',
					'title' => 'Pacientes'
			];
				
			array_push($listaRoles, $object);
		}
		
		if($verListaMed == 'S'){
			$object = (object) [
					'name' => 'liMenuBusqMed',
					'href' => '/private/lista-medicos',
					'icon' => 'wb-time',
					'title' => 'Agendas'
			];
			
			array_push($listaRoles, $object);
		}
	
		if($verTablon == 'S'){
			$object = (object) [
					'name' => 'liMenuTabMed',
					'href' => '/private/tablon-medico',
					'icon' => 'wb-order',
					'title' => 'Tablon Médico'
			];
			
			array_push($listaRoles, $object);
		}
		
		if($verTablonEnfermeria == 'S'){
			$object = (object) [
					'name' => 'liMenuTabEnf',
					'href' => '/private/tablon-enfermeria',
					'icon' => 'wb-order',
					'title' => 'Tablon'
			];
				
			array_push($listaRoles, $object);
		}
		
		if($FichaPac == 'S'){
			$object = (object) [
					'name' => 'liMenuFicha',
					'href' => '/private/busqueda-paciente',
					'icon' => 'wb-clipboard',
					'title' => 'Ficha Paciente'
			];
		
			array_push($listaRoles, $object);
		}
		
		if($verListaDocs == 'S'){
			$object = (object) [
					'name' => 'liMenuDocs',
					'href' => '/private/lista-documentos',
					'icon' => 'wb-file',
					'title' => 'Documentos'
			];
			
			array_push($listaRoles, $object);
		}
		
		if($verConfiAdm == 'S'){
			$object = (object) [
					'name' => 'liConfAdm',
					'href' => '/private/configuraciones',
					'icon' => 'wb-wrench',
					'title' => 'Configuraciones'
			];
				
			array_push($listaRoles, $object);
		}
		
		if($verConfiAdm == 'S'){
			$object = (object) [
					'name' => 'liConfAdm',
					'href' => '/private/firma-documentos',
					'icon' => 'wb-mobile',
					'title' => 'Firmar'
			];
		
			array_push($listaRoles, $object);
		}
		
		if($verMiAgenda == 'S'){
			$object = (object) [
					'name' => 'liMenuAgendaMed',
					'href' => '/private/mi-agenda',
					'icon' => 'wb-time',
					'title' => 'Agenda'
			];
		
			array_push($listaRoles, $object);
		}
		
		if($verVisitas == 'S'){
			$object = (object) [
					'name' => 'liMenuVisitas',
					'href' => '/private/visitas',
					'icon' => 'wb-time',
					'title' => 'Visitas'
			];
			array_push($listaRoles, $object);
		}
		
		if($verIngresos == 'S'){
			$object = (object) [
					'name' => 'liMenuIngresos',
					'href' => '/private/ingresos',
					'icon' => 'wb-heart',
					'title' => 'Ingresos'
			];
			array_push($listaRoles, $object);
		}
		
		if($verActQuirurgico == 'S'){
				$object = (object) [
						'name' => 'liMenuActQui',
						'href' => '/private/actos-quirurgicos',
						'icon' => 'wb-scissor',
						'title' => 'Actos Quirúrgicos'
				];
			
			array_push($listaRoles, $object);
		}
		
		return Response::json(array('usuario'=>$usu, 'rolusr'=>$rolusr, 'listaRoles' => $listaRoles, 'empresa'=>$empresa));
	}
	
	public function gestionOpcionesFichaPaciente(){
		
		$usu = $this->obtenerUsuarioSes();
		
		$rolusr = RolUsuario::where('ideusr','=', $usu->id)->get();
		
		$listaOpciones = array();
		
		$verHist = "N";
		$verPruebasComp = "N";
		
		foreach($rolusr as $r){
			
			if($r->codrol == 'DUE' || $r->codrol == 'AUX'){
				$verHist = 'S';
			}
				
			if($r->codrol == 'AUX'){
				$verPruebasComp = 'S';
			}
			
			if($r->codrol == 'ADE'){
				$verPruebasComp = 'S';
				
			}
				
			if($r->codrol == 'ADMIN'){
				$verHist = 'S';
				$verPruebasComp = 'S';
			}
		}
		
		if($verHist == 'S'){
			$object = (object) ['opcion' => 'verHist'];
		
			array_push($listaOpciones, $object);
		}
		
		if($verPruebasComp == 'S'){
			$object = (object) ['opcion' => 'verPruebasComp'];
		
			array_push($listaOpciones, $object);
		}
		
		return $listaOpciones;
	}
	
	public function mataSesiones(){
		Session::flush();
		return Response::json(array('msg'=>'Desconectado'));
	}
	
	public function obtenerEmpresaSes(){
		return Empresa::where('id','=', Session::get('usuario.idempresa')[0])->get()->first();
	}
	
	public function accederEmpresa(){
		
		Session::push('usuario.idempresa', Input::get('idempresa'));
		$empresa = $this->obtenerEmpresaSes();
		Session::push('usuario.nombreEmpresa', $empresa->nombre);
		return Response::json(array('msgOk'=>'Actualizado','si'=>Session::get('usuario.idempresa')[0]));
	}
	
}
