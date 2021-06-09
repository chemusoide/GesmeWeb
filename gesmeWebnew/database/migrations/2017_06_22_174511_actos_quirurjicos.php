<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActosQuirurjicos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('act_quirurgicos', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->integer('idusr');
			$table->text('idpac');
			$table->timestamp('fecfin')->nullable();
			$table->timestamp('fecint')->nullable();
			$table->timestamp('fecbaj')->nullable();
			$table->text('obsant')->nullable();
		});
		
		Schema::create('act_quirurgicos_cie', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->integer('idact');
			$table->integer('idcie');
			$table->timestamp('fecbaj')->nullable();
			$table->text('obsant')->nullable();
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
