<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Visitas extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('visitas', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->integer('idusr');
			$table->text('idpac');
			$table->string('codpru');
			$table->string('obs');
			$table->integer('codestado');
			$table->date('fecbaj');
			$table->timestamps();
		});
		
		Schema::create('pruebas', function(Blueprint $table)
		{
			
			$table->string('codpru');
			$table->string('tipo');
			$table->date('fecbaj');
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
