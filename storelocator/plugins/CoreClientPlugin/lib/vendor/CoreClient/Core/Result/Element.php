<?php
/**
 * Descrive ed incapsula i dati ottenuti
 * da una chiamata al core.
 *
 * @author ftassi
 * @package Core
 * @todo unit-test
 * @todo definire parsing per blocco relativo alla paginazione
 *
 */
class Core_Result_Element extends SimpleXMLElement
{
  /**
   * Nome del nodo xml che contiene le informazioni sullo status
   * della richiesta
   *
   * @var string
   */
  const XML_ROOT_ELEMENT = 'response';

  /**
   * Nome del nodo che contiene il set di dati principale della richiest
   * o del nodo in esame
   * @var string
   */
  const XML_DATA_ELEMENT = 'data';

  /**
   * Nome del nodo che contiene le collections relative alla richiesta
   * o al nodo in esame
   * @var string
   */
  const XML_COLLECTION_ELEMENT = 'collection';

  /**
   * Nome del nodo che contiene l'item della collection. Ogni collection avrà
   * uno o più elementi di questo tipo
   * o al nodo in esame
   * @var string
   */
  const XML_COLLECTION_ITEM_ELEMENT = 'element';

  /**
   * elemento che contiene le informazioni di stato
   * @var string
   */
  const XML_STATUS_ELEMENT = 'status';

  /**
   * Elemento che descrive il result della riposta (ok|ko)
   * @var string
   */
  const XML_STATUS_RESULT_ELEMENT = 'result';

  /**
   * Elemento che contiene la request identifier
   * della risposta
   * @var debug
   */
  const XML_STATUS_REQUEST_IDENTIFIER = 'request_identifier';

  /**
   * Xml relativo alla risposta (o alla porzione di risposta del server)
   * @var string
   */
  protected $xmlData;

  /**
   * Caller generico. Mette a disposizione i wrapper per i
   * dati all'interno del tag <data>
   *
   * @param string $method
   * @param array $arguments
   * @return string
   */
  public function __call($method, $arguments)
  {
    if ($verb = substr($method, 0, 3) == 'get')
    {
      $element = strtolower(substr($method, 3));
      //FIXME: verificare come modificare questo elemento con una regola XPATH
      $value = (string)$this->data->$element;
      return trim($value);
    }
  }

  /**
   * Da utilizzare per i blocchi statici
   *
   * @return string
   */
  public function getBlock()
  {
    $value = (string)$this->element->data->block;
    return trim($value);
  }

  /**
   * Recupera il set di collections associato all'oggetto.
   *
   * Il metodo ritorna un array di oggetti Core_Result_Element
   * rappresentanti ognuno un elemento della collection
   *
   * @return array
   */
  public function getCollections()
  {
    return $this->xpath(sprintf('./%s', self::XML_COLLECTION_ELEMENT));
  }

  /**
   * Recupera una collection specifica
   *
   * Il metodo si occupa di estrarre tutti gli item di una collection particolare.
   *
   * @param string $type il tipo di collection che si vuole ottenere
   * @return array
   */
  public function getCollection($type=null)
  {
    if( $type ){
      $data = $this->xpath(sprintf("./%s[@type='%s']", self::XML_COLLECTION_ELEMENT, $type, self::XML_COLLECTION_ITEM_ELEMENT));
    }
    else{
      $data = $this->xpath(sprintf('./%s[1]', self::XML_COLLECTION_ELEMENT, self::XML_COLLECTION_ITEM_ELEMENT));
    }
    return $data[0];
  }

  /**
   * Seleziona il set di elements all'interno del nodo. 
   * 
   * Se il nodo si riferisce ad una collection viene recuperato
   * il set di element all'interno di quella, altrimenti quello
   * all'interno della prima collection disponibile 
   * 
   * @return unknown_type
   */
  public function getElements()
  {
    //FIXME: verificare come modificare questo elemento con una regola XPATH
    /*
    $data = array();
    foreach($this->children() as $element){
    $data[] = $element;
    }
    return $data;
    */
    if ($this->getName() == self::XML_COLLECTION_ELEMENT)
    {
      $xpath = sprintf('./%s', self::XML_COLLECTION_ITEM_ELEMENT);
    }
    else
    {
      $xpath = sprintf('./%s[1]/%s', self::XML_COLLECTION_ELEMENT, self::XML_COLLECTION_ITEM_ELEMENT);
    }
    return $this->xpath($xpath);
  }


  public function getElement()
  {
    $elements = $this->getElements();
    return $elements[0];
  }


  /**
   * Ritorna gli elementi della collection.
   *
   * Il metodo ritorna una array di nodi element se questi sono
   * presenti all'interno del resultSet (i nodi saranno presenti solo
   * se l'oggetto fa riferimento ad nodo collection).
   *
   * @return array
   */
  public function getCollectionItems()
  {
//    return $this->xpath(sprintf('./%s/*', self::XML_COLLECTION_ITEM_ELEMENT));
    return $this->getElements();
  }

  /**
   * Ritorna l'attributo type
   *
   * @return unknown_type
   */
  public function getType(){
    return (string)$this->attributes()->type;
  }

  /**
   * Recupera il set di collections associato all'oggetto.
   *
   * Il metodo ritorna un array di oggetti Core_Result_Element
   * rappresentanti ognuno un elemento della collection
   *
   * @return array
   */
  public function getNonce()
  {
    $nonceArray = $this->xpath(sprintf('./%s/%s/nonce', self::XML_COLLECTION_ITEM_ELEMENT, self::XML_DATA_ELEMENT));
    return (string)$nonceArray[0]; //TODO: Butti, fix
  }

  /**
   * Verifica lo status della richiesta.
   *
   * Il metodo solleva un eccezione se il result della chimata è ko. In
   * questo caso viene sollevata un'eccezione di tipo Core_Api_Exception()
   * con messaggio composto dai dettagli dell'errore presente in error_detail e un code
   * ottenuto convertendo da hex a decimale i due codici (tipo ed errori) dell'errore
   * ritornato dal ws.
   * Nel caso in cui lo status risulti essere ok ma la risposta contenga un error_detail
   * questo viene usato per generare un errore php(trigger_error) del livello di error_level
   *
   * @throws Core_Api_Exception se lo status indica un errore (result ko).
   * @todo implementazione
   */
  public function checkStatus()
  {
    $root = $this->getName();
    $errorDetail = $this->xpath('./status/error_detail');

    if( sizeof($errorDetail) > 0 ){
      $errorDetail = $errorDetail[0];
    }
    else{
      $errorDetail = null;
    }

    if (!empty($errorDetail))
    {
      $status = $this->xpath('./status');
      $status = $status[0];

      $errorLevel = $errorDetail->error_level;
      $coreErrorType = $errorDetail->core_error_type;
      $coreErrorCode = $errorDetail->core_error_code;
      $errorMessage = $errorDetail->error_message;

      $message = sprintf('%s - Error Type:%s Error Code:%s, %s',
      $errorLevel,
      $coreErrorType,
      $coreErrorCode,
      $errorMessage);

      if ((string)$status->result === 'ko')
      {
        $code = hexdec($coreErrorType . $coreErrorCode);
        throw new Core_Api_Exception($message, $code);
      }
      else
      {
        switch((integer)$errorLevel)
        {
          case 1:
            $errorType = E_USER_ERROR;
            break;

          case 2:
            $errorType = E_USER_WARNING;
            break;

          case 8:
          default:
            $errorType = E_USER_NOTICE;
            break;
        }
        trigger_error($message, $errorType);
      }
    }

    return true;
  }
}