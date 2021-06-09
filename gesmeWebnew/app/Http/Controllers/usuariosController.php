<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Rol;
use App\Especialidad;
use Response;
use Carbon\Carbon;
use App\Usuario;
use App\RolUsuario;
use App\EspecialidadUsuario;
use App\EmpresaUsu;
use Session;
use Input;
use Hash;
use Mail;
use DB;

class usuariosController extends Controller {
	
	public function generarPass(){
		$result = "";
		$chars = "abcdefghijklmnopqrstuvwxyz_?!-0123456789";
		$charArray = str_split($chars);
		for($i = 0; $i < 10; $i++){
			$randItem = array_rand($charArray);
			$result .= "".$charArray[$randItem];
		}
		return $result;
	
	}
	
	public function obtenerEspecialidadMedico(){
		$listaEspecialidades = DB::table('especialidades')
					->leftJoin('especialidades_usuario', 'especialidades.codesp', '=', 'especialidades_usuario.codesp')
					->where('especialidades_usuario.ideusr', '=', Input::get('ideusr') )
					->select('especialidades.*')
					->orderBy('especialidad', 'desc')
					->get();
		return Response::json(array('listaEspecialidades'=> $listaEspecialidades));
	}
	
	public function enviarEmailAcepta($ema, $nombre,$pass){
	
		// I'm creating an array with user's info but most likely you can use $user->email or pass $user object to closure later
	
	
		$userAdm = array(
				//'email'=> $sesMail,
				'email' => 'noreply@policlinicoquirurgico.com',
				'name'=>   'Centro Policlinico Quirurgico',
				'emailDes'=> $ema,
				'nameDes'=>   $nombre
		);
	
		// the data that will be passed into the mail view blade template
		$data = array(
				'detail'=>'Nos complace anuncarle que su solicitud de alta fue aceptada. Podrá acceder usando su email y con la siguiente contraseña:',
				'nombre'	=> $nombre,
				'pass' => $pass
		);
	
	
	
		// use Mail::send function to send email passing the data and using the $user variable in the closure
		Mail::send('emails.welcome', $data, function($message) use ($userAdm)
		{
			$message->from($userAdm['email'], $userAdm['name']);
			$message->to($userAdm['emailDes'], $userAdm['nameDes'])->subject('Acceso a Gesmeweb');
		});
	
		return 'OK';
	
	}
	
	public function obtenerDatosInitAlta(){
	
		$rol = Rol::where('nomrol','<>', 'Administrador')->get();
		$esp = Especialidad::all();
		$empresasUsu = '';
		$espeUsu = '';
		if(Input::get('id')){
			$espeUsu = EspecialidadUsuario::where('ideusr', '=', Input::get('id'))->get();
			$empresasUsu = app('App\Http\Controllers\empresasController')->getEmpresaByIdUsr(Input::get('id'));
		}
		return Response::json(array('roles'=> $rol, 'especialidades'=> $esp, 'especialidadesUsuario'=> $espeUsu, 'empresasUsu'=> $empresasUsu));
	}
	
	public function altaManualUsr(){
		
		/****** USUARIO **********/
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		
		$usu = new Usuario();
		$usu->nomusr = 'Jesús Mª';
		$usu->apusr = 'Mirasol García';
		$usu->emailusr = 'jmirasol@hotmail.com';
		$usu->numtel1 = '';
		$usu->numtel2 = '';
		$usu->numcoleg = '070834246';
		$usu->dniusr = '';
		$usu->password = Hash::make('070834246');
		$usu->fecbajadmin =  $defaulfeccha->format('Y/m/d');
		$usu->fecaceptado = Carbon::now('Europe/Madrid')->format('Y/m/d');
		$usu->save();
		
		/****** ROL **********/
		$rolUsr = new RolUsuario();
		$rolUsr->ideusr = $usu->id;
		$rolUsr->codrol = 'MED';
		
		$rolUsr->save();
		
		/****** ESPECIALIDAD **********/
		$rolUsr = new EspecialidadUsuario();
		$rolUsr->ideusr = $usu->id;
		$rolUsr->codesp = 'ARE';
			
		$rolUsr->save();
		
		return $usu->nomusr;
		
	}
	 
