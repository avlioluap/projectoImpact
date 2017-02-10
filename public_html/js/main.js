var api_key = '4b07c3fad8d2567ab1fa1ae07ba73319';
var allArtistAlbums;
var videoPlayList = "https://www.youtube.com/embed?playlist=";

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

function searchAllAlbums( artistName ){
  return $.ajax({
		method: 'GET',
		url: "lastFm/searchAllAlbums",
    data: { artista: artistName },
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
        //devolver uma listagem de albums do artista
        $('#LoadingImage').show();
        setTimeout(function() {
            getArtistAlbums( artistName );
            $('#LoadingImage').hide();
        }, 0);

      }

		}).fail(function() {
			errorMsg("Someting went wrong please try again!!!");
		});

  });
}

function clickArtistImg(){
  $("#artistImgBlocks").on('click', '.artistImg', function(){

    $("#artista").val($(this).attr('data-name'));
    $("#artistaProc").val($(this).attr('data-name'));

    $(".artistImg").removeClass("active");
    $(this).addClass('active');
    //devolver uma listagem de albums do artista
    $('#LoadingImage').show();
    setTimeout(function() {
        getArtistAlbums( $("#artista").val() );
        $('#LoadingImage').hide();
    }, 0);

  });
}

function clickAlbumImg(){
  $("#albumImgBlocks").on('click', '.albumImgBlock', function(){

    $("#nome").val( $(this).attr('data-name') );

    $(".albumImgBlock").removeClass("active");
    $(this).addClass('active');

    //devolver uma listagem de albums do artista
    //$('#LoadingImage').show();

    /*setTimeout(function() {
        searchAlbumsObject($("#nome").val());
        $('#LoadingImage').hide();
    }, 0);*/

    $("#cover").val( $(this).find('.albumImg').attr('src') );

    $("#insertAlbum").fadeIn();

  });
}

function getArtistAlbums( artistName ){

  //search for the keyword typed in the input
  searchAllAlbums( artistName ).done(function( data ) {
    //guarda o obj
    allArtistAlbums = data.topalbums.album;
    //clonar block de albumImgBlock
    searchAlbumsObject();

  }).fail(function() {
    errorMsg("Someting went wrong please try again!!!");
  });
}

function searchAlbumsObject(searchValue="") {

  var deafultBlock = $(".defaultAlbumImgBlock");
  //clear da div
  $("#albumImgBlocks").empty();

  $.each(allArtistAlbums, function(index, val) {
    if (searchValue != "") {

      if (val.name.toLowerCase().indexOf(searchValue.toLowerCase()) >= 0){

        if (val.name != "(null)" && val.image[2]['#text'] != "") {

          var clonedBlock = deafultBlock.clone().toggleClass('defaultAlbumImgBlock albumImgBlock');

          clonedBlock.appendTo('#albumImgBlocks');
          //cover
          clonedBlock.find('.albumImg').attr('src', val.image[2]['#text']);
          //nome do album
          clonedBlock.find('.albumName').html(val.name);
          //data-name
          clonedBlock.attr('data-name', val.name);
        }
      }
    } else {
      if (val.name != "(null)" && val.image[2]['#text'] != "") {

        var clonedBlock = deafultBlock.clone().toggleClass('defaultAlbumImgBlock albumImgBlock');
        clonedBlock.appendTo('#albumImgBlocks');
        //cover
        clonedBlock.find('.albumImg').attr('src', val.image[2]['#text']);
        //nome do album
        clonedBlock.find('.albumName').html(val.name);
        //data-name
        clonedBlock.attr('data-name', val.name);
      }
    }

  });
}

function searchAlbum(){
  $("#procAlbum").on('click', function() {
    var albumName = $("#nome").val();
    //procurar album na listagem com like da procura
    searchAlbumsObject(albumName);
    //devolver erro se nao encontrar

    //ao encontrar mostrar o result

    /*
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
      */

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

$(document).ready(function() {
  //esconder campos
  $("#albumInput").hide();
  $("#insertAlbum").hide();
  $("#messages").hide();

  //procurar artista
  searchArtist();
  //click da imagem altera  o nome de pesquisa
  clickArtistImg();
  clickAlbumImg();
  //procurar album
  searchAlbum();

  //adicionar album
  $("#inserirNovoAlbum").submit( function( event ) {
    if ( $("#nome").val().length != 0 &&  $("#artista").val().length != 0 ) {
      formData = $(this).serialize();
      url = $(this).attr('action');
      insertAlbum( url, formData );
    }
		//event.preventDefault();
	});

  if ( $("#albumSingleContent").length ) {
    //adicionar video
    videoUrl = $("#albumSingleContent").attr('data-playlist-url');
    html ='<iframe width="100%" height="500px"' +
        'src="'+videoUrl+'"' +
        'frameborder="0" allowfullscreen ></iframe>';

        $("#player").html(html);

  }
});
