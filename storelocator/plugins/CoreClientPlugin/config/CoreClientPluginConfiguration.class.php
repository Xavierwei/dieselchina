<?php

/**
 * CoreClientPluginConfiguration configuration.
 *
 * @package CoreClientPlugin
 * @subpackage config
 * @author     dtorresan
 */
class CoreClientPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    //imposto gli helpers del core come standard helpers
    $standard_helpers = sfConfig::get('sf_standard_helpers', array());

    //ciclo l'array di helpers da aggiungere (può essere più di uno separato da virgola)
    foreach (array('CoreClient') as $helper)
    {
      if (!in_array($helper, $standard_helpers))
      {
        $standard_helpers[] = $helper;
        sfConfig::set('sf_standard_helpers', $standard_helpers);
      }
    }
  }
}