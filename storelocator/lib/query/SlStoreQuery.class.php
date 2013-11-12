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
class SlStoreQuery extends Doctrine_Query {

  const STATUS_OPENED = 'Open';
  
  static public function create($conn = null, $class = null) {
    return parent::create($conn, 'SlStoreQuery')->from('SlStore s');
  }//create
  
  public function retrieveOnline() {
    return $this->addWhere('s.online = ?', array(true));
  }
  
  public function retrieveOpened() {
    return $this
      ->innerJoin('s.SlStoreStatus ss')
      ->isOnline()
      ->andWhere('ss.name = ?',  array(self::STATUS_OPENED));
  }//retrieveOpened
  
  public function addTypeFilter($type=NULL){
    if ($type == NULL) {
      return $this;
    }//if
    $type = is_array($type) ? $type : array($type);
    return $this
      ->innerJoin('s.SlStoreTypes st')
      ->whereIn('st.name', $type);
  }//addTypeFilter
  
  public function addProdLineFilter($prodline=NULL, $visible=true){
   
    $this->innerJoin('s.SlProductLines pl')->addWhere('pl.visible = ?', array($visible));
    if ($prodline == NULL) {
      return $this;
    }//if

    $prodline = is_array($prodline)? $prodline : array($prodline);
    return $this
      ->whereIn('pl.name', $prodline);
  }//addTypeFilter
  
  public function retrieveOpenedById ($id) {
    return $this->retrieveOpened()->andWhere('s.id = ?',  array($id));
  }//distinctCountries 
  
  public function retrieveClosest($dist, $maxDistance, $status, $prodline, $visible=true, $type) {
 
    return $this->select("*, ($dist) as distance")
      ->retrieveOpened()
      ->addProdLineFilter($prodline, $visible)
      ->addTypeFilter($type)
      ->addWhere("$dist <= ? and latitude <> ? and longitude <> ?", array($maxDistance, '', ''))
      ->orderBy('distance');

  }//retrieveClosest
  
  
  public function getExtraDataKey($datakey) {
    return $this->innerJoin('s.SlExtraData se')
        ->addWhere('se.datakey = ?', array($datakey));
  }//getExtraDataKey
  
  
  public function whereFilter ($country, $city, $type) {

    if ($country) {
      $this->addWhere('s.country_id = ?', array($country));
    }//if
    if ($city) {
      $this->addWhere('s.city_id = ?', array($city));
    }//if
    if ($type != "" && count($type) > 0) {
      $this->innerJoin('s.SlProductLines pl')->addWhere('pl.id = ?', array($type));
    }//if
    else {
      $this->innerJoin('s.SlProductLines pl')->addWhere('pl.visible = ?', array(true));
    }//else
    return $this;
  }//whereFilter
  
  public function inCountries($countries=array()) {
    return $this->whereIn('s.country_id', $countries);
  }//inCountries

  
  public function isOnline() {
    return $this->addWhere('s.online = ?', true);
  }//isOnline
  
  public function hidePlNotVisible() {
    return $this->innerJoin('s.SlProductLines pl')->addWhere('pl.visible = ?', array(true));
  }//hidePlNotVisible
  
}//SlStoreQuery 
