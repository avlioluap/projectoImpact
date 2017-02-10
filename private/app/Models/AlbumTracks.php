<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlbumTracks extends Model {

  protected $fillable = array('ablums_id', 'titulo', 'faixa', 'lastFm_url', 'youtube_id');

	//
  public function __construct()
  {

  }


}
