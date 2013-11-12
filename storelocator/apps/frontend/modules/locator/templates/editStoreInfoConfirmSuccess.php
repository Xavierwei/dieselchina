<?php use_javascript('/assets/core/js/tamingselect.js')?>
<?php include_partial('head_js', array('currentStore' => $currentStore))?>

<div id="main">
  <?php include_partial('top', array('isClosestStore' => $isClosestStore, 'currentStore' => $currentStore, 'city' => $city, 'country' => $country, 'storesJSON' => $storesJSON))?>
  <?php include_partial('left', array('storesFinderForm' => $storesFinderForm, 'stores' => $stores, 'country' => $country))?>

  <div id="cs" class="news edit-profile-data">
    <div class="head"><h2>Edit Store Field</h2></div>
    <div class="content">
      <h1>
        Store data saved<br />
        <em>It will take 24 hours to see updates in the website</em>
      </h1>
      <div id="saved-store-data">
        <?php echo Markdown($currentStore->getStoreExtraData()->getInfo())?>
      </div>
      <p>
      <a href="<?php echo proxy_url_for('@store_detail?slug='.$currentStore->getSlug().'&id='.$currentStore->getId())?>">Back to store</a> |
      <a href="<?php echo proxy_url_for('@store_edit_data?slug='.$currentStore->getSlug().'&id='.$currentStore->getId())?>">Edit</a>
      </p>
    </div>
  </div>
</div>
