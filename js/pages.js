/**
 * Created by Particular on 03/07/14.
 */


var blockedPages = {
    "blockedLoggedinPages" : ["login","register"],
    "blockedValidationPages" :["edit_profile","change_password"],
    "blockedGuestPages" : []
};


$.each(blockedPages, function(key,value){
    $.each(value, function(key2, value2){
      $('.'+value2).click(function (){
          switch (key){
              case "blockedLoggedinPages":
                    if(loggedIn){
                        $.growl( {title: 'Erro',
                                icon: 'glyphicon glyphicon-remove',
                                message: 'Esta página não pode ser acessada por você.'},
                            options_danger
                        );
                    }else{
                        document.location.href=$(this).data('href');
                    }
                  break;
              case "blockedValidationPages":
                    if($.cookie("validated")=="0"){
                        $.growl( {
                            title: 'Aviso',
                            icon: 'glyphicon glyphicon-exclamation-sign',
                            message: 'Você precisa validar sua conta antes de acessar essa página, <a href="'+MAINURL+'/?page=validate">clique aqui</a> para validar sua conta.'
                        },options_warning);
                    }else{
                        document.location.href=$(this).data('href');
                    }

                  break;
              case "blockedGuestPages":
                  if(!loggedIn){
                      $.growl( {title: 'Erro',
                              icon: 'glyphicon glyphicon-remove',
                              message: 'Você precisa estar logado para acessar esta página, <a href="'+MAINURL+'/?page=login">clique aqui</a> para fazer login.'},
                              options_danger
                      );
                  }else{
                     document.location.href=$(this).data('href');
                  }
                  break;
          }
         });
    });
});
