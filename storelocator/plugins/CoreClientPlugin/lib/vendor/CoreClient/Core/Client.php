<?php

//gestione include tramite include_path se non già caricata tramite autoload
if (!class_exists('HTTP_Request2')) {
  require_once 'HTTP/Request2.php';
}
require_once dirname(__FILE__).'/Request/Exception.php';
require_once dirname(__FILE__).'/Result/Element.php';
require_once dirname(__FILE__).'/Api/Exception.php';
require_once dirname(__FILE__).'/Client/Api.php';

/**
 * Classe client per il WS rest core.diesel.com
 *
 * La classe è un wrapper per i metodi esposti dal WS che permette
 * alle applicazione satellite di interagire in maniera trasparente
 * con il servizio core.
 * La classe si occupa di:
 *
 *  * Mappare i metodi del WS
 *  * Effettuare controlli di coerenza sul numero di parametri previsti dal metodo
 *  * Effettuare le chiamate "di servizio" necessarie all'autenticazione dell'applicazione chiamante
 *  * Aggiungere il parametro relativo al CurrentUserId per i metodi che ne hanno bisogno (parametro obbligatorio)
 *
 * Ogni chiamata al servizio ritornerà un istanza di Core_Result_Element.
 * La classe si occupa anche di gestire gli errori inviati dal WS lanciando:
 *
 *  * Un'eccezione di tipo Core_Request_Exception se la risposta ha un code != da 200
 *  * Un'eccezione di tipo Core_Api_Exception se la richiesta è andata a buon fine ma contiene un messaggio di errore grave
 *  * Un errore user-error di php (ERROR, WARNING o NOTICE a seconda del level inviato dal WS).
 *
 * @see Core_Result_Element
 * @package Core
 * @author ftassi
 *
 */
class Core_Client
{
  /**
   * Nome del parametro relativo al codice nonce.
   * @var string
   */
  const PARAMETER_OAUTH_NONCE = 'oauth_nonce';

  /**
   * Nome del parametro relativo alla firma
   * @var string
   */
  const PARAMETER_OAUTH_SIGNATURE = 'oauth_signature';

  /**
   * Parametro utilizzato per passare la consuner key
   * @var string
   */
  const PARAMETER_OAUTH_CONSUMER_KEY = 'oauth_consumer_key';

  /**
   * Nome del parametro relativo al current uuid
   * @var string
   */
  const PARAMETER_CURRENT_USER_UUID = 'q_user_id';

  /**
   * La consumer key relativa al client
   * @var string
   */
  public $consumerKey = null;

  /**
   * La consumer secret key utilizzata per firmare le richieste
   * @var string
   */
  public $consumerSecretKey = null;

  /**
   * La secret key relativa al client
   * @var string
   */
  public $secretKey = null;

  /**
   * EndPoint del servizio
   * @var string
   */
  protected $endPoint;

  /**
   * Memorizza il data store utilizzato per accedere ai dati utente
   * @var User_Data_Store_Interface
   */
  protected $userDataStore;

