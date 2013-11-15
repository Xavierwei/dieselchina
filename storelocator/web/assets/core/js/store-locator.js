$(document).ready(function(){
	if($('.layoutStoreLocator').length > 0) {
		init(); //map-init
		$layout = $('.layoutStoreLocator');
		StoreLocator.init($layout);
		
		if(store_located) {
			StoreLocator.updateStores('',init_country,'');
		} else {
			StoreLocator.updateStores('','xxx','');
			map.setZoom(3);
			$('form select#country',$layout).val('All Country');
			$(".closestStore",$layout).hide();
			$(".storeFinder",$layout).width('100%');
		}
		//$(window).scrollTop(0);
		
		if(!UA.IS_IPAD && !UA.IS_IPHONE_OR_IPOD) {
			$(window).smartresize(function() {
				var size= [$(window).width(),$(window).height()];
				StoreLocator.resize(size);
			});
		} else {
			
			$(window).bind('orientationchange', function(){
				var size= [$(window).width(),$(window).height()];
				StoreLocator.resize(size);
			});
		}
		
	
		if (country_inited) {
			StoreLocator.first_time = false;
			formSubmit();
		}//if
		
		$('form select, form input',$layout).change(function(){formSubmit()});
		$('.stores .items a',$layout).live('click', function(e) {
			StoreLocator.closeBubbles();
			e.preventDefault();
			var geoTag = $('address',this).attr('rel').split(',');
			var point = new google.maps.LatLng(geoTag[0], geoTag[1]);
			map.setCenter(point);
			map.setZoom(18);
			updateCurrStoreDiv($(this));
		})
		$(".scrollable",$layout).bind('onSeek', function(){
			var carousel= StoreLocator.carousel;
			var current_index= carousel.getIndex();
			if(current_index >= StoreLocator.limitCarousel) {
				$('.next',$layout).addClass('disabled');
				$('.disable',$layout).show();
			} else {
				$('.next',$layout).removeClass('disabled');
				$('.disable',$layout).hide();
			}
		})
		$(".closestStore > div",$layout).bind('click', function() {
			StoreLocator.first_time = true;
			StoreLocator.updateStores('',init_country,'');
			$('form select#country',$layout).val(init_country);
			$('form select#city',$layout).val(0);
		})
		
	    
		
	}
	
	function formSubmit() {
		var values = $('form').serializeArray();
		var type,country,city;
		$.each(values, function(index,value){
			switch(value.name) {
				case 'type': type = value.value; break;
				case 'country': country = value.value; break;
				case 'city': city= value.value; break; 
			}
		});
		StoreLocator.updateStores(type,country,city);

	}

    // show weixin qr code
    $('.weixin_wrap').hover(function(){
        $(this).find('.weixin_qr').fadeIn();
    }, function() {
        $(this).find('.weixin_qr').fadeOut();
    });

    $('#header #menu').click(function(){
        if( $(window).width() <= 640 ){
            if($('#header #menu').height() < 400)
            {
                $('#header #menu').height(618);
            }
            else
            {
                $('#header #menu').height(0);
            }
        }
    });

    $('*').click(function(e){
        if($(e.target).attr('id') != 'menu' && $(window).width() <= 640) {
            $('#header #menu').height(0);
        }
    });
	
});
	
	


