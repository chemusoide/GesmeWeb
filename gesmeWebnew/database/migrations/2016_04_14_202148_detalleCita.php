<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DetalleCita extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('detalles_cita', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->integer('idecita');
			$table->timestamp('fecinicita');
			$table->timestamp('fecfincita');
			$table->text('lineaConsulta');
			$table->text('diagnostico');
			$table->text('tratamiento');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
