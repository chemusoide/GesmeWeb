<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Usuario extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
			Schema::create('usuarios', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->string('nomusr',100);
			$table->string('apusr',100);
			$table->string('emailusr',100);
			$table->integer('numtel1');
			$table->integer('numtel2');
			$table->string('dniusr',20);
			$table->string('password',60);
			$table->string('numcoleg',100);
			$table->binary('urlFot');
			$table->date('fecbajadmin'); // fecha en la que le dio de baja el admin
			$table->date('fecaceptado'); //fecha en la que fue aceptado
			$table->text('comentario');
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
