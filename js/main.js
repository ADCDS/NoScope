/**
 * Created by Particular on 07/06/14.
 */
var options_success = {allow_dismiss: 'false',
    position: {
        from: 'bottom',
        align: 'right'
    },
    delay: 0,
    type: 'success',
    template: {
        title_divider: "<hr class='separator' />"
    }};
var options_danger = {allow_dismiss: 'false',
    position: {
        from: 'bottom',
        align: 'right'
    },
    delay: 0,
    type: 'danger',
    template: {
        title_divider: "<hr class='separator' />"
    }};
var options_warning = {allow_dismiss: 'false',
    position: {
        from: 'bottom',
        align: 'right'
    },
    delay: 0,
    type: 'warning',
    template: {
        title_divider: "<hr class='separator' />"
    }};
$('.logout').click(function () {

    $.ajax({
        url : MAINURL+"/ajax/logout.php",
        success: function(data, textStatus, jqXHR)
        {

            if(data=="0"){
            $.growl('Você saiu da sua conta!', options_success);
            document.location=MAINURL;
            }else{
                $.growl('Você não está conectado!', options_danger);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $.growl('Ocorreu um erro, tente novamente mais tarde!', options_danger);
        }
    });

});
$("[name='show_email']").bootstrapSwitch();
$("[name='show_friends']").bootstrapSwitch();
$("[name='show_games']").bootstrapSwitch();
$("[name='publicado']").bootstrapSwitch();
$('#copes').qtip({
    content: 'Estes são seus Copes!',
    position: {
        target: 'mouse', // Track the mouse as the positioning target
        adjust: { x: 5, y: 5 } // Offset it slightly from under the mouse
    },
    style: { classes: 'qtip-light qtip-shadow' }
});
$('.glyphicon-trash').qtip({
    content: 'Deletar',
    position: {
        target: 'mouse', // Track the mouse as the positioning target
        adjust: { x: 5, y: 5 } // Offset it slightly from under the mouse
    },
    style: { classes: 'qtip-light qtip-shadow' }
});
$('#unpublish').qtip({
    content: 'Despublicar',
    position: {
        target: 'mouse', // Track the mouse as the positioning target
        adjust: { x: 5, y: 5 } // Offset it slightly from under the mouse
    },
    style: { classes: 'qtip-light qtip-shadow' }
});
$('#publish').qtip({
    content: 'Publicar',
    position: {
        target: 'mouse', // Track the mouse as the positioning target
        adjust: { x: 5, y: 5 } // Offset it slightly from under the mouse
    },
    style: { classes: 'qtip-light qtip-shadow' }
});
function timeSince(date) {

    var seconds = Math.floor((new Date()/1000 - date) / 1000);

    var interval = Math.floor(seconds / 31536000);

    if (interval > 1) {
        return interval + " years";
    }
    interval = Math.floor(seconds / 2592000);
    if (interval > 1) {
        return interval + " months";
    }
    interval = Math.floor(seconds / 86400);
    if (interval > 1) {
        return interval + " days";
    }
    interval = Math.floor(seconds / 3600);
    if (interval > 1) {
        return interval + " hours";
    }
    interval = Math.floor(seconds / 60);
    if (interval > 1) {
        return interval + " minutes";
    }
    return Math.floor(seconds) + " seconds";
}
function checkConection(){
    $.ajax({
        url : MAINURL+"/ajax/check_session.php",
        type: "POST",
        data : $('#form-signin').serialize(),
        success: function(data, textStatus, jqXHR)
        {
            if(data=="1"){
                $.growl('Você não está conectado!', options_danger);
                document.location=MAINURL;

            }else{
                loggedIn = true;
            }

        }
            });
}
$.fn.pageMe = function(opts){
    var $this = this,
        defaults = {
            perPage: 7,
            showPrevNext: false,
            hidePageNumbers: false
        },
        settings = $.extend(defaults, opts);

    var listElement = $this;
    var perPage = settings.perPage;
    var children = listElement.children();
    var pager = $('.pager');

    if (typeof settings.childSelector!="undefined") {
        children = listElement.find(settings.childSelector);
    }

    if (typeof settings.pagerSelector!="undefined") {
        pager = $(settings.pagerSelector);
    }

    var numItems = children.size();
    var numPages = Math.ceil(numItems/perPage);

    pager.data("curr",0);

    if (settings.showPrevNext){
        $('<li><a href="#" class="prev_link">«</a></li>').appendTo(pager);
    }

    var curr = 0;
    while(numPages > curr && (settings.hidePageNumbers==false)){
        $('<li><a href="#" class="page_link">'+(curr+1)+'</a></li>').appendTo(pager);
        curr++;
    }

    if (settings.showPrevNext){
        $('<li><a href="#" class="next_link">»</a></li>').appendTo(pager);
    }

    pager.find('.page_link:first').addClass('active');
    pager.find('.prev_link').hide();
    if (numPages<=1) {
        pager.find('.next_link').hide();
    }
    pager.children().eq(1).addClass("active");

    children.hide();
    children.slice(0, perPage).show();

    pager.find('li .page_link').click(function(){
        var clickedPage = $(this).html().valueOf()-1;
        goTo(clickedPage,perPage);
        return false;
    });
    pager.find('li .prev_link').click(function(){
        previous();
        return false;
    });
    pager.find('li .next_link').click(function(){
        next();
        return false;
    });

    function previous(){
        var goToPage = parseInt(pager.data("curr")) - 1;
        goTo(goToPage);
    }

    function next(){
        goToPage = parseInt(pager.data("curr")) + 1;
        goTo(goToPage);
    }

    function goTo(page){
        var startAt = page * perPage,
            endOn = startAt + perPage;

        children.css('display','none').slice(startAt, endOn).show();

        if (page>=1) {
            pager.find('.prev_link').show();
        }
        else {
            pager.find('.prev_link').hide();
        }

        if (page<(numPages-1)) {
            pager.find('.next_link').show();
        }
        else {
            pager.find('.next_link').hide();
        }

        pager.data("curr",page);
        pager.children().removeClass("active");
        pager.children().eq(page+1).addClass("active");

    }
};
var $_GET = {};

document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
    function decode(s) {
        return decodeURIComponent(s.split("+").join(" "));
    }

    $_GET[decode(arguments[1])] = decode(arguments[2]);
});
$(document).ready(function () {

    (function ($) {

        $('.filter').keyup(function () {

            var rex = new RegExp($(this).val(), 'i');
            $('.searchable tr').hide();
            $('.searchable tr').filter(function () {
                return rex.test($(this).text());
            }).show();

        })

    }(jQuery));
});
function calcHeight(iframe,plus)
{

    //find the height of the internal page
    if(plus){
        var the_height=
            document.getElementById(iframe).contentWindow.
                document.body.scrollHeight+plus;
    }else{
        var the_height=
            document.getElementById(iframe).contentWindow.
                document.body.scrollHeight;
    }


    //change the height of the iframe
    document.getElementById(iframe).height=
        the_height;
    $("#commentArea").contents().find("#newc").click(function(){
        calcHeight('commentArea',40);
        $("html, body").animate({ scrollTop: $(window).height()}, 50);
        return false;
    });
    $("#commentArea").contents().find(".backc").click(function(){
        calcHeight('commentArea');
        $("html, body").animate({ scrollTop: $(window).height()}, 50);
        return false;
    });

}
