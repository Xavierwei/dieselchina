<?php

require_once 'geoipcity.inc';


/**
 * GeoIPProxy mette a disposizione un'interfaccia per accedere al database GeoIP di maxmind.com.
 *
 * Per maggiori dettagli vedere:
 *
 * http://www.maxmind.com/app/php
 *
 * Il database aggiornato con gli IP pu� essere scaricato da questo indirizzo:
 *
 * http://www.maxmind.com/app/geolitecity
 *
 *
 * @author  dtorresan
 * @package GeoIPPlugin
 */
class GeoIPWrapper
{

  /** oggetto di tipo GeoIP */
  private $geoip;


  /**
   * Class constructor.
   */
  public function __construct()
  {
    //apri il database
    $geoIPCityPath = sfConfig::get('sf_data_dir') . '/geoip/GeoLiteCity.dat';
    $this->geoip = geoip_open($geoIPCityPath, GEOIP_STANDARD);
  }


  /**
   * Class destructor.
   */
  function __destruct() {
    if(!empty( $this->geoip ) ){
      geoip_close($this->geoip);
    }
  }


  /**
   * Ritorna l'address in base alla request passata
   *
   * @param $request
   * @return unknown_type
   */
  public function getAddrByRequest(sfWebRequest $request){
    //prelevo l'indirizzo del client sia con chiamata diretta che sotto reverse proxy
    $addr = $request->getRemoteAddress();
    if( $request->getForwardedFor() ){
      $addr = $request->getForwardedFor(); //IP da reverse proxy
      $addr = $addr[0];
    }

    //FIXME: solo per testare IP diversi dal proprio, togliere poi
    if( $request->getParameter('ip', null) ){
      $addr = $request->getParameter('ip', null);
    }

    return $addr;
  }


  /**
   * Ritorna l'oggetto record in base all'indirizzo inserito
   *
   * @param $addr indirizzo ip
   * @return un oggetto geoiprecord
   */
  public function getGeoIPRecordByAddr($addr)
  {
    return geoip_record_by_addr($this->geoip, $addr);
  }


  /**
   * Ritorna l'oggetto record in base alla request sfWebRequest
   *
   * @param $addr indirizzo ip
   * @return un oggetto geoiprecord
   */
  public function getGeoIPRecordByRequest(sfWebRequest $request)
  {
    $addr = $this->getAddrByRequest($request);

    return geoip_record_by_addr($this->geoip, $addr);
  }


  /**
   * Ritorna il country code di provenienza in base all'IP passato. Null se non trovato.
   * Lo stesso risultato si può ottenere con:
   * $record = getGeoIPRecordByAddr($addr);
   * $record->country_code;
   *
   * @param $addr indirizzo ip
   * @return string country code
   */
  public function getCountryCodeByAddr($addr){
    $record = $this->getGeoIPRecordByAddr($addr);
    if( $record ){
      return $record->country_code;
    }

    return NULL;
  }


  /**
   * Ritorna il country code di provenienza in base alla request passata. Null se non trovato.
   * Lo stesso risultato si può ottenere con:
   * $record = getGeoIPRecordByAddr($addr);
   * $record->country_code;
   *
   * @param $addr indirizzo ip
   * @return string country code
   */
  public function getCountryCodeByRequest(sfWebRequest $request){
    $addr = $this->getAddrByRequest($request);

    return $this->getCountryCodeByAddr($addr);
  }


  /**
   * Ritorna la stringa con il nome della regione corrispettivo in base al record passato
   *
   * @return un oggetto geoiprecord
   */
  public function getRegionName($record)
  {
    if( $record ){
      include 'geoipregionvars.php';
      return $GEOIP_REGION_NAME[$record->country_code][$record->region];
    }
    return NULL;
  }

}
