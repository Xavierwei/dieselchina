// plugins
(function($) {

    $.fn.selectCheckbox = function(settings) {
    
        var config = {
            check: 'all',
            unckeck: 'none',
            checkbox: false
        };
        
        if (settings) {
            $.extend(config, settings);
        }
        
        return this.each(function(i) {
        
            var data = $.extend(true, {}, config), node = $(this);
            
            if (!data.checkbox) return true;
            
            function toggle(selected){
                //console.log(selected);
                if (selected == data.check) {
                    $(data.checkbox).attr('checked', 'checked');
                }
                
                if (!selected || selected == data.unckeck) {
                    $(data.checkbox).removeAttr("checked");
                }
            }
            
            if (node.val()) toggle(node.val());
            
            node.change(function(){
                toggle($(this).val());
            });
            
        }); // return function
        
    }; // selectCheckbox
    
})(jQuery);

window.jQuery(document).ready(function(){
    
    // safe $ namespace for jQuery
    (function($){
        
        // tabs
        $( "#tabs.edit" ).tabs();
       
       
       $('#storelist li').click(function () {
         window.location.href = $('a', this).attr('href');
       })
        
        // customize selectbox
        $('select').selectBox();
        
        // show form hint
        $('.question-mark').fancybox();
        
        // checkboxmultiplo
        $('#jqmultiselect label').each(function() {
		    if ($(this).find(':checkbox').attr('checked')) $(this).addClass('selected');
		});
		  // al click sul checkbox metto/tolgo la classe 'selected'
		$('#jqmultiselect :checkbox').click(function(e) {
		    var checked = $(this).attr('checked');
		    $(this).closest('label').toggleClass('selected', checked);
		});
		        
        // check all to none
        (function(){
            var select = $('.weekdays select'), checkbox = $('.store-closed');
            
            function toggle(open){
                if (open == 'yes') {
                    select.selectBox('enable');
                    checkbox.removeAttr('checked');
                } else if (open == 'no') {
                    select.selectBox('disable');
                    checkbox.attr('checked', 'checked');
                }
            }
            
            $('#opening-time-no, #opening-time-yes').each(function(e){
            	
                //if ($(this).is(':checked')) toggle($(this).val());
                
                $(this).change(function(e){
                    toggle($(e.target).val());
                });
            })
        })();
        
        // store closed
        $('.store-closed').each(function(){
            
            var select = $(this).parent().find('select');
           
            $(this).change(function(){
                if ($(this).is(':checked')) {
                    select.selectBox('disable');
                } else {
                    select.selectBox('enable');
                }
            }).change();
        });
        
        //Two hours for day
        $("#twotimeforday").change(function(){
        	
            if ($(this).is(':checked')) {
            
            	$('#main #opening-times form .weekdays').css('marginLeft','0px');
            	$('.afternoon').show('fast');
            	
            } else {
                
                $('#main #opening-times form .weekdays').css('marginLeft','100px');
            	$('.afternoon').hide('fast');
                
            }
            
        }).change();
        
        $('#copytimes').click(function(){
        	
        	
        	var monFromSelected = $("select[name='monday[from]'] option:selected").val();
        	var monToSelected = $("select[name='monday[to]'] option:selected").val();
        	
        	var monFromPmSelected = $("select[name='monday[frompm]'] option:selected").val();
        	var monToPmSelected = $("select[name='monday[topm]'] option:selected").val();
        	
        	$('select.from').each(function(index) {
			    $(this).val(monFromSelected).attr('selected',true);
			    $(this).selectBox('value', monFromSelected);
			});
			
			$('select.to').each(function(index) {
			    $(this).val(monToSelected).attr('selected',true);
			    $(this).selectBox('value', monToSelected);
			});
			
			$('select.frompm').each(function(index) {
			    $(this).val(monFromPmSelected).attr('selected',true);
			    $(this).selectBox('value', monFromPmSelected);
			});
			
			$('select.topm').each(function(index) {
			    $(this).val(monToPmSelected).attr('selected',true);
			    $(this).selectBox('value', monToPmSelected);
			});
			
        });
        
        
        // submit store hours
        $('#saveOpening').click(function(){
            
           $('#resultMsgOpening p').html("Wait please");
	                
           var option = { 		   
           		success: function(){$('#resultMsgOpening p').html("Updated done");}
			};
			
		    $('#formOpeningTimes').ajaxSubmit(option);
            
            return false;
        });
        
       
        
        // editor
        $('.text-editor .editor-modal').each(function(){
            
            var newsId = $(this).attr('id'), titleId = 'title-' + newsId, paraId = 'para-' + newsId, service = $(this).attr('action'),
                setting = {
                    buttonList : ['fontSize', 'bold', 'italic', 'underline', 'link'],
                    uploadURI: uploadUrl
                };
            
            $('.editor-title textarea', this).attr('id', titleId);
            $('.editor-para textarea', this).attr('id', paraId);
            
           //var title = new nicEditor(setting).panelInstance(titleId);
            var para = new nicEditor(setting).panelInstance(paraId);
          
        
        });
        
        // edit store news (text-editor)
        $('.sprite-edit-delete.edit, .add-new').fancybox();
        
        // delete store news
        $('.sprite-edit-delete.delete').click(function(){
        	
            var id = $(this).attr('href').replace('#news-', "");
            
            $('#formNews #id').val(id);
            
            var option = { 		   
           		success: function(data){ 
           			
           			document.location.href=document.location.href.substring(0, document.location.href.indexOf("#")) + "#store-news";
           			window.location.reload();
           		}
			};
			
			if (window.confirm("Are you sure to delete this news ?")){
				
				$('#formNews').ajaxSubmit(option);
				
			}
			
        });
        
        // submit store news
        $('.saveNews').click(function(){
         
         	var formPnt = $(this).parent().attr("id");
         	var formId  = $('#' + formPnt + ' :input:hidden').val(); 
         	
         	$('#para-news-' + formId).val(nicEditors.findEditor('para-news-' + formId).getContent());
         	
         	$('#resultNews-'+ formId +' p').html("Wait please");
	           
            var option = { 		   
           		success: function(data){ 
           			$('#resultNews-'+ formId +' p').html("Updated done");
           			document.location.href=document.location.href.substring(0, document.location.href.indexOf("#")) + "#store-news";
           			window.location.reload();
           		}
			};
			
		    $('#news-' + formId).ajaxSubmit(option);
            
            return false;
         	
        });
        
        $('#saveNewNews').click(function(){
         	
         	$('#para-addNews').val(nicEditors.findEditor('para-addNews').getContent());
         	
         	$('#resultNews p').html("Wait please");
	           
            var option = { 		   
           		success: function(){ 
           			$('#resultNews p').html("Updated done"); 
           			document.location.href=document.location.href.substring(0, document.location.href.indexOf("#")) + "#store-news";
           			window.location.reload();

           	    }
			};
			
		    $('#addNews').ajaxSubmit(option);

            return false;
         	
        });
        
        
        
        function validate(form) {
            var flag = true;
            
            $('.required:not(a)', form).each(function(){
                
                var msg = $(this).attr('data-error'), select = $(this).parent().find('a.required');
                
                if ($(this).val() && $(this).val() != msg) {
                    $(this).removeClass('error');
                    select.removeClass('error');
                } else {
                    flag = false;
                    
                    $(this).addClass('error');
                    select.addClass('error');
                    $(this).val(msg);
                    
                    $(this).focus(function(){
                        if (!$(this).val() || $(this).val() == msg) $(this).val('');
                        $(this).removeClass('error');
                        select.removeClass('error');
                    })
                    .blur(function(){
                        if (!$(this).val()) {
                            $(this).val(msg);
                            $(this).addClass('error');
                            select.addClass('error');
                        }
                    });
                }
                
            });
            
            return flag;
        }
        
        // google map
        (function(){
            
            // global variable
            var container = $('#google-map'), canvas = $('#map', container)[0], map = false, markerArray = [], marker = false, loc, geocoder;
           
          /* $('.name-address').click(function(){
           
	            if (($("#lat").val() != '') && ($("#lng").val() != '')){
	           
	            	loc = new google.maps.LatLng($("#lat").val(), $("#lng").val());
	            	iniMap();
	                
	           }
           
           }); */
            // GPS location
            
            //$('#city, #country, #add-1, #add-2').blur(function() {
            $('#refreshMap').click(function(e) {
			  e.preventDefault();
			  if((navigator.geolocation)||(google.gears)) {
                geocoder = new google.maps.Geocoder();
	            	
                var $lat = $('#lat');
                var $lng = $('#lng');
                if ($lat.val() != "" && $lng.val() != "") {
                  var latlng = new google.maps.LatLng($lat.val(), $lng.val());
                  geocoder.geocode({'latLng': latlng}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                      loc = results[0].geometry.location;
                      if (map){
                        map.setCenter(loc);
                        map.setZoom(17);
                        marker = iniMarker();
                      } else { 
                        iniMap ();
                      }//else
                    }//else
                    else {
                      fail('Sorry, Google map fail to find the location. Please try again.');
                    }//else
                  }); 
                 }//if
                 else {
                   var countryOrCity = $('#country option:selected').text() + ' ' + $('#city option:selected').text() + ' ';
                   var address = ($('#add-1').val()) ? countryOrCity + $('#add-1').val() : ($('#add-2').val()) ? countryOrCity + $('#add-2').val() : countryOrCity;
                   if (!$.trim(address) && !$.trim(countryOrCity)) {
                     fail('Please enter a valid address!');
                   }//else
                   else {
                     geocoder.geocode({'address': address}, function(results, status) {
                      if (status == google.maps.GeocoderStatus.OK) {
                        loc = results[0].geometry.location;
                        if (map){
                          map.setCenter(loc);
                          map.setZoom(17);
                          marker = iniMarker();
                        } //if
                        else {
                          iniMap ();
                        }//else
                        $("#lat").val(loc.lat());
                        $("#lng").val(loc.lng());
                      } //if
                      else {
                        fail('Sorry, Google map fail to find the location. Please try again.');
                      }
                     });
                   }//else
                 }//else
	            }//if
                else {
               	  fail('Your browser does not support GPS location!');
	            }//else
			});
            
            function iniMap(){
            	
                map = new google.maps.Map(canvas, {
                    zoom: 17,
                    disableDefaultUI: true,
                    center: loc,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });
                map.setCenter(loc);
                marker = iniMarker();
                
                //var markerPosition = marker.getPosition();
            }
            
            function iniMarker(){
                if (typeof markerArray != 'undefined') {
                    for (i in markerArray) {
                        markerArray[i].setMap(null);
                    }
                }
                
                marker = new google.maps.Marker({
                    icon: '/assets/store-admin/img/marker-store.png',
                    shadow: '/assets/store-admin/img/marker-store-shadow.png',
                    position: loc,
                    map: map,
                    draggable: true,
                    title: "You are here!"
                });
                
                google.maps.event.addListener(marker, "drag", function(e){
                    var coordinate = e.latLng;
                    (function populateInputs(coordinate) {
                        $("#lat").val(coordinate.lat());
                        $("#lng").val(coordinate.lng());
                    })(coordinate);
                });
                
                markerArray.push(marker);
                
                return marker;
            }
            
            function fail(msg){
                if ($('.fail', container).length) return false;
                
                msg = $('<strong class="fail">' + msg + '</strong>');
                msg.hide();
                container.append(msg);
                msg.fadeIn('500', function(){
                    setTimeout(function(){
                        msg.fadeOut().remove();
                    }, 5000);
                });
            }
            
            // send store name and address
	        $('#saveNameAddress').click(function(){
	            
	           if (validate("#formNameAddress")) {
	                
	               $('#resultMsg p').html("Wait please");
	                
	               var option = { 		   
	               		success: function(data){
                          if (data.isnew) {
                            document.location.href = POST_ADD_URL + '/' + data.slug + '#name-address';
                          }
                          else {
                            $('#resultMsgName p').html("Updated done");
                          }
                        }
					};
					
					$('#formNameAddress').ajaxSubmit(option);
					
	            }
	            
	            return false;
	       
	        });
	        
        })();
        			
        // end google map
        
        // Support
        
        // send store name and address
        $('#sendSupport').click(function(){
            
           if (validate("#formSupport")) {
                
               $('#resultMsgSupport p').html("Wait please");
                
               var option = { 		   
               		success: function(){$('#resultMsgSupport p').html("Support sent, <a href='/admin.php'>back to Store Admin</a>");}
				};
				
				$('#formSupport').ajaxSubmit(option);
				
            }
            
            return false;
       
        });
        
     // send store name and address
        $('#sendStoreSupport').click(function(){
           if (validate("#formStoreSupport")) {
               $('#resultMsgStoreSupport p').html("Wait please");
               var option = { 		   
               		success: function(){$('#resultMsgStoreSupport p').html("Support sent");}
				};
				$('#formStoreSupport').ajaxSubmit(option);
            }
            return false;
        });
        // end support
        
     $('#country').change(function () {
          $c = $(this); 
          // city
          $.ajax({
            url: $c.attr('url') + '/' + $c.find(":selected").attr('value'),
            success: function (data) {
              $cities = $('#city');

              var newOptions = {};
              $.each(data, function(key, val) {
                newOptions[val.id]=val.name;
              });
              $cities.selectBox('options', newOptions);
            }
          });
        });
    })(window.jQuery);
    // end safe $ namespace for jQuery
    
});


$(window).load(function() {
//  $('#add-1').blur();
  $('#refreshMap').click();
})