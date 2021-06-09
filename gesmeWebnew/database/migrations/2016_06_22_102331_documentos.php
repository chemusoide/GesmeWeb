<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Documentos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('documentos', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->text('nombre');
			$table->text('html');
			$table->text('tipo');
			$table->date('fecbaj');
			$table->timestamps();
		});
		
		
		Schema::create('config_documentos', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->text('iddoc');
			$table->text('dato');
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
