<?php

class Util {
  
  static function getRawSqlQuery(Doctrine_Query $query) {
    $queryString = $query->getSqlQuery();
  
    foreach ($query->getFlattenedParams() as $param) {
      $queryString = join(var_export(is_scalar($param) ? $param : (string) $param, true), explode('?', $queryString, 2));
    }//foreach
  
    return $queryString;
  }//getRawSqlQuery
  
  static function cleanNullValues($val){
    return !empty($val) ? $val : '';
  }//cleanNullValues
  
  
}//Util