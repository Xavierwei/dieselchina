<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/assets/core/img/favicon.ico" />
    <link rel="shortcut icon" href="/assets/core/img/favicon.gif" />
    <?php include_core_javascripts() ?>
    <?php include_cdn_javascripts() ?>
    
    <?php if (has_slot("head_javascripts")): ?>
      <?php include_slot("head_javascripts");?>
    <?php endif; ?>
    <?php include_core_stylesheets() ?>
    <?php include_cdn_stylesheets() ?>
    <?php if (has_slot("head_stylesheets")): ?>
      <?php include_slot("head_stylesheets");?>
    <?php endif; ?>
  </head>
  <body>
    <div id="cnt">
    <?php include_core_header() ?>
    <?php echo $sf_content ?>
    <?php include_core_footer() ?>
    </div>
    <?php use_helper('Analythics');?>
    <?php echo get_analythics_code();?>
  </body>
</html>
