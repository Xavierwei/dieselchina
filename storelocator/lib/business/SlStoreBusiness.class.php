<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SlStoreBusiness
 *
 * @author tram
 */
class SlStoreBusiness {
  
  public function findClosestStore($latitude, $longitude, $maxDistance=10000000, $multiple = false, $pline=NULL, $visible=true, $type=NULL) {
    $k = 6372.795477598; //raggio quadratico medio
   // $maxDistance =10000000;

    // formule per il calcolo in radianti
    $lat_alfa = "PI()*  $latitude /180";
    $lat_beta = "PI()*  latitude /180";
    $lng_alfa = "PI()* $longitude /180";
    $lng_beta = "PI()* longitude /180";

    // calcolo angolo compreso
    $fi = " $lng_beta - $lng_alfa ";

    $sin ="SIN($lat_alfa)*SIN($lat_beta)";
    $cos = "COS($lat_alfa)*COS($lat_beta)";
    $dist = "($k * ACOS($sin + ($cos * COS($fi))))";

    $q = SlStoreQuery::create()->retrieveClosest($dist, $maxDistance, SlStoreQuery::STATUS_OPENED, $pline, $visible, $type);
    
    if (!$multiple) {
      $store = $q->fetchOne();
      if (!$store) {
        $store = SlStoreQuery::create()->retrieveOpened()->fetchOne();
      }//if
    }//if
    else {
      $store = $q->execute();
    }//else
    
    return $store;
  }//findClosestStore
  
  public function getCities($country, $pline=NULL) {
    $cities = SlCityQuery::create()
      ->select('sc.name, sc.id')
      ->distinct()
      ->joinOpenedStoresAndProductLine($pline)
      ->whereCountry($country)
      ->orderBy('sc.name ASC')
      ->fetchArray();

    $res = array();
    $res[] = '所有城市';
    foreach ($cities as $city) {
      $res[$city["id"]] = ucwords(strtolower($city["name"]));
    }//foreach
    return $res;
  }//getCities
  
  
  public function getCountries($pline = NULL) {
    $countries = SlCountryQuery::create()
      ->select('scn.name, scn.id')
      ->distinct()
      ->joinOpenedStoresAndProductLine($pline)
      ->orderBy('scn.name ASC')
      ->fetchArray();
    
    $res = array();
    $res[] = 'All Countries';
    foreach ($countries as $country) {
      if ($country["name"] != "") {
        $res[$country["id"]]= ucwords(strtolower($country["name"]));
      }//if
    }//foreach
    return $res;
  }//getCities
  
  public function getStores($country=null, $city=null, $type = array()) {

    $stores = SlStoreQuery::create()
        ->retrieveOpened()
        ->whereFilter($country, $city, $type)
        ->execute();
//    echo Util::getRawSqlQuery($stores);
//    var_dump ($stores);
//    die();
        //->orderBy('s.city ASC, order ASC, name ASC');
    
    $storesAsArray = array();
    

    foreach($stores as $store){

      $storesAsArray[] = array(
        'id'        => $store->getId(),
        'slug'      => $store->getSlug(),
        //'brand'     => strtoupper(Util::cleanNullValues($store->getBrand())),
        'name'      => Util::cleanNullValues($store->getName()),
        'address'   => Util::cleanNullValues($store->getAddress()),
        'city'      => Util::cleanNullValues($store->getCity()),
        'country'   => Util::cleanNullValues($store->getCountry()),
        'zip'       => Util::cleanNullValues($store->getZip()),
        'telf'      => Util::cleanNullValues($store->getTelf()),
        'latitude'  => Util::cleanNullValues($store->getLatitude()),
        'longitude' => Util::cleanNullValues($store->getLongitude()),
        'public_type' =>  Util::cleanNullValues($store->getOneType()),
        //'info' =>  Util::cleanNullValues(nl2br($store->getStoreExtraData()->getInfo())),
        'hours' => $store->hasTimes()? $store->getTimestable()->toString(): '',
        //'additional' => $store->getStoreExtraData()->getTimesNotes()?$store->getStoreExtraData()->getTimesNotes():"",
      );

    }
   
    return $storesAsArray;
  }
  
}//SlStoreBusiness 


