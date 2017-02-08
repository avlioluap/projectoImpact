<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Impactwave\Razorblade\Form;
use App\Models\Albums as Album;
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
		$albums = Album::where('id_user', \Auth::id())
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
		$searchAlbums = Album::where('id_user', \Auth::id())
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
			$matchThese = ['id_user' => \Auth::id(), 'nome' => $nome, 'artista' => $artista];

			$searchAlbum = Album::where($matchThese)
			->first();

			//caso ja exista entrada devolver mensagem
			if ($searchAlbum) {
				return \Response::json([
					'state' => 'fail',
					'msg' => 'Ja existe uma entrada com esse nome'
				]);
			}
			//parse dados do json
			$key = "4b07c3fad8d2567ab1fa1ae07ba73319";
			$url = "http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=".$key."&artist=".$artista."&album=".$nome."&format=json";
			$json = file_get_contents($url);
			$obj = json_decode($json);

			$novoAlbum = new Album;
	    $novoAlbum->nome = $obj->album->name;
	    $novoAlbum->ano  = $obj->album->wiki->published;
	    //tags
	    $novoAlbum->tag  = Album::getTags($obj->album->tags->tag);

	    $novoAlbum->artista = $obj->album->artist;
			$novoAlbum->id_user = \Auth::id();
			$novoAlbum->url_api = $url;
			$novoAlbum->cover = Input::get('cover');

			$novoAlbum->save();
			//retorn mensagem de confirmação
			return  \Response::json([
				'state' => 'inserido',
				'msg' => 'Album inserido com successo'
			]);
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
		//
		$album = Album::where('id', $id)->first();

		return View::make('albums.single', compact('album'));
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
