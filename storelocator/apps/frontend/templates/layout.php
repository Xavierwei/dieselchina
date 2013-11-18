<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
      <meta name="viewport" content="width=640, minimum-scale=0.5, maximum-scale=1.0" />
  	
  	<?php if (has_slot('discovery_metas')):?>
  	  <?php include_slot('discovery_metas')?>
  	<?php endif;?>


    <meta name="title" content="Diesel - 单宁，成衣，鞋，腕表，饰品，内衣，时尚眼镜" />
    <meta name="keywords" content="Diesel - 单宁，成衣，鞋，腕表，饰品，内衣，时尚眼镜" />
    <meta name="description" content="Diesel - 单宁，成衣，鞋，腕表，饰品，内衣，时尚眼镜" />
    <link rel="image_src" href="<?php echo asset_absolute_path('/assets/core/img/logo.gif');?>" />
    <?php include_title() ?>
    <link rel="shortcut icon" href="/assets/core/img/favicon.ico" />
    <link rel="shortcut icon" href="/assets/core/img/favicon.gif" />
    <link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
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
      <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
              (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
              m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-45837418-1', 'dieselchina.com.cn');
          ga('send', 'pageview');

      </script>
  </head>
  <body>
    <?php include('include/header.php');?>
    <div id="cnt">
        <div class="mobile-title store-mobile-title"><img src="/img/mobile/title_store.png" /></div>
        <?php echo $sf_content ?>
    </div>
    <?php include('include/footer.php');?>
  </body>
</html>
