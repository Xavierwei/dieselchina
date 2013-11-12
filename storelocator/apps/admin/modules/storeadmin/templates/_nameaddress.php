<form method="post" id="formNameAddress" action="<?php echo url_for('storeadmin/nameAddress'); ?>">
    <fieldset>
        <!-- store-name -->
        <div class="group">
            <label for="store-name">Store name</label>
            <a class="question-mark" href="#hint-store-name">?</a>
            <details id="hint-store-name" class="hint">
                <summary>Store name</summary>
                <p>usually is the name of the city where the store is; if a city has more than one shop,<br/>use the name of the street/district where the store is.<br/>Don't use the type of the store like "Kid" or "Accessories", etc...<br/>Please don’t attempt to add things like location, directions or other qualifiers to the title.</p>
            </details>
            <input type="text" id="store-name" class="required" name="store-name" value="<?php echo $storeTable->getName();?>" data-error="Insert Store name">
        </div>
        <!-- store-name -->
        
        <!-- add-1 -->
        <div class="group">
          <label for="add-1">Address line 1:</label>
          <a class="question-mark" href="#hint-add-1">?</a>
          <details id="hint-add-1" class="hint">
            <summary>Address line 1</summary>
            <p>Use a precise, accurate address to describe your business location.</p>
          </details>
          <input type="text" id="add-1" class="required" name="add-1" value="<?php echo $storeTable->getAddress();?>" data-error="Insert Address">
        </div>
        <!-- add-1 -->
        
        <div class="group">
          <label for="country">Country</label>
          
          <select id="country" class="required" url="<?php echo  url_for('getcities_nopar'); ?>" name="country" <?php if (!$sf_user->isSuperAdmin() && !$storeTable->isNew()): ?>disabled="disabled"<?php endif; ?>>
            <option value="">Select Country</option>
            
            <?php if ($sf_user->isSuperAdmin() || $storeTable->isNew() ): ?>
              <?php foreach ($countries as $c): ?>
                <option value="<?php echo $c->getId(); ?>" <?php echo ($storeTable->getCountryId() == $c->getId()) ? 'selected="selected"' : ''; ?>><?php echo $c->getName(); ?></option>
              <?php endforeach; ?>
            <?php else: ?>
                <option selected="selected" value="<?php echo $storeTable->getCountryId(); ?>"><?php echo $storeTable->getSlCountry()->getName(); ?></option>
            <?php endif; ?>
          </select>
          
        </div>
        <!-- country -->
