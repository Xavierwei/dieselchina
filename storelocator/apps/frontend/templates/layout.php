<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
      <meta name="viewport" content="width=640, user-scalable=no" />
  	
  	<?php if (has_slot('discovery_metas')):?>
  	  <?php include_slot('discovery_metas')?>
  	<?php endif;?>
  	
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <link rel="image_src" href="<?php echo asset_absolute_path('/assets/core/img/logo.gif');?>" />
    <?php include_title() ?>
    <link rel="shortcut icon" href="/assets/core/img/favicon.ico" />
    <link rel="shortcut icon" href="/assets/core/img/favicon.gif" />
    <link rel="stylesheet" type="text/css" media="screen" href="/css/common.css">
    <?php include_cdn_stylesheets() ?>
    <?php $appContext = proxy_get_appcontext();?>
    <?php if( $appContext != '' ):?><link rel="stylesheet" type="text/css" media="screen" href="<?php echo asset_absolute_path("/assets/core/css/".$appContext.".css");?>" /><?php endif;?>
    <?php if (has_slot("head_stylesheets")): ?>
      <?php include_slot("head_stylesheets");?>
    <?php endif; ?>
    <?php include_cdn_javascripts() ?>
      <link rel="stylesheet" type="text/css" href="/css/mobile640.css" media="screen and (max-width: 640px)">
      <!--[if IE 6]>
      <link rel="stylesheet" type="text/css" media="screen" href="/css/ie6.css" />
      <script type="text/javascript" src="/js/ie6.js"></script>
      <script src="/js/DD_belatedPNG.js"></script>
      <script>
          DD_belatedPNG.fix('*');
      </script>
      <![endif]-->
  </head>
  <body>
    <?php include('include/header.php');?>
    <div id="cnt">
    <?php echo $sf_content ?>
    </div>
    <?php include('include/footer.php');?>
  </body>
</html>
