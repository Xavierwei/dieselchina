;(function($, window, document, undefined){
    $(function(){
        
        // global
        var viewport = $('html, body'),
            slm = $('#store-locator-mobile'),
            slider = $('.slider', slm),
            sliderHeight = slider.height(),
            slides = $('.slide', slider),
            bubble = $('#bubble', slm),
            storeFinder = $('#store-finder', slm),
            storeFinderHeader = $('.section-header', storeFinder),
            storeInfo = $('.info', storeFinder),
            storeKm = $('.km strong', storeInfo),
            storeFilter = $('#filter-store', slm),
            storeType = $('#type', storeFilter),
            storeCountry = $('#country', storeFilter),
            storeCity = $('#city', storeFilter),
            btnStoreFinderClose = $('.btn-close-store-finder', storeFinder),
            storeList = $('#store-list'),
            storeListResult = Tempo.prepare('store-list-result', {'var_braces' : '\\[\\[\\]\\]', 'tag_braces' : '\\[\\?\\?\\]'}),
            storeListEmpty = $('#store-list-empty', storeList),
            breadcrumb = $('.breadcrumb', storeList),
            gps = false,
            gmap = $('#google-map'),
            map = $("#map", gmap),
            markerOption = {
                draggable: false,
                icon: 'http://storage.diesel.com/assets/core/img/marker_store_big.png'
            },
            gpsError = false,
            cache;
        
        breadcrumb = $.extend({}, breadcrumb, {
            type: $('.type', breadcrumb),
            country: $('.country', breadcrumb),
            city: $('.city', breadcrumb),
            update: function(){
                this.type.text($('option:selected', storeType).text());
                this.country.text($('option:selected', storeCountry).text());
                this.city.text($('option:selected', storeCity).text());
            }
        });
        
        bubble = $.extend({}, bubble, {
            name: $('h2', bubble),
            type: $('h3', bubble),
            city: $('h4', bubble),
            add: $('p', bubble),
            tel: $('.btn-call', bubble),
            route: $('.btn-route', bubble),
            infoWindow: function(marker, event, data){
                this.name.text(data.name);
                this.type.text(data.type);
                this.city.text(data.city);
                this.add.text(data.address);
                this.tel.attr('href', 'tel:' + data.telf).find('strong').text(data.telf);
                this.route.attr('href', 'http://maps.google.com/maps?daddr=' + data.to + '&saddr=' + gps.address.replace(/\s+/g, "+"));
                
                this.fadeIn();
            }
        });
        
        // hide navigation bar
        viewport.scrollTop(0);
        
        // reset position
        window.onorientationchange = function() {
            viewport.scrollTop(1);
        };
        
        // close bubble
        $('.btn-close-bubble', bubble).click(function(e){
            e.preventDefault();
            bubble.hide();
        });
        
        // show store finder
        $('.btn-store-finder', storeFinder).click(function(e){
            e.preventDefault();
            
            slider.animate({
                height: storeFinder.height()
            }, 200, function(){
                storeInfo.hide();
                
                if (!gpsError) btnStoreFinderClose.show();
                
                gmap.slideUp(400, function(){
                    viewport.animate({
                        scrollTop: 0
                    }, 400);
                });
                
                /*
                viewport.animate({
                    scrollTop: storeFinder.offset().top - $('#header').height() + 2
                }, 400);
                */
                
                slider.removeClass('close');
            });
        });
        
        // close store finder
        btnStoreFinderClose.click(function(e){
            e.preventDefault();
            
            slider.animate({
                height: storeFinderHeader.outerHeight()
            }, 200, function(){
                
                gmap.slideDown(400, function(){
                    storeInfo.show();
                    btnStoreFinderClose.hide();
                });
                
                /*
                viewport.animate({
                    scrollTop: 0
                }, 400, function(){
                    storeInfo.show();
                    btnStoreFinderClose.hide();
                });
                */
                
                slider.addClass('close');
            });
        });
        
        // filter city
        $('select#country', storeFilter).change(function(e){
            $.ajax({
                url: url_cities,
                data: storeFilter.serialize(),
                success: function(data) {
                    var options = '';
                    $.each(data, function(k, v){
                        options += '<option value="' + k + '">' + v + '</option>';
                    });
                    $("select#city").html(options);
                }
            });
            
            return false;
        });
        
        // filter store form
        storeFilter.submit(function(e){
            if (storeCountry.val()) {
                viewport.animate({
                    scrollTop: storeFinder.offset().top - $('#header').height() + 2
                }, 400, function(){
                    slides.addClass('in').removeClass('out');
                    slider.css({ height: 'auto' });
                    
                    breadcrumb.update();
                    
                    $.ajax({
                        url: url_get_stores,
                        data: storeFilter.serialize(),
                        success: function(data) {
                            
                            $.each(data, function(k, obj){
                                if (gps && gps.address) obj.from = gps.address.replace(/\s+/g, "+");
                                obj.to = obj.address.replace(/\s+/g, "+") + ',' + obj.city + ',' + obj.country;
                            });
                            
                            storeListResult.render(data);
                                                
                            if ($.isEmptyObject(data)) {
                                storeListEmpty.show();
                            } else {
                                storeListEmpty.hide();
                            }
                        }
                    });
                });
                
                storeCountry.removeClass('error');
            } else {
                storeCountry.addClass('error');
            }
            
            return false;
        });
        
        // retrun to store finder form list
        $('.btn-slide-back', storeList).click(function(e){
            e.preventDefault();
            viewport.animate({
                scrollTop: storeFinder.offset().top - $('#header').height() + 2
            }, 400, function(){
                slides.addClass('out').removeClass('in');
                slider.animate({
                    height: storeFinder.height()
                }, 400);
            });
        });
        
        // GPS
        navigator.geolocation.getCurrentPosition(
            function(position) {
                gps = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                    accur: position.coords.accuracy
                };
                
                map.gmap3(
                    { action: 'init',
                        options:{
                            center: [gps.lat, gps.lng],
                            disableDefaultUI: true
                        }
                    },
                    {
                        action: 'getAddress',
                        latLng: [gps.lat, gps.lng],
                        callback: function(results){
                            gps.address = results && results[1] ? results && results[1].formatted_address : 'no address';
                        }
                    }
                );
                
                $.ajax({
                    url: url_get_nearest_stores,
                    data: {lat: gps.lat, lng: gps.lng},
                    success: function(data) {
                        
                        cache = [];
                        $.each(data, function(k, obj){
                            if (k == 'km') return true;
                            // obj.from = gps.address.replace(/\s+/g, "+");
                            obj.to = obj.address.replace(/\s+/g, "+") + ',' + obj.city + ',' + obj.country;
                            cache.push({lat: obj.latitude, lng: obj.longitude, data: obj});
                        });
                        
                        storeKm.text(data.km);
                        
                        map.gmap3(
                            { action: 'addMarkers',
                                markers: cache,
                                marker:{
                                    options: markerOption,
                                    events: {
                                        click: function(marker, event, data){
                                            bubble.infoWindow(marker, event, data);
                                        }
                                    }
                                }
                            },
                            'autofit'
                        );
                    }
                });
            },
            function(error) {
                alert('Position Unavailable');
                gpsError = true;
                $('.btn-store-finder', storeFinder).trigger('click');
            }
        );
    });
})(jQuery, window, document);