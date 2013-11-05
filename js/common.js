// default parameters of the site
var _socialLayer = { w: 666, h: 494 };
var _registerLayer = { w: 793, h: 645 };
var _loginLayer = { w: 793, h: 450 };
var xhr = [];

var _www_url = "http://"+document.domain;

var DIESEL = {
	IS_IPAD: (navigator.userAgent.match(/iPad/i) != null) ? true : false,
	IS_IPHONE_OR_IPOD: ((navigator.userAgent.match(/iPhone/i) != null) || (navigator.userAgent.match(/iPod/i) != null)) ? true : false,
	IS_ANDROID: ((navigator.userAgent.match(/Android/i) != null))? true : false
};

function D_IS_MOBILE(){
//  return (DIESEL.IS_IPAD || DIESEL.IS_ANDROID || DIESEL.IS_IPHONE_OR_IPOD);
  return (DIESEL.IS_ANDROID || DIESEL.IS_IPHONE_OR_IPOD);
}

jQuery(window).load(function() { 
	// refresh placard fonts

	refreshFonts();
});

jQuery(document).ready(function() {
  
  // colorbox social
  if (jQuery.fn.colorbox) {
	  jQuery('a.social').colorbox({iframe: true, innerWidth: _socialLayer.w, innerHeight: _socialLayer.h, scrolling: false});
	  /*var legal_notes = jQuery('a.legal-notice').colorbox({iframe: true, innerWidth: _socialLayer.w, innerHeight: _socialLayer.h, scrolling: true});*/
  }
  
  // preload rollover sprite
  var preloaderBtnsOver = new Image(1,1); 
  preloaderBtnsOver.src=_www_url+"/assets/core/img/btns_over.gif"; 
  
  // layour class for filters
  jQuery('ul.filters li:last').addClass('last');
 
  // automatic colorbox at page load
  if (jQuery.url) {
    var url = jQuery.url(location.href);
    if ('_c_a_' in url.params) {
      var size = ('_l_s_' in url.params) ? url.params._l_s_.split('x') : ['415', '305'];
      jQuery.fn.colorbox({
        href:url.params._c_a_,
        iframe: true,
        open: true,
        innerWidth: parseInt(size[0]),
        innerHeight: parseInt(size[1])
      });
    }
  }

  // rpx popups
  jQuery('a.popup').each(function(i) {
    var size = {w: 640, h: 480 };
    jQuery.each(
      jQuery(this).attr('class').split(' '),
      function(index, value) {
        var match = value.match(/^size\d+x\d+$/);
        if (match) {
          var parts = match[0].substr('size'.length).split('x');
          size.w = parseInt(parts[0]);
          size.h = parseInt(parts[1]);
        }
      }
    );
    jQuery(this).open({
      width: size.w,
      height: size.h,
      top:  window.screenY + (window.outerHeight - size.h) / 2,
      left: window.screenX + (window.outerWidth - size.w) / 2
    });
    jQuery(this).click(function() { return false; });
  });
  
  // legal popup
 /* if (jQuery('a.legal').size() > 0) {
    jQuery('a.legal').open({
	   width: 600,
       height: 700,
       top:  window.screenY + (window.outerHeight - 700) / 2,
       left: window.screenX + (window.outerWidth - 600) / 2
    });
  }*/
  
  // menu voice dynamic selection
  if (document.location.href.match('/be-stupid')) { jQuery('#m_bs').addClass('selected') }
  else if (document.location.href.match('/lifestyle')) { jQuery('#m_ls').addClass('selected') }
  else if (document.location.href.match('/collection')) { jQuery('#m_cl').addClass('selected') }
  else if (document.location.href.match('/store-locator')) { jQuery('#m_sl').addClass('selected') }
  
  // header padding setting
  setTimeout("headerInit()",1000);
  
  // footer column resize
  footerColumnResize();
  
  // trigger resize event on orientation change
  if (DIESEL.IS_ANDROID || DIESEL.IS_IPHONE_OR_IPOD || DIESEL.IS_IPAD) {
	  	jQuery('body').addClass('iPad');
	  
		var supportsOrientationChange = "onorientationchange" in window,
		orientationEvent = supportsOrientationChange ? "orientationchange" : "resize";
		
		window.addEventListener(orientationEvent, function() {
			jQuery(window).resize();
		}, false);
	}

  if(D_IS_MOBILE()) {
    jQuery('body').addClass('smphone');
  }
  
  // fix per 55DSL
  if (jQuery('.limitededition').length > 0 && jQuery('.limitededition a[href=http://55dsl.com/1055/]').length > 0) {
	  jQuery('body').prepend(jQuery('#header'));
	  jQuery('body').append(jQuery('#footer'));
	  jQuery('#cnt').width(980);
  }
  
  // fix per Diesel Island
  setInterval("if (jQuery('#content #buttons').length > 0) { jQuery('#content').height(jQuery('#buttons').height()); }", 1000);
  
  // fix per Diesel Be Stupid
  if ((window.location.href).indexOf('be-stupid') != -1) {
	  jQuery('#cnt').width(980);
  }
  
  // fix per Diesel Adidas
  if ((window.location.href).indexOf('diesel-adidas') != -1) {
	  jQuery('body').addClass('diesel-adidas');
  }
  
  // fix per Eyewear
  /*if ((window.location.href).indexOf('eyewear') != -1) {
	  if ((DIESEL.IS_ANDROID || DIESEL.IS_IPHONE_OR_IPOD || DIESEL.IS_IPAD) && (window.location.href).indexOf('eyewear-gallery') == -1) {
		  window.location.href = window.location.href.replace('eyewear/', 'eyewear-gallery');
	  }
	  if (jQuery('#panel_chooseStyle').length > 0) {
		  /* jQuery('body').addClass('diesel-eyewear');
		  jQuery('html').css('overflow', 'auto');
		  
		  jQuery(window).resize(function(){
			  jQuery('#container').height(jQuery(window).height() - jQuery('#header').height());
			  jQuery('#chooseStyle .content').css('margin-top', Math.floor((jQuery('#container').height() - jQuery('#chooseStyle .content .slug').height()) / 2));
		  }); */
	 /* }
  }*/
  
  // footer mobile nav row
  (function(){
      
      if (!DIESEL.IS_IPHONE_OR_IPOD) return false;
      
      var $footer = jQuery('#footer'), $footerRow = jQuery('.nav_row', $footer);
          
      $footerRow.each(function(){
          var $title = $('h2', this),
              $col = $('.nav_column', this);
          
          $title.click(function(){
              $col.slideToggle(600);
          });
      });
  })();
});


