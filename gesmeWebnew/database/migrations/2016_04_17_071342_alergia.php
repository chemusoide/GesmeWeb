<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alergia extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('alergia_usuario', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->text('codalergia');
			$table->text('alergia');
			$table->integer('idecpac');
			$table->integer('idemedcrea');
			$table->integer('idemedbaj');
			$table->timestamp('feccrea');
			$table->timestamp('fecbaj');
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
