<?php

/**
 * SlStore
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    collections
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class SlStore extends BaseSlStore {

  public function getCountry () {
    $c = $this->getSlCountry();
    return $c ? $c->getName() : NULL;
  }//getCountry

  public function getCity () {
    $c = $this->getSlCity();
    return $c ? $c->getName() : NULL;
  }//getCity

  public function getType () {
    $c = $this->getSlStoreType();
    return $c ? $c->getName() : NULL;
  }//getCity

  public function getExtraDataByKey($datakey) {
    $datas = $this->getSlExtraData();
    foreach ($datas as $d) {
      if ($d->getDatakey() == $datakey) {
        return $d;
      }//if
    }//foreach
    return NULL;
  }//getExtraDataByKey
  

  public function getInfo () {
    return $this->getExtraDataByKey(SlExtraData::INFO);
  }//getTimestable

  public function getAdditionalAddress () {
    return $this->getExtraDataValue(SlExtraData::ADDITIONAL_ADDRESS);
  }//getAdditionalAddress

  public function hasType ($type_id) {
    $types = $this->getSlStoreTypes();

    foreach ($types as $t) {
      if ($t->getId() == $type_id) {
        return true;
      }//if
    }//foreach
    return false;
  }//hasType
  
  public function setExtraData($key, $value) {
    $extradata = $this->getExtraDataByKey($key);
    if ($extradata) {
      $extradata->setValue($value);
    }//if
    else {
      $extradata = new SlExtraData();
      $extradata->setDatakey($key);
      $extradata->setValue($value);
      $extradata->setSlStore($this);
    }//else
    return $extradata->save();
  }//setExtraData

  public function isOwnedBy ($currUs) {
    $user = $this->getSlCountry()->getsfGuardUser();
   
    return $user->getId() == $currUs->getId() || $currUs->getIsSuperAdmin();
  }//isOwnedBy
  
  public function hasTwoTimesADay () {
    return $this->getExtraDataValue(SlExtraData::TWO_TIMES_A_DAY);
  }//hasTwoTimesADay
  
  public function setTwoTimesADay ($twoTimes=true) {
    return $this->setExtraData(SlExtraData::TWO_TIMES_A_DAY, $twoTimes);
  }//setTwoTimesADay
  
  public function setTimestable ($timestable) {
    return $this->setExtraData(SlExtraData::TIMESTABLE, serialize($timestable));
  }//setTimestable
  
  public function getExtraDataValue ($key) {
    $data = $this->getExtraDataByKey($key);
    return $data? $data->getValue() : NULL;
  }//getExtraDataValue
  
  public function getTimestable () {
    $times = $this->getExtraDataValue(SlExtraData::TIMESTABLE);
    return $times? unserialize($times) : new StoreTimestable() ;
  }//getTimestable

  public function hasTimes () {
    return $this->getExtraDataValue(SlExtraData::HAS_TIMESTABLE);
  }//hasTimes

  public function setHasTimestable ($hasTimes=true) {
    return $this->setExtraData(SlExtraData::HAS_TIMESTABLE, $hasTimes);
  }//setHasTimestable

  public function getLatestNews() {
    return NULL;
   //return SlStoreNewsTable::createQuery('SlStoreNews sn'); //->where('sn.store_id = ?', array($this->getId()))->orderBy('sn.created_at desc')->fetchOne();
  }//getLastNews
  
  public function addProductLine(SlProductLine $prodline) {
    $spl = new SlStoreProductLine();
    $spl->setSlStoreId($this->_get('id'));
    $spl->setSlProductLineId($prodline->getId());
    $spl->save();
    return true;
  }//addProductLine

  public function getOneType() {
    $types = $this->getSlStoreTypes();
    $tp = $types[0];
    return $tp->getName();
  }
  
  public function isClosed () {
    $stat = $this->getSlStoreStatus();
    return $stat->getName() != 'Open';
  }//isClosed

  
  public function hasProductLine ($pl_id) {
    $plines = $this->getSlProductLines();

    foreach ($plines as $pl) {
      if ($pl->getId() == $pl_id) {
        return true;
      }//if
    }//foreach
    return false;
  }//hasType
  
  
  
  public function delete(Doctrine_Connection $conn = null) {
    $pls = SlStoreProductLineTable::getInstance()->findBySlStoreId($this->_get('id'));
    
    foreach ($pls as $p) {
      $p->delete();
    }//foreach
    
    $st = SlStoreStoreTypeTable::getInstance()->findBySlStoreId($this->_get('id'));
    
    foreach ($st as $t) {
      $t->delete();
    }//foreach
    
    $exs = SlExtraDataTable::getInstance()->findByStoreId($this->_get('id'));
    
    foreach ($exs as $e) {
      $e->delete();
    }//foreach

    return parent::delete($conn);
  }//delete
  
  
}//SlStore
