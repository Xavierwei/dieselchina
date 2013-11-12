<?php

class FixKidStoresTask extends sfDoctrineBaseTask {

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
    $this->name             = 'fix-kid-stores';
    $this->briefDescription = 'Fix kid stores';
    $this->detailedDescription = <<<EOF
The [storelocator:fix-kid-stores] fixa gli store Diesel kid precedentemente impostati come store type "outlet".
Call it with:

  [php symfony storelocator:fix-kid-stores|INFO]
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

    $this->logSection('storelocator', '- Fixing kid stores...');
    
    $c = $this->migrateStores();
    $this->logSection('storelocator', "- $c stores fixed successfully");
    
  }//execute
  
 
  
  private function migrateStores () {
    $i=0;
    $stores = Doctrine_Query::create()->from('Stores s')->addWhere("s.address IS NOT NULL and s.address <> '' and s.type = ?", array('Kid'))->execute();
    $storeType = SlStoreTypeTable::getInstance()->findOneByName('Diesel');

    $typeColl = new Doctrine_Collection('SlStoreType');
    $typeColl->add($storeType);
    
    foreach ($stores as $s) {
      try {
        
        if ($s->getAddress() != NULL && $s->getAddress() != "") {
          
          $store = Doctrine_Query::create()->from('SlStore s')->addWhere("s.address = ?",$s->getAddress())->fetchOne();
          
          $del_q= Doctrine_Query::create()->delete('SlStoreStoreType ss')->where('ss.sl_store_id = ?', array($store->getId()) )->execute();
          
          $sst = new SlStoreStoreType();
          $sst->setSlStoreId($store->getId());
          $sst->setSlStoreTypeId(2);
          $sst->save();
          
        }//if

        $this->logSection('storelocator', '------ Store modified: ' . $store->getName()) ;    
        $i++;
      }//try
      catch (Doctrine_Exception $e) {
        $this->logSection('storelocator', 'ERROR: ' . $s->getId() . ' ' . $s->getName() . ' -- ' . $e->getMessage() . ' ' ) ;    
        echo $e->__toString();
      }//catch
    }//foreach
    return $i;
  }//migrateStores
  
}//DatabaseDataMigrationTask
