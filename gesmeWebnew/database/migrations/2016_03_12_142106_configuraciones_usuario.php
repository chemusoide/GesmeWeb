<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConfiguracionesUsuario extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('especialidades', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->string('codesp',100);
			$table->text('especialidad');
			$table->timestamps();
		});
		
		Schema::create('especialidades_usuario', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->string('codesp',100);
			$table->integer('ideusr');
			$table->timestamps();
		});
		
		
		Schema::create('roles', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->string('codrol',100);
			$table->text('nomrol');
			$table->timestamps();
		});
		
		
		Schema::create('roles_usuario', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->string('codrol',100);
			$table->integer('ideusr');
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
