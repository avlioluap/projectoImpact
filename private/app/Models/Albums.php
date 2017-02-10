<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Albums extends Model {

	//
  protected $fillable = array('user_id', 'nome', 'artista', 'ano', 'tag', 'url_api', 'cover');

  public function __construct()
  {

  }

  public function albumTracks(){
    return $this->hasMany('App\AlbumTracks');
  }


  public static function getTags($tags){
    $tagValue = "";
    foreach ($tags as $key => $value) {
      if ($key > 0) {
        $tagValue .= "|".$value->name;
      } else {
        $tagValue .= $value->name;
      }

    }
    return $tagValue;
  }


}
