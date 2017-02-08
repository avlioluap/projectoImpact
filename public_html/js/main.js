var api_key = '4b07c3fad8d2567ab1fa1ae07ba73319';

function errorMsg( msg ) {
  var html = '<span class="warningMsg label alert">'+ msg +'</span>';
	$("#messages").empty();
	$('#messages').html(html).fadeIn();
}

function searchArtistApi( artistName ) {
  var urlApi = 'http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist='+ artistName +'&api_key='+ api_key +'&format=json';
	return $.ajax({
		method: 'GET',
		url: urlApi
	});
}

function searchAlbumApi ( artistName, albumName ) {
  var urlApi = 'http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key='+ api_key +'&artist='+ artistName +'&album='+ albumName +'&format=json';
  return $.ajax({
		method: 'GET',
		url: urlApi
	});
}

function insertAlbumAjax ( url , dados) {
  return $.ajax({
		method: 'GET',
		url: url,
    data: dados,
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
	});
}

function searchArtist(){

  $("#procArtistaBtn").on('click', function (){

    var artistName = $("#artistaProc").val();

    //limpa as esolhas antigas se tiver
    $('#artistImgBlocks').html('');

		if ( $("#artistaProc").val().length == 0 ) {
			//input his empty, output warning text
			errorMsg("Adicione um artista que quer inserir.");
			return;
		}

    //search for the keyword typed in the input
		searchArtistApi( artistName ).done(function( data ) {
      //oops não encontrei registo valido na api devolver mensagem
      if ( data.error == 6 ) {
        errorMsg("Não foi encontrado o artista que pretende.");
        return;
      } else {
        //mostrar img da escolha e escrever no input o nome
        $("#artista").val(data.artist.name);
        html = '<div class="artistImgBlock"><img src="'+data.artist.image[2]['#text']+'" data-name="'+data.artist.name+'" class="artistImg active"><span>'+data.artist.name+'</span></div>';
        $('#artistImgBlocks').append(html);
        //gerar escolhas alternativas
        $.each(data.artist.similar.artist, function(index, val) {
          html = '<div class="artistImgBlock"><img src="'+val.image[2]['#text']+'" data-name="'+val.name+'" class="artistImg"><span>'+val.name+'</span></div>';
          $('#artistImgBlocks').append(html);

        });
        //aparecer a opção para escolher o album
        $("#albumInput").fadeIn();
      }

		}).fail(function() {
			errorMsg("Someting went wrong please try again!!!");
		});

  });
}

function clickArtistImg(){
  $("#artistImgBlocks").on('click', '.artistImg', function(){
    $("#artista").val($(this).attr('data-name'));
    $(".artistImg").removeClass("active");
    $(this).addClass('active');
  });
}

function searchAlbum(){
  $("#procAlbum").on('click', function() {

      var albumName = $("#nome").val();
      var artistName = $("#artista").val();
      var urlApi = 'http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key='+ api_key +'&artist='+ artistName +'&album='+ albumName +'&format=json';

      if ($("#nome").val().length == 0) {
          //input his empty, output warning text
          errorMsg("Adicione o nome do album que quer inserir.");
          return;
      }
      //search for the keyword typed in the input
      searchAlbumApi(artistName, albumName).done(function(data) {
          //oops não encontrei registo valido na api devolver mensagem
          if (data.error == 6) {
              errorMsg("Não foi encontrado o album que pretende.");
              return;
          } else {
              //mostrar a capa do album
              urlImg = data.album.image[2]['#text'];
              //$("#albumCover").attr('src', urlImg);
              html = '<div class="albumImgBlock"><img src="'+urlImg+'" data-name="" class="albumImg active"><span></span></div>';
              $('#albumImgBlocks').html('').append(html);

              //guardar link da img
              $("#cover").val(urlImg);
              //gerar tags
              $("#ablumTags").html('');
              $.each(data.album.tags.tag, function(index, val) {
                  html = '<span class="btn btn-default">' + val.name + '</span>';
                  $("#ablumTags").append(html);
                  if (index > 0) {
                      $('#tag').val($('#tag').val() + '-' + val.name);
                  } else {
                      $('#tag').val($('#tag').val() + val.name);
                  }
              });
              //release do album
              //$("#ano").val(data.album.wiki.published);
              //url_api do album
              //$("#url_api").val(urlApi);
              //mostrar botao para submeter form
              $("#insertAlbum").fadeIn();
          }
      }).fail(function() {
          errorMsg("Someting went wrong please try again!!!");
      });

  });
}

function insertAlbum( url, formData ){
  insertAlbumAjax(url, formData).done(function(data) {
    //dar mensagem que inseriu novo album

      errorMsg(data.msg);

  }).fail(function() {
      errorMsg("Someting went wrong please try again!!!");
  });
}

function searchTracks(artistName, albumName){

  searchAlbumApi(artistName, albumName).done(function(data) {

    console.log(data);
    $.each(data.album.tracks.track, function(index, val) {

        html = '<a href="'+val.url+'" target="_blank" class="songs">' +
        '<span class="glyphicon glyphicon-headphones" aria-hidden="true">' +
        '</span> : <span class="trackList">' + val.name +'</span>' +
        '</a>';

        $("#trackList").append(html);

    });

  }).fail(function() {
      errorMsg("Someting went wrong please try again!!!");
  });
}

$(document).ready(function() {
  //esconder campos
  $("#albumInput").hide();
  $("#insertAlbum").hide();
  $("#messages").hide();

  //procurar artista
  searchArtist();
  //click da imagem altera  o nome de pesquisa
  clickArtistImg();
  //procurar album
  searchAlbum();

  //adicionar album
  $("#inserirNovoAlbum").submit( function( event ) {
    if ( $("#nome").val().length != 0 &&  $("#artista").val().length != 0 ) {
      formData = $(this).serialize();
      url = $(this).attr('action');
      insertAlbum( url, formData );
    }
		event.preventDefault();
	});

  if ( $("#albumSingleContent").length ) {
    //procurar a lista de tracks
    searchTracks( $("#albumSingleContent").attr('data-artista'), $("#albumSingleContent").attr('data-album') );
  }
});
