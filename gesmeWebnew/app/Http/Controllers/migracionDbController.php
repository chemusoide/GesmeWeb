<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App;
use PDF;
use Carbon\Carbon;
use Response;
use Input;
use View;
use DB;

use App\Paciente;
use App\Usuario;
use App\Antecedente;
use App\Cita;
use App\DetalleCita;
use App\HistorialBorrar;

class migracionDbController extends Controller {
	
	public function insertarMedicosBorrar(){
		$listaMed =  DB::table('medicos_borrar')->get();
		
		foreach($listaMed as $med){
			if( $med->NOMBRE){
				$borrMed= new Usuario();
				$borrMed->nomusr = $med->NOMBRE;
				$borrMed->idold2 = $med->ID;
				$borrMed->apusr = " ";
				$borrMed->numcoleg = $med->COLEGIADO;
				$borrMed->dniusr = $med->DNI;
					
				//return $usu;
				$borrMed->save();
			}
			
		}
		
		return 'Hecho insertarMedicosBorrar';
	}
	
	public function citasUpdateSeguros(){
		//Para ver que seguros usan
		$listaCit =  DB::table('historial_borrar')
		->where('MEDICO','=', '3')
		->whereNull('idsegnew')
		->select('SEGURO') 
		->distinct()->orderBy('SEGURO', 'desc')->get();
		//FIN Para ver que seguros usan
		
		//Para actualizarles el nuevo seguro
		//UPDATE `historial_borrar` SET `idsegnew`= 105 WHERE `SEGURO` = 17
		//FIN Para actualizarles el nuevo seguro
		
		
		return $listaCit;
		
		//Actualizar el id de Seguro por medico
		/*$listaCit =  DB::table('historial_borrar')
		->where('MEDICO','=', '170')
		->whereNotNull('idsegnew')->get();

		
		foreach($listaCit as $visita){
		$cita2 = DB::table('historial_borrar')
		->whereNull('idsegnew')
		->where('ID','=', $visita->ID);
		
		if($cita2)
			$cita2 =  DB::table('historial_borrar')
			->where('ID','=', $visita->ID)->update(['idsegnew' =>  $visita->idsegnew]);
		}
		
		
		return $listaCit;*/
	}
	
