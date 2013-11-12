<?php use_helper('JavascriptBase')?>
<?php use_javascript('/assets/core/js/tamingselect.js')?>
<?php include_partial('head_js', array())?>
<div id="main" class="store-facebook">

<?php javascript_tag();?>
    var STORES_PER_PAGE = 4;
    var currentStorePage = 1;
    var storeHref = '<?php echo proxy_url_for('@facebook_detail?id=store_id&slug=store_slug');?>';
    var stores = <?php echo $sf_data->getRaw('storesJSON');?>;

    function renderStores() {
        var htmlStores = '';
      
        if( stores != null && stores.length > 0 ){
            var pages = Math.ceil(stores.length / STORES_PER_PAGE);

            var offset = STORES_PER_PAGE * (currentStorePage - 1);
            for (s = offset; s < STORES_PER_PAGE+offset; s++){
              if( s >= stores.length ) break;
              var storeLink = storeHref;
              storeLink = storeLink.replace('store_id', stores[s].id);
              storeLink = storeLink.replace('store_slug', stores[s].slug);
              htmlStores += '<div class="result">' +
                            '<address>' + stores[s].city + '<br/>' +
                            stores[s].public_type + ' - ' + stores[s].name + '<br/>' +
                            stores[s].address + '<br/>' +
                            stores[s].telf + '<br/>' + '<br/>' +
                            stores[s].info +
                            '</address>' +
                            '<a onclick="trackWoodland(\'storelocator/wp_to_info\');" class="btn mapinfo" href="' + storeLink + '">Map &amp; info</a>' +
                            '</div>';
            }

            //pagination
            htmlStores+='<div class="pager">';
            if( pages > 1 ){
              for (p=1; p<=pages; p++){
                  if( p == currentStorePage ){
                    htmlStores+=p;
                  }
                  else{
                    htmlStores+='<a href="javascript:updateStorePage(' + p + ');">' + p + '</a>';
                  }
                  if( p > 0 && p < pages){
                    htmlStores+=' / ';
                  }
              }
            }
            htmlStores+='</div>';
        }
        else{
            htmlStores = '<div class="result">' +
            '<address>No stores were found for this search<address>' +
            '</div>';
        }

        document.getElementById('storeResults').innerHTML = htmlStores;
    }

    function updateStorePage($page){
      currentStorePage = $page;
      renderStores();
    }

    function selectedCity()
    {
      jQuery('select#type option:first').attr('selected', 'selected');
      updateStoreByCity();
    }
    
    function updateStoreByCity(){
        var countryValue = document.getElementById('country').value.split('/');
        countryValue = countryValue[countryValue.length-1].replace('+',' ');
        var cityValue = document.getElementById('city').value.replace('+',' ');
        var type = $('#type').val();
        
        if (cityValue != 0) {
          $('.top .left h2').html(cityValue);
          _typeface_js.renderDocument( function(e) { e.style.visibility = 'visible' }, '.top .left h2');
          $('.top .left address').html('');
        }
        jQuery.getJSON("<?php echo proxy_url_for('@get-stores');?>",{country: countryValue, city: cityValue, type: type}, function(data){
          stores = data;
          currentStorePage = 1;
          renderStores();
        });
        $.getJSON("<?php echo proxy_url_for('@get-markers');?>", {country: countryValue, city: cityValue, type: type}, function(stores) {
          if (stores.length > 0) { 
            map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
            setStores(stores);
          }
        });
    }
<?php end_javascript_tag();?>

		<div class="store-title">
			<h1>Store finder:</h1>
		</div>
    <div id="map_canvas" class="map"></div>

<div id="ls">    
  <div class="box store-finder">
		<div class="content">
      <form id="store-search" action="#">
      
				<div class="store-filter">
					<h2>FILTER:</h2>
					
					<?php //echo $storesFinderForm['type']->render(array('onchange'=>'updateStoreByCity()'));?>
					
					<div class="radio-group">
	          <label for="all">
	            <input type="radio" name="type" value="" checked="checked" class="type" id="all" onclick="updateStoreByCity()" />
	            <span>All</span>
	          </label>
						<?php 
						$typeChoices = $storesFinderForm['type']->getWidget()->getChoices();
						foreach($typeChoices as $key=>$choice){
							if( $key != "" ){
						?>
						<label for="<?php echo $key?>">
							<input type="radio" name="type" value="<?php echo $choice?>" class="type" id="<?php echo $key?>" onclick="updateStoreByCity()" />
							<span><?php echo $choice?></span>
						</label>
						<?php
							}
						}
						?>
					</div>
				</div>
      
        <ul id="country" class="turnintoselect countryManager">
          <?php 
            foreach( $storesFinderForm['country']->getWidget()->getChoices() as $countryChoice ):?>
          <li>
            <a href="<?php echo proxy_url_for('@stores_country?country='.urlencode($countryChoice));?>" >
              <?php echo $countryChoice;?>
            </a>
          </li>
          <?php endforeach;?>
        </ul>

        <?php javascript_tag();?>
          jQuery(window).load(function() {
            $('#country').change(function() {countryManager();});
          });
          function countryManager(){
            var comboValue = document.getElementById('country').value.split('/');
            comboValue = comboValue[comboValue.length-1].replace('+', ' ');
            $('.top .left h2').html(comboValue);
            $('.top .left address').html('');
            _typeface_js.renderDocument( function(e) { e.style.visibility = 'visible' }, '.top .left h2');
            jQuery.getJSON("/store-locator/get-cities",{ country: comboValue, ajax: 'true'}, function(j){
              var options = '';
              jQuery.each(j, function(key, val){
                options += '<option value="' + key + '">' + val + '</option>';
              });
              
              jQuery("select#city").html(options);
              jQuery("select#city").appendTo(jQuery(".city").parent('div.form-row'));
              jQuery('select#type option:first').attr('selected', 'selected');
              jQuery("div.city").remove();
              updateStoreByCity();
              $.getJSON('<?php echo proxy_url_for('@get-markers');?>?country='+comboValue, function(stores) {
                if (stores.length > 0) { 
                  map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
                  setStores(stores);
                }
              });
            });
          }
        <?php end_javascript_tag();?>
        <?php echo $storesFinderForm['city']->render(array('onchange'=>'selectedCity()'));?>
      </form>
			
			<div class="results" id="storeResults">
      	<div class="result"></div>
      </div>
      
			</div>
   </div>
</div>

</div>
