<?php

class StoresQuery extends Doctrine_Query {
  
  static public function create($conn = null, $class = null) {
    return parent::create($conn, 'StoresQuery')->from('Stores s');
  }//create
  
  public function distinctCountries () {
    return $this->select('DISTINCT (s.country) as c')
        ->orderBy('c');
  }//distinctCountries 

  public function selectDataByCity($city) {
    return $this->select('s.area as area, s.country as country')
        ->where('s.city = ?', array($city))
        ->limit(1);
  }//selectDataByCity

  public function distinctCities () {
    return $this->select('DISTINCT (s.city) as c')
        ->orderBy('c');
  }//distinctCities

  public function distinctStoreType () {
    return $this->select('DISTINCT (s.type) as t')
        ->orderBy('t');
  }//distinctCities
}//StoreQuery 

