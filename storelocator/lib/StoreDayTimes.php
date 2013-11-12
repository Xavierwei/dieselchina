<?php
class StoreDayTimes {
  const FROM = "from";
  const TO = "to";
  const FROMPM = "frompm";
  const TOPM = "topm";
  const CLOSED = "closed";
  const CLOSEDEV = "closed-ev";
  
  private $times;
  private $day;
  
  private function getDefault() {
    $times = array();
    $times["from"] = "8:30 AM";
    $times["frompm"] = "3:15 PM";
    $times["to"] = "12:30 AM";
    $times["topm"] = "8:45 PM";
    return $times;
  }//getDefault
  
  public function __construct ($day=NULL, $times=NULL) {
    $this->times = ($times==NULL? $this->getDefault() : $times) ;
    $this->day = $day;
  }//__construct
  
  public function isClosed($evening=false) {
    $key = $evening?self::CLOSEDEV : self::CLOSED;
    
    return key_exists( $key , $this->times)? ($this->times[$key]=='on') : false;
  }//isClosed
  
  public function getTime ($when) {
    return key_exists($when, $this->times) ? $this->times[$when] : NULL;
  }//getTime
  
  public function getDay() {
    return $this->day;
  }//getDay
  
  public function toString($twotimes=false) {
    $html = ucwords($this->day) . '<br/><span class="times">';
    $html .= ($this->isClosed()? 'Closed' : ($this->getTime( self::FROM ) . ' - ' . $this->getTime( self::TO ) ) ) .' ' ;
    if ($twotimes) {
      $html .= $this->isClosed(true)? ($this->isClosed()? '' : ' / Closed') : (' / ' .$this->getTime( self::FROMPM ) . ' - ' . $this->getTime( self::TOPM ) ) ;
    }//if
    return $html.'</span>';
  }//toString

}//StoreDayTimes 