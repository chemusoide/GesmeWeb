<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pacientes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pacientes', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->string('nompac',100);
			$table->string('ap1pac',100);
			$table->string('ap2pac',100);
			$table->date('fecnacpac');
			$table->integer('numtel1');
			$table->integer('numtel2');
			$table->string('dniusr',20);
			$table->string('sexpac',1);
			$table->string('emailpac',100);
			$table->string('dirpac',200);
			$table->string('cppac',10);
			$table->integer('idpais');
			$table->integer('idseguro');
			$table->string('numseg',100);
			$table->text('comentario');
			$table->timestamps();
		});
		
		Schema::create('paises', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->text('nompais');
			$table->timestamps();
		});
		
		Schema::create('aseguradoras', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->text('nomseguro');
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
