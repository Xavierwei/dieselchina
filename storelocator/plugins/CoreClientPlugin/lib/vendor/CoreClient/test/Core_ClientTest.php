<?php
/**
 * File di unit-test per la classe core_client
 * 
 * Questa suite di test si occupa di verificare le funzionalità principali della classe
 * simulando delle response del web service. La simulazione della response è possibile 
 * sovrascrivendo il metodo getRequest del client e utilizzando un'istanza di HTTP2_Request
 * e un adapter Mock che contiene la response voluta.
 * 
 * In questo modo è possibile testare, anche in assenza del ws core, la mappatura degli errori,
 * l'utilizzo del dataStore per accedere ai dati utente di sessione e il controllo di coerenza
 * sul numero dei parametri.
 * 
 * Per il testing della firma per i metodi protetti è necessario implementare un test funionale
 * che utilizzi il ws reale.
 * 
 * @todo Implementare test funzionale con ws reale.
 * @package Core
 * @subpackage Test
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
$includePath = ini_get('include_path');
ini_set('include_path', $includePath. ':'.dirname(__FILE__).'/../');

require_once 'PHPUnit/Framework.php';
require_once 'Net/URL2.php';
require_once 'Core/Client.php';
require_once 'HTTP/Request2.php';
require_once 'HTTP/Request2/Adapter/Mock.php';
require_once 'User/Data/Store/interface.php';

class Core_ClientTest extends PHPUnit_Framework_TestCase
{
  /**
   * Test per chiamata a metodo pubblico (non richiede la firma).
   *
   * Il test verifica la chiamata a due metodi esistenti passando
   * il corretto numero di parametri e ad un metodo inesistente.
   * Il test verifica anche che non vengano lanciate eccezioni chimando
   *
   */
  public function testGetPublicValidCall()
  {

    $methodMap = $this->getMethodMap();
    $response = $this->getOkResponse();
    $dataStore = new MockLoggedUserDataStore();

    $client = $this->getMockedClient($response, array('http://testcore', $dataStore, 'fakeConsumerKey', 'fakeConsumerSecret', $methodMap));
    $result = $client->testMethod1();
    $this->assertThat($result, $this->isInstanceOf('Core_Result_Element'));

    $client = $this->getMockedClient($response, array('http://testcore', $dataStore, 'fakeConsumerKey', 'fakeConsumerSecret', $methodMap));
    $result = $client->testMethod2('fakeParam');
    $this->assertThat($result, $this->isInstanceOf('Core_Result_Element'));

    $client = $this->getMockedClient($response, array('http://testcore', $dataStore, 'fakeConsumerKey', 'fakeConsumerSecret', $methodMap));
    $result = $client->getInvalidMethod('fakeParam');
    $this->assertEquals(null, $result);
     
  }

  /**
   * Test per chiamata con numero di parametri non validi
   *
   * Il test verifica il controllo sul numero di parametri impostato nella
   * mappa passata all'oggetto Core_Client. Nel metodo viene testato che
   * passando un numero di parametri inferiore al numero di parametri
   * obbligatori venga generata un'eccezione. Il controllo su un numero
   * di parametri superiori non viene effettuato (e possibile passare
   * parametri extra ad ogni chiamata).
   *
   * @expectedException Core_Api_Exception
   */
  public function testGetInvalidParamNums()
  {
    $methodMap = $this->getMethodMap();
    $response = $this->getOkResponse();
    $dataStore = new MockLoggedUserDataStore();

    $client = $this->getMockedClient($response, array('http://testcore', $dataStore, 'fakeConsumerKey', 'fakeConsumerSecret', $methodMap));
    $result = $client->testMethod2();
    //    $this->assertThat($result, $this->isInstanceOf('Core_Result_Element'));
  }
  
  /**
   * Verifica che effettuando una richiesta legata all'utente di sessione
   * venga lanciata un'eccezione nel caso in cui l'utente non sia valido
   *  
   * @expectedException Core_Api_Exception
   */
  public function testGetCurrentUserWithoutUser()
  {
    $methodMap = array(
      'testmethod'  =>  array(
        'isSecure'  =>            false,
        'requestCurrentUuid'  =>  true,
        'autoAddLoggedUser'   =>  true,
        'http_verb' =>  HTTP_Request2::METHOD_GET,
        'api_method'  =>  'test.method',
        'params'      =>  array()
    ));
    
    $response = $this->getOkResponse();
    $dataStore = new MockAnonymousUserDataStore();
    $client = $this->getMockedClient($response, array('http://testcore', $dataStore, 'fakeConsumerKey', 'fakeConsumerSecret', $methodMap));
    $result = $client->testMethod('fakeParam', 'fakeParam2');
    
  }

  /**
   * Test di chiamate per risposte errate.
   * 
   * Il metodo riceve un set di response dal dataProvider e verifica
   * che l'eccezione lanciata sia del tipo $exceptionType
   * 
   * @dataProvider getErrorResponseProvider
   * @param string $response
   * @param string $exceptionType il tipo di eccezione attesa 
   */
  public function testGetErrorResponse($response, $exceptionType)
  {
    $methodMap = $this->getMethodMap();
    $dataStore = new MockLoggedUserDataStore();
    $client = $this->getMockedClient($response, array('http://testcore', $dataStore, 'fakeConsumerKey', 'fakeConsumerSecret', $methodMap));
    
    try
    {
      $result = $client->testMethod1();
    }
    catch(Exception $e)
    {
      $this->assertThat($e, $this->isInstanceOf($exceptionType));
    }
  }
  
  /**
   * Data Provider per test testGetErrorResponse
   * 
   * Ritorna un set di response errare e relativa eccezione prevista dal client.
   *  
   * @return array
   */
  public function getErrorResponseProvider()
  {
    return array(
      array($this->getWarningResponse(), 'PHPUnit_Framework_Error'),
      array($this->getKoResponse(), 'Core_Api_Exception'),
      array($this->get404Response(), 'Core_Request_Exception'),
      array($this->get500Response(), 'Core_Request_Exception'),
      array($this->get501Response(), 'Core_Request_Exception'),
    );
  }

  /**
   * Il test verifica le chiamate che richiedono autenticazione.
   * 
   */
  public function getTestAuthenticatedMethod()
  {
    $mock = new HTTP_Request2_Adapter_Mock();
    $mock->addResponse($this->getNonceResponse());
    $mock->addResponse($this->getOkResponse());
    
    $request = new HTTP_Request2('http://testcore', HTTP_Request2::METHOD_GET, array('adapter' => $mock));

    $client = $this->getMock('Core_Client', array('createRequest'), $constructParameters);
    $client->expects($this->any())->method('createRequest')->will($this->returnValue($request));

    return $client;
  }
  
  /**
   * Crea un istanza di Core_Client con un metodo fake per la generazione della request
   *
   * Il metodo permette di associare al client una richiesta fake ottenuta utizzando
   * l'adapter mock fornito dal pacchetto HTTP_Request2. In questo modo è possibile simulare
   * il comportamento del webService core. La richiesta ritornerà come risultato il contenuto di $response
   *
   * @param string $response la risposta completa di intestazioni
   * @return Core_Client
   */
  protected function getMockedClient($response, $constructParameters)
  {
    $mock = new HTTP_Request2_Adapter_Mock();
    $mock->addResponse($response);
    $request = new HTTP_Request2('http://testcore', HTTP_Request2::METHOD_GET, array('adapter' => $mock));

    $client = $this->getMock('Core_Client', array('createRequest'), $constructParameters);
    $client->expects($this->any())->method('createRequest')->will($this->returnValue($request));

    return $client;
  }

  /**
   * Genera un array di mappa generico per i metodi
   * del servizio
   *
   * @return array
   */
  protected function methodMapDataProvider()
  {
    $methodMap = array(
      'testMethod'  =>  array(
        'isSecure'  =>            false,
        'requestCurrentUuid'  =>  false,
        'http_verb' =>  HTTP_Request2::METHOD_GET,
        'api_method'  =>  'test.method',
        'params'      =>  array(),
    )
    );

    return $methodMap;
  }

  /**
   * Ritorna un array di mappa per i metodi del WS
   * @return array
   */
  protected function getMethodMap()
  {
    $methodMap = array(
      'testmethod1'  =>  array(
        'isSecure'  =>            false,
        'requestCurrentUuid'  =>  false,
        'autoAddLoggedUser'   =>  false,
        'http_verb' =>  HTTP_Request2::METHOD_GET,
        'api_method'  =>  'test.method',
        'params'      =>  array()
    ),
      'testmethod2'  =>  array(
        'isSecure'  =>            false,
        'requestCurrentUuid'  =>  true,
        'autoAddLoggedUser'   =>  true,
        'http_verb' =>  HTTP_Request2::METHOD_GET,
        'api_method'  =>  'test.method',
        'params'      =>  array('param1')
    )
    );

    return $methodMap;
  }

  /**
   * Genera una response con status ok
   * @return string
   */
  protected function getOkResponse()
  {
    $response =
    "HTTP/1.1 200 OK\r\n" .
    "Content-Length: 32\r\n" .
    "Connection: close\r\n" .
    "\r\n".
    "<response>
      <status>
          <result>
            Ok
          </result>
      </status>
    </response>
    ";

    return $response;
  }
  
  /**
   * Genera una response per il codice nonce
   * 
   * @return string
   */
  protected function getNonceResponse()
  {
    $response =
    "HTTP/1.1 200 OK\r\n" .
    "Content-Length: 32\r\n" .
    "Connection: close\r\n" .
    "\r\n".
    "<response>
      <status>
          <result>
            Ok
          </result>
      </status>
      <data>
        <nonce>test_nonce_code</nonce>
      </data>
    </response>
    ";

    return $response;
  }

  /**
   * Genera una response con status ok
   * @return string
   */
  protected function getWarningResponse()
  {
    $response =
    "HTTP/1.1 200 OK\r\n" .
    "Content-Length: 32\r\n" .
    "Connection: close\r\n" .
    "\r\n".
    "<response>
      <status>
        <result>ok</result>
        <error_detail>
          <error_level>2</error_level>
          <core_error_type>02</core_error_type>
          <core_error_code>101</core_error_code>
          <request_identifier>12609819110.11014200</request_identifier>
          <error_message><![CDATA[Error Message]]></error_message>
        </error_detail>
      </status>
    </response>
    ";

    return $response;
  }

  /**
   * Genera una risposta con messaggio di errore (status ko)
   * @return string
   */
  protected function getKoResponse()
  {
    $response =
    "HTTP/1.1 200 OK\r\n" .
    "Content-Length: 32\r\n" .
    "Connection: close\r\n" .
    "\r\n".
    "<response>
      <status>
        <result>ko</result>
        <error_detail>
          <core_error_type>02</core_error_type>
          <core_error_code>101</core_error_code>
          <request_identifier>12609819110.11014200</request_identifier>
          <error_message><![CDATA[Error Message]]></error_message>
        </error_detail>
      </status>
    </response>
    ";

    return $response;
  }
  

  /**
   * Genera una response 404
   * 
   * @return string
   */
  protected function get404Response()
  {
    $response =
    "HTTP/1.1 404 Not Found\r\n" .
    "Content-Length: 32\r\n" .
    "Connection: close\r\n" .
    "\r\n";

    return $response;
  }
  
  /**
   * Genera una response 500
   * 
   * @return string
   */
  protected function get500Response()
  {
    $response =
    "HTTP/1.1 500 Internal Server Error\r\n" .
    "Content-Length: 32\r\n" .
    "Connection: close\r\n" .
    "\r\n";

    return $response;
  }
  
  /**
   * Genera una response 500
   * 
   * @return string
   */
  protected function get501Response()
  {
    $response =
    "HTTP/1.1 501 Not Implemented\r\n" .
    "Content-Length: 32\r\n" .
    "Connection: close\r\n" .
    "\r\n";

    return $response;
  }

}

/**
 * Implementazione mock dell'interfaccia User_Data_Store_Interface
 * simulando la presenza di un utente loggato
 * 
 * @author ftassi
 *
 */
class MockLoggedUserDataStore implements User_Data_Store_Interface
{
  public function isAuthenticated(){ return true;}

  public function getUserId(){ return 'userId';}
}

/**
 * Implementazione mock dell'interfaccia User_Data_Store_Interface
 * simulando la presenza di un utente non loggato
 * 
 * @author ftassi
 *
 */
class MockAnonymousUserDataStore implements User_Data_Store_Interface
{
  public function isAuthenticated(){ return false;}

  public function getUserId(){ return null;}
}

