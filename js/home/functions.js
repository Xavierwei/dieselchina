
	$(function() {
	
		var App = function($el) {
			
			this.$el = $el;
			if ($.isFunction(this.init)) this.init();
			
		}
		
		App.prototype = {
	
			
//			------------------------------------------------------
//							INIT
//			------------------------------------------------------
	
			
			init: 			function() {
							
								this.$rows = this.$el.find('.row');
								this.$cols = this.$el.find('.col');
								this.$elmBig = this.$rows.not('.rs');
								this.$elmSmall = this.$rows.filter('.rs');
								
								this.$navTop = $();
								this.$navBottom = $();
								this.$navs  = $();
								this.currentIndex = false;
								this.offsetTop = this.$el.offset().top;
								this.isTouch = Modernizr.touch;
								this.isSmartphone = this.$el.hasClass('mobile') ? true : false;
								this.rowsData;
								
								this.initNav();
								this.initCols();
								this.initRows();
								this.resize();
								this.navHandle();
								this.rowSetCurrent(0);
								if (document.location.hostname !== "www.diesel.localhost") this.initMap();
								
								setTimeout($.proxy(function() {
								
									this.$el.css('visibility', 'visible');
									
								}, this));
								
								$(window).on('resize', $.proxy(this.resize, this));
								$(window).on('scroll', $.proxy(this.navHandle, this));
								$(window).on('scroll', $.proxy(this.rowHandle, this));
								$(window).on('resize', $.proxy(this.rowHandle, this));

								// campaign
								this.keepCenterCampaign();
								$(window).on('resize', $.proxy(this.keepCenterCampaign, this));

								if( $(window).width() <= 640 ){
         							this.resizeMobile();
     							}
				

							},


//			------------------------------------------------------
//							FUNCTIONS
//			------------------------------------------------------
			
			
			keepCenterCampaign: function() {

								this.$el.find('.campaign-block').each(function(index, elm) {

									var $elm = $(elm),
										height = $elm.find('.campaign-block-body').height();

									$elm.height(height);

								});

							},

			resize: 		function() {
			
								var width = this.$el.width(),
									factor = {
										b: this.isSmartphone === true ? 43 : 22,
										s: this.isSmartphone === true ? 43 : 15
									}
									heightBig = Math.ceil((width * factor.b) / 100),
									heightSmall = Math.ceil((width * factor.s) / 100);

								this.$elmBig.height(heightBig );
								this.$elmSmall.height(heightSmall );
								
								if (this.isSmartphone) {
									this.$rows.height(heightBig * 2);
								}
								
								this.rowsData = [];
								
								this.$rows.each($.proxy(function(index, elm) {
								
									var $row = $(elm),
										index = $row.index(), 
										limit = $row.offset().top + ($row.height() / 2) - this.offsetTop;
									
									this.rowsData.push({
										index: index,
										limit: limit
									});
								
								}, this));
				
							},

	        /**
	         * mobile640  Size
	         * @param {string}
	         * @example
	         **/
         	resizeMobile: 	function(){
         						
         						for( var i=0 ; i < $('.row').length - 1 ; i++){
         							if(i!=3){
         								$('.row').eq(i).height( $('.row').eq(i).children('.col').height() * 2 );	
         							}         							
         						}
         						
     						},
		
			waitImage:		function(src, callback) {
			
								var img = new Image();
								
								img.onload = function(e) {
									
									var size = {
											width: img.width,
											height: img.height
										}
										
									if ($.isFunction(callback)) callback(src, size);
									
								}
								
								img.onerror = function(e) {
									if ($.isFunction(callback)) callback(false);
								};
								
								img.src = src;
		
							},
			
			getColorMod: 	function(color, val) {
				
								var hexToRgb = function(hex) {
									var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
									return result ? [parseInt(result[1], 16), parseInt(result[2], 16), parseInt(result[3], 16)] : false;
								}
								
								var	array = hexToRgb(color),
									r = parseFloat(array[0] + val),
									g = parseFloat(array[1] + val),
									b = parseFloat(array[2] + val),
									rgb = array ? {
										r: r >= 250 ? 200 : r,
										g: g >= 250 ? 200 : g,
										b: b >= 250 ? 200 : b
									} : false;
								
								return 'rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')';;
				
							},


//			------------------------------------------------------
//							RENDERING
//			------------------------------------------------------
			
						
			initMap: 		function() {
				
								var url_get_nearest_stores = "/storelocator/web/store-locator/get-around-me?type[]=Diesel&type[]=Flagship&type[]=Planet",
									$storeLocatorName = $('.store-locator-store'),
									$storeLocatorAdress = $('.store-locator-address'),
									$storeLocatorMap = $('.store-locator-map'),
									$loader = $('<span />').addClass('preloader');
								
								$storeLocatorMap.append($loader);
								
								$.ajax({
									url: url_get_nearest_stores,
									success: $.proxy(function(data) {
										var cStore = data[0],
											centerlat = cStore.latitude - 0.0013000,
											$map = $('<img />').addClass('bg').attr('src',  "http://maps.google.com/maps/api/staticmap?center="+centerlat+","+cStore.longitude+"&markers=icon:http://dieselchina.com.cn/DieselDropMaker64X64X150dpi.gif|"+cStore.latitude+","+cStore.longitude+"&zoom=16&size=640x300&scale=2&sensor=false" );
										$storeLocatorName.html(cStore.name+'<br/>');
										$storeLocatorAdress.html(cStore.type + ' - ' + cStore.city + '<br/>' + cStore.address + ' ' + cStore.zip + '<br/>' + 'phone:' +cStore.telf);
										$storeLocatorMap.find('.col-body').append($map);
										$loader.remove();
										
										this.initImgBg($map, $storeLocatorMap);
										
									}, this)
								});
				
							},
			
			initNav: 		function() {
				
								if (this.isSmartphone) return;
								
								this.$navTop = $('<div />').addClass('navigation navigation-top'),
								this.$navBottom = $('<div />').addClass('navigation navigation-bottom');
								this.$navs = this.$navTop.add(this.$navBottom);
								
								var $bodyBg = $('<div />'),
									$bg = $('<div />').addClass('bg').append($bodyBg),
									$arrow = $('<span />').addClass('arrow');
								
								this.$navs.append($bg).append($arrow);
								
								this.$navs
									.on('hide', function() {
										// hide navigation bar
										$(this).addClass('hide');
									})
									.on('show', function() {
										// show navigation bar
										$(this).removeClass('hide');
									})
									.on('changeColor', $.proxy(function(e, color) {
										
										var $elm = $(e.currentTarget),
											$bg = $elm.find('.bg div');
										
										if (Modernizr.csstransforms && Modernizr.csstransitions) {
											// css3 transition
											$bg.css({
												transition: 'background 500ms ease',
												backgroundColor: this.getColorMod(color, 12)
											});
										} else {
											// no css3 transition support
											$bg.css({
												backgroundColor: this.getColorMod(color, 12)
											});
										}
										
									}, this))
									.trigger('hide')
									.appendTo(this.$el);
								
								this.$navTop.on('click', $.proxy(this.navPrev, this));
								this.$navBottom.on('click', $.proxy(this.navNext, this));
								
								this.offsetTop += this.$navTop.outerHeight();
				
							},
			
			initRows: 		function() {
				
								this.$rows.each($.proxy(function(index, elm) {
								
									var $elm = $(elm),
										color = $elm.find('.col').first().data('color');
									
									$elm.data('color', color);									
									
								
								}, this));
				
							},
			
			initCols: 		function() {
				
								this.$cols.each($.proxy(function(index, elm) {
								
									var $elm = $(elm),
										$colBody = $('<div />').addClass('col-body'),
										$imgBg =  $elm.find('img.bg');
									
									this.initCol($elm);
									
									// handle link
									if ($elm.data('link')) {
										
										var link = $elm.data('link'),
											$link = $elm.data('mainLink');
										
										$elm.find('a').on('click', function(e) {
											e.stopImmediatePropagation();
										});
										
										$elm.css({
											cursor: 'pointer'
										}).on('click', function(e) {
											if ($link.attr('target') === '_blank' || (e.which === 2)) window.open(link, '_blank');
											else window.location.href = link;
											$elm.data('mainLink').triggerHandler('click');
										});
										
									}
									
									// add col body
									$elm.wrapInner($colBody);
									
									// init img bg
									if ($imgBg.length) this.initImgBg($imgBg, $elm);
									
									// init type col
									if ($elm.parent('.row').hasClass('rs')) {									
										// colonne altezza Small
										this.$elmSmall = this.$elmSmall.add($elm);
									} else {
										// colonne altezza Big
										this.$elmBig = this.$elmBig.add($elm);
									}
								
								}, this));
					
							},
			
			initCol: 		function($elm) {
				
								if (!$elm || !$elm.length || !$elm.hasClass('col')) return;
															
								var coordinates = $elm.data('master') ? $elm.data('master').toString().split(';') : false;
									$row = coordinates ? coordinates.length > 1 ? this.$rows.eq(coordinates[1] - 1) : $elm.parent('.row') : false,
									$master = coordinates !== false ? $row.find('.col').eq(coordinates[0] - 1) : $elm,
									$mainLink = $master.find('a.main').first(),
									link = $mainLink.attr('href') || false,
									color = $master.data('color') || false;
								
								if (!$master.is($elm)) {
									
									var classDirection = $master.index() < $elm.index() ? 'from-left' : 'from-right',
										$hover = $('<div />').addClass('hover').addClass(classDirection).css({
											backgroundColor: color
										});
									
									$elm.append($hover);
									
									if (!this.isTouch) {
									
										$master.add($elm).on('mouseenter', function() {
											
											if (Modernizr.csstransforms && Modernizr.csstransitions) {
												// css3 transition
												$hover.addClass('show');
											} else {
												// no css3 transition support
												$hover.animate({
													width: '140%'
												}, 400);
											}
										
										}).on('mouseleave', function() {
											
											if (Modernizr.csstransforms && Modernizr.csstransitions) {
												// css3 transition
												$hover.removeClass('show');
											} else {
												// no css3 transition support
												$hover.animate({
													width: '0%'
												}, 400);
											}
											
										});
									
									}
									
								}
								
								$elm.data('mainLink', $mainLink);
								$elm.data('link', link);
								$elm.data('color', color);
								
								$elm.css({
									backgroundColor: color
								});
												
							},
			
			navHandle: 		function() {
				
								var scroll = $(window).scrollTop(),
									indexLast = this.$rows.length - 1,
									offsetFirstRow = this.$rows.eq(0).offset().top,
									offsetLastRow = this.$rows.eq(indexLast).offset().top,
									heightOffset = $(window).height() - this.$rows.eq(indexLast).height();
								
								// Nav Top visibility condition
								if (scroll <= offsetFirstRow) this.$navTop.trigger('hide'); else this.$navTop.trigger('show');
								// Nav Bottom visibility condition
								if (scroll + heightOffset >= offsetLastRow) this.$navBottom.trigger('hide'); else this.$navBottom.trigger('show');
												
							},
			
			rowHandle: 		function() {
				
								if (this._manualScroll === true) return;
								
								var scroll = $(window).scrollTop();
								
								$.each(this.rowsData, $.proxy(function(index, item) {
								
									if (item.limit <= scroll) {
										
										this.rowSetCurrent(item.index + 1);
										return;
										
									} else if (scroll === 0) {
										
										this.rowSetCurrent(0);
										return;
										
									}
								
								}, this));
				
							},
							
			initImgBg: 		function($img, $col) {
				
								if (!$img || !$img.length) return;
								
								var src = $img.attr('src'),
									$loader = $('<span />').addClass('preloader');
								
								$col.append($loader);
								
								this.waitImage(src, $.proxy(function(src, size) {
									
									var $wrap = $('<div />');
									
									$img.data('size', size).wrap($wrap).data('wrapper', $wrap).data('col', $col);
									
									setTimeout($.proxy(function() {
									
										this.imgBgResize($img);
										
										$(window).on('resize', $.proxy(function(src, size) {
										
											this.imgBgResize($img);
										
										}, this))
										
										$img.fadeIn(200);
										
										$loader.remove();
									
									}, this));
									
								}, this));
				
							},
			
			imgBgResize:	function($img) {
			
								if (!$img || !$img.length) return;
								
								var imgWidth = $img.data('size').width,
									imgHeight = $img.data('size').height,
									pageWidth = $img.data('col').width(),
									pageHeight = $img.data('col').height(),				
									_test = (pageWidth / pageHeight) > (imgWidth / imgHeight),
									size = {
										width: _test ? pageWidth : Math.ceil((pageHeight * imgWidth) / imgHeight),
										height: _test ? Math.ceil((pageWidth * imgHeight) / imgWidth) : pageHeight
									}
								
								$img.data('wrapper').css({width: size.width, marginLeft: -size.width / 2});
								$img.width(size.width).height(size.height);
							
							},


//			------------------------------------------------------
//							NAVIGATION
//			------------------------------------------------------
			

			navNext: 		function(e) {
				
								e.stopPropagation();
								
								var index = this.currentIndex + 1;
								
								this.rowSetCurrent(index);
								this.scrollToCurr();
								
							},
							
			navPrev: 		function(e) {
								
								e.stopPropagation();
								
								var index = this.currentIndex - 1;
								
								this.rowSetCurrent(index);
								this.scrollToCurr();
								
							},
			
			rowSetCurrent: 	function(index, scrollTo) {
				
								if (index === undefined) return;
								
								var index = index > 0 ? index > this.$rows.length - 1 ? this.$rows.length - 1 : index : 0;
								
								this.currentIndex = index;
								
								var $currentRow = this.$rows.eq(this.currentIndex),
									color = this.currentIndex === 0 ? '#000000' : $currentRow.data('color');
								
									
								this.$navs.trigger('changeColor', color);																
				
							},
			
			scrollToCurr: 	function() {
				
								this._manualScroll = true;
								
								var $row = this.$rows.eq(this.currentIndex);
									offset = $row.offset().top - this.offsetTop;
								
								$('html, body').animate({
									
									scrollTop: offset
									
								}, 200, $.proxy(function() {
									
									this._manualScroll = false;
									
								}, this));
				
							}
			
		}
		
		
				
		var $homepage = $('#homepage');
			appHomepage = new App($homepage);
		
		
	
	});