/**
 * Keypress handler per la validazione di username e email nelle
 * pagine di registrazione e signin
 */
var registration_validator_keypress_handler = (function() {
  var timeoutId = 0;
  return function(e) {
    var img = jQuery("#" + e.data.imgId);
    var input = jQuery(this);
    if (timeoutId) {
      clearTimeout(timeoutId);
      timeoutId = 0;
    }
    timeoutId = setTimeout(function() {
      jQuery.ajax({
        url: e.data.url,
        success: function(retval) {
          var newSrc = retval ? e.data.imgKoSrc : e.data.imgOkSrc;
          img.attr('title', retval || '');
          if (img[0].src.substring(img[0].src.length - newSrc.length) != newSrc)
            img.fadeOut(200, function() {
              img[0].src = newSrc;
              img.fadeIn(200);
            });
        },
        complete: function() {},
        data: {
          v: input.val(),
          f: e.data.fullValidation
        },
        type: 'POST',
        dataType: 'json'
      });
    }, 750);
  };
})();

/* trim function for strings ************************************************************************************************/
String.prototype.trim = function() {
  str = this.replace(/^\s+/, '');
  for (var i = str.length - 1; i >= 0; i--) {
    if (/\S/.test(str.charAt(i))) {
      str = str.substring(0, i + 1);
      break;
    }
  }
  return str;
};
/***************************************************************************************************************************/

/* dynamic content load ****************************************************************************************************/
var _loading_items = false;
var _loading_items_timeout;
var enableAjaxContent = function enableAjaxContent(url, resultPerPage) {
  jQuery(window).scroll(function() {
    var scrollY = jQuery(window).scrollTop();
    if (jQuery(document).height() == scrollY+jQuery(window).height() && !_loading_items && jQuery('.lastPage').size() == 0) {
      loadContentItems(url, resultPerPage);
    }
    // if the scroll passes the 2/3 of the left shoulder the footer is shown
    if (scrollY+jQuery(window).height()-jQuery('#footer').innerHeight() > jQuery('#ls').height()+jQuery('#ls').get(0).offsetTop && jQuery('.box').size() > 1) {
      jQuery('#footer').addClass('fixed');
      jQuery('#cs').css('paddingBottom', jQuery('#footer').innerHeight()+'px');
    } else {
      jQuery('#footer').removeClass('fixed');
    }
    // if the scroll passes the entire height of the left shoulder show a gotop
    if (scrollY > jQuery('#ls').height()+jQuery('#ls').get(0).offsetTop) {
      var atotop;
      if (jQuery('a.to-top').size() > 0) {
        atotop = jQuery('a.to-top');
      } else {
        atotop = jQuery('<a class="to-top" title="Go to top" href="#">Go to top</a>');
        atotop.css({opacity: 0, display: 'none'});
        jQuery(document).find('body').append(atotop);
        atotop.click(function() { animateScrollTo(0,1000); return false; });
      }
      atotop.stop().show().fadeTo(300, 1);
    } else {
      jQuery('a.to-top').stop().fadeTo(300, 0, function() { jQuery(this).hide(); });
    }
  });
};

