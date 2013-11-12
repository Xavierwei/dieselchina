<?php

// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it or make something more sophisticated.
$localRequest = in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'));
$hartPublicIpRequest = in_array($_SERVER['REMOTE_ADDR'], array('94.198.78.26', '89.96.201.194', '85.40.90.146'));
$hfarmLanRequest = (substr($_SERVER['REMOTE_ADDR'], 0, 3) == '10.');

if (!($localRequest || $hartPublicIpRequest || $hfarmLanRequest))
{
  die('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration)->dispatch();
