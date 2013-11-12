<?php

/**
 * Questa classe implementa dei metodi statici di utilit� per recuperare le configurazioni di un satellite.
 *
 * @package SatelliteSitesPlugin
 * @subpackage config
 * @author     dtorresan
 */
class SatelliteSitesConfig {

  const SATELLITE_PAGES_KEY        = 'pages';
  const SATELLITE_CORE_KEY         = 'core';
  const SATELLITE_BE_KEY           = 'be';
  const SATELLITE_API_KEY          = 'api';
  const SATELLITE_COLLECTIONS_KEY  = 'collection';
  const SATELLITE_STORELOCATOR_KEY = 'storelocator';
  

  /**
   * Ritorna la lista come array di tutti i satelliti
   * @return array
   */
  public static function getListOfSatellites(){
    $list = sfConfig::get('env_satellites', array());
    return $list;
  }

  
  /**
   * Ritorna la configurazione rispetto all'attuale satellite (se impostata la proprietà app_satellite_key)
   * @return unknown_type
   */
  public static function getCurrentConfig() {
    $satelliteKey = sfConfig::get('app_satellite_key', '');
    if( $satelliteKey != '' ){
      return SatelliteSitesConfig::getConfig($satelliteKey);
    }
    return NULL;
  }
  
  
  /**
   * Ritorna rispetto al setellite corrente, il valore della chiave ricercata
   * 
   * @param $key la chiave di cui restituire il valore
   * @return unknown_type
   */
  public static function getCurrentConfigKey($key){
    $config = SatelliteSitesConfig::getCurrentConfig();
    return $config[$key];
  }
  
  
  /**
   * Ritorna la configurazione specifica per il satellite identificato dalla chiave satelliteIdentifier
   * 
   * @param $satelliteIdentifier identificativo del satellite
   * @return unknown_type
   */
  public static function getConfig($satelliteIdentifier) {
    $list = self::getListOfSatellites();
    $config = array();
    if (key_exists($satelliteIdentifier, $list)) {
      $config = $list[$satelliteIdentifier];
    }
    return $config;
  }

  
  /**
   * Ritorna rispetto al setellite corrente, il valore della chiave ricercata
   * 
   * @param $satelliteIdentifier identificativo del satellite
   * @param $key la chiave di cui restituire il valore
   * @return unknown_type
   */
  public static function getConfigKey($satelliteIdentifier, $key){
    $config = SatelliteSitesConfig::getConfig($satelliteIdentifier);
    return $config[$key];
  }
  

  /**
   * Ritorna la configurazione del satellite sulla base dell'url
   * 
   * @param $satelliteUrl url del satellite da ricercare
   * @param $stripHost
   * @return unknown_type
   */
  public static function getConfigByUrl($satelliteUrl, $stripHost = false) {
    $satelliteHost = "";
    $compareBeginWith=false;
    Core_Logger::msg(__METHOD__." recupero url da ricercare nella configurazione dei satelliti" );
    if ($stripHost) {
      $protocol = parse_url($satelliteUrl, PHP_URL_SCHEME);
      $host = parse_url($satelliteUrl, PHP_URL_HOST);
      $port = parse_url($satelliteUrl, PHP_URL_PORT);
      if ($protocol != "") {
        $satelliteHost = $protocol."://";
      }
      $satelliteHost = $satelliteHost.$host;
      if ($port != "") {
        $satelliteHost = $satelliteHost.":".$port;
      }
      $compareBeginWith=false;

      Core_Logger::msg(__METHOD__." ricerca ESATTA di $satelliteHost " );
    } else {
      $compareBeginWith=true;
      $satelliteHost = $satelliteUrl;
      Core_Logger::msg(__METHOD__." ricerca INIZIA CON di $satelliteHost " );
    }
    $list = self::getListOfSatellites();
    $config = array();
    foreach ($list as $satelliteConfig) {
      if (
      ($compareBeginWith && (strncmp($satelliteHost, $satelliteConfig["url"], strlen($satelliteConfig["url"])) == 0) )
      ||
      (! $compareBeginWith && ($satelliteConfig["url"] == $satelliteHost) )
      ) {
        Core_Logger::msg(__METHOD__." TROVATO ".$satelliteConfig["url"] );
        //trovato
        $config = $satelliteConfig;
      }
    }
    return $config;
  }
}