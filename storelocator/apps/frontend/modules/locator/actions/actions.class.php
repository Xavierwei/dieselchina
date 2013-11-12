<?php

/**
 * page actions.
 *
 * @package    collections
 * @subpackage page
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class locatorActions extends sfActions
{

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    $browser = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
    if ($browser !== FALSE)  {
      $this->redirect('@homepage_mobile'); 
    }//if
  
    $this->jsstorelocated = false;
    $this->getStore($request);
    $this->setTemplate('restyling');
  }
  
  public function executeIndexMobile(sfWebRequest $request) {
    
    $this->storesFinderForm = new StoresFinderForm();
    $this->setLayout('layoutMobile');
  }
  

  /**
   * Scheda di dettaglio dello store.
   *
   * La action viene differenziata dalla home per poter cachare
   * la richiesta.
   */
  public function executeStoreDetail(sfWebRequest $request)
  {
    $this->getStore($request);

    $this->setTemplate('restyling');
  }


  
  
  /* store locatore facebook */
  public function executeFacebookSplash(sfWebRequest $request)
  {
  //  die ($request->getParameter('kid'));
    $this->setLayout('layoutFacebook');
    $this->jsstorelocated = false;
    $this->isFb = true;
    $this->getStore($request);
    $this->setTemplate('restyling');
    
  }
  
  /* store locatore facebook */
  public function executeFacebookStore(sfWebRequest $request)
  {
		$this->getStore($request);
  }
  
  public function executeFacebookFinder(sfWebRequest $request)
  {
    $this->isClosestStore = false;

    //Store Finder
    $this->storesFinderForm = new StoresFinderForm();

		//valorizzo la variabile js degli stores
    $this->stores = array();
		$this->storesJSON = json_encode($this->stores);
  }

 
  /**
   * Permette la visualizzazione degli stores filtrata per country e city se presenti
   *
   * @param $request
   */
  public function executeStores(sfWebRequest $request) {
    $this->country = $request->getParameter('country', null);
    $this->city = $request->getParameter('city', null);

    $this->currentStore = false;
    $this->isClosestStore = false;

    //Store Finder
    $this->storesFinderForm = new StoresFinderForm();

    //se è presente un country, pre popolo il form con i valori di default corretti per country e city
    if( $this->country ){
      $this->storesFinderForm->bind( array( 'country'=>$this->country, 'city'=>$this->city ) );

      //valorizzo la select city in base al country
      $cities = $this->getFormFieldCityValue($this->country);
      $this->storesFinderForm->getWidget('city')->setOption( 'choices', $cities);

      if( $this->city ){
        $this->storesFinderForm->getWidget('city')->setDefault($this->city);
      }//if

      //valorizzo la variabile js degli stores
      $this->stores = StoresTable::getStores($this->country, $this->city);
      $this->storesJSON = json_encode($this->stores);
      $this->storeId = 0;

      //valorizzo le news associate allo store
      $this->storeNews = null;

      $title = $this->country;
      if( $this->city ){
        $title .= ', ' . $this->city;
      }//if
      //titolo pagina
      $this->getResponse()->setTitle("Diesel Store Locator - " . $title);
    }//if
    else{
      $this->forward404();
    }//else

    $this->setTemplate('index');
  }//executeStores


  /**
   * Lista di tutti gli stores, utile per indicizzazione SEO.
   *
   * La action viene differenziata dalla home per poter cachare
   * la richiesta.
   */
  public function executeStoreList(sfWebRequest $request) {
    $this->getResponse()->addMeta('ROBOTS', 'NOINDEX, FOLLOW');
    $this->stores = StoresTable::retrieveOpened();
  }//executeStoreList


  /**
   * Executes getCountries action
   *
   * @param sfRequest $request A request object
   */
  public function executeGetCountries(sfWebRequest $request) {
    $pline = $request->getParameter('type');
    if ($pline && !is_array($pline)) {
      $pline = array($pline);
    }//if
    $sb = new SlStoreBusiness();
    $countries = $sb->getCountries($pline);
    
    $this->setLayout(false);
    $this->getResponse()->setHttpHeader('Content-Type','application/json; charset=utf-8');

    return $this->renderText( json_encode($countries) );
  }//executeGetCountries


  /**
   * Executes getCities action
   *
   * @param sfRequest $request A request object
   */
  public function executeGetCities(sfWebRequest $request) {
    $country = $request->getParameter('country', null);
    $pline = $request->getParameter('type');
    if ($pline && !is_array($pline)) {
      $pline = array($pline);
    }//if
    
    $cities = $this->getFormFieldCityValue($country, $pline);
    $this->setLayout(false);
    $this->getResponse()->setHttpHeader('Content-Type','application/json; charset=utf-8');

    return $this->renderText( json_encode($cities) );
  }//executeGetCities


  /**
   * Executes getStores action
   *
   * @param sfRequest $request A request object
   */
  public function executeGetStores(sfWebRequest $request) {
    $country = $request->getParameter('country', null);
    $city      = $request->getParameter('city', null);
    $type      = $request->getParameter('type', null);

    $sb = new SlStoreBusiness();
    $stores = $sb->getStores($country, $city, $type);

//    $stores    = StoresTable::getStores($country, $city, $type);
    $this->setLayout(false);
    $this->getResponse()->setHttpHeader('Content-Type','application/json; charset=utf-8');
    return $this->renderText( json_encode($stores) );
  }


  /**
   * Ritorna la lista di città in base al countryId.
   * Se non ci sono stores allora ritorna "All Cities".
   * @param $countryId
   * @return unknown_type
   */
  private function getFormFieldCityValue($country, $pline = NULL){
    $bs = new SlStoreBusiness();
    $cities = $bs->getCities($country, $pline);

    return $cities;
  }//getFormFieldCityValue


  /**
   * Ritorna lo store più vicino sulla base dell'IP
   * oppure se valorizzato il parametro ID, ritorna lo store relativo
   * @param sfWebRequest $request
   */
  protected function getStore(sfWebRequest $request) {
    $this->isClosestStore = false;

    $id = $request->getParameter('id', null);
    
    if( $id ){
      //prelevo i dati dello store
//      $this->currentStore = StoresTable::retrieveById($id);
      $this->currentStore = SlStoreQuery::create()->retrieveOpenedById($id)->fetchOne();
      $this->isClosestStore = false;
    }//if
    else{
      //ricerco lo store piu vicino
      $this->isClosestStore = true;

      //georeferenzio l'utente in base all'IP
      $geoIp = new GeoIPWrapper();
      $geoIpRecord = $geoIp->getGeoIPRecordByRequest($request);

      $latitude = 0;
      $longitude = 0;

      if( $geoIpRecord ){
        $latitude = $geoIpRecord->latitude;
        $longitude = $geoIpRecord->longitude;
        $this->jsstorelocated = true;
      }//if
      //die("rhi");
      $this->currentStore = $this->getClosestStore( $request, NULL, $latitude, $longitude);
      //var_dump($this->currentStore->toArray());
     // die();
      /*$bStore = new SlStoreBusiness();
      $this->currentStore  = $bStore->findClosestStore($latitude, $longitude);*/
    
    
      
//      $latitude =  42.8333;
//      $longitude =12.8333;
      //$bStore = new SlStoreBusiness();
      //$this->currentStore  = $bStore->findClosestStore($latitude, $longitude);

    }//else

    //Store Finder
    $this->storesFinderForm = new StoresFinderForm();

    //se è stato individuato uno store, pre popolo il form con i valori di default corretti per country e city
    if( $this->currentStore ){
      $this->country = $this->currentStore->getCountryId();
      $this->storesFinderForm->bind( array( 'country'=>$this->country ) );

      //valorizzo la select city in base al country
      $cities = $this->getFormFieldCityValue($this->country);
      $this->storesFinderForm->getWidget('city')->setOption( 'choices', $cities);
      $this->storesFinderForm->getWidget('city')->setDefault($this->currentStore->getCity());

      //valorizzo la variabile js degli stores
      $this->stores = StoresTable::getStores($this->country, $this->currentStore->getCity());
      $this->storesJSON = json_encode($this->stores);
      $this->storeId = $this->currentStore->getId();

      //valorizzo le news associate allo store

      $this->storeNews = $this->currentStore->getLatestNews();
      
      //titolo pagina
      $this->getResponse()->setTitle("Diesel Store Locator - " . $this->currentStore->getName() . ', ' . $this->currentStore->getAddress());
    }//if
    else{
      $this->forward404();
    }//else
  }//getStore

  
  
  
  /**
   * Recupera gli store nella città dello store più vicino
   *
   * @param sfWebRequest $request
   */
  public function executeGetClosestsCityStores(sfWebRequest $request)
  {
    $lat = $request->getParameter('lat');
    $lng = $request->getParameter('lng');
    $store = StoresTable::findClosestStore($lat, $lng);

    $parmHolder = $this->getRequest()->getParameterHolder();
    $parmHolder->set('city', $store->getCity());
    $parmHolder->set('country', $store->getCountry());
    $this->forward('locator', 'getStores');

  }

  public function executeRestyling(sfWebRequest $request)
  {
    $this->executeStoreFinder($request);
  }
  
  public function executePrint(sfWebRequest $request)
  {
    
  }
  
  public function executeGetAroundMeStores(sfWebRequest $request) {
    
    $lat = NULL;
    $lon = NULL;
    if ($request->hasParameter('lat')) {
      $lat = $request->getParameter('lat'); // 45.564313208589674
      $lon = $request->getParameter('lng'); // 12.428072172620007
    }//if
    
    $this->currentStore = $this->getClosestStore( $request, $request->getParameter('type'), $lat, $lon, $request->getParameter('type'));
   
    $this->currentStoreArray = $this->currentStore->toArray();
    
    $this->currentStoreArray['km'] = 50;
    $this->currentStoreArray['country'] = $this->currentStore->getCountry();
    $this->currentStoreArray['city'] = $this->currentStore->getCity();
    $this->currentStoreArray['type'] = $this->currentStore->getOneType();

    $this->setLayout(false);
    $this->getResponse()->setHttpHeader('Content-Type','application/json; charset=utf-8');

    return $this->renderText( json_encode(array($this->currentStoreArray)) );    
  }//executeGetAroundMeStores

  private function getClosestStore($request, $prodline = NULL, $latitude=NULL, $longitude=NULL, $type=NULL) {
     if ($latitude == NULL) {
      $geoIp = new GeoIPWrapper();
      $geoIpRecord = $geoIp->getGeoIPRecordByRequest($request);

      $lat = 0;
      $lng = 0;

      if( $geoIpRecord ){
        $lat = $geoIpRecord->latitude;
        $lng = $geoIpRecord->longitude;
      }
    }
    else {
      $lat = $latitude;
      $lng = $longitude;
    }
    $visible = in_array('helmets', $prodline)?false : true;

    $km = 0;
    $bStore = new SlStoreBusiness();
    $this->currentStore  = NULL;
    do {
      $km+=100;
      $this->currentStore  = $bStore->findClosestStore($lat, $lng, $km, true, NULL, $visible, $type);
    } while (count($this->currentStore) == 0 );
    
    return $this->currentStore[0];
    
  }//getClosestStore
  
}


