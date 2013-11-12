<?php

/**
 * Questo Cron Task viene utilizzato per effettuare l'aggiornamento del file GeoLiteCity.dat.gz prelevandolo dal
 * sito di maxmind.com.
 * Il file una volta scaricato viene decompresso con gunzip.
 * 
 * E' necessario aver installato i seguenti comandi unix e che siano disponibili nel path:
 * - wget
 * - gunzip
 *
 * Può essere avviato con la seguente riga di comando:
 *
 *   php symfony geoip:update-database
 *
 * @author Denis Torresan
 */
class GeoIPUpdateDatabaseTask extends sfDoctrineBaseTask
{

  /**
   * Configura i parametri per l'inizializzazione del task
   * @see lib/vendor/symfony/lib/task/sfTask#configure()
   */
  protected function configure()
  {
    $this->addOptions(array(
    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
    ));

    $this->namespace        = 'geoip';
    $this->name             = 'update-database';
    $this->briefDescription = 'Update database';
    $this->detailedDescription = <<<EOF
The [geoip:update-database|INFO] Update GeoLiteCity.dat.gz.
Call it with:

  [php symfony geoip:update-database|INFO]
EOF;
  }


  /**
   * Esegue il task
   * @see lib/vendor/symfony/lib/task/sfTask#execute()
   */
  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection('geoip', sprintf('Update GeoLiteCity'));
    
    $datGzFileName = 'GeoLiteCity.dat.gz';
    $downloadURL = 'http://geolite.maxmind.com/download/geoip/database/' . $datGzFileName;
    $downloadDir = sfConfig::get('sf_data_dir') . '/geoip/';
    
    //crea directory geoip se non presente
    if (! file_exists($downloadDir)) {
      $this->logSection('geoip', sprintf('Create directory data/geoip.'));
      mkdir($downloadDir, 0755, true);
    }
    
    //wget
    $command = sprintf('wget %s --directory-prefix=%s', $downloadURL, $downloadDir);
    $this->logSection('geoip', sprintf('Executing WGet: %s', $command));
    $output = shell_exec($command);
    $this->logSection('geoip', sprintf('WGet result: %s', $output));

    //gunzip
    $datGzPath = $downloadDir . $datGzFileName;
    $command = sprintf('gunzip -f %s', $datGzPath);
    $this->logSection('geoip', sprintf('Executing Gunzip: %s', $command));
    $output = shell_exec($command);
    $this->logSection('geoip', sprintf('Gunzip result: %s', $output));
  }

}
