<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
  <meta name="viewport" content="width=320" />
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
    <?php if (!cache('include_core_stylesheets')): ?><?php include_core_stylesheets() ?><?php cache_save(); endif; ?>
    <?php include_cdn_stylesheets() ?>
    <?php if (has_slot("head_stylesheets")): ?>
      <?php include_slot("head_stylesheets");?>
    <?php endif; ?>
	  <style>
      body {
        overflow: hidden; 
      }
      
      #cnt, #footer, #header {
        min-width: 810px;
        padding: 0;
        overflow: hidden;
      }
      
      #cnt .layoutStoreLocator {
        margin: 52px 0 0 0;
      }
      
      #map {
      height: 340px !important; 
      }
    </style>
  </head>
  <body>
  <div id="header">
    <div class="logo"><a onclick="trackWoodland('main_menu_to_logo');" href="http://www.diesel.com/" target="_blank">DIESEL</a></div>
  </div>
    <div id="cnt">
    <?php echo $sf_content ?>
    </div>
    
    <div id="footer">
      <div class="cl" id="footer_top">
        <ul id="diesel_piva">
          <li>&copy; 2012 Diesel</li>
          <li>P.IVA IT00642650246</li>
        </ul>
      </div>
      
    </div>
    
  <?php use_helper('Analythics') ?>
  <?php echo get_analythics_code(); ?>
  </body>
  
  <div id="fb-root"></div>
  <script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>
  <script type="text/javascript" charset="utf-8">
  	FB.init({
  		appId  : '432921040054190', // solveig app 
  		//appId  : '122362567849567', // test generico
  		status : true, // check login status
  		cookie : true, // enable cookies to allow the server to access the session
  		xfbml  : true  // parse XFBML
  	});
  	FB.Canvas.setSize({height: 800});

  </script>
</html>
