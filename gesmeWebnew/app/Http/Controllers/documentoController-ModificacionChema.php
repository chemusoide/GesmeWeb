<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App;
use PDF;
use Response;
use Session;
use Input;
use View;
use DB;

Use App\Documento;
use App\Especialidad;
Use App\ConfigDocs;
use App\Parametro;
use App\Paciente;
use App\Usuario;
use App\Seguro;
use App\Cita;



class documentoController extends Controller {
	
	public function obtenerDesParam($tipo, $coddom){
		$tip = Parametro::
		where('tipo','=', $tipo)
		->where('coddom','=', $coddom)->get()->first();
	
		return $tip->desval;
	
	}
	
	public function obtenerInformesGrupo($numGrupo){
		$arr = null;
		
		if($numGrupo == 1)
			$arr = array(6,4,5,8,7,3);
		
		if($numGrupo == 2)
			$arr = array(6,2,4,5,8,7,3,9 ,10,15,16);
		
		if($numGrupo == 3)
			$arr = array(8,12,13,14,16);
		
		return $arr;
		
	}
	
	public function obtenerDocById($id){
		return Documento::where('id', '=', $id)->get()->first();
	}
	
	public function listadocumentos(){
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		$tipos = app('App\Http\Controllers\agendaController')->obtenerPrametrosInfo("TIPO_INFORME");
		$listaEsp = Especialidad::orderBy('especialidad', 'asc')->get();
		$listadoEmpresas = app('App\Http\Controllers\empresasController')->obtenerEmpresas();
		return View::make('pages.private/listadoDocs', array('version' => $v->desval, 'listaTipos' => $tipos, 'listaEsp' => $listaEsp, 'listadoEmpresas' => $listadoEmpresas, 'idEmpresaSes' => Session::get('usuario.idempresa')[0]));
	}
	
