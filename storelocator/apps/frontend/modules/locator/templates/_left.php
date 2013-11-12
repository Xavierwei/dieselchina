<?php use_helper('Markdown');?>
<div id="ls">
  <div class="box store-finder">
    <div class="head">
      <h2>Store finder</h2>
    </div>
    <div class="content">
      <form id="store-search" action="#">
        <ul id="country" class="turnintoselect countryManager">
          <?php 
            foreach( $storesFinderForm['country']->getWidget()->getChoices() as $countryChoice ):?>
          <li>
            <a href="<?php echo proxy_url_for('@stores_country?country='.urlencode($countryChoice));?>"
              <?php echo $countryChoice==$country?'class="selected"':'';?>>
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
        <?php echo $storesFinderForm['type']->render(array('onchange'=>'updateStoreByCity()'));?>
      </form>
      <div class="results" id="storeResults">
        <?php foreach ( $stores as $store ):?>
        <div class="result">
          <address>
            <?php echo $store["city"]?>
            <br/>
            <?php echo $store['public_type'];?>
            - 
            <?php echo $store["name"]?>
            <br/>
            <?php echo $store["address"]?>
            <br/>
            <?php echo $store["telf"]?>
            <br/>
            <br/>
            <?php echo $store->getRaw('info');?>
          </address>
          <a onclick="trackWoodland('storelocator/wp_to_info');" class="btn mapinfo" href="<?php echo proxy_url_for('@store_detail?id='.$store["id"].'&slug='.$store["slug"]);?>">Map &amp; info</a>
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </div>
</div>
