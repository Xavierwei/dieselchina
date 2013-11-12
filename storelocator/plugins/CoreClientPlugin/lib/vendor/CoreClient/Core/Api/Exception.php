<?php
/**
 * Eccezione Core_Exception.
 * 
 * Il client Core_Client solleva eccezioni di tipo Core_Exception 
 * nel caso in cui la chiamata al WS generi un errore o nel caso in cui 
 * la chiamata non sia formalmente corretta (manca un parametro obbligtorio)
 * 
 * @author ftassi
 * @package Core
 * @subpackage Exception 
 */
class Core_Api_Exception extends Exception{}