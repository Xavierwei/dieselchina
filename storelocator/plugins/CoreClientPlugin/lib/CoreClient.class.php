<?php

/**
 * CoreClient è una classe che permette implementa metodi utili per la gestione del Core_Client.
 *
 * Per la configurazione vengono utilizzate le seguenti configurazioni (da inserire nel file app.yml):
 *   - env_CoreClientPlugin_endPoint:
 *   - env_CoreClientPlugin_consumerKey:
 *   - env_CoreClientPlugin_consumerSecret:
 *
 * Es (app.yml):
 *
 * all:
 *
 *   # Configurazione del plugin CoreClient
 *   CoreClientPlugin:
 *     endPoint: http://core.diesel.com
 *     consumerKey: 123
 *     consumerSecret: 12345678
 *
 * @author  dtorresan
 * @package CoreClientPlugin
 */
class CoreClient
{

  /**
   * Crea una nuova istanza del Core_Client.
   *
   * @return Core_Client una istanza di Core_Client
   */
  static public function createCoreClient()
  {
    $endPoint = sfConfig::get('env_CoreClientPlugin_endPoint', 'http://core.diesel.hostname');
    $userDataStore = new sfUserDataStore();
    $consumerKey = sfConfig::get('env_CoreClientPlugin_consumerKey', '');
    $consumerSecret = sfConfig::get('env_CoreClientPlugin_consumerSecret', '');

    return (new Core_Client($endPoint, $userDataStore, $consumerKey, $consumerSecret));
  }

  
  /**
   * Ritorna il risultato di una chiamata alle API
   *
   * Uso:
   *
   *   echo CoreClient::callAPIMethod('getFooter');
   *
   * @return string
   */
  static public function callAPIMethod($methodName, $params = array()){
    $coreClient = CoreClient::createCoreClient();

    $result = null;
    try{
      $result = call_user_func_array(array( $coreClient, $methodName), $params);
    }
    catch(Exception $e){
      return $e->getMessage();
    }

    return $result;
  }

  

  /**
   * Ritorna un blocco statico html (da utilizzarsi per le chiamate a blocchi statici come header/footer/ecc...
   *
   * Uso:
   *
   *   echo CoreClient::getBlock('getFooter');
   *
   * @return string
   */
  static public function getBlock($methodName, $parameters = array()){
    $coreClient = CoreClient::createCoreClient();

    $result = null;
    try{
      $result = call_user_func_array(array($coreClient, $methodName), $parameters);
    }
    catch(Exception $e){
      return $e->getMessage();
    }

    return $result->getBlock();
  }


  /**
   * Ritorna la collection di risultati
   *
   * Uso:
   *
   *   echo CoreClient::getCollection('itemGetRelatedById');
   *
   * @return string
   */
  static public function getCollection($methodName, $params = array()){
    $coreClient = CoreClient::createCoreClient();

    $result = null;
    try{
      $result = call_user_func_array(array( $coreClient, $methodName), $params);
    }
    catch(Exception $e){
      return $e->getMessage();
    }

    return $result->getCollection();
  }

}
