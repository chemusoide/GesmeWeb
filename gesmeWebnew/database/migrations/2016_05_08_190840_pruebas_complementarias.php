<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PruebasComplementarias extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prueba_complemtaria', function(Blueprint $table)
		{
		$table->increments('id')->index();
			$table->text('tipprueba');
			$table->text('tipo');
			$table->text('archivo');
			$table->text('observacion');
			$table->timestamp('fechabaja')->nullable();
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
