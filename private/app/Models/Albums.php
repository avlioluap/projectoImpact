<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Albums extends Model {

	//
  protected $fillable = array('id_user', 'nome', 'artista', 'ano', 'tag', 'url_api', 'cover');

}