  /**
   * Mappa dei metodi di WS
   * @var array
   * @todo completare definizione
   */
  protected $methodMap = array(
  // ** NOTA IMPORTANTE **
  // il nome del metodo (chiave dell'array) deve essere messo tutto in minuscolo, es: blockgetheader e non blockGetHeader !!

  //API GET NONCE
    'getnonce' => array(
      'isSecure'            => false,
      'requestCurrentUuid'  => false,
      'autoAddLoggedUser'   => false,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'oauth.requestNonce',
      'params'              =>  array()
  ),

  //BLOCK API
    'blockgetheader' => array(
      'isSecure'            => false,
      'requestCurrentUuid'  => false,
      'autoAddLoggedUser'   => true,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'block.getHeader',
      'params'              =>  array('context')
  ),
    'blockgetfooter' => array(
      'isSecure'            => false,
      'requestCurrentUuid'  => false,
      'autoAddLoggedUser'   => true,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'block.getFooter',
      'params'              =>  array()
  ),
    'blockgetstylesheets' => array(
      'isSecure'            => false,
      'requestCurrentUuid'  => false,
      'autoAddLoggedUser'   => false,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'block.getStylesheets',
      'params'              =>  array()
  ),
    'blockgetjavascripts' => array(
      'isSecure'            => false,
      'requestCurrentUuid'  => false,
      'autoAddLoggedUser'   => false,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'block.getJavascripts',
      'params'              =>  array()
  ),

  //COMMENTS API
    'commentsgetforitem' => array(
      'isSecure'            => true,
      'requestCurrentUuid'  => false,
      'autoAddLoggedUser'   => false,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'comments.getForItem',
      'params'              =>  array('q_item_id')
  ),

  //ITEM API
    'itemgetiditembyurl' => array(
      'isSecure'            => false,
      'requestCurrentUuid'  => false,
      'autoAddLoggedUser'   => false,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'item.getIdItemByUrl',
      'params'              =>  array('q_url')
  ),
    'itemgetbyid' => array(
      'isSecure'            => true,
      'requestCurrentUuid'  => false,
      'autoAddLoggedUser'   => false,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'item.getById',
      'params'              =>  array('q_item_id')
  ),
    'itemgetrelatedbyid' => array(
      'isSecure'            => false,
      'requestCurrentUuid'  => false,
      'autoAddLoggedUser'   => false,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'item.getRelatedById',
      'params'              =>  array('q_item_id', 'q_item_type', 'q_item_source')
  ),
    'itemincrementviewbyurl' => array(
      'isSecure'            => true,
      'requestCurrentUuid'  => false,
      'autoAddLoggedUser'   => true,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'item.incrementViewByUrl',
      'params'              =>  array('set_url')
  ),
    'itemgetpopular' => array(
      'isSecure'            => false,
      'requestCurrentUuid'  => false,
      'autoAddLoggedUser'   => false,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'item.getPopular',
      'params'              =>  array('q_item_type', 'q_item_source')
  ),
    'itemgetstorenewsbystoreid' => array(
      'isSecure'            => true,
      'requestCurrentUuid'  => false,
      'autoAddLoggedUser'   => false,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'item.getStoreNewsByStoreId',
      'params'              =>  array('q_store_id')
  ),


  //TAG API
    'taggetbyuser' => array(
      'isSecure'            => true,
      'requestCurrentUuid'  => true,
      'autoAddLoggedUser'   => false,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'tag.getByUser',
      'params'              =>  array('q_user_id')
  ),
    'taggetforitem' => array(
      'isSecure'            => true,
      'requestCurrentUuid'  => false,
      'autoAddLoggedUser'   => false,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'tag.getForItem',
      'params'              =>  array('q_item_id')
  ),
    'taggetforitembyuser' => array(
      'isSecure'            => true,
      'requestCurrentUuid'  => true,
      'autoAddLoggedUser'   => false,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'tag.getForItemByUser',
      'params'              =>  array('q_item_id')
  ),
    'taggetshared' => array(
      'isSecure'            => true,
      'requestCurrentUuid'  => true,
      'autoAddLoggedUser'   => false,
      'http_verb'         =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'tag.getShared',
      'params'              =>  array('q_item_id')
  ),
  //USER API
    'usergetprofile' => array(
      'isSecure'            => true,
      'requestCurrentUuid'  => true,
      'autoAddLoggedUser'   => false,
      'http_verb'           =>  HTTP_Request2::METHOD_GET,
      'api_method'          =>  'user.getProfileById',
      'params'              => array('q_item_id'),
  ),
  );

  /**
   * Memorizza l'endpoint del WS
   *
   * @param string $endPoint
   * @param User_Data_Store_Interface $userDataStore Data store dei dati utente
   * @param string La consumer key utilizzata per richiedere codici nonce
   * @param string La consumer secret key utilizzata per firmare le chiamate
   * @param array $methodMap Parametro opzionale per specificare un array di mappa diverso da quello di default
   */
  public function __construct($endPoint, User_Data_Store_Interface $userDataStore, $consumerKey, $consumerSecret, $methodMap = array())
  {
    $this->endPoint = $endPoint;
    $this->consumerKey = $consumerKey;
    $this->consumerSecretKey = $consumerSecret;
    $this->userDataStore = $userDataStore;

    if (!empty($methodMap))
    {
      $this->methodMap = $methodMap;
    }
  }

  /**
   * Effettua la chiamata verso il WS.
   *
   * @param string $method
   * @param array $arguments
   * @return Core_Result_Element
   */
  public function __call($method, $arguments)
  {
    $method = strtolower($method);
    if (in_array($method, array_keys($this->methodMap)))
    {
      return $this->callApi($method, $arguments);
    }
  }


