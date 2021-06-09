<?php namespace App\Http\Middleware;

use Closure;
use Session;

class sessionMiddleware {


	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (!$request->secure()) {
			$rutaMod = str_replace("/public","",$request->getRequestUri()); 
                return redirect()->secure($rutaMod);
         }
		if(sizeof(Session::get('usuario.email')) == 0)
			return redirect('/');
		return $next($request);
	}

}
