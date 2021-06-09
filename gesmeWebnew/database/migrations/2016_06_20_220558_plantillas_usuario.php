<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PlantillasUsuario extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plantillas_usuario', function(Blueprint $table)
		{
		$table->increments('id')->index();
			$table->integer('idusr');
			$table->text('tituloplantilla');
			$table->text('txtplantilla');
			$table->date('fechabaja');
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
