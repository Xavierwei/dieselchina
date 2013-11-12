<?php $basePath = "/assets/store-locator/mobile/" ?>

<?php use_stylesheet(asset_absolute_path($basePath . 'css/style.css')) ?>

<?php use_javascript('http://maps.google.com/maps/api/js?sensor=true') ?>
<?php use_javascript(asset_absolute_path($basePath . 'js/gmap3.min.js')) ?>
<?php use_javascript(asset_absolute_path($basePath . 'js/tempo.min.js')) ?>
<?php use_javascript(asset_absolute_path($basePath . 'js/script.js')) ?>

<script>
//parametro country
var url_cities = "<?php echo url_for('@get-cities');?>"; 
var url_get_stores = "<?php echo url_for('@get-stores');?>"; 
var url_get_nearest_stores = "<?php echo url_for('@get_around_me_no_param');?>"; 

</script>

<div id="store-locator-mobile">

    <div id="google-map">
        <div id="map">
        
        </div>
        
        <div id="bubble">
            <a class="btn-close-bubble" href="#"><img src="<?php echo asset_absolute_path($basePath . 'img/btn-close-info.png'); ?>" alt="" /></a>
            
            <div class="block address">
                <h2></h2>
                <h3></h3>
                <h4></h4>
                <p></p>
            </div>
            
            <div class="block">
                <a class="btn-call" href="tel:+39 055 8422 645" onclick="trackWoodland('storelocator/mobile_call_store');">Call <strong>+39 055 8422 645</strong></a>
                <a class="btn-route" href="http://maps.google.com/maps?daddr=San+Francisco,+CA&saddr=cupertino" onclick="trackWoodland('storelocator/mobile_to_maps');">OPEN IN MAPS</a>
            </div>
        </div>
    </div>
    
    <div class="slider section close">
        <div id="store-finder" class="block slide">
            <div class="section-header">
                <a class="btn-store-finder" href="#" onclick="trackWoodland('storelocator/mobile_find_stores');">FIND STORES</a>
                <a class="btn-close-store-finder" href="#"><img src="<?php echo asset_absolute_path($basePath . 'img/btn-close-finder.png'); ?>" alt="" /></a>
                
                <div class="info">
                    <h3>AROUND ME</h3>
                    <div class="km">
                        <strong></strong>
                        <span>km</span>
                    </div>
                </div>
            </div>
            
            <form id="filter-store" class="content" action="#" method="post">
                <fieldset>
                    <label>
                        <span>SELECT STORE TYPE</span>
                        <?php echo $storesFinderForm['type']; ?>
                    </label>
                    <label>
                        <span>SELECT COUNTRY</span>
                        <?php echo $storesFinderForm['country']; ?>
                    </label>
                    <label>
                        <span>SELECT CITY</span>
                        <select id="city" name="city">
                            <option value="all">All Cities</option>
                        </select>
                    </label>
                </fieldset>
                
                <input class="btn-find-store" type="submit" name="find-store" value="FIND STORE" onclick="trackWoodland('storelocator/mobile_find_store');" />
            </form>
        </div>
        
        <div id="store-list" class="block slide">
            <div class="section-header">
                <a class="btn-slide-back" href="#">
                    <span>BACK</span>
                </a>
                
                <div class="breadcrumb">
                    <strong class="type"></strong>
                    <span>></span>
                    <strong class="country"></strong>
                    <span>></span>
                    <strong class="city"></strong>
                </div>
            </div>
            
            <ul id="store-list-result" class="content list">
                <li data-template style="display: none;">
                    <h2>[[name]]</h2>
                    <h3>[[public_type]]</h3>
                    <h4>[[city]]</h4>
                    <p>[[address]]</p>
                    <p>[? if telf ?]<strong>Ph. [[telf]]</strong>[? endif ?]</p>
                    <a class="btn-route" href="http://maps.google.com/maps?daddr=[[to]]&saddr=[[from]]" data-lat="[[latitude]]" data-lon="[[longitude]]" onclick="trackWoodland('storelocator/mobile_to_maps');">OPEN IN MAPS</a>
                </li>
                <li id="store-list-empty" data-template-fallback>
                    <p>No stores were found for this search...</p>
                </li>
            </ul>
        </div>
    </div>
    
</div>