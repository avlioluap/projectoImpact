@extends('layout.main')

@section('content')

  <div class="albumBox box box-default">
    <div class="container">

      <div class="col-lg-12">
        <br>
      </div>

        <div class="col-lg-12">
          <!-- adicionar novo album -->
          <a href="{{ URL::to('albums/create') }}">
            <div class="info-box">
              <!-- Apply any bg-* class to to the icon to color it -->
              <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
              <div class="info-box-content">
                <span class="info-box-text"><h1>Adicionar Album</h1></span>
              </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
          </a>
        </div>

        <!-- search -->
        <div class="col-lg-12">
          <form id="searchMyAlbums" action="albums/search" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="searchAlbums" class="form-control" placeholder="Pesquisa...">
                <span class="input-group-btn">
                  <button type="submit" name="seach" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                  </button>
                </span>
            </div>
          </form>
        </div>

        <!-- lista de albums -->
        <div class="col-lg-12">
          <div class="row">
            <!-- TODO foreach aos albums -->
            @foreach ($albums as $album)
            <a href="{{ URL::to("albums/show/$album->id") }}">
              <div class="albumThumb col-lg-3 col-md-4 col-xs-12">
                  <div class="albumCover">
                    <img src="{{ $album->cover }}" alt="nome do album">
                  </div>
                  <div class="albumViewInfo">
                    <a href="{{ URL::to("albums/show/$album->id") }}" class="btn btn-danger btn-flat">Ver info</a>
                  </div>
              </div>
            </a>
            @endforeach
          </div>
        </div>

        <!-- pagination -->
        <div class="col-lg-12">
          pagination
        </div>
    </div>
  </div>

@endsection
