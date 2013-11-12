<?php

/**
 * itemsMap actions.
 *
 * @package    searchClient
 * @author     Denis Torresan
 */

// Necessary due to a bug in the Symfony autoloader
require_once(sfConfig::get('sf_plugins_dir').'/ItemsMapPlugin/modules/itemsMap/lib/BaseitemsMapActions.class.php');


class itemsMapActions extends BaseitemsMapActions
{

  /**
   * Implementare in questo metodo le query necessarie per costruire l'array di itemsmaps
   * 
   * @see plugins/ItemsMapPlugin/modules/itemsMap/lib/BaseitemsMapActions#getItemsMap()
   */
  protected function getItemsMap(){
    return parent::getItemsMap();
  }
  
}
