<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgendaMed extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('confi_agenda', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->integer('idusr');
			$table->string('diaseml',1);
			$table->string('diasemm',1);
			$table->string('diasemx',1);
			$table->string('diasemj',1);
			$table->string('diasemv',1);
			$table->string('diasems',1);
			$table->string('diasemd',1);
			$table->integer('horaini');
			$table->integer('minini');
			$table->integer('horafin');
			$table->integer('minfin');
			$table->integer('durcon');
			$table->date('fecbajadmin');
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
