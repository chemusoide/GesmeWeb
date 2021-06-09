<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Empresa extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('empresas', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->text('nombre');
			$table->date('fecbaj');
			$table->timestamps();
		});
		
		Schema::create('empresa_usu', function(Blueprint $table)
		{
			$table->integer('idempresa');
			$table->integer('idusu');
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
