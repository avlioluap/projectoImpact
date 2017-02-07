var api_key = '4b07c3fad8d2567ab1fa1ae07ba73319';

function errorMsg( msg ) {
  var html = '<span class="warningMsg label alert">'+ msg +'</span>';
	$("#messages").empty();
	$('#messages').html(html);
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
  $("#procArtista").on('click', function (){
    var artistName = $("#artista").val();

		if ( $("#artista").val().length == 0 ) {
			//input his empty, output warning text
			errorMsg("Adicione um artista que quer inserir.");
			return;
		}

    //search for the keyword typed in the input
		searchArtistApi( artistName ).done(function( data ) {
      //do stuff
      //oops não encontrei registo valido na api devolver mensagem
      if ( data.error == 6 ) {
        errorMsg("Não foi encontrado o artista que pretende.");
        return;
      } else {
        $("#albumInput").fadeIn();
      }


		}).fail(function() {
			errorMsg("Someting went wrong please try again!!!");
		});

  });
}

function searchAlbum(){
  $("#procAlbum").on('click', function() {
      var albumName = $("#nome").val();
      var artistName = $("#artista").val();
      var urlApi = 'http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key='+ api_key +'&artist='+ artistName +'&album='+ albumName +'&format=json';

      if ($("#nome").val().length == 0) {
          //input his empty, output warning text
          errorMsg("Adicione o album que quer inserir.");
          return;
      }
      //search for the keyword typed in the input
      searchAlbumApi(artistName, albumName).done(function(data) {
          //do stuff
          //oops não encontrei registo valido na api devolver mensagem
          if (data.error == 6) {
              errorMsg("Não foi encontrado o album que pretende.");
              return;
          } else {
              //mostrar a capa do album
              urlImg = data.album.image[2]['#text'];
              $("#albumCover").attr('src', urlImg);
              //guardar link da img
              $("#cover").val(urlImg);
              //gerar tags
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
              $("#ano").val(data.album.wiki.published);
              //url_api do album
              $("#url_api").val(urlApi);
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
    console.log(data);
  }).fail(function() {
      errorMsg("Someting went wrong please try again!!!");
  });
}

$(document).ready(function() {
  //esconder campos
  $("#albumInput").hide();
  $("#insertAlbum").hide();

  //procurar artista
  searchArtist();
  //procurar album
  searchAlbum();

  //adicionar album
  $("#inserirNovoAlbum").submit( function( event ) {
    formData = $(this).serialize();
    url = $(this).attr('action');
    insertAlbum( url, formData );
		event.preventDefault();
	});
});
