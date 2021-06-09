<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Response;
use Carbon\Carbon;
use Session;
use Input;
use DB;
use View;
use App;

use App\Paciente;
use App\Documento;
use App\DocPacFirma;


class firmaController extends Controller {
	
	function initFirmarDocumentos($documento){
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		$listIdFirma =  DocPacFirma::whereIn('idpac', function($query) use($documento) {
			$query->select(DB::raw(" MD5(id)"))
			->from(with(new Paciente)->getTable())
			->where('dniusr', '=', $documento);
		})->whereNull('strfirma')
		->select('id')->get();		
		
		return View::make('pages.firma/detalleFirma', array('version' => $v->desval, 'listIdFirma'=> $listIdFirma,'tipFirma' =>'FIRMAPAC' ));
	}
	
function initFirmarDocumentosProf($med ){
		$v = app('App\Http\Controllers\SessionController')->versionArchivos();
		$listIdFirma =  DocPacFirma::where('nombremed', '=', $med)
		->whereNull('strfirmamed')
		->select('id')->get();		
		
		return View::make('pages.firma/detalleFirma', array('version' => $v->desval, 'listIdFirma'=> $listIdFirma, 'tipFirma' =>'FIRMAMED'));
	}
	
	function insertarDocFirma($idpac, $iddoc, $str, $actQui, $datosMed){
		
		
		$doc = app('App\Http\Controllers\documentoController')->obtenerDocById($iddoc);
		
		$dFirma = new DocPacFirma();
		$dFirma->idpac = $idpac;
		$dFirma->iddoc = $iddoc;
		$dFirma->stringdoc = $str;
		$dFirma->idact = $actQui;
		if($datosMed){
			$dFirma->idmed = $datosMed['id'];
			$dFirma->nombremed = $datosMed['nomusr'];
		}
		
		$dFirma->swiactrev = $doc->swiactrev;
		//$dFirma->fecha = Carbon::now('Europe/Madrid')->format('Y/m/d');
		$dFirma->save();
		return 'OK';
	}
	
	function obtenerDocAFirmarById($id){
		$infoDoc =  DocPacFirma::where('id', '=', $id)->get()->first();
		return $infoDoc;
	}
	
	function obtenerDocAFirmar(){
		
		$infoDoc = $this->obtenerDocAFirmarById(Input::get('id'));
		return Response::json(array('infoDoc' => $infoDoc));
	}
	
	function guardarDocFirmado(){
		$infoDoc = $this->obtenerDocAFirmarById(Input::get('id'));
		if(Input::get('tipFirma') == 'FIRMAPAC')
			$infoDoc->strfirma = Input::get('firma');
		
		if(Input::get('tipFirma') == 'FIRMAMED')
			$infoDoc->strfirmamed = Input::get('firma');
		$infoDoc->actrev = Input::get('actrev');
		$infoDoc->fecha = Carbon::now('Europe/Madrid')->format('Y/m/d');
		$infoDoc->save();
		return Response::json(array('msgOk' => 'OK'));
	}
	
	function obtenerDocFirmaPac(){
		
		$listaDocs =  DocPacFirma::where('idpac', '=', md5(Input::get('idpac')))
		->join('documentos', 'documentos.id', '=', 'docs_pacs_firma.iddoc')
		->select('docs_pacs_firma.id',
				 'docs_pacs_firma.fecha',
				 'documentos.nombre')->get();
		
		return Response::json(array('listaDocs' => $listaDocs));
	}
	
	function obtenerDocFirmaMed(){
	
		$listaDocs =  DocPacFirma::where('idmed', '=', md5(Input::get('idmed')))
		->join('documentos', 'documentos.id', '=', 'docs_pacs_firma.iddoc')
		->select('docs_pacs_firma.id',
				'docs_pacs_firma.fecha',
				'documentos.nombre')->get();
	
		return Response::json(array('listaDocs' => $listaDocs));
	}
	
	function numdocsMedFirmaPend($idmed){
		
		$listaDocsPend =  DocPacFirma::whereNull('strfirmamed')
		
		->where(function($q){
			
					
				$q->orWhere(function($q2) {
					$q2->whereNotNull('idmed');
				});
						
				$q->orWhere(function($q3){
	
					$q3->whereNotNull('nombremed');
				});
							
		});
		
		if($idmed){
			$listaDocsPend = $listaDocsPend->where('idmed', '=', $idmed);
		}
		
		
		
		$listaDocsPend = $listaDocsPend->select('idmed',
				'nombremed',
				DB::raw('count(*) as totDocs'))
		->groupBy('idmed', 'nombremed')
		->get();
		
		return $listaDocsPend;
	}
	
	function verListaDocsPendFirma(){
		
		$listaDocsPend = $this->numdocsMedFirmaPend(Input::get('idmed'));
		return Response::json(array('listaDocsPend' => $listaDocsPend));
	}
	
	function verDocFirmado(){

		$data = new Documento();
		$pac = Paciente::where('id', '=', Input::get('idPacList'))->get()->first();
		
		$infoDoc = $this->obtenerDocAFirmarById(Input::get('id'));
		
		if(!$infoDoc->swiactrev || $infoDoc->actrev == 'S'){
			
			$data->html =str_replace("@@FIRMA@@",'<img height="55px;" height="55px;" src="data:image/png;base64,'. $infoDoc->strfirma.'">', $infoDoc->stringdoc);
			$data->html =str_replace("@@FIRMA_NO@@",'', $data->html);
		}else{
			$data->html =str_replace("@@FIRMA_NO@@",'<img height="55px;" height="55px;" src="data:image/png;base64,'. $infoDoc->strfirma.'">', $infoDoc->stringdoc);
			$data->html =str_replace("@@FIRMA@@",'', $data->html);
		}
		
		if($infoDoc->strfirmamed)
			$data->html =str_replace("@@FIRMA_MEDICO@@",'<img height="55px;" height="55px;" src="data:image/png;base64,'. $infoDoc->strfirmamed.'">', $data->html);
		else 
			$data->html =str_replace("@@FIRMA_MEDICO@@",'', $data->html);
		$data->empresa = Session::get('usuario.idempresa')[0];
		
		$nombreCompletoUsr = null;
		$view =  View::make('PDF.docs', compact('data', 'nombreCompletoUsr'))->render();
		$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML($view);
		return $pdf->stream('documento.pdf');
		
	}
	
	function obtenerDocsFirmadoByActQui($actQui, $arrTipDoc){
		return DocPacFirma::whereIn('iddoc', $arrTipDoc)
		->where('idact', '=', $actQui)->get();
	}
	
}
