<?php
class dieselSsoFilter extends sfFilter
{
  public function execute($filterChain)
  {
    if (!$this->checkSsoCookie() && sfContext::getInstance()->getUser()->isAuthenticated())
    {
     // Core_Logger::msg(__METHOD__ . ' user isAuthenticated = ' . (string)sfContext::getInstance()->getUser()->isAuthenticated(), sfLogger::DEBUG);
     // Core_Logger::msg(__METHOD__ . ' Eseguo il logout', sfLogger::DEBUG);
      sfContext::getInstance()->getUser()->signout();
    }

    $filterChain->execute();
  }

  /**
   * Check del cookie SSO
   *
   * Il cookie SSO viene considerato valido
   * se esiste e se contiene l'id presente nella
   * sessione di symfony
   *
   * @return boolean
   */
  protected function checkSsoCookie()
  {
    $ssoCookie = sfConfig::get(myUser::CONFIG_SSO_COOKIE_NAME, myUser::DEFAULT_SSO_COOKIE_NAME);

    $uuid = sfContext::getInstance()->getRequest()->getCookie($ssoCookie, null);
    $d1 = sfContext::getInstance()->getRequest()->getCookie('d1', null);
    $d2 = sfContext::getInstance()->getRequest()->getCookie('d2', null);
    
   // Core_Logger::msg(__METHOD__ . ' cookie sso = ' . $uuid, sfLogger::DEBUG );
   // Core_Logger::msg(__METHOD__ . ' uuid sessione symfony = ' . sfContext::getInstance()->getUser()->getUuid(), sfLogger::DEBUG );
   // Core_Logger::msg(__METHOD__ . ' cookie d1 = ' . $d1, sfLogger::DEBUG );
   // Core_Logger::msg(__METHOD__ . ' cookie d2 = ' . $d2, sfLogger::DEBUG );
    
    if (! is_null($uuid))
    {
      return ((sfContext::getInstance()->getUser()->getUuid() == $uuid) && !is_null($d1) && !is_null($d2));
    }
    
    return false;
  }
}