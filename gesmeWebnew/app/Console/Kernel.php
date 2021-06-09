<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Command;

use App\Usuario;
use Carbon\Carbon;
use DB;
use Mail;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		
		$schedule->call(function () {

			$diaAviso1 = 1;
			$diaAviso2 = 10;

			$fecActual = Carbon::now();
			$mes = $fecActual->month;
			$dia = $fecActual->day;
			$anyo = $fecActual->year;
			

			 DB::table('logs')->delete();

			$usuarios = DB::table('usuarios')
            ->where('fecbajadmin', '>',Carbon::now()->format('Y/m/d'))
            ->where('feccaduca', '>',Carbon::now()->format('Y/m/d'))
            ->where('fecaceptado', '<=',Carbon::now()->format('Y/m/d'))
            ->where('rolusr', '=', 'Alumno')
            ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('pagos')
                  ->whereRaw('usuarios.id = pagos.idusu')
                  ->where('pagos.estadopago', '=', 'PAGADO' )
                  ->where('pagos.mes', '=',  Carbon::now()->month )
                  ->where('pagos.anyo', '=', Carbon::now()->year);
        	})->get();
			

            foreach($usuarios as $usu){
            	
            	$mandarMail = false;
            	$msg = "";

            	if($usu->diadisconectusr == $dia){
            		$msg = 'Se ha procedido a dar de baja su usuario ya que no pago su mensualidad. Para cualquier consulta ponganse en contacto con la academa.';
            		$mandarMail = true;
            		DB::table('usuarios')
		            ->where('id',$usu->id)
		            ->update(['feccaduca'=> Carbon::now()->format('Y/m/d')]);

            	}else if($dia == $diaAviso1){
            		$msg = 'Aun no ha realizado el pago de la mensualidad. Si desea continuar accediendo a la plataforma deberá realzar el pago.';
            		$mandarMail = true;

            	}else if($dia == $diaAviso2){
            		$msg = 'Aun no ha realizado el pago de la mensualidad, Se procedera a dar de baja su usuario en los proximos días. Si desea continuar accediendo a la plataforma deberá realzar el pago.';
            		$mandarMail = true;

            	}

            	// Si se cumple alguno de los requisitos se mandará el email
            	if($mandarMail == true){

            		/**********/

            		$userAdm = array(
						//'email'=> $sesMail,
						'email' => 'noreply@dpicode.com',
						'name'=>   'Dpicode',
						'emailDes'=> $usu->emailusr,
						'nameDes'=>   $usu->nomusr . " " . $usu->apusr
					);

					// the data that will be passed into the mail view blade template
					$data = array(
						'detail'=>$msg
					);

					

					// use Mail::send function to send email passing the data and using the $user variable in the closure
					Mail::send('emails.baja', $data, function($message) use ($userAdm)
					{
					  $message->from($userAdm['email'], $userAdm['name']);
					  $message->to($userAdm['emailDes'], $userAdm['nameDes'])->subject('REMIUS - Aviso automático');
					});

            		/*************/

            	}

             }
           
        })->daily(); 
	}



		


}
