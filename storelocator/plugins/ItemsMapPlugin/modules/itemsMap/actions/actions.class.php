<?php

/**
 * itemsMap actions.
 *
 * @package    searchClient
 * @author     Denis Torresan
 */

// Necessary due to a bug in the Symfony autoloader
require_once(dirname(__FILE__).'/../lib/BaseitemsMapActions.class.php');

class itemsMapActions extends BaseitemsMapActions
{
  // See how this extends BaseitemsMapActions? You can replace it with
  // your own version by adding a modules/itemsMap/actions/actions.class.php
  // to your own application and extending BaseitemsMapActions there as well.
}
