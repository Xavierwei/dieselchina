<?php
class StoreTimestable {

  
  private $timestable; 
  
  private $days = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
  
  private function initDays($times=NULL) {
    $ds = array();
    foreach ($this->days as $d) {
      $ds[$d] =  new StoreDayTimes($d, (($times && key_exists($d, $times)) ? $times[$d] : NULL) );
    }//foreach
    return $ds;
  }//initDays
  
  public function __construct ($times=NULL) {
    $this->timestable = $this->initDays($times);
  }//__construct
  
  public function getTimes ($day) {
    return $this->timestable[$day];
  }//getTimes
  
  public function toString($twotimes = false) {
    $html = '';
    foreach ($this->days as $d) {
      $html .= '<span>'.$this->getTimes($d)->toString($twotimes) . '</span><br/>';
    }//foreach
    return $html;
  }//toString
  
}//StoreTimestable