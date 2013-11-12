<?php
/**
 * Classe di interfaccia per accedere ai dati 
 * dell'utente di sessione.
 * 
 * Il Core_Client utilizza un'implementazione di User_Data_Store
 * per accedere ai dati dell'utente di sessiones
 * 
 * @author ftassi
 * @package Core
 * @subpackage UserDataStore
 *
 */
interface User_Data_Store_Interface
{
  
  /**
   * Verifica se esiste un utente di sessione
   * 
   * @return boolean
   */
  public function isAuthenticated();
  
  /**
   * Recupera la userUuid che viene accodato alle richieste
   * che fanno riferimento all'utente di sessione.
   * @return string
   */
  public function getUserId();
}