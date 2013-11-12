<?php

/**
 * Questo Cron Task viene utilizzato per impostare le coordinate latitudine e longitudine per tutti gli store
 * che hanno le coordinate nulle o 0,0.
 * Tipicamente questo accade per gli store importati.
 *
 * Pu� essere avviato con la seguente riga di comando:
 *
 *   php symfony storelocator:update-store-coords <application> <limit_records>
 *
 * dove:
 *   <application>: � l'applicazione su cui far girare il task (backend | frontend)
 *   <limit_records>: numero di record da aggiornare (0=no limit) (da inserire per evitare timeout di esecuzione)
 *
 * es:
 *   php symfony storelocator:update-store-coords frontend 100
 *
 * riga cron (unire il primo * e /30):
 *
 *   # cron update store coords task every 30 minutes
 *   * /30 * * * * ~/symfony storelocator:update-store-coords frontend 100 >> ~/log/update-store-coords.log
 *
 * Il task utilizza come parametro predefinito l'env "dev". Negli ambienti dove prod e dev NON condividono lo stesso database è
 * necessario specificare il parametro --env=prod per utilizzare il db di produzione.
 *
 * @author Denis Torresan
 */
class StoreLocatorUpdateStoreCoordsTask extends sfDoctrineBaseTask
{

  /**
   * Configura i parametri per l'inizializzazione del task
   * @see lib/vendor/symfony/lib/task/sfTask#configure()
   */
  protected function configure()
  {
    $this->addArguments(array(
    new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
    new sfCommandArgument('limit_records', sfCommandArgument::REQUIRED, 'Number of rows limit (0=no limit)'),
    ));

    $this->addOptions(array(
    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
    ));

    $this->namespace        = 'storelocator';
    $this->name             = 'update-store-coords';
    $this->briefDescription = 'Update Store Coords';
    $this->detailedDescription = <<<EOF
The [storelocator:update-store-coords|INFO] Update store coords when null or 0,0.
Call it with:

  [php symfony storelocator:update-store-coords|INFO]
EOF;
  }


  /**
   * Esegue il task
   * @see lib/vendor/symfony/lib/task/sfTask#execute()
   */
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $limit = (int)$arguments['limit_records'];

    $q = Doctrine_Query::create()
    ->from('Stores s')
    ->addWhere('s.latitude is null OR s.latitude=0 OR s.longitude is null OR s.longitude=0');

    if( $limit > 0 ){
      $q->limit($limit);
    }

    $stores = $q->execute();
    foreach($stores as $store){
      try{
        $q=urlencode($store->address.' '.$store->city.' '.$store->country);
        $content=file_get_contents("http://maps.google.com/maps/geo?q=".$q."&output=csv");
        $values=split(',', $content);
        $store->setLatitude((float)$values[2]);
        $store->setLongitude((float)$values[3]);
        $store->save();
        $this->logSection('storelocator', sprintf('Update store %s: "%s"', $store->getId(), $store->getName()));
      }
      catch (Exception $exception) {
        $this->logSection('storelocator', sprintf('Error update store %s: "%s". Exception: %s', $store->getId(), $store->getName(), $exception->getMessage()));
      }
    }
  }

}