  /**
   * Api per recupero header
   *
   * @param string $trackingCode
   */
  public function blockGetHeader($trackingCode = '')
  {
    $arguments = array();
    if (!empty($trackingCode))
    {
      $arguments = array('set_tracking_code' => $trackingCode);
    }
    
    $context = getenv('HART_APP_CONTEXT') ? getenv('HART_APP_CONTEXT') : 'default';
    $arguments['context'] = $context;
    return $this->callApi('blockgetheader', $arguments);
  }

  /**
   * Api per recupero footer
   * @param string $trackingCode
   */
  public function blockGetFooter($trackingCode = '')
  {
    $arguments = array();
    if (!empty($trackingCode))
    {
      $arguments = array('set_tracking_code' => $trackingCode);
    }
    return $this->callApi('blockgetfooter', $arguments);
  }

  /**
   * Effettua la chiamata alla api.
   *
   * @internal Il metodo centralizza tutto il codice comune
   * necessario ad effettuare una chiamata.
   *
   * @param string $apiMethod La stringa che identifica il metodo all'interno della method map.
   * @param array $arguments
   */
  protected function callApi($method, $arguments)
  {
    $apiMethod = $this->createApiMethod($method, $arguments);
    $apiUrl = $this->createUrl($apiMethod);
    $request = $this->createRequest($apiUrl, $apiMethod);
    $this->addRequestParameters($request, $apiMethod);

    if ($apiMethod->getIsSecure() === true)
    {
      $nonce = $this->requestNonce();
      $this->addSignature($request, $nonce);
    }
    $result = $this->getApiResult($request);
    return $result;
  }

  /**
   * Istanzia l'oggetto Core_Client_Api.
   *
   * Se non viene passato un array di parametri il
   * metodo cerca di recuperare i dati necessari dall'array
   * methodMap
   *
   * @param string $method
   * @param array $arguments
   * @param array $params
   * @return Core_Client_Api
   */
  protected function createApiMethod($method, $arguments, $params = null)
  {
    if (is_null($params))
    {
      $params = $this->methodMap[$method];
    }

    $apiMethod = new Core_Client_Api();
    $apiMethod->fromArray($this->methodMap[$method]);
    $apiMethod->setParams($arguments);
    return $apiMethod;
  }
  /**
   *  Richiede e ritorna un codice nonce.
   *
   *  @return string $nonce
   */
  protected function requestNonce()
  {
    if (is_null($this->consumerKey))
    {
      throw new Core_Client_Exception('You must define a consumer key first');
    }
    $params = array(self::PARAMETER_OAUTH_CONSUMER_KEY => $this->consumerKey);
    $result = $this->callApi('getnonce', $params);
    return $result->getNonce();
  }

  /**
   * Firma la request.
   *
   * La firma viene generata:
   * * Aggiungedo il request_nonce ai parametri della richiesta
   * * Ordinando i paraemtri della request in ordine alfabetico (per chiave)
   * * encodando il valore di ogni parametro con rawurlencode()
   * * generando una query string con http_build_query()
   * * firmando la query string con la secret key del consumer
   *
   * Il metodo aggiunge i parametri alla richiesta $request
   *
   * @param HTTP_Request2 $request
   * @param string $nonce
   */
  protected function addSignature(HTTP_Request2 $request, $nonce)
  {
    if (is_null($this->consumerSecretKey))
    {
      throw new Core_Client_Exception('You must define a consumer secret key first');
    }
    $request->getUrl()->setQueryVariable(self::PARAMETER_OAUTH_NONCE, $nonce);
    $request->getUrl()->setQueryVariable(self::PARAMETER_OAUTH_CONSUMER_KEY, $this->consumerKey);
    $parameters = $request->getUrl()->getQueryVariables();
    uksort($parameters, 'strnatcmp');
    foreach ($parameters as $key => $val)
    {
      $parameters[$key] = rawurlencode($val);
    }
    $queryString = http_build_query($parameters);
    $signature = base64_encode(hash_hmac('sha1', $queryString, $this->consumerSecretKey));
    $request->getUrl()->setQueryVariable(self::PARAMETER_OAUTH_SIGNATURE, $signature);
  }

  /**
   * Compone la url per la richiesta
   *
   * @param string $method
   * @return string
   */
  protected function createUrl(Core_Client_Api $method)
  {
    $url = sprintf('%s/%s', $this->endPoint, $method->getApiMethod());
    return $url;
  }