	public function altaUsuario(){
	
		$usu = '';
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
		if(!Input::get('id')){
			$usuAux = Usuario::where('emailusr', '=', Input::get('emailusr'))
			->where('fecbajadmin', '>', Carbon::now('Europe/Madrid')->format('Y/m/d') )->get()->first();
			if(sizeof($usuAux) > 0){
				return Response::json(array('msgError'=>'Ya existe un usuario dado de alta para esta direccion de correo'));
			}
	
	
			$usu = new Usuario();
		}else{
			$usu = Usuario::where('id', '=', Input::get('id'))->get()->first();
		}
	
		$usu->nomusr = Input::get('nomusr');
		$usu->apusr = Input::get('apusr');
		$usu->emailusr = Input::get('emailusr');
		$usu->numtel1 = Input::get('numtel1');
		$usu->numtel2 = Input::get('numtel2');
		$usu->numcoleg = Input::get('numcoleg');
		$usu->tipdoc = Input::get('tipdoc');
		$usu->dniusr = Input::get('dniusr');
		if(!Input::get('id')){
			if (!Input::get('esPrivate') && Input::get('esPrivate') == "S"){
				$passAutogen = $this->generarPass();
				$usu->password = Hash::make($passAutogen);
				$usu->fecbajadmin =  $defaulfeccha->format('Y/m/d');
				$usu->fecaceptado = Carbon::now('Europe/Madrid')->format('Y/m/d');
			}else{
				$usu->fecbajadmin = $defaulfeccha->format('Y/m/d');
				$usu->fecaceptado = $defaulfeccha->format('Y/m/d');
			}
		}
	
		//return $usu;
		$usu->save();
	
		//Se borran los todos los roles y especializades del usuario y se insertan las nuevas.
	
		RolUsuario::where('ideusr', '=', $usu->id)->delete();
		EspecialidadUsuario::where('ideusr', '=', $usu->id)->delete();
		EmpresaUsu::where('idusu', '=', $usu->id)->delete();
	
		foreach(Input::get('rolesSelec') as $t){
			$rolUsr = new RolUsuario();
			$rolUsr->ideusr = $usu->id;
			$rolUsr->codrol = $t;
				
			$rolUsr->save();
	
		}
		
		foreach(Input::get('empresaSelect') as $t){
			$eUsu = new EmpresaUsu();
			$eUsu->idempresa = $t;
			$eUsu->idusu = $usu->id;
			$eUsu->fecbaj = Carbon::create(3000, 12, 31, 0, 0, 0)->format('Y/m/d');
			$eUsu->save();
		
		}
	
		if(Input::get('espeSelect')){
			foreach(Input::get('espeSelect') as $t){
				$rolUsr = new EspecialidadUsuario();
				$rolUsr->ideusr = $usu->id;
				$rolUsr->codesp = $t;
					
				$rolUsr->save();
					
			}
		}
	
	
	
		return Response::json(array('usuario'=> $usu));
	}
	
	public function obtenerUsuariosByMail($email){
		return Usuario::where('emailusr', '=', $email)->get()->first();
	}
	
	public function obtenerUsuarios(){
	
		$bajaUsr = '>';
		if(Input::get('verusrbaja') == 'true'){
			$bajaUsr = '<=';
		}
	
		$usu = Usuario::
		where('fecbajadmin',$bajaUsr, Carbon::today()->toDateString() );
		if(Input::get('esUsrAdm') == 'false')
			$usu = $usu->where('numcoleg','<>', '')->whereNotNull('numcoleg');
		$usu = $usu->get();
		$roles = Rol::all();
	
		$rolesusr = RolUsuario::all();
	
		return Response::json(array('usuario'=>$usu,'roles'=>$roles , 'rolesusr'=>$rolesusr , 'msgOk'=> 'OK'));
	}
	
	public function aceptarUsuario(){
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
	
		$usu =  Usuario::find(Input::get('id'));
	
		$passAutogen = $this->generarPass();
	
		$usu->password = Hash::make($passAutogen);
		$usu->fecbajadmin =  $defaulfeccha->format('Y/m/d');
		$usu->fecaceptado = Carbon::now('Europe/Madrid')->format('Y/m/d');
	
		$usu->save();
	
		$nombreCompletoUsr = $usu->nomusr . " " . $usu->apusr;
	
		$ok = $this->enviarEmailAcepta($usu->emailusr,  $nombreCompletoUsr, $passAutogen);
	
		return Response::json(array('usuario'=>$usu));
	}
	
	public function bajaUsuario(){
	
		$usu =  Usuario::find(Input::get('id'));
		$usu->fecbajadmin =  Carbon::now('Europe/Madrid')->format('Y/m/d');
		$usu->save();
		return Response::json(array('usuario'=>$usu));
	}
	
	public function readmitirUsuario(){
		$defaulfeccha = Carbon::create(3000, 12, 31, 0, 0, 0);
	
		$usu =  Usuario::find(Input::get('id'));
		$usu->fecbajadmin =  $defaulfeccha->format('Y/m/d');
		$usu->save();
	
		return Response::json(array('usuario'=>$usu));
	}
	
	public function actualizarDatosUsuario(){
	
		$usuAux = Usuario::where('id', '<>', Input::get('id'))
		->where('emailusr', '=', Input::get('emailusr'))->get()->first();
		if(sizeof($usuAux) > 0){
			return Response::json(array('msgError'=>'Ya existe un usuario dado de alta para esta direccion de correo'));
		}
	
	
		$usu = Usuario::where('id', '=', Input::get('id'))->get()->first();
		$usu->nomusr = Input::get('nomusr');
		$usu->apusr = Input::get('apusr');
		$usu->emailusr = Input::get('emailusr');
		$usu->numtel1 = Input::get('numtel1');
		$usu->numtel2 = Input::get('numtel2');
		$usu->numcoleg = Input::get('numcoleg');
		$usu->dniusr = Input::get('dniusr');
	
		$usu->save();
	
		return Response::json(array('usuario'=>$usu, 'msgOk'=> 'Se guardo correctamente'));
	}
	
