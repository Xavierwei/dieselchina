<?php


/**
 * SatelliteSitesHelper
 *
 * @author  dtorresan
 * @package SatelliteSitesPlugin
 */


/**
 * Ritorna un url relativo al satellite corrente con chiave passata come parametro
 * @param $key la chiave del satellite
 * @param $path il path relativo del satellite
 * @return unknown_type
 */
function url_for_current_satellite($path){
  $relativePath = SatelliteSitesConfig::getCurrentConfigKey('relative_path');
  return $relativePath . $path;
}


/**
 * Ritorna un url relativo al satellite con chiave passata come parametro
 * @param $key la chiave del satellite
 * @param $path il path relativo del satellite
 * @return unknown_type
 */
function url_for_satellite($key, $path, $absolute = false){
  if ($absolute)
  {
    $relativePath = SatelliteSitesConfig::getConfigKey($key, 'url');
    $relativePath = substr($relativePath,0,-1);
  }
  else
  {
    $relativePath = SatelliteSitesConfig::getConfigKey($key, 'relative_path');
  }
  
  return $relativePath . $path;
}

/**
 * Ritorna un url relativo al satellite pages
 * @param $path
 * @return unknown_type
 */
function url_for_satellite_pages($path, $absolute = false){
  return url_for_satellite(SatelliteSitesConfig::SATELLITE_PAGES_KEY, $path, $absolute);
}


/**
 * Ritorna un url relativo al satellite be
 * @param $path
 * @return unknown_type
 */
function url_for_satellite_be($path, $absolute = false){
  return url_for_satellite(SatelliteSitesConfig::SATELLITE_BE_KEY, $path, $absolute);
}


/**
 * Ritorna un url relativo al satellite core
 * @param $path
 * @return unknown_type
 */
function url_for_satellite_core($path, $absolute = false){
  return url_for_satellite(SatelliteSitesConfig::SATELLITE_CORE_KEY, $path, $absolute);
}


/**
 * Ritorna un url relativo al satellite collection
 * @param $path
 * @return unknown_type
 */
function url_for_satellite_collection($path, $absolute = false){
  return url_for_satellite(SatelliteSitesConfig::SATELLITE_COLLECTIONS_KEY, $path, $absolute);
}


/**
 * Ritorna un url relativo al satellite store locator
 * @param $path
 * @return unknown_type
 */
function url_for_satellite_storelocator($path, $absolute = false){
  return url_for_satellite(SatelliteSitesConfig::SATELLITE_STORELOCATOR_KEY, $path, $absolute);
}


/**
 * Ritorna un url relativo al satellite corrente con chiave passata come parametro
 * @param $key la chiave del satellite
 * @param $path il path relativo del satellite
 * @return unknown_type
 */
function url_for_current_satellite_asset($path){
  $relativePath = SatelliteSitesConfig::getCurrentConfigKey('assets_path');
  return $relativePath . $path;
}
