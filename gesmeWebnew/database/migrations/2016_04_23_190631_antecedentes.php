<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Antecedentes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('antecedentes_pac', function(Blueprint $table)
		{
		$table->increments('id')->index();
			$table->text('codant');
			$table->text('desant');
			$table->text('obsant')->nullable();
			$table->integer('idpac');
			$table->integer('idcita');
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
