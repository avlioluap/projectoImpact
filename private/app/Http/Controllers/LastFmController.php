<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Input;

class LastFmController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function getAllAlbumsFromArtist()
	{
		$url = "http://ws.audioscrobbler.com/2.0/?method=artist.gettopalbums&artist=".Input::get('artista')."&api_key=".env('API_KEY')."&format=json";
		$json = file_get_contents($url);
		$obj = json_decode($json);

		return  \Response::json($obj);
	}

	public static function getTracksFromAlbum($album){
		
		$url = "http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=".env('API_KEY')."&artist=".$album->artista."&album=".$album->nome."&format=json";
		$json = file_get_contents($url);
		$obj = json_decode($json);

		return  $obj;
	}


}
