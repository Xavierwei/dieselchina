<?php
/**
 * Interfaccia per definizione SSOUser
 * 
 * @author dtorresan
 * @package SSOPlugin
 */
interface SSOUser
{
  
  /**
   * Recupera la userUuid che viene accodato alle richieste
   * che fanno riferimento all'utente di sessione.
   * @return string
   */
  public function getUuid();
}