@extends('layout.main')

@section('content')
<div id="LoadingImage"></div>
<div class="albumBox box box-default">

  <div class="container">
    <h1>Adicionar artista</h1>
    <!-- mensagems de output -->
    <div id="messages" class="alert alert-info" role="alert"></div>

    <form id="inserirNovoAlbum" action="albums/insert">

      <div class="form-group">
        <label for="artistaProc"><h3>Nome do artista</h3></label>
        <div class="row">
          <div class="col-lg-8">
            <input type="text" class="form-control" id="artistaProc" name="artistaProc" placeholder="Inserir nome do artista">
            <input type="hidden" class="form-control" id="artista" name="artista" placeholder="nome do artista escolhido">
          </div>
          <div class="col-lg-4">
            <a id="procArtistaBtn" class="btn btn-primary">Procurar artista</a>
          </div>
        </div>
      </div>

      <!-- list de escolhas de artistas dados pela api -->
      <div class="form-group">
        <div id="artistImgBlocks"></div>
      </div>

      <!-- escolha do album consoante a escolha anterior -->
      <div id="albumInput" class="form-group">
          <label for="nome"><h3>Nome do album</h3></label>
          <div class="row">
            <div class="col-lg-8">
              <input type="text" class="form-control" id="nome" name="nome" placeholder="Inserir nome do album">
              <input type="hidden" class="form-control" id="cover" name="cover" placeholder="cover do album">
            </div>
            <div class="col-lg-4">
              <a id="procAlbum" class="btn btn-primary">Procurar album</a>
            </div>
          </div>
      </div>

      <!-- escolha da capa do album consoante as escolhas da api -->
      <div id="albumImgBlocks" class="row">

        <!--
        <div id="albumImgBlock" class="col-lg-4"></div>
        <div id="ablumTags" class="albumTags" class="col-lg-8"></div>
        -->
      </div>

      <!-- inserir novo album -->
      <button id="insertAlbum" type="submit" class="btn btn-primary">Inserir album</button>

    </form>
  </div>
</div>
<div class="defaultAlbumImgBlock col-lg-3" data-name="null">
  <img src="" alt="" class="albumImg">
  <span class="albumName"></span>
  <span class="albumTags"></span>
</div>
@endsection
