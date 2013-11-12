<?php

/**
 * storeadmin actions.
 *
 * @package    collections
 * @subpackage storeadmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class storeadminActions extends sfActions {
  
  public function myStoresSuccess () {
    
  }//myStores
  
  public function executeNewStore () {
    
    $user = $this->getUser()->getGuardUser();
    $this->storeTable = new SlStore();
    
    if ($user->getIsSuperAdmin()) {
      $this->countries = SlCountryTable::getInstance()->createQuery('SlCountryTable s')
            ->distinct()
            ->orderBy('s.name')
            ->execute();
    }//if
    else {
      $this->countries = $user->getCountries();
    }//else
    
    $this->storeTypes = SlStoreTypeTable::getInstance()->findAll();
    $this->plines = SlProductLineTable::getInstance()->findAll();
    $this->statuses = SlStoreStatusTable::getInstance()->findAll();
    $this->cities = SlCityTable::getInstance()->findAll();
    
    
  }
  
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request) {
  	$this->user = $this->getUser()->getGuardUser();
    if ( $this->getUser()->isSuperAdmin() || count($this->user->getCountriesArray()) > 0  ) {
      $this->stores = SlStoreQuery::create()->inCountries($this->user->getCountriesArray())->hidePlNotVisible()->orderBy('name')->execute();
    }//if
    else {
      $this->stores = array();
    }//else
    $this->storeadded = false;
    if ( $request->hasParameter('storeadded') && $request->getParameter('storeadded')){
      $this->storeadded= true;
    }//if
    
    
  }//executeIndex
  
  public function executeEditStore(sfWebRequest $request) {
    $this->storeTable = SlStoreTable::getInstance()->findOneBySlug($request->getParameter('slug'));
        
    $this->countries = SlCountryTable::getInstance()->createQuery('SlCountryTable s')
          ->distinct()
          ->orderBy('s.name')
          ->execute();
    
    $this->storeTypes = SlStoreTypeTable::getInstance()->findAll();
    $this->plines = SlProductLineTable::getInstance()->findAll();
    $this->statuses = SlStoreStatusTable::getInstance()->findAll();
    
    $user = $this->getUser()->getGuardUser();
    $this->cities = SlCityTable::getInstance()->createQuery('SlCityTable s')
          ->distinct()
          ->addWhere('s.country_id = ?', array($this->storeTable->getCountryId()))
          ->orderBy('s.name')
          ->execute();
    
    /*if ($user->getIsSuperAdmin()) {
      $this->cities = SlCityTable::getInstance()->createQuery('SlCityTable s')
          ->distinct()
          ->orderBy('s.name')
          ->add
          ->execute();
    }
    else {
      $this->cities = SlCityTable::getInstance()->createQuery('SlCityTable s')
          ->distinct()
          ->addWhere('c.country_id = ?', array($this->storeTable->getCountryId()))
          ->orderBy('s.name')
          ->execute();
     // $this->cities = SlCityTable::getInstance()->findByCountryId($this->storeTable->getCountryId());
    }*/
    
    
    $this->timesOpenClose = array('8:00 AM' => '8:00 AM', '8:15 AM' => '8:15 AM', '8:30 AM' => '8:30 AM', '8:45 AM' => '8:45 AM', '9:00 AM' => '9:00 AM',
             '9:00 AM' => '9:00 AM', '9:15 AM' => '9:15 AM', '9:30 AM' => '9:30 AM', '9:45 AM' => '9:45 AM', '10:00 AM' => '10:00 AM',
             '10:00 AM' => '10:00 AM', '10:15 AM' => '10:15 AM', '10:30 AM' => '10:30 AM', '10:45 AM' => '10:45 AM', '10:00 AM' => '10:00 AM',
             '11:00 AM' => '11:00 AM', '11:15 AM' => '11:15 AM', '11:30 AM' => '11:30 AM', '11:45 AM' => '11:45 AM', '11:00 AM' => '11:00 AM',
             '12:00 AM' => '12:00 AM', '12:15 AM' => '12:15 AM', '12:30 AM' => '12:30 AM', '12:45 AM' => '12:45 AM', '12:00 AM' => '12:00 AM',
             '1:00 PM' => '1:00 PM', '1:15 PM' => '1:15 PM', '1:30 PM' => '1:30 PM', '1:45 PM' => '1:45 PM', '1:00 PM' => '1:00 PM',
             '2:00 PM' => '2:00 PM', '2:15 PM' => '2:15 PM', '2:30 PM' => '2:30 PM', '2:45 PM' => '2:45 PM', '2:00 PM' => '2:00 PM',
             '3:00 PM' => '3:00 PM', '3:15 PM' => '3:15 PM', '3:30 PM' => '3:30 PM', '3:45 PM' => '3:45 PM', '3:00 PM' => '3:00 PM',
             '4:00 PM' => '4:00 PM', '4:15 PM' => '4:15 PM', '4:30 PM' => '4:30 PM', '4:45 PM' => '4:45 PM', '4:00 PM' => '4:00 PM',
             '5:00 PM' => '5:00 PM', '5:15 PM' => '5:15 PM', '5:30 PM' => '5:30 PM', '5:45 PM' => '5:45 PM', '5:00 PM' => '5:00 PM',
             '6:00 PM' => '6:00 PM', '6:15 PM' => '6:15 PM', '6:30 PM' => '6:30 PM', '6:45 PM' => '6:45 PM', '6:00 PM' => '6:00 PM',
             '7:00 PM' => '7:00 PM', '7:15 PM' => '7:15 PM', '7:30 PM' => '7:30 PM', '7:45 PM' => '7:45 PM', '7:00 PM' => '7:00 PM',
             '8:00 PM' => '8:00 PM', '8:15 PM' => '8:15 PM', '8:30 PM' => '8:30 PM', '8:45 PM' => '8:45 PM', '8:00 PM' => '8:00 PM',
             '9:00 PM' => '9:00 PM', '9:15 PM' => '9:15 PM', '9:30 PM' => '9:30 PM', '9:45 PM' => '9:45 PM', '9:00 PM' => '9:00 PM');
    
    $this->timestable = $this->storeTable->getTimestable() ? $this->storeTable->getTimestable() : new StoreTimestable ();

    $this->setTemplate('main');
  }//executeEditStore
  
  
  /*
  private function checkDetailModification ($oldStore, $request) {
    $mod = array();
    if ($oldStore->getName() != $request->getParameter('store-name')) {
      $mod[] = 'Name';
    }//if
    if ($oldStore->getAddress() != $request->getParameter('add-1') || $oldStore->getStoreExtraData()->getAdditionalAddress() != $request->getParameter('add-2') || $oldStore->getCountry() != $request->getParameter('country') ||
        $oldStore->getZip() != $request->getParameter('postal-code') || $oldStore->getCity() != $request->getParameter('city') || $oldStore->getLongitude() != $request->getParameter('lat') || $oldStore->getLatitude() != $request->getParameter('lng')) {
      $mod[] = 'Address';
    }//i
    if ($oldStore->getTelf() != $request->getParameter('telephone')) {
      $mod[] = 'Telephone number';
    }//if
    return $mod; 
  }//checkModification
  */
  
  public function executeNameAddress(sfWebRequest $request) {
  
    $isnew = false;
    if ($request->hasParameter('slug') && $request->getParameter('slug') != "") {
      $this->store = SlStoreTable::getInstance()->findOneBySlug($request->getParameter('slug'));
    }//if
    else {
      $isnew = true;
      $this->store = new SlStore();
      $this->store->setOnline(false);
      $sendmail = true;
    }//else

    
    if ($this->store && ($this->store->isNew() || $this->store->isOwnedBy($this->getUser()->getGuardUser() )) ) {
      
      $this->store->setName($request->getParameter('store-name'));
      $this->store->setAddress($request->getParameter('add-1'));
   
      if ($this->store->isNew() || $this->getUser()->getGuardUser()->getIsSuperAdmin()) {
        $this->store->setCountryId($request->getParameter('country'));
      }//if

      $this->store->setExtraData(SlExtraData::ADDITIONAL_ADDRESS, $request->getParameter('add-2'));
      
      $this->store->setCityId($request->getParameter('city'));
      
      $this->store->setLatitude($request->getParameter('lat'));
      $this->store->setLongitude($request->getParameter('lng'));
      $this->store->setStoreStatusId($request->getParameter('status'));
      $this->store->setTelf($request->getParameter('telephone'));
      $this->store->setEmail($request->getParameter('email'));
      $this->store->setZip($request->getParameter('postal-code'));
      
      if ($this->store->isNew()) {
        $this->store->setOnline(0);
 
      }//if
      
      $this->store->save();
      
      $del_q= Doctrine_Query::create()->delete('SlStoreStoreType ss')->where('ss.sl_store_id=?', array($this->store->getId()) )->execute();
  	
      if ($request->hasParameter('shop-type')) {
        $types = new Doctrine_Collection('SlStoreType');
        $types->add( Doctrine_Query::create()->from('SlStoreType s')->where('s.id = ?', array($request->getParameter('shop-type')))->fetchOne() ); 

        $this->store->setSlStoreTypes($types);
        $this->store->save();
      }//if
      
      $del_q= Doctrine_Query::create()->delete('SlStoreProductLine sp')->where('sp.sl_store_id=?', array($this->store->getId()) )->execute();
  	
      if ($request->hasParameter('product-line')) {
        $pls = new Doctrine_Collection('SlProductLine');
        foreach ( $request->getParameter('product-line') as $pl ) {
          $pls->add( Doctrine_Query::create()->from('SlProductLine p')->where('p.id = ?', array($pl))->fetchOne() ); 
        }//foreach

        $this->store->setSlProductLines($pls);
        $this->store->save();
      
      }//if
      
      $this->getResponse()->setContentType('application/json');
      $res = array('result' => true, 'action' => 'executeNameAddress', 'isnew' => $isnew, 'slug' => $this->store->getSlug());
      $this->renderText(json_encode($res));
      
      $this->getUser()->setFlash('notice', 'Store Added');
      
      if ($sendmail) {

        $name = $this->store->getName();
        $address = $this->store->getAddress();
        $country = $this->store->getCountry();
        $city= $this->store->getCity();
        $slug = $this->store->getSlug();
        $id = $this->store->getId();
        $text = <<<END
          New store added:
            $name
            $address
            $country - $city
            Store details: http://storelocator.diesel.com/admin.php/edit-store?slug=$slug#name-address
            Administrator link: http://storelocator.diesel.com/backend.php/sl_store/$id/edit
END;
        $this->sendMailNotification('[STORELOCATOR] New Store Added', $text);
      }//if
      
      
      return sfView::NONE;
    }//if
    $this->getResponse()->setContentType('application/json');
    
    $res = array('result' => false, 'action' => 'executeNameAddress', 'isnew' => $isnew, 'slug' => $this->store->getSlug());
    $this->renderText(json_encode($res));
    $this->getUser()->setFlash('notice', 'Store Added');
    return sfView::NONE;

    /*
  	$this->extraDataTable = $this->storeTable->getStoreExtraData();
  
//  	$mods = $this->checkDetailModification($this->storeTable, $request); 
  	
  	$this->storeTable->setName($request->getParameter('store-name'));
  	$this->storeTable->setAddress($request->getParameter('add-1'));
  	$this->extraDataTable->setAdditionalAddress($request->getParameter('add-2'));
  	
  	$this->storeTable->setCountry($request->getParameter('country'));
  	
  	$del_q= Doctrine_Query::create()->delete('StoreStoreType ss')->where('ss.stores_id=?', array($this->storeTable->getId()) )->execute();
  	
  	$types = new Doctrine_Collection('StoreType');
  	foreach ( $request->getParameter('shop-type') as $shop_t ) {
  	  $types->add( Doctrine_Query::create()->from('StoreType s')->where('s.id = ?', array($shop_t))->fetchOne() ); 
  	}//foreach
  	
	  $this->storeTable->setStoreTypes($types);
	  $this->storeTable->save();
  	
  	$this->storeTable->setZip($request->getParameter('postal-code'));
  	$this->storeTable->setCity($request->getParameter('city'));
  	$this->storeTable->setTelf($request->getParameter('telephone'));
  	$this->storeTable->setLatitude($request->getParameter('lat'));
  	$this->storeTable->setLongitude($request->getParameter('lng'));
  			
  	$this->storeTable->save();
  	$this->extraDataTable->save();
  	
  	if (count($mods) > 0 ) {
    	$this->sendMailNotification( '[STORE MODIFIED]' . $this->storeTable->getCountry().' > '.$this->storeTable->getCity().' > '.$this->storeTable->getName()  ,  "user: " .$this->getUser()->getUsername() . ' - ' . $this->getUser()->getEmail() .
    	"\nModified data: \n -". implode('\n- ', $mods) ."\n");
  	}//if
  	
  	$this->renderText("{result:'true',action='executeNameAddress'}");
  	return sfView::NONE;
  */
  }//executeNameAddress

  public function executeOpeningTimes(sfWebRequest $request) {
    $store = SlStoreTable::getInstance()->findOneBySlug($request->getParameter('slug'));
    
    if ($store && $store->isOwnedBy($this->getUser()->getGuardUser())) {
      if ($request->getParameter('opening-time') == 'true') {
        $store->setHasTimestable(true);
        $timestable = new StoreTimestable ($request->getParameter('times'));
      }//if
      else {
        $store->setHasTimestable(false);
        $timestable = new StoreTimestable ();
      }//else
      
      $store->setTimestable($timestable);
      $store->setTwoTimesADay($request->hasParameter('twotimeforday'));
      $this->renderText("{result:'true',action='executeOpeningTimes'}");
    }//if
  	
/*  	if ($this->extraDataTable->getOpeningTimes() !=  $this->timestable) {
    	$this->sendMailNotification('[STORE MODIFIED]' .$this->storeTable->getCountry().' > '.$this->storeTable->getCity().' > '.$this->storeTable->getName() ,  "user: " .$this->getUser()->getUsername() . ' - ' . $this->getUser()->getEmail() . 
    	"\nModified data: \n -Timestable\n");
  	}//if
  */	

  	return sfView::NONE;
  }//executeOpeningTimes
  
  public function executeNewsMod(sfWebRequest $request) {
    
    $news = SlStoreNewsTable::getInstance()->findOneBySlug($request->getParameter('slug'));
    if ($news) {
      $news->setTitle($request->getParameter('titolo'));
      $news->setParagraph($request->getParameter('paragrafo'));
      $news->save();
    }//if
    
  	$this->renderText("{result:'true',action='executeNewsMod'}");
  	return sfView::NONE;
  
  }//executeNewsMod
  
  public function executeNewsDel(sfWebRequest $request) {
    
    $news = SlStoreNewsTable::getInstance()->findOneById($request->getParameter('id'));
    if ($news) {
      $news->delete();
      $news->save();
    }//if
  	
  	$this->renderText("{result:'true',action='executeNewsDel'}");
  	return sfView::NONE;
  }//executeNewsDel
  
  public function executeNewsAdd(sfWebRequest $request) {
    $store = SlStoreTable::getInstance()->findOneBySlug($request->getParameter('slug'));
    
    if ($store && $store->isOwnedBy($this->getUser()->getGuardUser())) {
  	
      $news = new SlStoreNews();
      $news->setTitle($request->getParameter('titolo'));
      $news->setParagraph($request->getParameter('paragrafo'));
      $news->setStoreId($store->getId());
      $news->save();

/*      $this->sendMailNotification('[ADDED NEWS] '.$this->storeTable->getCountry().' > '.$this->storeTable->getCity().' > '.$this->storeTable->getName() ,  "user: " .$this->getUser()->getUsername() . ' - ' . $this->getUser()->getEmail() . 
          "\nAdded news\n".
        $request->getParameter('titolo') . "\n\n" .
        strip_tags ($request->getParameter('paragrafo')) );
*/
      $this->renderText("{result:'true',action='executeNewsAdd'}");
    }//if
  	return sfView::NONE;
  }//executeNewsAdd
  
  public function executePrehelpfaq(sfWebRequest $request) {
  }//executePrehelpfaq

  
  public function executeGetAjaxStores(sfWebRequest $request) {
    if ($request->getParameter('country') == NULL) {
      return sfView::NONE; 
    }//if
    $this->stores = StoresTable::getStoresByCountry($request->getParameter('country'));
  }//executeGetAjaxStores
  
  public function executeWithoutStore(sfWebRequest $request) {
  	
  }//executeWithoutStore
  
  public function executeRefreshNews(sfWebRequest $request) {
    $store = SlStoreTable::getInstance()->findOneBySlug($request->getParameter('slug'));
    $this->newsTable = $store->getSlNews();
    $this->renderText( $this->getPartial('storenews', array("newsTable" => $this ->newsTable)) );
    return sfView::NONE;
  }//executeRefreshNews

  public function executeSupport(sfWebRequest $request) {
  }//executeSupport

  public function executeFaq(sfWebRequest $request) {
  }//executeSupport
  
  public function executeSendSupport (sfWebRequest $request) {
  	
  	$objectSupport = $request->getParameter('objectSupport');
  	$requestSupport = $request->getParameter('requestSupport');
  	
  	// send an email to the affiliate
  	//array('bumfuzzle@diesel.com' => 'Diesel store locator'), "alberto_speggiorin@diesel.com",
 /*   $message = $this->getMailer()->compose(array('bumfuzzle@diesel.com' => 'Diesel store locator'), "alberto_speggiorin@diesel.com", $objectSupport,  "user: " .$this->getUser()->getUsername() . "\n". $requestSupport);
   
    $this->getMailer()->send($message);
  	*/

    $this->sendMailNotification($objectSupport,  "user: " .$this->getUser()->getUsername() . "\n". $requestSupport);
    
  	$this->renderText("{result:'true',action='executeSendSupport'}");
    return sfView::NONE;
	
  }//executeSendSupport
  
  
  public function executeSendStoreSupport (sfWebRequest $request) {
  	
  	$store = $request->getParameter('store');
  	
  	// send an email to the affiliate
  	//array('bumfuzzle@diesel.com' => 'Diesel store locator'), "alberto_speggiorin@diesel.com",
  	$this->sendMailNotification("Store Management Request", "user: " .$this->getUser()->getUsername() . "\n ID Store:".  $store);
  	
  	$this->renderText("{result:'true',action='executeSendStoreSupport'}");
    return sfView::NONE;
	
  }//executeSendSupport
  

  public function executeFaqNotLogged (sfWebRequest $request) {
  }//executeFaqNotLogged

  public function executeHowWorks (sfWebRequest $request) {

  }//executeFaqNotLogged

  
  private function sendMailNotification ($subj="", $text="", $from=array('bumfuzzle@diesel.com' => 'Diesel store locator'), $to="alberto_speggiorin@diesel.com") {
    $message = $this->getMailer()->compose($from, $to, $subj, $text);
   
    $this->getMailer()->send($message);
  }//sendMailNotification
  
  
  public function executeGetCities (sfWebRequest $request) {
    $this->cities = SlCityQuery::create()->whereCountry($request->getParameter('country_id'))->orderBy('sc.name')->execute();
        
    $res = $this->cities->toArray();
    
    $this->setLayout(false);
    $this->getResponse()->setHttpHeader('Content-Type','application/json; charset=utf-8');
    
    $this->renderText(json_encode($res));
    return sfView::NONE;
  }//executeGetCities
  
  
  private function getNiceditOut ($request, $status, $showLoadingMsg = false) {
    $script = '
        try {
            '.(( $request->getMethod() == sfRequest::POST) ? 'top.' : '').'nicUploadButton.statusCb('.json_encode($status).');
        } catch(e) { alert(e.message); }
    ';
    
    if($request->getMethod() == sfRequest::POST) {
        return '<script>'.$script.'</script>';
    }//if
    else {
        return $script;
    }//else
    
    if($request->getMethod() == sfRequest::POST && $showLoadingMsg) {      

      return <<<END
          <html><body>
              <div id="uploadingMessage" style="text-align: center; font-size: 14px;">
                  <strong>Uploading...</strong><br />
                  Please wait
              </div>
          </body></html>
END;

    }
    return ""; 
  }//getNiceEditOut

}
