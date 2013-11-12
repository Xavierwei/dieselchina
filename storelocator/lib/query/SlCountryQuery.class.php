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
class SlCountryQuery extends Doctrine_Query {

  
  static public function create($conn = null, $class = null) {
    return parent::create($conn, 'SlCountryQuery')->from('SlCountry scn');
  }//create
  
  public function selectName() {
    return $this->select('(scn.name) as country');
  }//selectName
 
  public function joinOpenedStoresAndProductLine ($pline = NULL) {
    $this->innerJoin('scn.SlStore s')
        ->addWhere('s.store_status_id = ? and s.online = ?', array(1, 1));
    if ($pline) {
      $this->innerJoin('s.SlProductLines p')
          ->whereIn('p.id', $pline);
    }//if
    
    return $this;
  }//joinStores

}//SlCountryQuery 