  /**
   * Aggiunge alla request i parametri necessari alla chiamata.
   *
   * Il metodo aggiunge ai parametri di $request quelli presenti in $method
   * e i parametri extra legati all'utente di sessione.
   *
   * @param HTTP_Request2 $request
   * @param Core_Clien_Api $method l'oggetto che descrive la api da chiamare
   * @throws Core_Api_Exception() se $method->getRequestUser() = true ma $this->userDataStore->isAuthenticated() = false
   */
  protected function addRequestParameters(HTTP_Request2 $request, Core_Client_Api $method)
  {
    $queryVariables = $method->getParams();
    $request->getUrl()->setQueryVariables($queryVariables);

    //accodo l'utente sempre se loggato
    if ($method->getAutoAddLoggedUser() && $this->userDataStore->isAuthenticated())
    {
      $request->getUrl()->setQueryVariable(self::PARAMETER_CURRENT_USER_UUID, $this->userDataStore->getUserId());
    }
    else if ($method->getRequestCurrentUuid() && $this->userDataStore->isAuthenticated())
    {
      $request->getUrl()->setQueryVariable(self::PARAMETER_CURRENT_USER_UUID, $this->userDataStore->getUserId());
    }
    else if ($method->getRequestCurrentUuid() && !$this->userDataStore->isAuthenticated())
    {
      throw new Core_Api_Exception('You need an authenticated user');
    }

  }

  /**
   * Verifica che la response abbia status code 200
   *
   * @throws Core_Client_Exception se lo status non è 200
   * @return boolean
   * @param HTTP_Request2_Response $response
   */
  protected function checkStatusCode(HTTP_Request2_Response $response)
  {
    if (! (200 == $response->getStatus()))
    {
      throw new Core_Request_Exception('Get ' . $response->getStatus() . ' status code');
    }
    return true;
  }

  /**
   * Effettua la chiamata api e ritorna il risultato
   *
   * Il risultato della chiamata viene incapsulato in un'oggetto
   * Core_Result_Element che ne facilita l'accesso. Dopo aver istanziato
   * l'oggetto Core_Result_Element viene chiamato il metodo Core_Result_Element::checkStatus()
   * per verificare lo stato della richiesta. Il checkStatus lancia un'eccezione di tipo
   * Core_Api_Exception se lo status della richiesta è un ko o un warning lo stato è ok ma la
   * richiesta contiene un blocco error_detail{@see Core_Result_Element::checkStatus()}.
   *
   * @param HTTP_Request2 $request
   * @return Core_Result_Element
   * @throws Core_Api_Exception se lo status della richiesta indica un errore (result ko).
   * @see Core_Result_Element::checkStatus()
   * @see checkStatus()
   *
   */
  protected function getApiResult(HTTP_Request2 $request)
  {
    $response = $request->send();
    $this->checkStatusCode($response);
    $data = $response->getBody();
    $result = $this->getResultElement($data);
    $result->checkStatus();
    return $result;
  }

  /**
   * Ritorna un'istanza di Core_Result_Element() per l'xml
   * $data.
   *
   * @param string $data xml di risposta da passare al costruttore di Core_Result_Element
   * @return Core_Result_Element
   */
  protected function getResultElement($data)
  {
    return new Core_Result_Element($data);
  }

  /**
   * Verifica che il numero di parametri inviati corrisponda al numero di
   * parametri inviato o sia superiore
   *
   * @param array $expectedArgs
   * @param array $args
   * @return boolean
   * @throws Core_Api_Exception se il numero di elementi non corrisponde
   */
  protected function checkRequestParameters($expectedArgs, $args)
  {
    if (count($expectedArgs) <= count($args))
    {
      return true;
    }
    else
    {
      throw new Core_Api_Exception(sprintf('Invalid arguments number, %d given, %d expected', count($expectedArgs), count($args)));
    }
  }
  /**
   * Genera una richiesta HTTP_Request
   *
   * @internal Estendendo questo metodo i vari client potranno personalizzare
   * la creazione della richiesta HTTP (aggiungendo ad esempio configurazioni
   * personalizzate)
   *
   * @param string $url
   * @param Core_Client_Api $method
   * @return HTTP_Request2
   */
  protected function createRequest($url, Core_Client_Api $method)
  {
    return new HTTP_Request2($url, $method->getHttpVerb());
  }

}