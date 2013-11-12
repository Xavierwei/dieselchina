<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SlStoreQuery
 *
 * @author tram
 */
class SlCityQuery extends Doctrine_Query {

  
  static public function create($conn = null, $class = null) {
    return parent::create($conn, 'SlCityQuery')->from('SlCity sc');
  }//create
  
  public function selectName() {
    return $this->select('(sc.name) as city');
  }//selectName
 
  
  public function whereCountry($country_id) {
    return $this->addWhere('sc.country_id = ?', array($country_id));
  }//whereCountry
  
  
  public function joinOpenedStoresAndProductLine ($pline = NULL) {
    $this->innerJoin('sc.SlStore s')
        ->addWhere('s.store_status_id = ? and s.online = ?', array(1, 1));
    if ($pline) {
      $this->innerJoin('s.SlProductLines p')
          ->whereIn('p.id', $pline);
    }//if
    
    return $this;
  }//joinStores
  

}//SlStoreQuery 
