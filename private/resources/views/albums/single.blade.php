@extends('layout.main')

@section('content')
<div class="albumBox box box-default">
  <div id="albumSingleContent" class="container" data-artista="{{ $album->artista }}" data-album="{{ $album->nome }}" data-playlist-url="{{ $videoUrl }}">
    <h2>{{ $album->artista }} - {{ $album->nome }}</h2>
    <!-- dados -->
    <h4>Data de lançamento:
      @if($album->ano)
      {{ $album->ano }}
      @else
      ---
      @endif
    </h4>
    <div class="row">
      <div class="col-lg-3 col-xs-12 cover">
        <!-- cover -->
        <img src="{{ $album->cover }}" alt="{{ $album->artista }} - {{ $album->nome }}">
        <div id="trackList" class="col-lg-12">
          @foreach ($songs as $song)
          <span class="trackList">{{ $song->faixa }}: {{ $song->titulo }}</span>
          @endforeach
        </div>
      </div>
      <div id="player" class="col-lg-9 col-xs-12"></div>
    </div>
  </div>
</div>
@endsection
