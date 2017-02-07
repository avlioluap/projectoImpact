@extends('layout.main')

@section('content')
<div class="albumBox box box-default">

  <div class="container">
    <h1>Adicionar artista</h1>
    <!-- mensagems de output -->
    <div id="messages" class="alert alert-info" role="alert"></div>

    <form id="inserirNovoAlbum" action="albums/insert">

      <div class="form-group">
        <label for="artista"><h3>Nome do artista</h3></label>
        <input type="text" class="form-control" id="artista" name="artista" placeholder="Inserir nome do artista">
        <a id="procArtista" class="btn btn-primary">Procurar artista</a>
      </div>

      <div id="albumInput" class="form-group">
          <label for="nome"><h3>Nome do album</h3></label>
          <input type="text" class="form-control" id="nome" name="nome" placeholder="Inserir nome do album">
          <a id="procAlbum" class="btn btn-primary">Procurar album</a>
      </div>

      <!-- dislay capa do album -->
      <div  class="albumCover">
        <img id="albumCover" src="" alt="">
      </div>

      <!-- tags para o album -->
      <div id="ablumTags" class="albumTags">

        <input type="text" class="form-control" id="tag" name="tag" placeholder="tags do album">
        <input type="text" class="form-control" id="ano" name="ano" placeholder="ano do album">
        <input type="text" class="form-control" id="url_api" name="url_api" placeholder="url_api do album">
        <input type="text" class="form-control" id="cover" name="cover" placeholder="cover do album">
      </div>

      <!-- inserir novo album -->
      <button id="insertAlbum" type="submit" class="btn btn-primary">Inserir album</button>

    </form>
  </div>
</div>
@endsection
