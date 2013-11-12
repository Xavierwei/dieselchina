<?php

/**
 * Questo task viene utilizzato per rinominare le immagini delle collezioni.
 *
 * Esempio:
 *   symfony coreclient:api frontend commentsgetForItem 2
 *
 * @author Denis Torresan
 */
class CallApiTask extends sfDoctrineBaseTask
{

  /**
   * Configura i parametri per l'inizializzazione del task
   * @see lib/vendor/symfony/lib/task/sfTask#configure()
   */
  protected function configure()
  {
    $this->addArguments(array(
    new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
    new sfCommandArgument('method', sfCommandArgument::REQUIRED, 'Api Name (es: blockGetHeader)'),
    new sfCommandArgument('params', sfCommandArgument::OPTIONAL, 'Api Params (es: 2,3)'),
    ));
    
    $this->addOptions(array(
    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
    ));

    $this->namespace        = 'coreclient';
    $this->name             = 'api';
    $this->briefDescription = 'Call API Client';
    $this->detailedDescription = <<<EOF
The [coreclient:api|INFO] Call API method.
Call it with:

  [php symfony coreclient:api|INFO]
EOF;
  }


  /**
   * Esegue il task
   * @see lib/vendor/symfony/lib/task/sfTask#execute()
   */
  protected function execute($arguments = array(), $options = array())
  {
    $method = $arguments['method'];
    $params = $arguments['params'];

    echo "Method: " . $method . "\n";
    echo "Params: " . $params . "\n";

    //FIXME: non funziona perchè non carica env.yml corretto
    
    $coreClient = CoreClient::createCoreClient();
    try{
      if( $params == NULL ){
        $result = $coreClient->$method();
      }
      else{
        $paramsArr = explode(',', $params);
        $result = call_user_func_array(array( $coreClient, $method), $paramsArr);
      }
      echo "RESULT: \n";
      var_dump($result->asXml());
    }
    catch(Exception $e){
      echo "ERROR: \n";
      echo $e->getTraceAsString();
    }
    
  }

}
