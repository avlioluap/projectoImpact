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
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if($request->ajax()){
			$data = Input::all();
			$data['id_user'] = \Auth::id();
			$album = Album::create($data);
			//return response()->json($data);
			return  \Response::json(['state' => 'inserido']);
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
		dd($album);
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
