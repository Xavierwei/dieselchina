<?php


/**
 * CoreClientHelper.
 *
 * @author  dtorresan
 * @package CoreClientPlugin
 */


/**
 * Ritorna l'url assoluto della pagina corrente
 * @return unknown_type
 */
function url_for_current_page(){
  return url_for ( sfContext::getInstance()->getRouting()->getCurrentInternalUri(), true );
}


/**
 * Ritorna l'url assoluto dell'action socialize.
 */
function socialize_url_for($type, $shareUrl = null)
{
  if (is_null($shareUrl))
  {
    $url = url_for_current_page();
  }
  else
  {
    $url = $shareUrl;
  }
  $path = '/core/socialize?type=' . $type . '&url=' . $url;
  return $path;
}


/**
 * Ritorna il link con chiamata onclick su JQuery per il box socialize.
 * Esempi di utilizzo:
 * 
 * <?php echo socialize_link_to('ilikeit', 'Like', 'btn like'); ?>
 * <?php echo socialize_link_to('share', 'Share', 'btn share'); ?>
 * <?php echo socialize_link_to('comment', 'View all', 'link view-all'); ?>
 * <?php echo socialize_link_to('tag', 'Add tag', 'link add-tag'); ?>
 *
 */
function socialize_link_to($type, $value, $cssClass, $shareUrl = null)
{
  $link = socialize_url_for($type, $shareUrl);
  return link_to($value, $link, array('class'=>$cssClass.' social'));
}


/**
 * Ritorna l'header da includere fornito dal Core
 *
 * @return string XHTML compliant <link> tag
 */
function include_core_header($params = array())
{
  echo CoreClient::getBlock('blockGetHeader', $params);
}


/**
 * Ritorna il footer da includere fornito dal Core
 *
 * @return string XHTML compliant <link> tag
 */
function include_core_footer($params = array())
{
  echo CoreClient::getBlock('blockGetFooter', $params);
}


/**
 * Ritorna il blocco stylesheet da includere fornito dal Core
 *
 * @return string XHTML compliant <link> tag
 */
function include_core_stylesheets()
{
  echo CoreClient::getBlock('blockGetStylesheets') . "\n";
}


/**
 * Ritorna il blocco javascript da includere fornito dal Core
 *
 * @return string XHTML compliant <link> tag
 */
function include_core_javascripts()
{
  echo CoreClient::getBlock('blockGetJavascripts') . "\n";
}
