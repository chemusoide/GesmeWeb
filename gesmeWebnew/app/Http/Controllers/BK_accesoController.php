<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Mail;

use App\ControlAcceso;


class accesoController extends Controller {

	public function inserTarAcceso($idMed, $idPac, $tipAcc, $swiEsp, $comentario){
		$acceso = new ControlAcceso();
		$acceso->codacces = $tipAcc;
		$acceso->idusr = $idMed;
		$acceso->idpac = $idPac;
		$acceso->swiespecial =$swiEsp;
		$acceso->comentario =$comentario;
		$acceso->save();
		
		if($swiEsp == "S"){
			$this->enviarMailAccesoespecial($tipAcc);
		}
		
		return 'OK';
		
	}
	
	public function enviarMailAccesoespecial($tipAcc){
	
		
	
		return 'OK';
	
	}

}
