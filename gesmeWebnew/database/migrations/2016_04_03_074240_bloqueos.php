<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Bloqueos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bloqueos_citas', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->integer('idusr');
			$table->integer('idmed');
			$table->integer('idpac');
			$table->integer('hora');
			$table->date('feccita');
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
