<?php
/**
 * Descrive una singola richiesta api
 *
 * @author ftassi
 *
 */
class Core_Client_Api
{
  /**
   * Indica se la api è protetta
   *
   * @var boolean
   */
  protected $isSecure;

  /**
   * Indica se la api richiede il currentUuid
   *
   * @var boolean
   */
  protected $requestCurrentUuid;

  /**
   * Indica se le chiamate a questa api devono
   * includere automaticamente le informazioni relative
   * all'utente loggato
   *
   * @var boolean
   */
  protected $autoAddLoggedUser;

  /**
   * Indica il verbo http utilizzato per la chiamata
   *
   * @var string
   */
  protected $httpVerb;

  /**
   * Indica il metodo http da chiamare (parte
   * dell' uri della chiamata)
   *
   * @var string
   */
  protected $apiMethod;

  /**
   * Array di parametri obbligatori (se presenti)
   *
   * @var array
   */
  protected $mandatoryParams;

  /**
   * Array associativo dei parametri da inviare all' api
   * @var array
   */
  protected $params;

  /**
   * Valorizza i parametri dell'oggetto da un array
   *
   * @param array $values
   * @return Core_Client_Api
   */
  public function fromArray($values)
  {
    $this->isSecure = $values['isSecure'];
    $this->requestCurrentUuid = $values['requestCurrentUuid'];
    $this->autoAddLoggedUser = $values['autoAddLoggedUser'];
    $this->httpVerb = $values['http_verb'];
    $this->apiMethod = $values['api_method'];
    $this->mandatoryParams = $values['params'];
    return $this;
  }

  /**
   * Setta i parametri per la chiamata
   *
   * Il metodo accetta come parametro un array (deve essere associativo se
   * contiene anche parametri che non tra quelli obbligatori) che viene
   * combinato con l'array dei parametri obbligatori per creare l'array che
   * verrà utilizzato per generare la query string della chiamata.
   * Se $params contiene lo stesso numero di parametri di $this->mandatoryParams
   * i due array vengono combinati e $this->mandatoryParams viene utilizzato per
   * creare le chiavi di $params.
   * Se $params contiene più valori di $this->mandatoryParams i primi elementi di
   * $params vegono combinate con $this->mandatoryParams, i restanti elementi vengono
   * mantenuti come sono.
   * Se $params contiene meno valori di $this->mandatoryParams il metodo ritorna
   * un'eccezione di tipo Core_Api_Exception
   *
   * @param array $params
   * @return Core_Client_Api
   * @throws Core_Api_Exception se il numero di parametri passato e inferiore a quelli obbligatori
   */
  public function setParams($params)
  {
    $params = empty($params) ? array() : $params;

    if (count($params) < count($this->mandatoryParams))
    {
      throw new Core_Api_Exception('Invalid parameters number');
    }

    
    if (count($this->mandatoryParams) > 0)
    {
      $chunckedParams = array_chunk($params, count($this->mandatoryParams), true);
      $queryVariables = array_combine($this->mandatoryParams, array_shift($chunckedParams));
      foreach ($chunckedParams as $params)
      {
        $queryVariables = array_merge($queryVariables, $params);
      }
    }
    else
    {
      $queryVariables = $params;
    }


    $this->params = $queryVariables;
    return $this;
  }

  /**
   * Getter per array parametri
   *
   * @return array
   */
  public function getParams()
  {
    return $this->params;
  }

  public function getApiMethod()
  {
    return $this->apiMethod;
  }

  /**
   * Ritorna il valore di httpVerb
   *
   * @return string
   */
  public function getHttpVerb()
  {
    return $this->httpVerb;
  }

  public function getAutoAddLoggedUser()
  {
    return $this->autoAddLoggedUser;
  }

  public function getIsSecure()
  {
    return $this->isSecure;
  }

  public function getMandatoryParams()
  {
    return $this->mandatoryParams;
  }

  public function getRequestCurrentUuid()
  {
    return $this->requestCurrentUuid;
  }

}