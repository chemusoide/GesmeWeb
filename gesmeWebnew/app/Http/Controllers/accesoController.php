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
	
		$ema = "Sixerss@gmail.com";
		$nombre = "Adrián Urbano León";
		
		$uSes = app('App\Http\Controllers\SessionController')->obtenerUsuarioSes();
		
	
		$userAdm = array(
				//'email'=> $sesMail,
				'email' => 'noreply@policlinicoquirurgico.com',
				'name'=>   'Centro Policlinico Quirurgico',
				'emailDes'=> $ema,
				'nameDes'=>   $nombre
		);
	
		$data = array(
				'tipAcc'=> $tipAcc,
				'nombre'	=> $uSes->nomusr
		);
	
	
	
		// use Mail::send function to send email passing the data and using the $user variable in the closure
		Mail::send('emails.accesoEspecial', $data, function($message) use ($userAdm)
		{
			$message->from($userAdm['email'], $userAdm['name']);
			$message->to($userAdm['emailDes'], $userAdm['nameDes'])->subject('Acceso Especial');
		});
	
		return 'OK';
	
	}

}
