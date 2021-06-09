<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirmaDocs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('docs_pacs_firma', function(Blueprint $table)
		{
			$table->increments('id')->index();
			$table->integer('idpac');
			$table->integer('iddoc');
			$table->text('stringdoc');
			$table->text('strfirma');
			$table->date('fecha');
			$table->timestamps();
		});
		
		Schema::table('docs_pacs_firma', function (Blueprint $table) {
			$table->integer('iddoc')->unsigned();
		    $table->foreign('iddoc')->references('id')->on('documentos');
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
