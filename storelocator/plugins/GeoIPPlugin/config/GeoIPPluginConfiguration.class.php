<?php

/**
 * GeoIPPluginConfiguration configuration.
 *
 * @package GeoIPPlugin
 * @subpackage config
 * @author     dtorresan
 */
class GeoIPPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    //aggiungo le librerie all'include path
    sfToolkit::addIncludePath(dirname(__FILE__).'/../lib/vendor/geolite/geoip/api/php');
  }
}