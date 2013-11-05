(function($) {
  $.fn.superbanner = function(o) {
    var superbanners = [];
    this.each(function() {
       superbanners.push(new $.superbanner(this, o));
    });
    return (superbanners.length == 1) ? superbanners[0] : superbanners;
  };
    
  // Default configuration properties.
  var defaults = {
    preload: true
  };
  
  $.superbanner = function(e, o) {
    this.o = $.extend({}, defaults, o || {}); 
    this.c = $(e); // container
    this.t; // timer
    var self = this;
      
    var l = 0;
    for (var k in this.o.steps) l++;
    this.n = l; // number of items;
      
    this.c.append('<ul></ul>');
    this.l = this.c.find('ul');
      
    this.setup();
  }
  
  $.superbanner.fn = $.superbanner.prototype = {
      channelsCarousel: '1.0.0'
  };
  
  $.superbanner.fn.extend = $.extend;
  
  $.superbanner.fn.extend({
    setup: function() {
      // preload for assets
      var self = this;
      if (this.o.preload) {
        $(window).load(function() {
          for (var k in self.o.steps)
            if (self.o.steps[k].img)
              $.preload([self.o.steps[k].img]);
            else 
              $.preload([self.o.steps[k].flv]);
        });
      }
      this.initCarousel();
    },
    initCarousel: function() {
      this.l.jcarousel({
        scroll: 1,
        wrap: 'circular',
        size: this.n,
        animation: 500,
        easing: 'swing',
        initCallback: this.initCallback,
        itemVisibleInCallback: {
            onBeforeAnimation: this.itemVisibleInCallback
        },
        itemVisibleOutCallback: {
            onAfterAnimation: this.itemVisibleOutCallback
        },
        sb: this
      });
    },
    itemVisibleInCallback: function(carousel, item, i, state, evt) {
      var sb = carousel.options.sb;
      var idx = carousel.index(i, sb.n);
      carousel.add(i, sb.getItemHTML(sb.o.steps[idx - 1]));
      var curItem = sb.o.steps[idx - 1];
      sb.clearTimer();
      sb.setNextTimer();
      if (sb.c.find('.videocont video').size())
        sb.c.find('.videocont video').get(0).play();
    },
    itemVisibleOutCallback: function(carousel, item, i, state, evt) {      
      /*var sb = carousel.options.sb;
      if (sb.c.find('.videocont video').size())
        sb.c.find('.videocont video').get(0).pause();*/
      carousel.remove(i);
    },
    getItemHTML: function(item){
      var itemHTML;
      if (item.img) { // image
        itemHTML = $('<a href="'+item.link+'"></a>');
        if (item.track)
          itemHTML.click(function() { trackWoodland(item.track) });
        if (item.target && item.target != 'self')
          itemHTML.attr('target', '_'+item.target);
        itemHTML.append('<img src="'+item.img+'" alt="" />');
      } else if (item.player) {
        itemHTML = $('<div class="videocont"></div>');
        if (/iPad|iPod|iPhone/.test(navigator.userAgent)) {
          itemHTML.html('<video width="980" src="'+item.mp4+'" height="490" disablefullscreen autoplay loop></video>');
        } else {
          var params = { wmode: 'transparent', allowfullscreen: 'true', allowScriptAccess: 'always' };
          var flashvars = { urlVideo: item.flv };
          itemHTML.append($.flash.create({ swf: item.player, params: params, flashvars: flashvars, width: 980, height: 490 }));
        }
        var link = $('<a href="'+item.link+'"></a>');
        if (item.track)
          link.click(function() { trackWoodland(item.track) });
        if (item.target && item.target != 'self')
          link.attr('target', '_'+item.target);
        itemHTML.append(link);
      } else if (item.html) {
        itemHTML = item.html;
      } else {
        return '';
      }
      this.item = $(itemHTML).data('item', item);
      return itemHTML;
    },
    initCallback: function(carousel, state){
      var sb = carousel.options.sb;      
      if (state == 'init') {
        if (sb.n < 2) {
          sb.hideArrows();
        } else if (!/iPad|iPod|iPhone/.test(navigator.userAgent)) {
          sb.fadeOutArrows();
          sb.c.mouseenter(function() {
            sb.fadeInArrows();
          });
          sb.c.mouseleave(function() {
            sb.fadeOutArrows();
          });          
        } else {
          sb.c.touchwipe({
            wipeLeft: function() { sb.c.find('.jcarousel-next').click(); },
            wipeRight: function() { sb.c.find('.jcarousel-prev').click(); },
            preventDefaultEvents: true
          });
        }
      }
    },
    setNextTimer: function() {
      var self = this;
      this.t = setInterval(function() { self.c.find('.jcarousel-next').click(); }, $(this.item).data('item').seconds*1000);
    },
    clearTimer: function() {
      clearTimeout(this.t);
    },
    hideArrows: function() {
      this.c.find('.jcarousel-next, .jcarousel-prev').remove();
    },
    fadeOutArrows: function() {
      this.c.find('.jcarousel-next').animate({opacity: 0, right: '-52px'},300,'swing');
      this.c.find('.jcarousel-prev').animate({opacity: 0, left: '-52px'},300,'swing');
    },
    fadeInArrows: function() {
      this.c.find('.jcarousel-next').animate({opacity: 1, right: '0px'},300,'swing');
      this.c.find('.jcarousel-prev').animate({opacity: 1, left: '0px'},300,'swing');
    }
  });
})(jQuery);