var StoreLocator = {
	stores: {},
	resize: function(size) {
		if(store_located) {
			if ($('#openingtimes').is(':visible')) {
				siz = (size[0] - $('.closestStore').width() * 2 - 80 - 10); 
			}
			else {
				siz = (size[0] - $('.closestStore').width()  - 40 - 10);
			}
			
			
			$('.storeFinder',this.$layout).css('width', siz);
		}//if
		$('.map',this.$layout).css('height',(size[1] - 407  - 5)); // 437 = $('#footer_main').height()
		
		this.getCarouselLimit();
		
	},
	init: function($layout) {
		this.first_time = true;
		this.$layout = $layout;
		this.$loader = $('.loader',$layout);
		var size= [$(window).width(),$(window).height()];
	
		this.resize(size);
		$(".scrollable").scrollable();
		this.carousel = $(".scrollable").data("scrollable");
	},
	getCarouselLimit: function() {
		var totalElem = this.stores.length || $('.scrollable .items > div').size();
		var visibleElem = parseInt(($('.scrollable', this.$layout).width())/200+1);//200 is the width of a single item
		this.limitCarousel = totalElem - visibleElem;	
		if(this.limitCarousel <= 0) {
			$('.next',$layout).addClass('disabled');
			$('.disable',$layout).show();
		} else {
			$('.next',$layout).removeClass('disabled');
			$('.disable',$layout).hide();
		}
	},
	updateStores: function(typeValue,countryValue,cityValue) {
		this.$loader.show();
		var that = this;
		
		jQuery.getJSON(JSON_PATH,{country: countryValue, city: cityValue, type: typeValue}, function(data){
	         that.stores = data;
	         that.onUpdateStores();//callback
	     });
	},
	onUpdateStores: function() {
		$('.stores .items',this.$layout).css('left',0);
		if(this.stores.length > 0) {
			//hide no shop available
			$('.stores .textOverlay',this.$layout).hide();
			this.updateStoreList();
			this.updateMap();
		}
		else {
			//show no shop available
			var msg= (!this.first_time ? '未找到相应店铺' : 'Select your country');
			$('.stores .textOverlay',this.$layout).text(msg).show();
			$('.stores .items',this.$layout).empty();
			this.$loader.hide();
		}

		
	},
	updateStoreList: function(){
		var that= this;
		var $items = $('.stores .items',this.$layout);
		$items.empty();
		$.each(this.stores, function(index, value){
			$items.append($(that.listTmp(value,false)));
		});
		this.getCarouselLimit();
	},
	updateMap: function(){
		
		
		var that= this;
		//cicle to create markers and append it on the map
		var bounds = new google.maps.LatLngBounds();
		this.clearMap();
		$.each(this.stores,function(index,value) {
			var point= new google.maps.LatLng(value.latitude, value.longitude);
			
			//marker
			markers.push(new google.maps.Marker({
			    map: map,
			    position: point,
			    draggable: false,
			    icon: 'http://storage.diesel.com/assets/core/img/marker_store.png'
			}));
			bounds.extend(point);
			 
			that.closeBubbles();
			//bubble
			bubbles.push(new InfoBubble({
			    map: map,
			    content: that.listTmp(value,true),
			    position: point,
			    shadowStyle: 0,
			    padding: 1,
			    backgroundColor: '#fff',
			    borderRadius: 0,
			    arrowSize: 10,
			    borderWidth: 1,
			    borderColor: '#ccc',
			    disableAutoPan: false,
			    hideCloseButton: false,
			    arrowPosition: 50,
			    backgroundClassName: 'diesel_info_window',
			    arrowStyle: 0,
			    minWidth: 235,
			    maxWidth: 335,
			    minHeight: 235,
			    maxHeight: 335
			  })
			);
              
            if (value.id == singleStore) {
              currentbubble = bubbles.length - 1;
              currentmarker = markers.length - 1;
            }
			
			var markersLength = markers.length;
			var bubblesLength = bubbles.length;
			google.maps.event.addListener(markers[markersLength-1], 'click', function() {
				that.closeBubbles();
				updateCurrStoreDiv ($(bubbles[bubblesLength-1].content));

				if (!bubbles[bubblesLength-1].isOpen()) {
			    	bubbles[bubblesLength-1].open(map, markers[markersLength-1]);
			    }
			});
			
			
		});
		
		
		if(that.first_time) {
			that.first_time = false;
			var geoTag = $('.closestStore address').attr('rel').split(',');
			startPoint = new google.maps.LatLng(geoTag[0], geoTag[1]);
			map.setCenter(startPoint);
			map.setZoom(18);
            

		}else {
			map.fitBounds(bounds);
		}
		
		if (this.stores.length == 1) {
			map.setZoom(15);
		}
		
		that.$loader.hide();
        
        if (!storeopened && singleStore) {
      /*    setTimeout(function(){bubbles[currentbubble].open(map, markers[currentmarker]);},1500);*/
          setTimeout(function(){google.maps.event.trigger(markers[currentmarker], 'click');}, 400);

          storeopened = true;
        }//if
		
	},
	clearMap: function() {
		if (markers) {
		    for (i in markers) {
		      markers[i].setMap(null);
		    }
		}
		markers = [];
		bubbles = [];
	},
	closeBubbles: function() {
		$.each(bubbles, function(index,value){
			value.close();
		});
	},
	listTmp: function(data,showmore){
		//window.console.log(data);
		var html = '<div class='+(showmore ? 'bubble' : '')+'>';
		if(!showmore)
			html += '<a title="" href="">';
		html += '<strong class="shop-city">'+data.city+'</strong>';
		html += '<address rel="'+data.latitude+','+data.longitude+'">'+data.public_type+' - ' + data.name;
        html += '<br/>'+data.address+' '+data.zip+'<br>';
        html += data.telf;
        if (data.additional && data.additional != "") {
          html += '<br/><br/><span class="additional">'+ data.additional+'</span>';
        }//if
        html += '</address>';

		html += "<span class='shop-hours' style='display:none;'>"+data.hours+"</span>";
		html += "<span class='shop-name' style='display:none;'>"+data.public_type + ' - ' + data.name + "</span>";
		//html += '<a onclick="window.open(\''+PRINT_URL+'\')"  class="print" title="print map">Print Map</a>';

		if(!showmore)
			html +=	'</a>';
		html +=	'</div>';
        return html;  
	}
}