	public function SustituirConfig($config, $htmlStr, $empresaSelect){
		$idPac = Input::get('impIdePacDat');
		$idMed = Input::get('impIdeMedDat');
		
		foreach($config as $c){
			if($c->dato == "DATOS_PACIENTE"){
				$pacAux = Paciente::where('id', '=', $idPac)->get()->first();
				
				$date=date_create($pacAux->fecnacpac);
				
				$htmlStr = str_replace("@@NOMBRECOMPLETO@@", mb_strtoupper($pacAux->ap1pac." ".$pacAux->ap2pac.", ".$pacAux->nompac), $htmlStr);
				$htmlStr = str_replace("@@DNI@@",mb_strtoupper($pacAux->dniusr), $htmlStr);
				
				if(Input::get('swiPacJus') && Input::get('swiPacJus') == "S"){
					$htmlStr = str_replace("@@NOMBRECOMPLETOSOL@@", mb_strtoupper($pacAux->ap1pac." ".$pacAux->ap2pac.", ".$pacAux->nompac), $htmlStr);
					$htmlStr = str_replace("@@DNISOL@@",mb_strtoupper($pacAux->dniusr), $htmlStr);
				}
				
				$htmlStr = str_replace("@@SEXO@@",$pacAux->sexpac, $htmlStr);
				$htmlStr = str_replace("@@TELMOVIL@@",$pacAux->numtel1, $htmlStr);
				$htmlStr = str_replace("@@TELFIJO@@",$pacAux->numtel2, $htmlStr);
				$htmlStr = str_replace("@@DIRPAC@@", mb_strtoupper($pacAux->dirpac." - C.P.:".$pacAux->cppac), $htmlStr);
				
				$listAlergias = app('App\Http\Controllers\citaController')->obtenerAlergiasUsrActivas($idPac, false);
				$alerPac = "";
				foreach($listAlergias as $aler){
					if(strlen($aler->codalergia) > 0){
						if(strlen($alerPac) > 0)
							$alerPac = $alerPac.", ";
							$alerPac = $alerPac.$aler->alergia;
					}					
				}
				
				$htmlStr = str_replace("@@ALERGIA@@", mb_strtoupper($alerPac), $htmlStr);
				if(intval(date_format($date,"Y")) > 0){
					$htmlStr = str_replace("@@FECNAC@@",date_format($date,"d/m/Y"), $htmlStr);
					$htmlStr = str_replace("@@CALCULO_EDAD@@", Carbon::createFromFormat('d/m/Y', date_format($date,"d/m/Y"))->age, $htmlStr);
				}else{
					$htmlStr = str_replace("@@FECNAC@@","", $htmlStr);
					$htmlStr = str_replace("@@CALCULO_EDAD@@","", $htmlStr);
				}
					
	
	
			}
			
			if($c->dato == "DATOS_MEDICO"){
				if (strpos($idMed, 'FAKEID') !== false) {
						
					$htmlStr = str_replace("@@NOMBRECOMPLETOMED@@", mb_strtoupper(Input::get('impNomMedDat')), $htmlStr);
				}else{
					$usrAux = Usuario::where('id', '=', $idMed)->get()->first();
						
					$htmlStr = str_replace("@@NOMBRECOMPLETOMED@@", mb_strtoupper($usrAux->apusr.", ".$usrAux->nomusr), $htmlStr);
				}
			}
			
			if($c->dato == "FECHA_ACTUAL"){
				$htmlStr = str_replace("@@FECHAACTUAL@@",Carbon::now('Europe/Madrid')->format('d/m/Y'), $htmlStr);
			}
			
			if($c->dato == "INFO_SEGURO"){
				$seguro = DB::table('aseguradoras')
					->leftJoin('pacientes', 'aseguradoras.id', '=', 'pacientes.idseguro')
					->where('pacientes.id', '=', $idPac)
					->get();;
				if(Input::get('impLitPrivado') ){
					$htmlStr = str_replace("@@SEGURO@@",mb_strtoupper('privado'),$htmlStr);
					$htmlStr = str_replace("@@POLIZA@@",'', $htmlStr);
				}
				
				if(sizeof($seguro) > 0){
					$htmlStr = str_replace("@@SEGURO@@",mb_strtoupper($seguro[0]->nomseguro),$htmlStr);
					$htmlStr = str_replace("@@SEGURO_SIN_FILTRO@@",mb_strtoupper($seguro[0]->nomseguro),$htmlStr);
					$htmlStr = str_replace("@@POLIZA@@",$seguro[0]->numseg, $htmlStr);
				}else{
					$htmlStr = str_replace("@@SEGURO@@","",$htmlStr);
					$htmlStr = str_replace("@@POLIZA@@","", $htmlStr);
					$htmlStr = str_replace("@@SEGURO_SIN_FILTRO@@","", $htmlStr);
				}
				
			}
			if($c->dato == "COMENTARIO"){
				if($empresaSelect){
					$htmlStr = str_replace("@@COMENTARIO@@",Input::get('impObsDat'), $htmlStr);
				}else{
					$htmlStr = str_replace("@@COMENTARIO@@","", $htmlStr);
				}
			}
			if($c->dato == "RESUMEN_HISTORIA_CLINICA"){
				if(Input::get('impResHisCli')){
					$htmlStr = str_replace("@@RESUMEN_HISTORIA_CLINICA@@",Input::get('impResHisCli'), $htmlStr);
				}else{
					$htmlStr = str_replace("@@RESUMEN_HISTORIA_CLINICA@@","", $htmlStr);
				}
			}
			if($c->dato == "HALLAZGOS_OBSERVADOS"){
				if(Input::get('impHaObsCli')){
					$htmlStr = str_replace("@@HALLAZGOS_OBSERVADOS@@",Input::get('impHaObsCli'), $htmlStr);
				}else{
					$htmlStr = str_replace("@@HALLAZGOS_OBSERVADOS@@","", $htmlStr);
				}
			}
			
			if($c->dato == "PIEZA_REMITIDA"){
				if(Input::get('impPezRem')){
					$htmlStr = str_replace("@@PIEZA_REMITIDA@@",Input::get('impPezRem'), $htmlStr);
				}else{
					$htmlStr = str_replace("@@PIEZA_REMITIDA@@","", $htmlStr);
				}
			}
			
			if($c->dato == "ESPECIALIDAD"){
				if(Input::get('impEspe')){
					$htmlStr = str_replace("@@ESPECIALIDAD@@",mb_strtoupper(Input::get('impEspe')), $htmlStr);
				}else{
					$htmlStr = str_replace("@@ESPECIALIDAD@@","", $htmlStr);
				}
			}
			
			if($c->dato == "PROCEDIMIENTO"){
				if(Input::get('impProc')){
					$htmlStr = str_replace("@@PROCEDIMIENTO@@",mb_strtoupper(Input::get('impProc')), $htmlStr);
				}else{
					$htmlStr = str_replace("@@PROCEDIMIENTO@@","", $htmlStr);
				}
			}
			
			if($c->dato == "AUTORIZACION"){
				if(Input::get('impAuto')){
					$htmlStr = str_replace("@@AUTORIZACION@@",Input::get('impAuto'), $htmlStr);
				}else{
					$htmlStr = str_replace("@@AUTORIZACION@@","", $htmlStr);
				}
			}
			
			if($c->dato == "COMENTARIO"){
				if(Input::get('impObsDat')){
					$htmlStr = str_replace("@@COMENTARIO@@",Input::get('impObsDat'), $htmlStr);
				}else{
					$htmlStr = str_replace("@@COMENTARIO@@","", $htmlStr);
				}
			}

			if($c->dato == "INTERVENCION"){
				if(Input::get('impInterven')){
					$htmlStr = str_replace("@@INTERVENCION@@",mb_strtoupper(Input::get('impInterven')), $htmlStr);
				}else{
					$htmlStr = str_replace("@@INTERVENCION@@","", $htmlStr);
				}
			}
			
			if($c->dato == "NOTAS"){
				if(Input::get('impNot')){
					$htmlStr = str_replace("@@NOTAS@@",Input::get('impNot'), $htmlStr);
				}else{
					$htmlStr = str_replace("@@NOTAS@@","", $htmlStr);
				}
			}
			
			if($c->dato == "NHISTORIA"){
				if(Input::get('impNHist')){
					$htmlStr = str_replace("@@NHISTORIA@@",Input::get('impNHist'), $htmlStr);
				}else{
					$htmlStr = str_replace("@@NHISTORIA@@","", $htmlStr);
				}
			}
			
			if($c->dato == "BOX"){
				if(Input::get('impBox')){
					$htmlStr = str_replace("@@BOX@@",Input::get('impBox'), $htmlStr);
				}else{
					$htmlStr = str_replace("@@BOX@@","", $htmlStr);
				}
			}
			
			if($c->dato == "FIRMA_MEDICO_MATEO"){
				if(Input::get('impFiMed') ){
					$htmlStr = str_replace("@@FIRMA_MEDICO_MATEO@@",'<img src="img/pdf/Firma_mateo_1.jpg" height="40px;">', $htmlStr);
				}else{
					$htmlStr = str_replace("@@FIRMA_MEDICO_MATEO@@",'', $htmlStr);
				}
			}
			
			if($c->dato == "FIRMA_MEDICO"){
					$htmlStr = str_replace("@@FIRMA_MEDICO@@",'', $htmlStr);
			}
			
			if($c->dato == "FECHA_HORA_USR"){
				if(Input::get('sFecIni')){
					$htmlStr = str_replace("@@FECHA_HORA_USR@@",Input::get('sFecIni')." ".Input::get('sHoraIni').":".Input::get('sMinIni'), $htmlStr);
				}else{
					$htmlStr = str_replace("@@FECHA_HORA_USR@@","", $htmlStr);
				}
			}
			
			if($c->dato == "EMPRESA"){
				if(Input::get('impEmp')){
					$htmlStr = str_replace("@@EMPRESA@@",mb_strtoupper(Input::get('impEmp')), $htmlStr);
				}else{
					$htmlStr = str_replace("@@EMPRESA@@","", $htmlStr);
				}
			}
			
			if($c->dato == "MEDICO_NO_OBL"){
				if(Input::get('nomMedNoObl')){
					$htmlStr = str_replace("@@MEDICO_NO_OBL@@",Input::get('nomMedNoObl'), $htmlStr);
				}else{
					$htmlStr = str_replace("@@MEDICO_NO_OBL@@","", $htmlStr);
				}
			}
			
			if($c->dato == "FECHA_GENERICA"){
				if(Input::get('sfechGen')){
					$htmlStr = str_replace("@@FECHA_GENERICA@@",Input::get('sfechGen'), $htmlStr);
				}else{
					$htmlStr = str_replace("@@FECHA_GENERICA@@","", $htmlStr);
				}
			}
			
			if($c->dato == "IMPORTE_TOTAL"){
				if(Input::get('importe')){
					$htmlStr = str_replace("@@IMPORTE_TOTAL@@",Input::get('importe'), $htmlStr);
				}else{
					$htmlStr = str_replace("@@IMPORTE_TOTAL@@","", $htmlStr);
				}
			}
			
		if($c->dato == "NOMBRE_EMPRESA"){
			if($empresaSelect){
				$empresa = app('App\Http\Controllers\empresasController')->getEmpresaById($empresaSelect);
				
				$htmlStr = str_replace("@@NOMBRE_EMPRESA@@",mb_strtoupper($empresa->nombre), $htmlStr);
			}else
				$htmlStr = str_replace("@@NOMBRE_EMPRESA@@",mb_strtoupper(Session::get('usuario.nombreEmpresa')[0]), $htmlStr);
				
		}
		
		if($c->dato == "DATOS_EXTRA_EMPRESA"){
			
			
			$empresaSes = app('App\Http\Controllers\SessionController')->obtenerEmpresaSes();
			$htmlStr = str_replace("@@DIR_EMPRESA@@",mb_strtoupper($empresaSes->direccion), $htmlStr);
		
		}		
			
			if($c->dato == "PRUEBAS"){
				$strDiv = '';
				
				if(Input::get('cPrea')){
					$strDiv = $strDiv.'<span style="margin-top:30px;"><strong>PREANESTÉSIA (DR. VERD)</strong></span>	<input style="margin-top:-3px;" type="checkbox" /><br>';
				}
				if(Input::get('cAna')){
					$strDiv = $strDiv.'<strong> ANÁLISIS : </strong> <br>	 <span style="margin-left:30px;margin-top:8px;">SANGRE<input style="margin-top:-3px;" type="checkbox"> <span style="margin-left:30px;margin-top:8px;">ORINA<input style="margin-top:-3px;" type="checkbox" /><br>';
				}
				if(Input::get('cEco')){
					$strDiv = $strDiv.'<span style="margin-top:30px;"><strong>ECOGRAFÍA  </strong></span><input style="margin-top:-3px;" type="checkbox" /><br>	';
				}
				if(Input::get('cRxt')){
					$strDiv = $strDiv.'<span style="margin-top:30px;"><strong>RX TÓRAX  </strong></span>	<input style="margin-top:-3px;" type="checkbox" /><br>	';
				}
				if(Input::get('cAudio')){
					$strDiv = $strDiv.'<span style="margin-top:30px;"><strong>AUDIO  </strong></span>	<input style="margin-top:-3px;" type="checkbox" /><br>';
				}
				if(Input::get('cVisi')){
					$strDiv = $strDiv.'<span style="margin-top:30px;"><strong>VISIÓN  </strong></span><input style="margin-top:-3px;" type="checkbox" /><br>';
				}
				if(Input::get('cEspi')){
					$strDiv = $strDiv.'<span style="margin-top:30px;"><strong>ESPIROMETRÍA </strong></span> 	<input style="margin-top:-3px;" type="checkbox" /><br>';
				}
				if(Input::get('cEle')){
					$strDiv = $strDiv.'<span style="margin-top:30px;"><strong>ELECTRO </strong></span> 	<input style="margin-top:-3px;" type="checkbox" /><br>';
				}
				
				$htmlStr = str_replace("@@PRUEBAS@@",$strDiv, $htmlStr);
				
			}
			
			if($c->dato == "DATOS_CONSULTA"){
				if(Input::get('idCitaSelect')){
					
					if (strpos(Input::get('idCitaSelect'), 'visita-') !== false) {
						$visita = app('App\Http\Controllers\visitaController')->obtenerVisitaByid(str_replace("visita-", "", Input::get('idCitaSelect')));
						
						$htmlStr = str_replace("@@FECHA_CONSULTA@@", date("d-m-Y", strtotime($visita->fecvisita)), $htmlStr);
						$htmlStr = str_replace("@@HORA_CONSULTA@@", '', $htmlStr);
						
						
					}else{
						$cita = app('App\Http\Controllers\citaController')->obtenerCitaByid(Input::get('idCitaSelect'));
						$detCita = app('App\Http\Controllers\citaController')->obtenerDetalleCitaByid(Input::get('idCitaSelect'));
						
						$htmlStr = str_replace("@@FECHA_CONSULTA@@", date("d-m-Y", strtotime($cita->feccita)), $htmlStr);
						if($detCita && $detCita->fecinicita)
							$htmlStr = str_replace("@@HORA_CONSULTA@@", date("H:i", strtotime($detCita->fecinicita)), $htmlStr);
						else
							$htmlStr = str_replace("@@HORA_CONSULTA@@", '', $htmlStr);
					}
					
					$htmlStr = str_replace("@@NOMBRECOMPLETOSOL@@", mb_strtoupper(Input::get('apSol').", ".Input::get('nombreSol')), $htmlStr);
					$htmlStr = str_replace("@@DNISOL@@",mb_strtoupper(Input::get('solDoc')), $htmlStr);
					
				}else{
					$htmlStr = str_replace("@@FECHA_CONSULTA@@","", $htmlStr);
					$htmlStr = str_replace("@@HORA_CONSULTA@@","", $htmlStr);
					
					$htmlStr = str_replace("@@NOMBRECOMPLETOSOL@@", "", $htmlStr);
					$htmlStr = str_replace("@@DNISOL@@","", $htmlStr);
				}
				
				
			}
			
			if($c->dato == "CITAS_DISP"){
				if((!Input::get('sFecDesde') || strlen(Input::get('sFecDesde')) <= 0) && (!Input::get('sFecHasta') || strlen(Input::get('sFecHasta')) <= 0))
					return 'Especifique algún filtro';
				$lista = Cita::where('citas.codestado','<>','CAN');
				if(Input::get('sFecDesde') && strlen(Input::get('sFecDesde')) > 0){
					$d = Carbon::createFromFormat('d/m/Y', Input::get('sFecDesde'));
					$lista = $lista->where('citas.feccita','>=', $d->format('Y/m/d'));
				}
				if(Input::get('sFecHasta') && strlen(Input::get('sFecHasta')) > 0){
					$d = Carbon::createFromFormat('d/m/Y', Input::get('sFecHasta'));
					$lista = $lista->where('citas.feccita','<=',$d->format('Y/m/d'));
				}
				if(Input::get('sIdseguro') && strlen(Input::get('sIdseguro')) > 0){
					$lista = $lista->where('citas.idsegactual','=', Input::get('sIdseguro'));
				}
				if(Input::get('sMed') && strlen(Input::get('sMed')) > 0){
					$lista = $lista->where('citas.idusr','=', Input::get('sMed'));
				}
				
				$lista = $lista->select('citas.*')->orderBy('citas.feccita', 'asc')->get();
				$strHtmlGen = "";
				$cont = 1;
				foreach($lista as $item){
					$nombreSeguro = "";
					$numeroSeguro = "";
					$idSeguroIt = "";
					
					$pacObt = Paciente::where(DB::raw(" MD5( pacientes.id)"), '=', $item->idpac)->get()->first();
					
					if($item->idsegactual &&  strlen($item->idsegactual) > 0){
						$idSeguroIt = $item->idsegactual;
						$numeroSeguro = $item->numseg;
					}
					
					if($idSeguroIt && strlen($idSeguroIt) > 0){
						$s = Seguro::where('id','=',$idSeguroIt)->get()->first();
						$nombreSeguro = $s-> nomseguro;	
					}
					
					$usrAux = Usuario::where('id', '=', $item->idusr)->get()->first();
					$espObt = Especialidad::where('codesp', '=',$item->codesp)->get()->first();
					
					/*$strHtmlGen = $strHtmlGen."<div> <span style='margin-top: 8px; text-align: justify; font-size: 13px;'>".$cont
						.'- FECHA: '.date("d-m-Y", strtotime($item->feccita)).' '.mb_strtoupper($espObt->especialidad).' ('.$this->obtenerDesParam('ESTADO_CITA', $item->codestado).')'
						.'<br>PACIENTE: '
						. mb_strtoupper($pacObt->nompac).' ' . mb_strtoupper($pacObt->ap1pac). ' ' . mb_strtoupper($pacObt->ap2pac);
						if($nombreSeguro && strlen($nombreSeguro) > 0)
							$strHtmlGen = $strHtmlGen. '<br>SEGURO: '. $nombreSeguro. ' : '. mb_strtoupper($numeroSeguro);
						$strHtmlGen = $strHtmlGen. '<br>DOCTOR: '. mb_strtoupper($usrAux->nomusr). ' '. mb_strtoupper($usrAux->apusr);
						
						$strHtmlGen = $strHtmlGen." </span> </div> <hr>";*/
					$nombreCompletoPac = "";
					
					$strTl = '';
					if($pacObt->numtel1)
						$strTl = $pacObt->numtel1;
					if($pacObt->numtel2){
						if($pacObt->numtel1)
							$strTl = $strTl. ' - ' .$pacObt->numtel2;
						else{
							$strTl = $pacObt->numtel2;
						}
					}
					
					if($pacObt)
						$nombreCompletoPac = mb_strtoupper($pacObt->nompac).' ' . mb_strtoupper($pacObt->ap1pac). ' ' . mb_strtoupper($pacObt->ap2pac). '   TLF: '. $strTl;
						
					$strHtmlGen = $strHtmlGen."<div> <span style='margin-top: 8px; text-align: justify; font-size: 13px;'>".$cont
							.'- FECHA: '.date("d-m-Y", strtotime($item->feccita))
							.' - PACIENTE: '
							. $nombreCompletoPac;
							if($nombreSeguro && strlen($nombreSeguro) > 0)
								$strHtmlGen = $strHtmlGen. ' - SEGURO: '. $nombreSeguro;
								$strHtmlGen = $strHtmlGen. ' - DOCTOR: '. mb_strtoupper($usrAux->nomusr). ' '. mb_strtoupper($usrAux->apusr);
					
								$strHtmlGen = $strHtmlGen." </span> </div> <hr>";
						$cont = $cont + 1;
				}
				$htmlStr = str_replace("@@LISTADO@@",$strHtmlGen, $htmlStr);
				
			}
			
			
		}
		return $htmlStr;
	}
	
	
	
	
	public function imprimirDoc(){
		$data = null;
		$medDatDoc = null;
		$listaImprimir = Input::get('numGrupo');
		
		
		if(Input::get('impIdeMedDatDoc'))
			$medDatDoc = json_decode( Input::get('impIdeMedDatDoc'), true);
		
		
		if(Input::get('swiGrupo') == 'S'){
			$idPac = Input::get('impIdePacDat');
			//si se firma con DR. Mateo se imprimira la hoja de preoperatorio
			$config = ConfigDocs::whereIn('iddoc', explode ("," ,  $listaImprimir))->get();
			
			foreach($config as $c){
				if($c->dato == "FIRMA_MEDICO"){
					$aDoc = explode ("," ,  $listaImprimir);
					$encontrado= false;
					foreach($aDoc as $a){
						if($a == "11"){
							$encontrado = true;
							break;
						}
					}
					if($encontrado == false){
						$listaImprimir = $listaImprimir.",11";
					}
					break;
				}
			}
			
			$docs = Documento::whereIn('id', explode ("," , $listaImprimir))->get();
						
			$htmlStr = '';
			
			$data = $docs[0];
			
			$i=0;
			$len = count($docs);
			$pru = '';
			$datosMed = null;
			foreach($docs as $d){
				$htmlStrAux = $d->html;
				$datosMed = null;
				if($medDatDoc){
					
					foreach (json_decode( Input::get('impIdeMedDatDoc'), true) as $objJSON){
						$encontrado = false;
						foreach ( $objJSON["documentos"] as $objDocJSON){
							if( $objDocJSON == $d->id){
								$datosMed = $objJSON;
								$encontrado = true;
								$htmlStrAux = str_replace("@@NOMBRECOMPLETOMED@@",  mb_strtoupper($objJSON["nomusr"]), $htmlStrAux);
								break;
							}
						}
						if($encontrado == true){
							break;
						}
					}
				}
				
				if(((strlen(Input::get('swiFirma')) > 0 && Input::get('swiFirma') == 'on') && $d->reqfirmapac == 'S')
					|| ((strlen(Input::get('swiFirmaMed')) > 0 && Input::get('swiFirmaMed') == 'on') && $d->reqfirmamed == 'S')){
					//return  $data->reqfirmapac;
					$htmlAdd = $htmlStrAux;
					$config = ConfigDocs::where('iddoc', '=', $d->id)->get();
					if(sizeof($config) > 0){
						$htmlAdd = $this->SustituirConfig($config, $htmlAdd, Input::get('listEmpresa'));
					}
					$htmlAdd = str_replace("@@SALTO_PAGINA@@",'', $htmlAdd);
					$idPacAux = null;
					$medInfoAux = null;
					if((strlen(Input::get('swiFirma')) > 0 && Input::get('swiFirma') == 'on') && $d->reqfirmapac == 'S')
						$idPacAux = md5($idPac);
						
					if(((strlen(Input::get('swiFirmaMed')) > 0 && Input::get('swiFirmaMed') == 'on') && $d->reqfirmamed == 'S')){
						if($datosMed)
							$medInfoAux = $datosMed;
						else{
							if (strpos(Input::get('impIdeMedDat'), 'FAKEID') !== false) {
								$medInfoAux['id'] = null;
								$medInfoAux['nomusr'] = mb_strtoupper(Input::get('impNomMedDat'));
							}else{
								$medInfoAux['id'] = Input::get('impIdeMedDat');
								$medInfoAux['nomusr'] =  mb_strtoupper(Input::get('impNomMedDat'));
							}
						}
							 
					}
						
					app('App\Http\Controllers\firmaController')->insertarDocFirma($idPacAux, $d->id, $htmlAdd, null, $medInfoAux);
				}else{
					//se repite 2 veces las intrucciones de paciente
					/*if($d->id == 7){
						$htmlStrAux2 = $htmlStrAux;
						$htmlStrAux = str_replace("@@SALTO_PAGINA@@",'<div style="page-break-after: always;"> </div>', $htmlStrAux);
						$htmlStr = $htmlStr.$htmlStrAux.$htmlStrAux2;
					}else*/
						$htmlStr = $htmlStr. $htmlStrAux;
				}
				
				if ($i == $len - 1) {
					$htmlStr = str_replace("@@SALTO_PAGINA@@",'', $htmlStr);
				}else{
					$htmlStr = str_replace("@@SALTO_PAGINA@@",'<div style="page-break-after: always;"> </div>', $htmlStr);
				}
				$i++;
			}
			
			$config = ConfigDocs::whereIn('iddoc', explode ("," ,  $listaImprimir))->get();
			
			if(sizeof($config) > 0){
				$htmlStr = $this->SustituirConfig($config, $htmlStr, Input::get('listEmpresa'));
			}
			$htmlStr = str_replace("@@FIRMA@@",'', $htmlStr);
			$htmlStr = str_replace("@@FIRMA_NO@@",'', $htmlStr);
			$htmlStr = str_replace("@@FIRMA_MEDICO@@",'', $htmlStr);
			
			$data->html = $htmlStr;
			
		}else{
			$idPac = Input::get('impIdePacDat');
			$idMed = Input::get('impIdeMedDat');
			
			$lista = $this->obtenerDocs(Input::get('impIdeDocDat'), null, null);
			
			$data =  $lista[0];
			
			$htmlStr =  $data->html;
			
			$config = ConfigDocs::where("iddoc","=", $data->id )->get();
			
			if(sizeof($config) > 0){
				$htmlStr = $this->SustituirConfig($config, $htmlStr, Input::get('listEmpresa'));
			}
			
			$htmlStr = str_replace("@@SALTO_PAGINA@@",'', $htmlStr);
			
			if(strlen(Input::get('swiFirma')) > 0 && Input::get('swiFirma') == 'on' && $data->reqfirmapac == 'S'){
				app('App\Http\Controllers\firmaController')->insertarDocFirma(md5($idPac), $data->id, $htmlStr, null, null);	
			}
			$htmlStr = str_replace("@@FIRMA@@",'', $htmlStr);
			$htmlStr = str_replace("@@FIRMA_NO@@",'', $htmlStr);
			$htmlStr = str_replace("@@FIRMA_MEDICO@@",'', $htmlStr);
			
			$data->html = $htmlStr;
			
			
		}
		if(Input::get('listEmpresa'))
			$data->empresa = Input::get('listEmpresa');
		else
			$data->empresa = Session::get('usuario.idempresa')[0];
		
		$nombreCompletoUsr = null;
		$view =  View::make('PDF.docs', compact('data', 'nombreCompletoUsr'))->render();
		$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML($view);
		return $pdf->stream('documento.pdf');
		
	}
	
