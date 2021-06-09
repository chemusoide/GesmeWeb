<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use View;
use Input;
use Session;
use Response;
use Carbon\Carbon;

use App\ActQuirurgicos;
use App\ActQuirurgicosCie;
use App\ActQuirurgicosCiePro;
use App\ActQuirurgicosPre;


class actQuiController extends Controller {
	
	
	private function obtenerActQuirurgicosCieDia($idAct, $swiFin){
		$lista = ActQuirurgicosCie::where('idact', '=', $idAct)
		->where('swifinal', '=', $swiFin)
		->leftJoin('cie_diagnostico', 'cie_diagnostico.id', '=', 'act_quirurgicos_cie.idcie')
		->select('act_quirurgicos_cie.*',
				'cie_diagnostico.clase',
				'cie_diagnostico.descie')
		->get();
		
		return $lista;
	}
	
	private function obtenerActQuirurgicosCiePro($idAct, $swiFin){
		$lista = ActQuirurgicosCiePro::where('idact', '=', $idAct)
		->where('swifinal', '=', $swiFin)
		->leftJoin('cie_procedimiento', 'cie_procedimiento.id', '=', 'act_quirurgicos_ciepro.idciepro')
		->select('act_quirurgicos_ciepro.*',
				'cie_procedimiento.codigo',
				'cie_procedimiento.desciepro')
				->get();
	
				return $lista;
	}
	
	public function obtenerListaActqui($idPac, $idUsr, $swiSolAct){
		$listaActQuiAbr = ActQuirurgicos::leftJoin('pacientes', DB::raw(" MD5( pacientes.id)"), '=', 'act_quirurgicos.idpac')
			->leftJoin('act_quirurgicos_pre', 'act_quirurgicos_pre.idact', '=', 'act_quirurgicos.id' );
		
		if($idPac){
			$idPac = md5($idPac);
			$listaActQuiAbr = $listaActQuiAbr->where('idpac', '=',$idPac );
		}
			
		if($idUsr)
			$listaActQuiAbr = $listaActQuiAbr->where('idusr', '=', $idUsr);
		if($swiSolAct){
			$listaActQuiAbr = $listaActQuiAbr->where('fecfin', '=' , '0000-00-00 00:00:00' )
								->where('fecbaj', '=' , '0000-00-00 00:00:00' );
		}else{
			$listaActQuiAbr = $listaActQuiAbr->where('fecfin', '<>' , '0000-00-00 00:00:00' );
		}
		
		
		$listaActQuiAbr = $listaActQuiAbr->select('act_quirurgicos.*',
				'pacientes.nompac',
				'pacientes.ap1pac',
				'pacientes.ap2pac',
				'pacientes.fecnacpac',
				DB::raw('act_quirurgicos_pre.id AS idpreop'))
				->get();
		
		return $listaActQuiAbr;
	}

	public function guardarActQui(){

		
		
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		$fecha = Input::get('fecIniAct');
		$hora = Input::get('horIniAct');
		$min = Input::get('minIniAct');
		if($hora < 10)
			$hora = '0'.$hora;
		if($min < 10)
			$min = '0'.$min;
		
		$fecint = $fecha.' '.$hora.':'.$min;
		$actQui = new ActQuirurgicos();
		$actQui->fecint = Carbon::createFromFormat('Y/m/d H:i',$fecint);
		$actQui->idusr = Input::get('idusr');
		$actQui->idpac =hash ( "md5" , Input::get('idpac'));
		$actQui->fecfin = $defaulfeccha;
		$actQui->fecbaj = $defaulfeccha;
		
		$actQui->save();
		
		$arrCie = json_encode(Input::get('cie'));
		$arrCie = json_decode($arrCie, true);
		//return $arrCie;
		foreach($arrCie as $cie){
			
			$actQuiCie = new ActQuirurgicosCie();
			$actQuiCie->idact = $actQui->id;
			$actQuiCie->idcie = $cie['id'];
			$actQuiCie->fecbaj = $defaulfeccha;
			$actQuiCie->save();
		}
		
		$arrCiePro = json_encode(Input::get('ciePro'));
		$arrCiePro = json_decode($arrCiePro, true);
		//return $arrCie;
		foreach($arrCiePro as $ciePro){
				
			$actQuiCie = new ActQuirurgicosCiePro();
			$actQuiCie->idact = $actQui->id;
			$actQuiCie->idciepro = $ciePro['id'];
			$actQuiCie->fecbaj = $defaulfeccha;
			$actQuiCie->save();
		}
		
		return Response::json(array('msgOk'=>'Se ha dado de alta un nuevo acto quirújico'));
	}
	
	public function obtenerActQuiAbiertosByUsr(){
		
		$listaActQuiAbr = $this->obtenerListaActqui(null, Input::get('idusr') , TRUE);
		
		return Response::json(array('listaActQuiAbr'=> $listaActQuiAbr));
		
	}
	
	public function obtenerActQuiAbiertosByIdpac(){
	
		$listaActQuiAbr = $this->obtenerListaActqui(Input::get('idpac'), null , TRUE);
	
		return Response::json(array('listaActQuiAbr'=> $listaActQuiAbr));
	
	}
	
	public function getDatosInfoActQui(){
		$docsIdFirma = array(5, 6);
		$listaCieInfo =$this->obtenerActQuirurgicosCieDia(Input::get('idAct'), 'N');
		$listaCieProInfo =$this->obtenerActQuirurgicosCiePro(Input::get('idAct'), 'N');
		$listaCieInfoFin =$this->obtenerActQuirurgicosCieDia(Input::get('idAct'), 'S');
		$listaCieProInfoFin =$this->obtenerActQuirurgicosCiePro(Input::get('idAct'), 'S');
		$listaDocsFirmada = app('App\Http\Controllers\firmaController')->obtenerDocsFirmadoByActQui(Input::get('idAct'), $docsIdFirma);
		return Response::json(array('listaCieInfo'=> $listaCieInfo, 'listaCieProInfo'=> $listaCieProInfo, 'listaCieInfoFin'=> $listaCieInfoFin, 'listaCieProInfoFin'=> $listaCieProInfoFin, 'listaDocsFirmada' => $listaDocsFirmada));
	}
	
