<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Albums as Album;
use App\Models\AlbumTracks as Song;

use Illuminate\Http\Request;

class AlbumsTracksController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public static function insertTracksFromAlbum($album_id, $artista, $album)
	{
		$url = "http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=".env('API_KEY')."&artist=".$artista."&album=".$album."&format=json";
		$json = file_get_contents($url);
		$obj = json_decode($json);

		foreach ($obj->album->tracks->track as $value) {

			$novaSong = new Song();
			$novaSong->albums_id = $album_id;
			$novaSong->titulo = $value->name;
			$novaSong->faixa = $value->{'@attr'}->rank;
			$novaSong->lastFm_url = $value->url;

			//obter o youtube id
			$html = file_get_contents($value->url);

			$doc = new \DOMDocument();
			@$doc->loadHTML($html);

			$classname="image-overlay-playlink-link";
			$finder = new \DomXPath($doc);
			$spaner = $finder->query("//*[contains(@class, '$classname')]");

			//$tags = $doc->getElementsByTagName('a.image-overlay-playlink-link');
			foreach ($spaner as $tag) {
				 $novaSong->youtube_id = $tag->getAttribute('data-youtube-id');
				 break;
			}


			$novaSong->save();

		}
	}



}
