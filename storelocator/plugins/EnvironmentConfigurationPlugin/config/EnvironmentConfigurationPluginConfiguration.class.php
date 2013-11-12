<?php

class EnvironmentConfigurationPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    if ($this->configuration instanceof sfApplicationConfiguration)
    {
	  $this->configuration->getConfigCache()->registerConfigHandler('config/env.yml', 'sfDefineDomainConfigHandler', array ('prefix' => 'env_',));
	  include($this->configuration->getConfigCache()->checkConfig('config/env.yml'));
    }
  }
}
