@extends('layout.main')

@section('content')
<div class="albumBox box box-default">

  <div id="albumSingleContent" class="container" data-artista="{{ $album->artista }}" data-album="{{ $album->nome }}">
    <h1>{{ $album->artista }} - {{ $album->nome }}</h1>


    <div class="row">
      <div class="col-lg-4 col-xs-12">
        <!-- cover -->
        <img src="{{ $album->cover }}" alt="{{ $album->artista }} - {{ $album->nome }}">
      </div>
      <div class="col-lg-8 col-xs-12">
        <!-- dados -->
        <h3>Data de lançamento: {{ $album->ano }}</h3>
        <div id="trackList" class="col-lg-12"></div>
      </div>
    </div>


  </div>
</div>
@endsection
