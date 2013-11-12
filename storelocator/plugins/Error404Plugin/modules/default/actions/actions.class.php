<?php
require_once(sfConfig::get('sf_plugins_dir').'/ItemsMapPlugin/modules/itemsMap/lib/BaseitemsMapActions.class.php');

/**
 * itemsMap actions.
 *
 * @author ftassi
 *
 */
class defaultActions extends sfActions
{
  /**
   * Error page for page not found (404) error
   *
   */
  public function executeError404()
  {
    
    $urls = array (
      '/collection/filter/female/ss12',
      '/collection/filter/male/ss12',
      '/jogg-jeans/filter/male/ss12',
      '/3d',
      '/lightexposure',
      '/fityourattitude',
      '/male',
      '/female',
      '/collection-diesel-home',
      '/timeframes',
      '/eyewear'
    );
    shuffle ($urls);
    $this->randomurl = $urls[0];
    $this->setLayout('layoutNoCache');
  }
}