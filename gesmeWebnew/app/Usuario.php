<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Usuario extends Model implements AuthenticatableContract, CanResetPasswordContract {
	
	use Authenticatable, CanResetPassword;
	//
	protected $table = 'usuarios';

	protected $fillable = [	'id',
							'nomusr',
							'apusr',
							'dirusr',
							'fecnacusr',
							'numtelfijusr',
							'numtelmovusr',
							'emailusr',
							'nvlformausr',
							'profusr',
							'switrabaja',
							'horprefusr',
							'urlFot',
							'fecbajadmin',
							'feccaduca',
							'rolusr',
							'fecaceptado',
							'aliasusr',
							'dniusr'
							];

	

	protected $hidden = ['password', 'remember_token'];

	
}