<!-- city -->
        <div class="group">
          <label for="city">City:</label>
          
          <select id="city" class="required" name="city" >
            <option value="">Select City</option>
              <?php foreach ($cities as $c): ?>
                <option value="<?php echo $c->getId(); ?>" <?php echo ($storeTable->getCityId() == $c->getId()) ? 'selected="selected"' : ''; ?>><?php echo $c->getName(); ?></option>
              <?php endforeach; ?>
           </select>
          
        </div>
        <?php /*
        <div class="group">
            <label for="city">City:</label>
            <a class="question-mark" href="#hint-city">?</a>
            <details id="hint-city" class="hint">
                <summary>City</summary>
                <p>The city where your business is located.</p>
            </details>
            <input type="text" id="city" class="required" name="city" value="<?php echo $storeTable->getCity();?>" data-error="Insert City">
        </div>
         * */ ?>
         
        <!-- city -->        
        <!-- add-2 -->
        <div class="group">
            <label for="add-2">Address line 2:</label>
            <a class="question-mark" href="#hint-add-2">?</a>
            <details id="hint-add-2" class="hint">
                <summary>Address line 2</summary>
                <p>Any additional useful address information.<br/>This includes shopping center names, cross streets, and other non essential address information.</p>
            </details>
            <input type="text" id="add-2" name="add-2" value="<?php echo $storeTable->getAdditionalAddress();?>" data-error="Insert Address">
        </div>
        <!-- add-2 -->
        
        
        
       
        <!-- postal-code -->
        <div class="group">
            <label for="postal-code">Postal code:</label>
            <a class="question-mark" href="#hint-postal-code">?</a>
            <details id="hint-postal-code" class="hint">
                <summary>Postal code</summary>
                <p>A postal code (ZIP) that is valid in the country where your business is located.</p>
            </details>
            <input type="text" id="postal-code" class="required" name="postal-code" value="<?php echo $storeTable->getZip();?>" data-error="Insert Postal code">
        </div>
        <!-- postal-code -->
        <!-- postal-code -->
        <div class="group">
            <label for="postal-code">Latitude:</label>
            
            <input type="text" id="lat" class="required" name="lat" value="<?php echo $storeTable->getLatitude();?>" data-error="Insert Latitude">
        </div>
        <!-- postal-code -->
        <!-- postal-code -->
        <div class="group">
            <label for="postal-code">Longitude:</label>
            <input type="text" id="lng" class="required" name="lng" value="<?php echo $storeTable->getLongitude();?>" data-error="Insert Longitude">
        </div>
        <!-- postal-code -->
        

        
        <!-- telephone -->
        <div class="group">
            <label for="telephone">Telephone:</label>
            <a class="question-mark" href="#hint-telephone">?</a>
            <details id="hint-telephone" class="hint">
                <summary>Telephone</summary>
                <p>Provide a phone number that connects to your individual business location as directly as possible.<br/>For example, you should provide an individual location phone number in place of a call center.</p>
            </details>
            <input type="text" id="telephone" class="required" name="telephone" value="<?php echo $storeTable->getTelf();?>" data-error="Insert telephone">
        </div>
        <!-- telephone -->
        
        <!-- telephone -->
        <div class="group">
            <label for="telephone">E-mail:</label>
            <input type="text" id="email" name="email" value="<?php echo $storeTable->getEmail();?>" data-error="Insert email">
        </div>
        <!-- telephone -->
                

        <div class="group">
          <label for="status">Status</label>
          
          <select id="status" class="required" name="status">
              
              <?php foreach ($statuses as $s): ?>
                <option value="<?php echo $s->getId(); ?>" <?php echo ($storeTable->getStoreStatusId() == $s->getId()) ? 'selected="selected"' : ''; ?>><?php echo $s->getName(); ?></option>
              <?php endforeach; ?>

          </select>
          
        </div>
         <div class="group">
          <label for="status">Shop type</label>
          
          <select id="shop-type" class="required" name="shop-type">
              
              <?php foreach ($types as $st): ?>
                <option value="<?php echo $st->getId(); ?>" <?php echo ($storeTable->hasType($st->getId())) ? 'selected="selected"' : ''; ?>><?php echo $st->getName(); ?></option>
              <?php endforeach; ?>

          </select>
          
        </div>
        
        <div class="group">
          <label for="country">Product Line:</label>
          <ul id="jqmultiselect">
            
            <?php foreach ($plines as $pl): ?>
              <li>
                <label for="cinema">
                <input type="checkbox" name="product-line[]" id="<?php echo $pl->getId(); ?>" value="<?php echo $pl->getId(); ?>" class="checkbox" <?php echo $storeTable->hasProductLine($pl->getId()) ? 'checked="checked"' : ''; ?>>
                <?php echo $pl->getName(); ?>
                </label>
              </li>
            <?php endforeach; ?> 
          </ul>
        </div>
        
       
        

<?php /*
         <!-- shop-type -->
        <div class="group">
          <label for="country">Shop type:</label>
          <ul id="jqmultiselect">
            
            <?php foreach ($types as $st): ?>
              <li>
                <label for="cinema">
                <input type="checkbox" name="shop-type[]" id="<?php echo $st->getId(); ?>" value="<?php echo $st->getId(); ?>" class="checkbox" <?php echo $storeTable->hasType($st->getId())? 'checked="checked"' : ''; ?>>
                <?php echo $st->getName(); ?>
                </label>
              </li>
            <?php endforeach; ?> 
          </ul>
        </div>
        <!-- shop-type -->
       */ ?> 
        
<?php /*        <input type="hidden" id="lat" name="lat" value="<?php echo $storeTable->getLatitude();?>">
        <input type="hidden" id="lng" name="lng" value="<?php echo $storeTable->getLongitude();?>">
 */ ?>
 
        <input type="hidden" id="slug" name="slug" value="<?php echo $storeTable->getSlug();?>">
        
        <button type="button" id="saveNameAddress" name="save" title="save">save</button>
        
        <div id="resultMsgName"><p></p></div>
        
        <a class="question-mark" href="#storeadded" style="display: none;" id="storeaddedclick">?</a>
        <details id="storeadded" class="hint">
            <summary>Store Added</summary>
            <p>The store was successfully added. Now you can edit store's details.</p>
        </details>
        
        
    </fieldset>
</form>

<div id="google-map">
	
    <h3>Find your position</h3>
    <a class="question-mark" href="#map-hint">?</a>
    <details id="map-hint" class="hint">
        <summary>Find your position</summary>
        <p>Please type in the address field to search the store.<br/>Once you find the desired location start dragging the marker to find a more precise coordinate.</p>
    </details>
    <div id="map"></div>
    <a href="#" id="refreshMap">Refresh map</a>
</div>

<?php if ($sf_user->hasFlash('notice')): ?>
<script>
  $(function() {
    $('#storeaddedclick').click();
  });
  
 </script>
 <?php endif; ?>