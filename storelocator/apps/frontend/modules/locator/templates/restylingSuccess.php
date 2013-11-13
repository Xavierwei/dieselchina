<div style="display: none;" id="ehiman"><?php echo $_SERVER['HTTP_X_FORWARDED_FOR']; ?></div>
<div style="display: none;" id="ehiman"><?php echo $_SERVER['REMOTE_ADDR']; ?></div>
<?php use_javascript('http://code.jquery.com/jquery-1.10.2.min.js') ?>
<?php use_javascript('/assets/core/js/tamingselect.js');?>
<!-- include jQuery Tools -->
<?php use_javascript('http://cdn.jquerytools.org/1.2.6/full/jquery.tools.min.js') ?>
<!-- include smarterize -->
<?php use_javascript(asset_absolute_path('/assets/core/js/jquery.smartresize.js')) ?>
<?php use_javascript('/assets/core/js/infobubble.js')?>

<script type="text/javascript">
  
  var JSON_PATH = <?php if (isset($isFb) && $isFb) :?>"https://diesel-a.akamaihd.net/fbapps/store-locator/get-stores"<?php else: ?>"<?php echo proxy_url_for('@get-stores');?>"<?php endif; ?>;
  var PRINT_URL = "<?php echo proxy_url_for('@print');?>"
  var init_country = "<?php echo $currentStore? $currentStore->getCountryId() : $country; ?>";
  var store_located = <?php echo  ($country != "" || $jsstorelocated) ? "true" : "false"; ?>;
  var country_inited = <?php echo $sf_request->hasParameter('country')? "true" : "false"?>;
  var singleStore = <?php echo $sf_request->hasParameter('id')? $sf_request->getParameter('id') : "null"?>;


  
</script>
<?php use_javascript('/assets/core/js/store-locator.js')?>



<div class="layoutStoreLocator">
  <div class="map" id="map"></div>
    
  <div class="stores">
    <a class="prev disabled" href="#"></a>
    <div class="scrollable">   
       <div class="items">
            
            
       </div>
    </div>
    <a class="next" href="#"></a>
    <span class="disable" style="display:none;" href="#"></span>
    <div class="textOverlay" style="display:none">No stores were found for this search</div>
  </div>
  <div class="info">
    <div class="storeFinder">
      <h2>STORE FINDER:</h2>
      <div>
        <?php $kid = $sf_params->has('kid') ? $sf_params->get('kid') : false; ?>
        <form action="">
          <div class="radio-group">
              <label for="all">
                <input type="radio" name="type" value="" <?php if (!$kid): ?>checked="checked"<?php endif; ?> class="type" id="all" />
                <span>All</span>
              </label>
              <?php $typeChoices = $storesFinderForm['type']->getWidget()->getChoices(); ?>
              <?php foreach($typeChoices as $key=>$choice): ?>
                <?php if( $key != "" ): ?>
                  <?php $slug = str_replace(" ", "-", strtolower($key)); ?>
                  <label for="<?php echo $key?>" class="<?php echo $slug; ?>">
                    <input type="radio" name="type" value="<?php echo $key?>" class="type" id="<?php echo $slug?>"/>
                    <span><?php echo $choice?></span>
                  </label>
                <?php endif; ?>
              <?php endforeach; ?>
                
          </div>

          <?php echo $storesFinderForm['country'] ?>
          <?php /*
           * <select id="country" name="country" class="countryManager">
            
            <?php 
              foreach( $storesFinderForm['country']->getWidget()->getChoices() as $countryChoice ):?>

            <?php
            $extraClass = "";
            if ( strtolower($countryChoice) == strtolower($country)) {
              $extraClass = ' selected="selected" ';
            }
            ?>
            <option <?php echo $extraClass?> value="<?php echo $countryChoice;?>"><?php echo $countryChoice;?></option>
            <?php endforeach; ?>
          </select>
  */?>
          <?php javascript_tag();?>
            jQuery(window).load(function() {
              $('#country').change(function() {countryManager();});
  
            });
            function countryManager(){
              var comboValue = document.getElementById('country').value.split('/');
              comboValue = comboValue[comboValue.length-1].replace('+', ' ');
              
              jQuery.getJSON("/store-locator/get-cities",{ country: comboValue, ajax: 'true'}, function(j){
                var options = '';
                jQuery.each(j, function(key, val){
                  options += '<option value="' + key + '">' + val + '</option>';
                });
                
                jQuery("select#city").html(options);
                jQuery("select#city").appendTo(jQuery(".city").parent('div.form-row'));
                jQuery('select#type option:first').attr('selected', 'selected');
                
                
              });
            }
          <?php end_javascript_tag();?>
          <?php echo $storesFinderForm['city']->render();?> <a class="other_country" href="http://www.diesel.com/store-locator" target="_blank">查找其他国家</a>
        </form>
        <span class="arrow"></span>
      </div>
      
    </div>
    <div class="closestStore" id="currentstore" style="width: 320px;">
      <h2>CLOSEST STORE:</h2>
      <div>
        <?php if ($currentStore):?>
          <h3><?php echo $currentStore ? strtoupper($currentStore->getCity()) : ""; ?> </h3>
          <address rel="<?php echo $currentStore? ($currentStore->getLatitude().",".$currentStore->getLongitude()):"";?>">
              <?php echo $currentStore->getOneType()." - ". $currentStore->getName(); ?><br>
              <?php echo $currentStore->getAddress() . " " . $currentStore->getZip() ?><br>
              <?php echo $currentStore->getTelf()?><br>
              <?php /* if ($currentStore->getStoreExtraData()->getOpeningTimes(ESC_RAW) != NULL): ?>
                <?php echo $currentStore->getStoreExtraData()->getOpeningTimes(ESC_RAW)->toString($currentStore->getStoreExtraData()->getTwotimeaday()); ?>
              <?php elseif ($currentStore->getStoreExtraData()->getInfo(ESC_RAW) != ''): ?>
                <?php echo nl2br($currentStore->getStoreExtraData()->getInfo(ESC_RAW))?>
              <?php endif; */ ?>
          </address>
            <?php endif;?>
          <span class="arrow"></span>
        
      </div>
      
    </div>
    
    <div class="closestStore" id="openingtimes" style="width: 320px;<?php if (!$currentStore->hasTimes()):?>display:none;<?php endif; ?>">
      <h2>OPENING TIMES:</h2>
      <div>
        <?php if ($currentStore):?>
          <?php /* <h3 id="ot_storename"><?php echo $currentStore ? strtoupper($currentStore->getCity()) : ""; ?> </h3> */?>
          <div id="ot_hours">
              <?php if ($currentStore->hasTimes()): ?>
                <?php echo nl2br($currentStore->getTimestable(ESC_RAW)->toString($currentStore->hasTwoTimesADay()) );?>
              <?php endif; ?>
          </div>
        <?php endif;?>
        <span class="arrow"></span>
        
      </div>
      
    </div>
  </div>
  <div class="loader"></div>
</div>


<?php if ($sf_params->has('kid') && $sf_params->get('kid')):?>
  <script>
    $(function() {
      $('#kid').click().attr('checked', 'checked');
      $('.radio-group input').attr('disabled', true);
    });
  </script>
<?php endif;?>



  