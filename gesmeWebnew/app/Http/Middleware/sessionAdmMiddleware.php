<?php namespace App\Http\Middleware;

use Closure;
use Session;
use App\Usuario;

class sessionAdmMiddleware {


	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if(sizeof(Session::get('usuario.email')) == 0)
			return redirect('/');

		//$usu =  Usuario::where('emailusr', '=', Session::get('usuario.email'))->get()->first();

		return $next($request);
	}

}
