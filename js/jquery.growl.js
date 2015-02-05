/*! Bootstrap Growl - v1.0.6 - 2014-01-29
* https://github.com/mouse0270/bootstrap-growl
* Copyright (c) 2014 Remable Designs; Licensed MIT */
!function(a){"use strict";var b=[];a.growl=function(c,d){var e,f,g,h,i=null,j=null,k=null;switch("[object Object]"==Object.prototype.toString.call(c)?(i=c.message,j=c.title?" "+c.title+" ":null,k=c.icon?c.icon:null):i=c,d=a.extend(!0,{},a.growl.default_options,d),d.template.icon="class"===d.template.icon_type?'<span class="">':'<img src="" />',f="bootstrap-growl-"+d.position.from+"-"+d.position.align,e=a(d.template.container),e.addClass(f),e.addClass(d.type?"alert-"+d.type:"alert-info"),d.allow_dismiss&&e.append(a(d.template.dismiss)),k&&e.append(d.template.icon?"class"==d.template.icon_type?a(d.template.icon).addClass(k):a(d.template.icon).attr("src",k):k),j&&(e.append(d.template.title?a(d.template.title).html(j):j),e.append(d.template.title_divider)),e.append(d.template.message?a(d.template.message).html(i):i),h=d.offset,a("."+f).each(function(){return h=Math.max(h,parseInt(a(this).css(d.position.from))+a(this).outerHeight()+d.spacing)}),g={position:"body"===d.ele?"fixed":"absolute",margin:0,"z-index":d.z_index,display:"none"},g[d.position.from]=h+"px",e.css(g),a(d.ele).append(e),d.position.align){case"center":e.css({left:"50%",marginLeft:-(e.outerWidth()/2)+"px"});break;case"left":e.css("left",d.offset+"px");break;case"right":e.css("right",d.offset+"px")}d.onGrowlShow&&d.onGrowlShow(event);e.fadeIn(d.fade_in,function(a){d.onGrowlShown&&d.onGrowlShown(a),d.delay>0&&(1==d.pause_on_mouseover&&e.on("mouseover",function(){clearTimeout(b[e.index()])}).on("mouseleave",function(){b[e.index()]=setTimeout(function(){return e.alert("close")},d.delay)}),b[e.index()]=setTimeout(function(){return e.alert("close")},d.delay))});return e.bind("close.bs.alert",function(a){d.onGrowlClose&&d.onGrowlClose(a)}),e.bind("closed.bs.alert",function(b){d.onGrowlClosed&&d.onGrowlClosed(b);var c=a(this).css(d.position.from);a(this).nextAll("."+f).each(function(){a(this).css(d.position.from,c),c=parseInt(c)+d.spacing+a(this).outerHeight()})}),e},a.growl.default_options={ele:"body",type:"info",allow_dismiss:!0,position:{from:"top",align:"right"},offset:20,spacing:10,z_index:1031,fade_in:400,delay:5e3,pause_on_mouseover:!1,onGrowlShow:null,onGrowlShown:null,onGrowlClose:null,onGrowlClosed:null,template:{icon_type:"class",container:'<div class="col-xs-10 col-sm-10 col-md-3 alert">',dismiss:'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>',title:"<strong>",title_divider:"",message:""}}}(jQuery,window,document);