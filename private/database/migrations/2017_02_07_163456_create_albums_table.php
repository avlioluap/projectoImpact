<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('albums', function(Blueprint $table)
		{
			$table->increments('id');
			$table->bigInteger('user_id')
						->references('id')
						->on('users')
						->onDelete('cascade');
			$table->string('nome');
			$table->string('ano')->nullable();
			$table->string('artista');
			$table->string('cover');
			$table->string('tag');
			$table->string('url_api');
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
		Schema::drop('albums');
	}

}
