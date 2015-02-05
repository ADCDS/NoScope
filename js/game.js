/**
 * Created by Particular on 26/07/14.
 */
$('#buygame').click(function(){
    if($("#buygame").attr("id")==null){
        var _iframe_dl = $('<iframe />')
            .attr('src', 'ajax/download_game.php?game='+$("#downloadgame").data('g'))
            .hide()
            .appendTo('body');
        $.growl('Inciando download do '+$(document).find("title").text(), options_success);
    }else{
    jQuery.ajax({url:"ajax/buygame.php", data:"game="+$(this).data('g'), dataType:"html", success: function(msg) {
        if(msg=="0"){
            $.growl('Você adquiriu o Game!', options_success);
            $("#buygame").removeClass("btn-default");
            $("#buygame").addClass("btn-success");
            $("#buygame").html("Download");
            $("#buygame").attr("id", "downloadgame");
        }else if(msg=="1"){
            $.growl('Você não tem copes suficientes para comprar esse jogo, <a href="'+MAINURL+'"/?page=copes">clique aqui</a> para adquirir copes!', options_danger);
        }
    }});
    }
});
$('#downloadgame').click(function(){
    var _iframe_dl = $('<iframe />')
        .attr('src', 'ajax/download_game.php?game='+$("#downloadgame").data('g'))
        .hide()
        .appendTo('body');
    $.growl('Inciando download do '+$(document).find("title").text(), options_success);
});
$('#enviaResenha').click(function(){
  if($('#resenhatext').val()!=""){
      jQuery.ajax({url:"ajax/resenha.php", data:{"gid": GID,"resenha": $('#resenhatext').val()}, dataType:"html", success: function(msg) {
          if(msg){
              //alert(msg);
              $.growl('Sua resenha foi enviada!', options_success);
              $('.form-inline').fadeOut();
              $('.commentBox').fadeOut();
              $('.commentList').html(msg);
          }else{

              $.growl('Ocorreu um erro ao enviar sua resenha', options_danger);
          }
      }
      });

        }else{
      $.growl('Digite uma resenha antes de enviar!', options_danger);
  }
  }
);
function deletaResenha(id){

    jQuery.ajax({url:"ajax/resenha.php", data:{"rresenha": id}, dataType:"html", success: function(msg) {
        if(msg){
            $.growl('Sua resenha foi deletada!', options_success);
            $('.form-inline').fadeIn();
            $('.commentList').html(msg);
        }else{

            $.growl('Ocorreu um erro ao deletar sua resenha', options_danger);
        }
    }
});
}