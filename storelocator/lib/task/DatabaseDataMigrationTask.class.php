<?php

class DatabaseDataMigrationTask extends sfDoctrineBaseTask {

  /**
   * Configura i parametri per l'inizializzazione del task
   * @see lib/vendor/symfony/lib/task/sfTask#configure()
   */
  protected function configure() {

    $this->addOptions(array(
    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace        = 'storelocator';
    $this->name             = 'migrate-data';
    $this->briefDescription = 'Migrate Storelocator Data';
    $this->detailedDescription = <<<EOF
The [storelocator:migrate-data] migrate old storelocator data in a new db structure.
Call it with:

  [php symfony storelocator:migrate-data|INFO]
EOF;

  }//configure


  /**
   * Esegue il task
   * @see lib/vendor/symfony/lib/task/sfTask#execute()
   */
  protected function execute($arguments = array(), $options = array()) {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $result = $q->execute("SET NAMES utf8 COLLATE utf8_unicode_ci");

    $this->logSection('storelocator', '- Migrating Locations...');
    $c = $this->migrateCities();
    $this->logSection('storelocator', "- $c locations migrated successfully");
    
    $this->logSection('storelocator', '- Migrating stores...');
    $c = $this->migrateStores();
    $this->logSection('storelocator', "- $c stores migrated successfully");
    
  }//execute
  
  private function migrateCities() {
    $cities = StoresQuery::create()->distinctCities()->fetchArray();
    $i = 0;
    foreach ($cities as $c) {
      try {
        $cName = $c['c']; 
        if ($cName) {
          $city = new SlCity();
          $city->setName($cName);

          $data = StoresQuery::create()->selectDataByCity($cName)->fetchArray();
          $data = $data[0];
          $this->logSection('storelocator', '------ city: ' . $cName);
          $country = SlCountryTable::getInstance()->findOneByName($data['country']);
          $this->logSection('storelocator', '--------- country: ' . ($country? $country->getName() : 'not found!') );
          if ($country == NULL) {
            $area = SlWorldAreaTable::getInstance()->findOneByName($data['area']);
            if ($area == NULL) {
              $this->logSection('storelocator', '----------- area NOT FOUND: ' . $data['area']);
              $area = new SlWorldArea();
              $area->setName($data['area']);
              $area->save();
              $this->logSection('storelocator', '----------- added area: ' . $data['area']);
            }//if
            $country = new SlCountry();
            $country->setName($data['country']);
            $country->setSlWorldArea($area);
            $country->save();
            $this->logSection('storelocator', '--------- added country: ' . ($country? $country->getName() : 'not found')) ;    
          }//if

          $city->setSlCountry($country);
          $city->save();
          $i++;
        }//if
      }//try
      catch (Doctrine_Exception $e) {
        $this->logSection('storelocator', 'ERROR: ' . $e->getMessage()) ;    
      }//catch
      
    }//foreach
    return $i;

  }//migrateCities
    
  private function convertStoreType ($st) {
    switch ( strtolower(trim($st)) ) {
      case 'diesel':
      case 'accessories':
      case 'denimoteque':
      case 'diesel store':
      case 'concept store':
      case 'diesel travel':
      case 'temporary':
      case 'diesel black gold':
        return 'Diesel';
        break;
      case 'planet':
      case 'diesel premium':
      case 'denim gallery': 
      case 'flagship': 
        return 'Flagship';
        break;
      case 'diesel outlet':
      case 'kid outlet':
      case 'kid':
      case '55dsl':
      case 'accessories outlet':
        return 'Outlet';
        break;
      default:
        return 'Corner';
        break;
    }//switch
  }//convertStoreType
  
  
  private function convertStatus ($st) {
    switch ($st) {
      case 'Pending opening':
        return 'Pending Open';
        break;
      case 'Opened':
        return 'Open';
        break;
      default:
        return 'Closed';
    }//switch
  }//convertStatus

  private function convertProductLine($pl) {
    /*Diesel
	Diesel Black Gold
	Diesel Kid
	Accessories
	Diesel Home*/
    
    switch ($pl) {
      case 'Diesel Home':
        return 'Diesel Home';
        break;
      case 'Diesel Black Gold':
        return 'Diesel Black Gold';
        break;
      case 'Accessories':
      case 'Accessories Outlet':
        return 'Accessories';
        break;
      case 'Kid':
      case 'Kid Outlet':
        return 'Diesel Kid';
        break;
      case 'Denim Gallery':
        return 'Diesel';
        break;
      default:
        return 'Diesel';
    }//switch
  }//convertProductLine
  
  private function migrateStores () {
    $i=0;
    $stores = Doctrine_Query::create()->from('Stores s')->addWhere("s.address IS NOT NULL and s.address <> ''")->execute();

    foreach ($stores as $s) {
      try {
        

        if ($s->getAddress() != NULL && $s->getAddress() != "") {
          $storecopy = Doctrine_Query::create()->from('SlStore s')->addWhere("s.address = ?",$s->getAddress())->fetchOne();
          
          if ($storecopy != NULL) {
            $namepl = $this->convertProductLine($s->getType());
            
            $prodline = SlProductLineTable::getInstance()->findOneByName($namepl);
            
            
            $this->logSection('storelocator','FOUND DUPLICATE -- adding productline: '. $storecopy->getAddress() . ' :: '. $prodline->getName() );
            
            $storecopy->addProductLine($prodline);
            
          }//if
          else {
            
            $city = SlCityTable::getInstance()->findOneByName($s->getCity());
            $country = SlCountryTable::getInstance()->findOneByName($s->getCountry());
            $warea = SlWorldAreaTable::getInstance()->findOneByName($s->getArea());
            $status = SlStoreStatusTable::getInstance()->findOneByName($this->convertStatus($s->getStatus()));
            $storeType = SlStoreTypeTable::getInstance()->findOneByName($this->convertStoreType($s->getType()));

            $typeColl = new Doctrine_Collection('SlStoreType');
            $typeColl->add($storeType);
         //   $prodLine = SlProductLineTable::getInstance()->findOneByName($s->getProductLine());

           if ($city == NULL) {
             $this->logSection('storelocator', 'ERROR: ' . $s->getId() . ' ' . $s->getName() . ' -- CITY ' . $s->getCity() == ""?'empty' : $s->getCity() ) ;    
           }
           if ($country == NULL) {
             $this->logSection('storelocator', 'ERROR: ' . $s->getId() . ' ' . $s->getName() . ' -- COUNTRY ' . $s->getCountry()== ""?'empty' : $s->getCountry()) ;    
           }//if
           if ($warea == NULL) {
             $this->logSection('storelocator', 'ERROR: ' . $s->getId() . ' ' . $s->getName() . ' -- WAREA ' .$s->getArea()== ""?'empty' : $s->getArea() ) ;    
           }//if
           if ($status == NULL) {
             $this->logSection('storelocator', 'ERROR: ' . $s->getId() . ' ' . $s->getName() . ' -- STATUS '.$s->getStatus()== ""?'empty' : $s->getStatus()) ;    
           }//if
           if ($storeType == NULL) {
             $this->logSection('storelocator', 'ERROR: ' . $s->getId() . ' ' . $s->getName() . ' -- TYPE ' . $s->getType()== ""?'empty' : $s->getType()) ;    
           }//if

            $store = new SlStore();
            $store->setName($s->getName());

            $store->setSlStoreTypes($typeColl);

            $store->setSlStoreStatus($status);
            $store->setSlWorldArea($warea);
            $store->setSlCity($city);
            $store->setSlCountry($country);
            $store->setAddress($s->getAddress());
            $store->setZip($s->getZip());
            $store->setTelf($s->getTelf());
            $store->setLatitude($s->getLatitude());
            $store->setLongitude($s->getLongitude());
            $store->save();
            
            $namepl = $this->convertProductLine($s->getType());
            $prodline = SlProductLineTable::getInstance()->findOneByName($namepl);
            $store->addProductLine($prodline);
          }//if
        }//if

        $i++;
       // $this->logSection('storelocator', '------ Store added: ' . $store->getName()) ;    
      }//try
      catch (Doctrine_Exception $e) {
        $this->logSection('storelocator', 'ERROR: ' . $s->getId() . ' ' . $s->getName() . ' -- ' . $e->getMessage() . ' ' ) ;    
        echo $e->__toString();
      }//catch
    }//foreach
    return $i;
  }//migrateStores
  
}//DatabaseDataMigrationTask
