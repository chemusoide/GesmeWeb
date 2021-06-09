<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ControlAccesos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('control_acceso', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->text('codacces');
			$table->integer('idusr');
			$table->integer('idpac')->nullable();
			$table->string('swiespecial',1);
			$table->string('comentario')->nullable();
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
