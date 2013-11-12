<?php

function string_slugify($text)
{
  return Doctrine_Inflector::urlize($text);
}

function getHumanCreatedAt($from, $to = "") 
{
  $from = strtotime($from);
  if ( empty($to) )
  $to = time();
  $diff = (int) abs($to - $from);
  if ($diff <= 3600) {
    $mins = round($diff / 60);
    if ($mins <= 1) {
      $mins = 1;
    }
    $since = sprintf(getN('%s min', '%s mins', $mins), $mins);
  } else if (($diff <= 86400) && ($diff > 3600)) {
    $hours = round($diff / 3600);
    if ($hours <= 1) {
      $hours = 1;
    }
    $since = sprintf(getN('%s hour', '%s hours', $hours), $hours);
  } elseif ($diff >= 86400) {
    $days = round($diff / 86400);
    if ($days <= 1) {
      $days = 1;
    }
    $since = sprintf(getN('%s day', '%s days', $days), $days);
  }
  return $since;
}

function getN($singular, $plural, $n)
{
  if ($n > 1 ) {
    return $plural;
  } else {
    return $singular;
  }

}