	public function actualizarPass(){
	
		$usu = Usuario::where('id','=',Input::get('id'))->get()->first();
	
	
		if((sizeof($usu) > 0) && Hash::check(Input::get('oldPassword'), $usu->password)){
	
			$usu->password =  Hash::make(Input::get('newPassword'));
			$usu->save();
	
			return Response::json(array('msgOk'=>'Contraseña Actualizada'));
		}else {
			return Response::json(array('msgError'=>'La contraseña actual introducida no es correcta. Vuelva a intentarlo'));
		}
	
			
	}
	
	public function restablecerContrasena(){
	
		$usu = Usuario::where('emailusr','=',Input::get('emailusr'))
		->where('fecbajadmin', '>' , Carbon::today()->toDateString())->get()->first();
	
		if(sizeof($usu) > 0){
			$passAutogen = $this->generarPass();
			$usu->password =  Hash::make($passAutogen);
				
			$usu->save();
	
			$nombreCompletoUsr = $usu->nomusr . " " . $usu->apusr;
			// se le enviará un mail
			//$ok = $this->enviarEmailAcepta($usu->emailusr, $usu->aliasusr,  $nombreCompletoUsr, $passAutogen);
			return Response::json(array('msgOk'=>'Recibirá un correo con la nueva contraseña que se le ha generado.', 'pass' => $passAutogen));
		}
	
	
		return Response::json(array('msgErr'=>'No existe ningún usuario activo con esa cuenta de correo'));
			
	}
	
	public function buscarMedNomApCol(){
	
		$usu = Usuario::where('fecbajadmin', '>', Carbon::now('Europe/Madrid')->format('Y/m/d') )
		->whereNotNull('numcoleg')
		->where('numcoleg', '<>', '');
		
		if(Input::get('nomusr')){
			$usu = $usu->where("nomusr", "=", Input::get('nomusr'));
		}
		
		if(Input::get('apusr')){
			$usu = $usu->where("apusr", "like", "%".Input::get('apusr')."%");
		}
		
		if(Input::get('numcoleg')){
			$usu = $usu->where("numcoleg", "=", Input::get('numcoleg'));
		}
	
		$listaUsuarios = $usu = $usu->get();
	
		return Response::json(array('listaUsuarios'=>$listaUsuarios));
			
	}
	
	public function listaMedicos(){
		$meds = DB::table('usuarios')
					->leftJoin('roles_usuario', 'usuarios.id', '=', 'roles_usuario.ideusr')
					->whereIn('roles_usuario.codrol', array( 'MED','FIS', 'OTE' ))
					->where('usuarios.fecbajadmin', '>', Carbon::now('Europe/Madrid')->format('Y/m/d') )
					->select('usuarios.*')
					->orderBy('apusr', 'asc')
					->distinct()->get();
		return $meds;
	}
	
		public function listaMedicosEspecialidad(){
		$meds = DB::table('usuarios')
					->leftJoin('roles_usuario', 'usuarios.id', '=', 'roles_usuario.ideusr')
					->leftJoin('especialidades_usuario', 'usuarios.id', '=', 'especialidades_usuario.ideusr')
					->leftJoin('especialidades', 'especialidades_usuario.codesp', '=', 'especialidades.codesp')
					->whereIn('roles_usuario.codrol', array( 'MED','FIS', 'OTE' ))
					->where('usuarios.fecbajadmin', '>', Carbon::now('Europe/Madrid')->format('Y/m/d') )
					
					->select("usuarios.id",
								"usuarios.nomusr",
								"usuarios.apusr",
								"usuarios.emailusr",
								"usuarios.numcoleg",
								"usuarios.comentario", DB::raw('group_concat(especialidades.especialidad) as especialidades'))
					->orderBy('apusr', 'asc')->groupBy("usuarios.id",
								"usuarios.nomusr",
								"usuarios.apusr",
								"usuarios.emailusr",
								"usuarios.numcoleg",
								"usuarios.comentario")->get();
		return $meds;
	}
	
	public function listaOtrosUsr(){
		$meds = DB::table('usuarios')
		->leftJoin('roles_usuario', 'usuarios.id', '=', 'roles_usuario.ideusr')
		->leftJoin('roles', 'roles.codrol', '=', 'roles_usuario.codrol')
		->whereIn('roles_usuario.codrol', array( 'DUV', "RES", "INY", "SCU", "QUI", "ECO", "AEC", "API", "AAC", "APP", "RAX"))
		->where('usuarios.fecbajadmin', '>', Carbon::now('Europe/Madrid')->format('Y/m/d') )
		->select('usuarios.*', 'roles.nomrol', 'roles.codrol')
		->orderBy('apusr', 'asc')
		->distinct()->get();
		return $meds;
	}
	
	public function obtenerListaMed(){
		$listaMedicos = $this->listaMedicos();
		return Response::json(array('listaMedicos'=>$listaMedicos));
	}
	

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
