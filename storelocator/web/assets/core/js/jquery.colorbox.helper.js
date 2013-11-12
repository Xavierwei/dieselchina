var speed = 400;

var determineIframeClose = function determineIframeClose() {
  if (jQuery('#cboxIframe').attr('src').match('socialize'))
    jQuery('#cboxClose').addClass('socialize');
  else 
    jQuery('#cboxClose').addClass('common');
}

jQuery(document).ready(function() {
  jQuery('.login a').bind('cbox_complete', determineIframeClose);
  jQuery('.register a').bind('cbox_complete', determineIframeClose);
  jQuery('.social').bind('cbox_complete', determineIframeClose);
  jQuery('.register a, .login a, .share, .social').bind('cbox_closed', function(){ jQuery('#cboxClose').removeClass(); });
});

function resizeCB(w, h, c) {
  //console.log(window.parent.location);
  //if (window.parent.opener) { //iframe
    var iw = jQuery('#cboxContent').width();
    var ih = jQuery('#cboxContent').height();
    if (w == 0) w = iw;
    if (h == 0) h = ih;
    var ww = jQuery(window).width();
    var l = (ww - w)/2;
    var wh = jQuery(window).height();
    var t = (wh - h)/2+$(window).scrollTop();
    jQuery('#cboxContent, #cboxLoadedContent, #cboxIframe, #cboxWrapper').animate({width: w+'px', height: h+'px'});
    if (h < wh) {
  	  jQuery('#colorbox').animate({width: w+'px', height: h+'px', top: t+'px', left: l+'px'}, 'normal', 'linear', c);
    } else {
  	  jQuery('#colorbox').animate({width: w+'px', height: h+'px', left: l+'px'}, 'normal', 'linear', c);
    }
  /*} else { // popup
    var iw = window.innerWidth;
    var ih = window.innerHeight;
    if (w == 0) w = iw;
    if (h == 0) h = ih;
    var ww = screen.width;
    var l = (ww - w)/2;
    var wh = screen.height;
    var t = (wh - h)/2;
    if (Math.abs(t - window.screenY) <= 40) wh = 0;
    if (h < wh) {
      $(window).animate({innerWidth: w+'px', innerHeight: h+'px', screenY: t+'px', screenX: l+'px'}, 'normal', 'linear', c);
    } else {
      $(window).animate({innerWidth: w+'px', innerHeight: h+'px', screenX: l+'px'}, 'normal', 'linear', c);
    }
  }*/
  if (w != iw || h != ih)
    $(window).unbind('resize.cbox_resize');
}

if (document.location.href.match('resizeCB')) {
  var spl = document.location.href.split('resizeCB=')[1];
  spl = spl.split('&')[0];
  spl = spl.split('x');
  var w = spl[0];
  var h = spl[1];
  if (!window.parent.opener) { // iframe
    window.top.resizeCB(w,h);
  } else { // popup
    window.parent.resizeCB(w,h);
    //window.parent.open('test.html','test','"width=770,height=480,top=297,left=462.5,directories=no,location=no,resizeable=yes,menubar=no,toolbar=no,scrollbars=yes,status=no"')
  }
}

if (document.location.href.match('openLoginLayer')) {
  var loginUrl = document.location.href.split('openLoginLayer=')[1];
  window.top.$.fn.colorbox({href: loginUrl, iframe: true, innerWidth: window.top._loginLayer.w, innerHeight: window.top._loginLayer.h, scrolling: false});
}
if (document.location.href.match('openRegisterLayer')) {
  var registerUrl = document.location.href.split('openRegisterLayer=')[1];
  window.top.$.fn.colorbox({href: registerUrl, iframe: true, innerWidth: window.top._registerLayer.w, innerHeight: window.top._registerLayer.h, scrolling: false, overlayClose: false});
}
if (document.location.href.match('redirectParent')) {
  var redirectUrl = document.location.href.split('redirectParent=')[1];
  window.top.document.location.href = redirectUrl;
}

var setRightClose = function setRightClose() {
  if (window.top.frames[window.top.jQuery('#cboxIframe').attr('name')]) {
    if (window.top.frames[window.top.jQuery('#cboxIframe').attr('name')].location.href.match('socialize'))
      window.top.jQuery('#cboxClose').removeClass().addClass('socialize');
    else 
      window.top.jQuery('#cboxClose').removeClass().addClass('common');
  }
}
jQuery(document).ready(function() { setRightClose() });
jQuery(window).load(function() { setRightClose() });

		
