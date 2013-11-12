<?php

/**
 * Classe che estende sfBasicSecurityUser ed implementa i metodi per verificare se un utente è loggato oppure no.
 * Si integra attraverso cookie al core, in tal modo la verifica dei dati utente viene effettuata decriptando ed interpretando
 * il cookie.
 *
 * @author  dtorresan
 * @package SSOPlugin
 */
class SSOSecurityUser extends sfBasicSecurityUser implements SSOUser
{

  //nome del cookie di SSO
  const SSO_COOKIE_NAME = 'diesel_sso';

  /**
   * Cookie d1 yoox
   * @var string
   */
  const D1_COOKIE_NAME = 'd1';

  /**
   * Cookie d2 yoox
   * @var string
   */
  const D2_COOKIE_NAME = 'd2';

  //user unique id valorizzato se utente autenticato
  protected $uuid = null;


  /**
   * Initializes the SSOSecurityUser object.
   *
   * @param sfEventDispatcher $dispatcher The event dispatcher object
   * @param sfStorage $storage The session storage object
   * @param array $options An array of options
   */
  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    parent::initialize($dispatcher, $storage, $options);
    sfContext::getInstance()->getLogger()->debug(__METHOD__);
    //preleva il cookie ed imposta stato utente

    if( $this->checkAuthCookie() ){
      sfContext::getInstance()->getLogger()->debug(__METHOD__ . ' Cookie SSO validi');
      $authCookieValue = $this->getAuthCookieValue();
      $this->uuid = $authCookieValue;
      $this->setAuthenticated(true);
    }
//Commentato per evitare di cachare la cancellazione del cookie sso
//    else{
//      sfContext::getInstance()->getLogger()->debug(__METHOD__ . ' Cookie SSO NON validi');
//      $this->uuid = null;
//      $this->setAuthenticated(false);
//      $this->cleanSSOCookie();
//    }
  }

  /**
   * Returns whether or not the user is anonymous.
   *
   * @return boolean
   */
  public function isAnonymous()
  {
    return !$this->isAuthenticated();
  }

  /**
   * Recupera la uuid che viene accodato alle richieste
   * che fanno riferimento all'utente di sessione.
   * @return string
   */
  public function getUuid()
  {
    return $this->uuid;
  }


  /**
   * Returns the string representation of the object.
   *
   * @return string
   */
  public function __toString()
  {
    return "";
  }


  /**
   * Ritorna il valore del cookie di autenticazione o null se non trovato o non valido
   * @return string
   */
  private function getAuthCookieValue(){
    $cookieValue = sfContext::getInstance()->getRequest()->getCookie(self::SSO_COOKIE_NAME);

    if( !empty($cookieValue)){
      //verifica correttezza valore
      if( !ctype_digit ( $cookieValue ) ){
        $cookieValue = null;
      }
    }

    return $cookieValue;
  }

  /**
   * Verifica l'esistenza dei cookie di sso.
   *
   * Per considerare la sessione valida devono essere
   * presenti i cookie d1 d2 (entrambi di yoox) e diesel_sso
   * @return unknown_type
   */
  private function checkAuthCookie()
  {
    $ssoCookieValue = sfContext::getInstance()->getRequest()->getCookie(self::SSO_COOKIE_NAME, null);
    $d1Value = sfContext::getInstance()->getRequest()->getCookie(self::D1_COOKIE_NAME, null);
    $d2Value = sfContext::getInstance()->getRequest()->getCookie(self::D2_COOKIE_NAME, null);
    
    sfContext::getInstance()->getLogger()->debug(__METHOD__ . self::SSO_COOKIE_NAME. ' = ' . $ssoCookieValue);
    sfContext::getInstance()->getLogger()->debug(__METHOD__ . self::D1_COOKIE_NAME. ' = ' . $d1Value);
    sfContext::getInstance()->getLogger()->debug(__METHOD__ . self::D2_COOKIE_NAME. ' = ' . $d2Value);
    
    if (is_null($ssoCookieValue) || is_null($d1Value) || is_null($d2Value))
    {
      sfContext::getInstance()->getLogger()->debug(__METHOD__ . ' Return false');
      return false;
    }
    
    sfContext::getInstance()->getLogger()->debug(__METHOD__ . ' Return true');
    return true;
  }

  /**
   * Elimina il cookie SSO
   */
  protected function cleanSSOCookie()
  {
    sfContext::getInstance()->getLogger()->debug(__METHOD__ . ' Elimino cookie SSO');
    $ssoCookie = self::SSO_COOKIE_NAME;
    sfContext::getInstance()->getResponse()->setCookie(
    $ssoCookie,
    '',
    time() - 86400,
    '/',
    sfConfig::get('env_sso_cookie_domain', '.diesel.com')
    );
  }

}
