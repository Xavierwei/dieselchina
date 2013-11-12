
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta name="viewport" content="width=1060" />
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <link rel="image_src" href="<?php echo asset_absolute_path('/assets/core/img/logo.gif');?>" />
    <?php include_title() ?>
    <link rel="shortcut icon" href="/assets/core/img/favicon.ico" />
    <link rel="shortcut icon" href="/assets/core/img/favicon.gif" />
    <?php if (!cache('include_core_javascripts')): ?><?php include_core_javascripts() ?><?php cache_save(); endif; ?>
    <?php include_cdn_javascripts() ?>
    <?php if (has_slot("head_javascripts")): ?>
      <?php include_slot("head_javascripts");?>
    <?php endif; ?>

<script src="/assets/store-admin/js/nicEdit.js"></script>

  <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
  
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
  <script src="/assets/store-admin/js/jquery-ui-1.8.14.custom.min.js"></script>
  <script src="/assets/store-admin/js/jquery.selectBox.min.js"></script>
  <script src="/assets/store-admin/js/jquery.fancybox-1.3.4.pack.js"></script>
  <script src="/assets/store-admin/js/jquery.cookie.js"></script>
  <script src="/assets/store-admin/js/functions.js"></script>
    
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  
  <link rel="shortcut icon" href="/assets/store-admin/img/favicon.ico">
  <link rel="apple-touch-icon" href="/assets/store-admin/img/apple-touch-icon.png">
  
  <link rel="stylesheet" href="/assets/store-admin/css/reset.css">
  <link rel="stylesheet" href="/assets/store-admin/css/jquery.selectBox.css">
  <link rel="stylesheet" href="/assets/store-admin/css/jquery.fancybox-1.3.4.css">
  <link rel="stylesheet" href="/assets/store-admin/css/style.css">
  
<?php /*  <script src="http://storage.diesel.com/assets/core/js/typeface-0.14.js"></script>
  <script src="http://storage.diesel.com/assets/core/js/placard_bold_condensed_bold.typeface.js"></script> */ ?>
      
  <script src="/assets/store-admin/js/modernizr-1.7.min.js"></script>
  <script src="/assets/store-admin/js/jquery.form.js"></script>

  <script>
    var POST_ADD_URL = '<?php echo url_for('@editstorenopar'); ?>';
  </script>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo asset_absolute_path("/assets/core/css/colorbox.css");?>" />

    <?php $appContext = proxy_get_appcontext();?>    
    <?php if( $appContext != '' ):?><link rel="stylesheet" type="text/css" media="screen" href="<?php echo asset_absolute_path("/assets/core/css/".$appContext.".css");?>" /><?php endif;?>
    <?php if (has_slot("head_stylesheets")): ?>
      <?php  include_slot("head_stylesheets");?>
    <?php endif; ?>
    
      
  </head>
  <body>

    <div id="cnt">
    <?php echo $sf_content ?>
    </div>

  <?php use_helper('Analythics') ?>
  <?php echo get_analythics_code(); ?>
  </body>
</html>
