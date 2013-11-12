<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  
  public function configureDoctrine(Doctrine_Manager $manager) {
	    $manager->setCollate('utf8_unicode_ci');
	    $manager->setCharset('utf8');
  }
    
  public function setup()
  {
    //aggiungo le librerie all'include path
    sfToolkit::addIncludePath(dirname(__FILE__).'/../lib/pear');

    //ridefinizione della configurazione sf_upload_dir su web/assets/store-locator
    $satelliteAssetsDir = sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.'assets';
    sfConfig::set('sf_upload_dir', $satelliteAssetsDir.DIRECTORY_SEPARATOR.'store-locator');

    //abilitazione plugins
    $this->enablePlugins(array(
      'Error404Plugin',
      'ToolkitPlugin',
      'sfDoctrinePlugin', 
      'sfJqueryReloadedPlugin',
      'EnvironmentConfigurationPlugin',
      'SSOPlugin',
      'CoreClientPlugin',
      'SatelliteSitesPlugin',
      'ItemsMapPlugin',
      'GeoIPPlugin',
      'caMarkdownEditorPlugin',
      'sfDoctrineGuardPlugin',
       'sfAdminDashPlugin'
    ));
  }
}