	public function obtenerDocs($id, $tipo, $grupo){
		$listadoDocs = Documento::where('fecbaj', '>', Carbon::now('Europe/Madrid')->format('Y/m/d'));
		if($id)
			$listadoDocs = $listadoDocs->where('id', "=", $id);
		if($grupo)
			$listadoDocs = $listadoDocs->whereIn('id',$this->obtenerInformesGrupo($grupo));
		if($tipo)
			$listadoDocs = $listadoDocs->where('tipo', "=", $tipo);
		$listadoDocs = $listadoDocs->get();
		
		return $listadoDocs;
	}
	
	public function buscarDocumentos(){
		$listadoDocs = $this->obtenerDocs(null, Input::get('tipoDoc'),Input::get('numGrupo'));
		
		$usuMateo = null;
		
		$listaObligatorio = null;
		
		if(Input::get('numGrupo') == 1)
			$listaObligatorio = array(6,4,5);
		if(Input::get('numGrupo') == 2){
			$listaObligatorio = array(10,11,16);
			$usuMateo = app('App\Http\Controllers\usuariosController')->obtenerUsuariosByMail("mateo@policlinicoquirurgico.com");
		}if(Input::get('numGrupo') == 3)
			$listaObligatorio = array(12,13,14,16);
		
		return Response::json(array('listadoDocs'=> $listadoDocs, 'listaObligatorio' => $listaObligatorio, 'usuMateo' => $usuMateo));
	}
	
	public function buscarConfiguracionesDoc(){
		//Quitamos los automaticos.
		$listadoDocsConfig = ConfigDocs::where("iddoc","=", Input::get('iddoc') )
		->where('dato', '<>', 'FECHA_ACTUAL')->get();
		
		
		
		return Response::json(array('listadoDocsConfig'=> $listadoDocsConfig));
	}
	
	public function buscarConfiguracionesDocGrupo(){
		$listadoDocsConfig = null;
		
		
		$listadoDocsConfig = ConfigDocs::whereIn('iddoc',Input::get('numGrupo'))->distinct()->get();
		
		$listaDocsMed = DB::table('documentos')
				->leftJoin('config_documentos', 'documentos.id', '=', 'config_documentos.iddoc')
				->where('config_documentos.dato', '=', "DATOS_MEDICO")
				->whereIn('config_documentos.iddoc',Input::get('numGrupo'))
				->get();
		
				
		return Response::json(array('listadoDocsConfig'=> $listadoDocsConfig, 'listaDocsMed' => $listaDocsMed));
		
	}
	

}