var appendContentItems = function(content) {
  // jQuery va in tilt quando gli si passa una stringa vuota, quindi evitiamo di farlo 
  if (content.trim() == '') {
    return;
  }
  content = jQuery(content);
  content.each(function() {
    if (jQuery(this).hasClass('box'))
      jQuery(this).css('opacity',0);
  });
  jQuery('#cs').append(content);
  refreshFonts();
  jQuery('.box').fadeTo(500,1);
};

var loadContentItems = function loadContentItems(url, resultPerPage) {
  resultPerPage = resultPerPage || 6;  

  elementCount = jQuery('#cs .box').length;
  if (elementCount <= 0)
    return;  
  pageNum = Math.ceil(elementCount / resultPerPage) + 1;
  
  _loading_items = true;
  var loading = jQuery("<div class=\"loading-items\">Loading contents</div>");
  loading.css({opacity: 0});
  jQuery('#cs').append(loading);
  loading.fadeTo(500,1);
  jQuery.ajax({url: url, type: "GET",
    data: { page_number: pageNum, page_record: resultPerPage },
    success: function(result) {
      appendContentItems(result);
      loading.fadeTo(500, 0, function() {loading.remove(); _loading_items = false;});
    }
  });
};
/**************************************************************************************************************************/

/* animated scroll ********************************************************************************************************/
var animateScrollTo = function animateScrollTo(y,duration) {
  var reg = /[0-9]+/g;
  y += '';
  if (y.match(reg) && y.match(reg).length > 0)
	  jQuery('html, body').animate({scrollTop:y}, duration);
  else
	  jQuery('html, body').animate({scrollTop:jQuery('#'+y).offset().top}, duration);
}
/*************************************************************************************************************************/

/* refresh placard fonts *************************************************************************************************/
var refreshFonts = function refreshFonts(){
//  if (_typeface_js) {
//    _typeface_js.renderDocument( function(e) { e.style.visibility = 'visible' });
//  }
}
/*************************************************************************************************************************/

/* open social layer external links **************************************************************************************/
var openSocialLayer = function openSocialLayer(type,url) {
  jQuery.fn.colorbox({
    href: _www_url+"/core/socialize?type="+type+"&url="+url,
    iframe: true,
    open: true,
    innerWidth: _socialLayer.w, 
    innerHeight: _socialLayer.h
  });
}
/*************************************************************************************************************************/

/* open social layer external links **************************************************************************************/
var openLayer = function openLayer(url,w,h) {
  jQuery.fn.colorbox({
    href: _www_url+url,
    iframe: true,
    open: true,
    innerWidth: w, 
    innerHeight: h
  });
}
/*************************************************************************************************************************/

// funzione fadeToggle per jquery
jQuery.fn.fadeToggle = function(speed, easing, callback) {
  return this.animate({opacity: 'toggle'}, speed, easing, callback);
};

/* skin for error tooltips ***********************************************************************************************/
$(function() {
  if ($.fn.qtip)
    $.fn.qtip.styles.diesel_errors = { // Last part is the name of the style
      width: 230,
      background: '#000',
      color: 'white',
      textAlign: 'left',
      padding: 15,
      border: {
         width: 5,
         radius: 0,
         color: '#FF0000'
      }
    }
});
/*************************************************************************************************************************/

/* header sublevel initialization ****************************************************************************************/
function headerInit() {
  var header_sub_margin = 0;
  var currentLink = null;
  jQuery('#menu > ul > li').each(function(){
    var header_sub_margin = jQuery(this).offset().left;
    if (header_sub_margin != 0) {
      header_sub_margin += 'px';
      jQuery(this).find('.sublevel ul').css('padding-left', header_sub_margin);
    }
    //rimozione del link principale dell'onlinestore in caso di mobile device
    // if(D_IS_MOBILE()){
    //   currentLink = $(this).children('a');
    //   if(currentLink.attr('href') == 'http://store.diesel.com/'){
    //     currentLink.attr('href','javascript:void(0);').attr('target','');
    //   }
    // }
  });
}
/*************************************************************************************************************************/

/* footer column resizing ************************************************************************************************/
function footerColumnResize() {
	var column_max_height = 0;
	jQuery('#footer #footer_nav_container .nav_column').each(function(){
	  thisHeight = parseInt(jQuery(this).height());
		if ( thisHeight > column_max_height) {
			column_max_height = thisHeight;
		}
	});
	jQuery('#footer #footer_nav_container .nav_column').height(column_max_height);
}
/*************************************************************************************************************************/