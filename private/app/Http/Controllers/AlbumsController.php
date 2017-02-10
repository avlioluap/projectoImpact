<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LastFmController AS LastFM;
use App\Http\Controllers\AlbumsTracksController AS Tracks;

use App\Models\Albums as Album;
use App\Models\AlbumTracks as Songs;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Impactwave\Razorblade\Form;
use Input;
use Redirect;
use View;

class AlbumsController extends Controller {

	public function __construct ()
  {
    $this->middleware ('auth');
  }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function albums()
	{
		//obter albums do user
		$albums = Album::where('user_id', \Auth::id())
		->get();

		return View::make('albums.list', compact('albums'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view ('albums.create');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function search()
	{

		$searchAlbums = Album::where('user_id', \Auth::id())
    ->where('nome', 'ILIKE', '%' . Input::get('searchAlbums') . '%')
		->orWhere('artista', 'ILIKE', '%' . Input::get('searchAlbums') . '%')
		->orWhere('tag', 'ILIKE', '%' . Input::get('searchAlbums') . '%')
		->get();

		return view ('albums.search', compact('searchAlbums'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if($request->ajax()){
			$nome = Input::get('nome');
			$artista = Input::get('artista');

			//procurar se ja inseriu esse album
			$matchThese = ['user_id' => \Auth::id(), 'nome' => $nome, 'artista' => $artista];

			$searchAlbum = Album::where($matchThese)
			->first();

			//caso ja exista entrada devolver mensagem
			if ($searchAlbum) {
				return \Response::json([
					'state' => 'fail',
					'msg' => 'Ja existe uma entrada com esse nome'
				]);
			}

			\DB::beginTransaction();
			try {
				//parse dados do json
				$url = "http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=".env('API_KEY')."&artist=".$artista."&album=".$nome."&format=json";
				$json = file_get_contents($url);
				$obj = json_decode($json);
				//guardar album
				$novoAlbum = new Album;
		    $novoAlbum->nome = $obj->album->name;

		    if (isset($obj->album->wiki->published)) {
		      $novoAlbum->ano  = $obj->album->wiki->published;
		    }

		    //tags
		    $novoAlbum->tag  = Album::getTags($obj->album->tags->tag);

		    $novoAlbum->artista = $obj->album->artist;
		    $novoAlbum->user_id = \Auth::id();
		    $novoAlbum->url_api = $url;
		    $novoAlbum->cover = $obj->album->image[2]->{'#text'};

		    $novoAlbum->save();

				//procurar as tracks do album
				Tracks::insertTracksFromAlbum($novoAlbum->id, $obj->album->artist, $obj->album->name);

				\DB::commit();
				$success = true;
			} catch (\Exception $e) {

				\DB::rollback();
				$success = false;
			}
			//devolver mensagem de successo
			if ($success) {
				//retorn mensagem de confirmação
				return  \Response::json([
					'state' => 'inserido',
					'msg' => 'Album inserido com successo'
				]);
			}
    }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$videoUrl = "https://www.youtube.com/embed?playlist=";
		//obter dados do album e das tracks
		$album = Album::where('id', $id)->first();
		$songs = Songs::where('albums_id', $album->id)->orderBy('faixa')->get();

		foreach ($songs as $song) {
			$videoUrl = $videoUrl.",".$song['youtube_id'];
		}

		return View::make('albums.single', compact('album', 'songs', 'videoUrl'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
