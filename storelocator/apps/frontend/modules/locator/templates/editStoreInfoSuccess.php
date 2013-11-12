<?php use_javascript('/assets/core/js/tamingselect.js')?>
<?php include_partial('head_js', array('currentStore' => $currentStore))?>

<div id="main">
  <?php include_partial('top', array('isClosestStore' => $isClosestStore, 'currentStore' => $currentStore, 'city' => $city, 'country' => $country, 'storesJSON' => $storesJSON))?>
  <?php include_partial('left', array('storesFinderForm' => $storesFinderForm, 'stores' => $stores, 'country' => $country))?>

  <div id="cs" class="news edit-news">
    <div class="head"><h2>Edit Store Data</h2></div>
    <div class="content">
      <form action="<?php echo proxy_url_for('@store_edit_data?slug='.$currentStore->getSlug().'&id='.$currentStore->getId())?>" method="post">
        <?php echo $form->renderHiddenFields()?>
        <fieldset>
          <?php echo $form['info']->renderRow(array('class' => 'textarea'))?>
        </fieldset>
        <input type="submit" class="submit save" value="Save">
      </form>
    </div>
  </div>
</div>
