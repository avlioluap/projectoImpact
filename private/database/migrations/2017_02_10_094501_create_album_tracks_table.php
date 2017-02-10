<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumTracksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('album_tracks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->bigInteger('albums_id')
						->references('id')
						->on('albums')
						->onDelete('cascade');
			$table->string('titulo');
			$table->integer('faixa');
			$table->string('lastFm_url');
			$table->string('youtube_id')->nullable();
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
		Schema::drop('album_tracks');
	}

}
