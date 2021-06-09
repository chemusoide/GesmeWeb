<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Citas extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('citas', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->integer('idusr');
			$table->integer('idpac');
			$table->integer('hora');
			$table->date('feccita');
			$table->string('codesp');
			$table->integer('codestado');
			$table->integer('durcon');
			$table->date('fecbaja');
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
