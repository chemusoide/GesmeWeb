<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ParametrosGesmeweb extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('parametros_gesmeweb', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->text('tipo'); //CONFIGURACION, MOVIMIENTOS...
			$table->text('coddom'); 
			$table->text('desval'); 
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
