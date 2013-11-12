<?php slot('head_stylesheets')?>
  <style>
    #footer {
      position: relative;
      z-index: 3;
      margin-top: -42px;
    }
  </style>
<?php end_slot()?>

<script type="text/javascript">
  $(document).ready(function() {
    trackWoodland("404?page=" + document.location.pathname + document.location.search + "&from=" + document.referrer);
  });
</script>

<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery(window).resize(function(){
      if (jQuery(window).height() - jQuery('#footer').height() - jQuery('#header').height() + 42 > jQuery('#errorpage').css('min-height').replace('px','')) {
        jQuery('#errorpage').height(jQuery(window).height() - jQuery('#footer').height() - 10);
        jQuery('#errorpage #not_found_main').css('top', Math.floor((jQuery('#errorpage').height() - jQuery('#errorpage #not_found_main').height()) / 2));
      }
    });

    jQuery(window).resize();
	});
</script>

<div id="errorpage">
  <img src="<?php echo asset_absolute_path('/assets/core/img/bg_404.jpg') ?>" id="not_found_bg" />
  <div id="not_found_birthday">&nbsp;</div>
  <div id="not_found_facebook">&nbsp;</div>
  <div id="not_found_main" class="noJS">
    <a id="not_found_random" href="<?php echo $randomurl; ?>">&nbsp;</a>
    <a id="not_found_hp" href="/">&nbsp;</a>
  </div>
  <div id="not_found_female">&nbsp;</div>
  <div id="not_found_youtube">&nbsp;</div>
</div>