<?php

/**
 * SatelliteSitesPluginConfiguration configuration.
 *
 * @package SatelliteSitesPlugin
 * @subpackage config
 * @author     dtorresan
 */
class SatelliteSitesPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    //imposto gli helpers come standard helpers
    $standard_helpers = sfConfig::get('sf_standard_helpers', array());

    //ciclo l'array di helpers da aggiungere (può essere più di uno separato da virgola)
    foreach (array('SatelliteSites', 'CdnAssets') as $helper)
    {
      if (!in_array($helper, $standard_helpers))
      {
        $standard_helpers[] = $helper;
        sfConfig::set('sf_standard_helpers', $standard_helpers);
      }
    }
  }
}