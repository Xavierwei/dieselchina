<?php

/**
 * Classe Symfony per accedere ai dati dell'utente di sessione.
 * 
 * Viene utilizzata dal Core_Client per accedere ai dati dell'utente di sessione.
 * 
 * @author  dtorresan
 * @package CoreClientPlugin
 */
class sfUserDataStore implements User_Data_Store_Interface
{

  /**
   * Verifica se esiste un utente di sessione
   *
   * @return boolean
   */
  public function isAuthenticated(){
    return sfContext::getInstance()->getUser()->isAuthenticated();
  }

  /**
   * Recupera la userUuid che viene accodato alle richieste
   * che fanno riferimento all'utente di sessione.
   * @return string
   */
  public function getUserId(){
    if( sfContext::getInstance()->getUser() instanceof SSOUser ){
      return sfContext::getInstance()->getUser()->getUuid();
    }
    else{
      return null;
    }
  }

}
