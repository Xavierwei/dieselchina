<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_title() ?>
        
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        
        <?php if (has_slot('discovery_metas')):?><?php include_slot('discovery_metas')?><?php endif;?>
        
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="viewport" content="width=device-width; user-scalable=0;">
        
        <link rel="image_src" href="<?php echo asset_absolute_path('/assets/core/img/logo.gif');?>" />
        <link rel="shortcut icon" href="/assets/core/img/favicon.ico" />
        <link rel="shortcut icon" href="/assets/core/img/favicon.gif" />
        
        <?php if (!cache('include_core_stylesheets')): ?><?php include_core_stylesheets() ?><?php cache_save(); endif; ?>
        <?php include_cdn_stylesheets() ?>
        <?php $appContext = proxy_get_appcontext();?>
        <?php if( $appContext != '' ):?><link rel="stylesheet" type="text/css" media="screen" href="<?php echo asset_absolute_path("/assets/core/css/".$appContext.".css");?>" /><?php endif;?>
        <?php if (has_slot("head_stylesheets")): ?><?php include_slot("head_stylesheets");?> <?php endif; ?>
        
        <?php if (!cache('include_core_javascripts')): ?><?php include_core_javascripts() ?><?php cache_save(); endif; ?>
        <?php include_cdn_javascripts() ?>
    </head>
    <body>
        <?php $headerKey = proxy_get_appcontext();?>
        <?php if (!cache('include_core_header'.$headerKey)): ?><?php include_core_header() ?><?php cache_save(); endif; ?>
        <div id="cnt">
        <?php echo $sf_content ?>
        </div>
        <?php if (!cache('include_core_footer')): ?><?php include_core_footer() ?><?php cache_save(); endif; ?>
        <?php use_helper('Analythics') ?>
        <?php echo get_analythics_code(); ?>
    </body>
</html>
