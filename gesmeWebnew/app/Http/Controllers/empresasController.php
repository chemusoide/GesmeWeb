<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Carbon\Carbon;
use DB;
use Input;
use Response;

use App\Empresa;
use App\EmpresaUsu;
use App\Usuario;

class empresasController extends Controller {

	public function volcadoClientePoliclinico(){
	
		$listaUsu = Usuario::all();
		
		foreach ($listaUsu as $usu){
			$eu = new EmpresaUsu();
			$eu->idempresa = 1;
			$eu->idusu = $usu->id;
			$eu->fecbaj = Carbon::create(3000, 12, 31, 0, 0, 0)->format('Y/m/d');
			$eu->save();
		}
		
		
		return Response::json(array('listaUsu'=> $listaUsu));
	}
	
	function obtenerEmpresas(){
		$listaEmpresas = Empresa::get();
		return $listaEmpresas;
	}
	
	function getEmpresaById($id){
		return Empresa::where('id', '=',$id)->get()->first();
	}
	
	function getEmpresaByIdUsr($id){
		return EmpresaUsu::where('idusu', '=',$id)->get();
	}
	
	

}
