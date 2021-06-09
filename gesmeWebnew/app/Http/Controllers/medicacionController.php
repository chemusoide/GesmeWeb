<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Carbon\Carbon;
use DB;
use Input;
use Response;

use App\Medicamento;
use App\MedicamentoPautado;
use App\MedicacionSuministrada;

class medicacionController extends Controller {
	
	public function obtenerMedicamentoIngresoByid($id){
		return MedicamentoPautado::where('id', '=',$id)->get()->first();
	}
	
	public function eliminarMedicacionIng(){
		$medicamentoIngreso = $this->obtenerMedicamentoIngresoByid(Input::get('id'));
		if($medicamentoIngreso){
			$medicamentoIngreso->fecbaj = Carbon::now('Europe/Madrid')->format('Y/m/d');
			$medicamentoIngreso->save();
			return Response::json(array('msgOK'=>'Se dio de baja correctamente'));
		}
		return Response::json(array('msgErr'=>'No se encontro nada'));
	}

	public function obtenerMedicacionIngActivos(){
		$listaMedPau = MedicamentoPautado::where('idingreso', '=', Input::get('idingreso'))
		->where('medicamentos_pautados.fecbaj', '>', Carbon::now('Europe/Madrid')->format('Y/m/d'))
		->join('medicamentos', 'medicamentos.id', '=', 'medicamentos_pautados.idmedicamento')
		->join('usuarios', 'usuarios.id', '=', 'medicamentos_pautados.idmed')
		->select('medicamentos_pautados.*',
				'medicamentos.descripcion',
				DB::raw("CONCAT(usuarios.nomusr, ' ' ,usuarios.apusr) AS medico"))->get();
		
		return Response::json(array('listaMedPau'=> $listaMedPau));
		
		
	}
	public function obtenerMedicamentos(){
		$listaMedicamentos = []; 
		if(Input::get('q') && strlen(Input::get('q')) > 2){
			$listaMedicamentos = Medicamento::where('descripcion', 'LIKE', '%'.Input::get('q').'%')
			->select('id',
					DB::raw('descripcion AS text'))->get();
		}
		return Response::json(array('listaMedicamentos'=> $listaMedicamentos,'size'=>strlen(Input::get('q'))));
	}
	
	public function guardarMedicamentosIngreso(){
		
		$nMedicamento = new MedicamentoPautado();
		$nMedicamento ->idmed = Input::get('idmed');
		$nMedicamento ->idingreso = Input::get('idingreso');
		$nMedicamento ->idmedicamento = Input::get('idmedicamento');
		$nMedicamento ->dosis = Input::get('dosis');
		$nMedicamento ->tipvia = Input::get('tipvia');
		$nMedicamento ->periodominutos = Input::get('periodominutos');
		$nMedicamento ->descripcion = Input::get('descripcion');
		if(Input::get('swiprecisa'))
			$nMedicamento ->swiprecisa = 'S';
		else
			$nMedicamento ->swiprecisa = 'N';
		$nMedicamento ->fecfin = Carbon::createFromFormat('Y/m/d', Input::get('fecfin'));
		$nMedicamento ->fecini = Carbon::createFromFormat('Y/m/d', Input::get('fecini'));
		$nMedicamento->save();
		return Response::json(array('nMedicamento'=> $nMedicamento,'msgOK'=>'Se dio de alta correctamente'));
		
	}
	
	public function obtenerMedicacionSumByrangoIdingreso($fecini, $fecfin, $idingreso){
		$medSumini = MedicacionSuministrada::
		join('medicamentos_pautados', 'medicamentos_pautados.id', '=', 'medicacion_suministrada.idmedpautado')
		->join('medicamentos', 'medicamentos.id', '=', 'medicamentos_pautados.idmedicamento')
		->join('usuarios', 'usuarios.id', '=', 'medicacion_suministrada.idusrsum')
		->where('medicamentos_pautados.idingreso', '=', $idingreso);
		
		if($fecini){
			$medSumini = $medSumini->where('fecsum','>', $fecini)
						 ->where('fecsum','<', $fecfin);
		}
		
		$medSumini = $medSumini->select('medicacion_suministrada.*',
				'medicamentos.descripcion',
				DB::raw("CONCAT(usuarios.nomusr, ' ' ,usuarios.apusr) AS usrAdm")
		)->get();
		
		return $medSumini;
	}
	
	public function medicacionSuministradaByIdIngreso(){
		
		$listaMedPau = MedicamentoPautado::
			leftJoin('medicacion_suministrada', 'medicacion_suministrada.idmedpautado', '=', 'medicamentos_pautados.id')
			->join('medicamentos', 'medicamentos.id', '=', 'medicamentos_pautados.idmedicamento')
			->where('medicamentos_pautados.idingreso', '=', Input::get('idingreso'))
			->where('medicamentos_pautados.fecbaj', '>', Carbon::now('Europe/Madrid')->format('Y/m/d'))
			->join('usuarios', 'usuarios.id', '=', 'medicamentos_pautados.idmed')
			->select('medicamentos_pautados.dosis',
					'medicamentos_pautados.fecini',
					'medicamentos_pautados.fecfin',
					'medicamentos_pautados.idingreso',
					'medicamentos_pautados.id AS idMedPautado',
					'medicamentos_pautados.periodominutos',
					'medicamentos_pautados.swiprecisa',
					'medicamentos_pautados.tipvia',
				'medicamentos.descripcion',
				DB::raw("CONCAT(usuarios.nomusr, ' ' ,usuarios.apusr) AS medico, max(medicacion_suministrada.fecsum) ultdosis, max(medicacion_suministrada.created_at) fecultsum"))
		->groupBy('medicamentos_pautados.dosis',
					'medicamentos_pautados.fecini',
					'medicamentos_pautados.fecfin',
					'medicamentos_pautados.idingreso',
					'idMedPautado',
					'medicamentos_pautados.periodominutos',
					'medicamentos_pautados.swiprecisa',
					'medicamentos_pautados.tipvia',
					
				'medicamentos.descripcion',
				"medico")
		->get();
		
		$listaMedSumini = $this->obtenerMedicacionSumByrangoIdingreso(Input::get('fecIniBusq'),Input::get('fecFinBusq'), Input::get('idingreso'));
		
		return Response::json(array('listaMedPau'=> $listaMedPau, 'listaMedSumini'=>$listaMedSumini));
		
	}
	
	public function medicacionSuministradaByIdIngresoHistorico(){
		$listaMedSumini = $this->obtenerMedicacionSumByrangoIdingreso(null, null, Input::get('idingreso'));
		return Response::json(array('listaMedSumini'=>$listaMedSumini));
	}
	
	public function registrarFirmaMedicacion(){
		$medSumini = new MedicacionSuministrada;
		$medSumini->idmedpautado = Input::get('idmedpautado');
		$medSumini->idusrsum = Input::get('idusrsum');
		$medSumini->fecsum = Input::get('fecsum');
		$medSumini->obs = '';
		$medSumini->save();
		return Response::json(array('medSumini'=> $medSumini, 'msgOK'=> 'Registrado correctamente'));
	}

}