	public function citasMedOld(){
	/********** MODIFICAR **********/
		$idMED = 170;
		$idMEDNwe = 55;
	/********** FIN **********/	
		
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		
		$listaCit =  DB::table('historial_borrar')
		->where('MEDICO','=', $idMED)
		//->where('VISITA','=', 247614)
		->select('*')
		->distinct()->orderBy('HISTORIA')/*->skip(0)->take(5000)*/->get();
		
		
		
		
		foreach($listaCit as $visita){
			$idMD5 = "";
			$pac = Paciente::where('idold2', '=', $visita->HISTORIA)->get()->first();
			
			$cita2 = DB::table('historial_borrar')
			->where('MEDICO','=', $idMED)
			->where('HISTORIA','=',$visita->HISTORIA)
			->get();
			
			$dateCita = null;
			
			if( $cita2){
				foreach($cita2 as $c2){
					$d = substr ( $c2->FECHA,0,2);
					$m = substr ( $c2->FECHA,3,2);
					$y = substr ( $c2->FECHA,6,4);
					//return substr ( $c2->FECHA,11,2);
					$dateCita = Carbon::create($y, $m, $d, 0, 0, 0);
				}
			}
			//return $cita2;
			;
			if(!$pac){
				$listPacBorrar =  DB::table('pacientes_borrar')
				->where('HISTORIA', '=', $visita->HISTORIA)
				->get();
				
				foreach($listPacBorrar as $pacBorrar){
				$paciente = new Paciente();
					$paciente->idold2 = $pacBorrar->HISTORIA;
					$paciente->nompac = $pacBorrar->NOMBRE;
					$paciente->ap1pac = $pacBorrar->APP1;
					$paciente->ap2pac = $pacBorrar->APP2;
					$paciente->fecnacpac = $pacBorrar->FNACI;
					if( $pacBorrar->SEXO)
						$paciente->sexpac = $pacBorrar->SEXO;
					else
						$paciente->sexpac = 'H';
					$paciente->numtel1 = $pacBorrar->TELEFONO;
					$paciente->numtel2 = $pacBorrar->MOVIL;
					$paciente->tipdoc = 'DNI';
					if($pacBorrar->DNI)
						$paciente->dniusr = $pacBorrar->DNI;
					else
						$paciente->dniusr = 'SIN DOCUMENTO';
					$paciente->emailpac = '';
					$paciente->dirpac = $pacBorrar->DIRECCION;
					$paciente->cppac = $pacBorrar->CP;
					$paciente->idpais = null;
					//$paciente->idseguro = $visita->idsegnew;
					//$paciente->numseg = $pacBorrar->POLIZA;
					$paciente->comentario = $pacBorrar->OBS;
					
					$paciente->save();
					$idMD5 = hash ( "md5" , $paciente->id);
					
					//return $paciente;
				}
				
			}else{
					$idMD5 = hash ( "md5" ,$pac->id);
					//$pac->idseguro = $visita->idsegnew;
					//$pac->save();
					//return $idMD5;
			}
			
			$citaExiste = Cita:: where('idold2', '=', $visita->VISITA)->get()->first();
			if(!$citaExiste){
				$cita = new Cita();
				$cita->idusr = $idMEDNwe;
				$cita->idpac = $idMD5;
				//$cita->idsegactual = $visita->idsegnew;
				$cita->idold2 = $visita->VISITA;
				//$cita->numseg = $visita->POLIZA;
				$cita->hora = 0;
				$cita->feccita = $dateCita->format('Y/m/d');
				if( $visita->VISTO == "S")
					$cita->codestado = 'FIN';
				else
					$cita->codestado = 'PLN';
					
				
				
				/***********ESPECIFICO!!!!!!!!!!!! ***************/
				$cita->codesp = 'MFA';
				
				
				$cita->durcon = 0;
				
				$cita->fecbaja = $defaulfeccha->format('Y/m/d');
				$cita->save();
				
			}
			
		
		}
		
		return 'citasMedOld';
	}
	
	public function insertarHistorialBorrar(){
		$lista = HistorialBorrar::
		//where('HISTORIA', '=', 7281)->
		/*where('VISITA', '>=', 230001)
		->where('VISITA', '<=', 260000	) */
		//->where('PARRAFO', '=', 1)
		/*where('VISITA', '=', 244219)
		->*/orderBy('FECHA', 'asc')
		->orderBy('VISITA', 'asc')
		->orderBy('PARRAFO', 'asc')
		->get();
		
		$arrString = array();
		
		//return $lista;
		
		foreach($lista as $obj){
			$pos = strrpos($obj->LINEAHISTORIAL, "lang3082");
			
			
			$lineaHistoria = substr($obj->LINEAHISTORIAL, $pos+8);
			
			$posFin = strrpos($lineaHistoria, "\r\npar"); 
			
			if(!$posFin)
				$posFin = strlen($lineaHistoria) - 5;
				
			$var = 'antes -  '.$lineaHistoria.'              - despues-';
			
			$lineaHistoria = substr($lineaHistoria, 0,$posFin);
			
			if($lineaHistoria){
			if($arrString && isset($arrString[$obj->VISITA]))
				$arrString[$obj->VISITA] = $arrString[$obj->VISITA]. '<p>' .substr($lineaHistoria,0,-2).'</p>';
			else
				$arrString[$obj->VISITA] = '<p>' .substr($lineaHistoria,0,-2).'</p>';
			}
		}
		$var ='';
		while ($v = current($arrString)) {
		
			$var = $var.key($arrString)."-";	
			//return key($arrString);
			
			$cita = Cita::where('idold2', '=', key($arrString))->get()->first();
			
			if($cita){
			$det = DetalleCita::where('idecita', '=', $cita->id)->get()->first();
				if(!$det){
					$det = new DetalleCita();
					$det->fecinicita  =  $cita->feccita;
				$det->idecita = $cita->id;
				$det->lineaConsulta = $v;
				$det->diagnostico = '';
				$det->tratamiento = '';
				$det->save();
				}
				
			}
			
			next($arrString);
		}
		return 'Sin return';
		return Response::json(array('arrString'=> $arrString));
	}
}