	public function guardarInfoPreoperatorio(){
		$preope = null;
		$msgOk = 'Se ha dado de alta un nuevo preoperatorio';
		if(!Input::get('id'))
			$preope = new ActQuirurgicosPre();
		else{
			$preope = ActQuirurgicosPre::where('id', '=', Input::get('id'))->get()->first();
			$msgOk = 'Se ha modificado correctamente el preoperatorio';
		}
		
		$preope->idact = Input::get('idact');
		
		$preope->sexpre = Input::get('sexpre');
		$preope->edadpre = Input::get('edadpre');
		$preope->tapre = Input::get('tapre');
		$preope->fcpre =  Input::get('fcpre');
		$preope->pesopre = Input::get('pesopre');
		$preope->estaturapre = Input::get('estaturapre');
		$preope->imcpre = Input::get('imcpre');
		$preope->alerpro = Input::get('alerpro');
		$preope->intpro = Input::get('intpro');
		$preope->fecintpro = Input::get('fecintpro');
		$preope->habtoxTapro = Input::get('habtoxTapro');
		$preope->habtoxAlpro = Input::get('habtoxAlpro');
		$preope->habtoxOtpro = Input::get('habtoxOtpro');
		$preope->antpatpro = Input::get('antpatpro');
		$preope->antquipro = Input::get('antquipro');
		$preope->incipro = Input::get('incipro');
		
		$preope->fcapre = Input::get('fcapre');
		$preope->ecgpre = Input::get('ecgpre');
		$preope->frespre = Input::get('frespre');
		$preope->anapre = Input::get('anapre');
		$preope->opapre = Input::get('opapre');
		
		$preope->bocpre = Input::get('bocpre');
		$preope->perpre = Input::get('perpre');
		$preope->propre = Input::get('propre');
		$preope->apepre = Input::get('apepre');
		$preope->mcepre = Input::get('mcepre');
		$preope->svepre = Input::get('svepre');
		$preope->clupre = Input::get('clupre');
		$preope->obspro = Input::get('obspro');
		
		$preope->save();
		return Response::json(array('preope'=> $preope, 'msgOk'=> $msgOk));
		
	}
	
	public function obtenerPreoperatorio(){
		$preoperatorioBBDD = ActQuirurgicosPre::where('idact', '=', Input::get('idact'))->get()->first();
		return Response::json(array('preoperatorioBBDD'=> $preoperatorioBBDD));
	}
	
	public function finalizarActQui(){
		$preoperatorioBBDD = ActQuirurgicos::where('id', '=', Input::get('idact'))->get()->first();
		
		$preoperatorioBBDD ->fecfin = Carbon::now('Europe/Madrid');
		$preoperatorioBBDD ->fecbaj = Carbon::now('Europe/Madrid');
		$preoperatorioBBDD -> save();
		return Response::json(array('preope'=> $preoperatorioBBDD, 'msgOk'=> 'Se ha finalizado correctamente'));
	}
	
	public function obtenerActQuiHistByUsr(){
		$listaActQuiAbr = $this->obtenerListaActqui(null, Input::get('idusr') , FALSE);
		return Response::json(array('listaActQuiAbr'=> $listaActQuiAbr));
	}
	
	public function guardarEdicionCieFin(){
		$arrCie = json_encode(Input::get('cie'));
		$arrCie = json_decode($arrCie, true);
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		//return $arrCie;
		foreach($arrCie as $cie){
				
			$actQuiCie = new ActQuirurgicosCie();
			$actQuiCie->idact = Input::get('idAct');
			$actQuiCie->idcie = $cie['id'];
			$actQuiCie->swifinal = "S";
			$actQuiCie->fecbaj = $defaulfeccha;
			$actQuiCie->save();
		}
		return Response::json(array('msgOk'=> 'Todo bien'));
	}
	
	public function guardarEdicionCieProFin(){
		$arrCie = json_encode(Input::get('ciePro'));
		$arrCie = json_decode($arrCie, true);
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		//return $arrCie;
		foreach($arrCie as $cie){
		
			$actQuiCie = new ActQuirurgicosCiePro();
			$actQuiCie->idact = Input::get('idAct');
			$actQuiCie->idciepro = $cie['id'];
			$actQuiCie->swifinal = "S";
			$actQuiCie->fecbaj = $defaulfeccha;
			$actQuiCie->save();
		}
		return Response::json(array('msgOk'=> 'Todo bien'));
	}
	
	
	//HOJA QUIRURGICA
	public function hojaQuiroRedirect(){
		Session::forget('actqui');
		Session::push('actqui', Input::get('idact'));
		return Response::json(array('msgOk'=> 'Todo bien'));
	}
	
	public function registroquirofano(){
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		
		$listaActQuiAbr = ActQuirurgicos::leftJoin('pacientes', DB::raw(" MD5( pacientes.id)"), '=', 'act_quirurgicos.idpac')
		
		->where('act_quirurgicos.id', '=', Session::get('actqui')[0])
		->select('act_quirurgicos.*',
				'pacientes.nompac',
				'pacientes.ap1pac',
				'pacientes.ap2pac',
				'pacientes.fecnacpac')
				->get();
		
		
		
		
		return View::make('pages.private/plantillaRegQui',array('actqui' =>$listaActQuiAbr, 'version' => $v->desval));
	}
	

}