var UA = {
		IS_IPAD: (navigator.userAgent.match(/iPad/i) != null) ? true : false,
		IS_IPHONE_OR_IPOD: ((navigator.userAgent.match(/iPhone/i) != null) || (navigator.userAgent.match(/iPod/i) != null)) ? true : false
}

/* map */

var map;
var markers = [];
var bubbles = [];
var startPoint;
var storeopened = false;
var currentbubble, currentmarker;

function init() {
	  if ($('.closestStore address').size() > 0) {
        
		  var geoTag = $('.closestStore address').attr('rel').split(',');
		  startPoint = new google.maps.LatLng(geoTag[0], geoTag[1]);
		  map = new google.maps.Map(document.getElementById('map'), {
		    zoom: 18,
		    center: startPoint,
		    mapTypeId: google.maps.MapTypeId.ROADMAP,
		    panControl: true,
		    zoomControl: true,
		    mapTypeControl: false,
		    scaleControl: true,
		    streetViewControl: false,
		    overviewMapControl: false
		  });
		  var bounds = new google.maps.LatLngBounds();
        


	  } 
	  else {
		  //var geoTag = $('.closestStore address').attr('rel').split(',');
		  geocoder = new google.maps.Geocoder();
		  var country = init_country;
		  var geocoder;

		  geocoder.geocode( {'address' : country}, function(results, status) {
		      if (status == google.maps.GeocoderStatus.OK) {
		    	  
		    	  startPoint = results[0].geometry.location;
				  map = new google.maps.Map(document.getElementById('map'), {
					    zoom: 10,
					    center: results[0].geometry.location,
					    mapTypeId: google.maps.MapTypeId.ROADMAP,
					    panControl: true,
					    zoomControl: true,
					    mapTypeControl: false,
					    scaleControl: true,
					    streetViewControl: false,
					    overviewMapControl: false
					  });
		         // map.setCenter(results[0].geometry.location);
		      }
		  });
		  
		  var bounds = new google.maps.LatLngBounds();  

	  }

}

function updateCurrStoreDiv ($infoshop) {

	var shopname = $('.shop-name',$infoshop).html(); 
	var shophours = $('.shop-hours',$infoshop).html(); 
	var $openingdiv =$('#openingtimes'); 
	var $currentdiv =$('#currentstore'); 
	
	$('h2', $currentdiv).html('CURRENT STORE');
	$('h3', $currentdiv).html($('.shop-city', $infoshop).html().toUpperCase());
	$('address', $currentdiv).replaceWith($('address', $infoshop).clone());
	
    if (shophours == "") {
    	if ( $openingdiv.is(":visible") ) {
	    	$openingdiv.hide();
	    	$('.storeFinder').css('width',($('.storeFinder').width() + $openingdiv.width()));
    	}//if
    }//if
    else {
    	if ( !$openingdiv.is(":visible") ) {
    		$openingdiv.show();
    		$('.storeFinder').css('width',($('.storeFinder').width() - $openingdiv.width() - 10 ));
    	}//if
    	
    	$('#ot_storename', $openingdiv).html(shopname);
    	$('#ot_hours', $openingdiv).html(shophours);
    }//else
    
  //  $('.storeFinder',this.$layout).css('width',(size[0] - $('.closestStore').width() * 2 - 80 - 10));
    
}


