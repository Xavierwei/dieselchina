<?php


/**
 * CdnAssetsHelper
 *
 * @author  dtorresan
 * @package SatelliteSitesPlugin
 */

/**
 * Ritorna true se la stringa inizia per $check
 *
 * @param $Haystack
 * @param $Needle
 * @return unknown_type
 */
function str_starts_with($Haystack, $Needle) {
  return strpos($Haystack, $Needle) === 0;
}

/**
 * Ritorna l'url assoluto dell'asset.
 * Se l'asset inizia per /, non viene aggiunto il path del satellite corrente perchè si suppone che sia già assoluto rispetto alla
 * root del webserver
 */
function asset_absolute_path($asset)
{
  //se url relativo lo mette assoluto rispetto al satellite
  if( str_starts_with($asset, "http://") ){
    return $asset;
  }
  
  //aggiungo il path del satellite solo se il path è relativo
  if( $asset{0} != "/" ){
    $asset = "/" . $asset;
    $asset = url_for_current_satellite_asset($asset);
  }
  
  $cdn_assets_url = sfConfig::get('env_cdn_assets_url', '');
  return $cdn_assets_url . $asset;
}


/**
 * Prints <link> tags for all stylesheets configured in view.yml or added to the response object.
 *
 * @see get_stylesheets()
 */
function include_cdn_stylesheets()
{
  $cdn_assets_url = sfConfig::get('env_cdn_assets_url', '');
  $stylesheets = get_stylesheets();
  echo str_replace('href="/', 'href="' . $cdn_assets_url . '/', $stylesheets);
}


/**
 * Prints <script> tags for all javascripts configured in view.yml or added to the response object.
 *
 * @see get_javascripts()
 */
function include_cdn_javascripts()
{
  $cdn_assets_url = sfConfig::get('env_cdn_assets_url', '');
  $javascripts = get_javascripts();
  echo str_replace('src="/', 'src="' . $cdn_assets_url . '/', $javascripts);
}
