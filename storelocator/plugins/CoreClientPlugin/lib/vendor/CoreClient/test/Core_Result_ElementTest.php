<?php
/**
 * Test unit per la classe Core_Result_Element
 * @author ftassi
 * @package Core
 * @subpackage Test
 *
 */

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../Core/Result/Element.php';
require_once dirname(__FILE__).'/../Core/Api/Exception.php';
error_reporting(E_ALL);
ini_set('display_errors',1);
class Core_Result_ElementTest extends PHPUnit_Framework_TestCase
{
  /**
   * Risultato fake di una chiamata (XML)
   * @var string
   */
  protected $xmlResutlData;

  /**
   * Inizializza l'xml utilizzato per testare la classe
   */
  public function setUp()
  {
    $this->xmlResutlData = <<<XML
<?xml version="1.0"?>
<response>
  <status>
      <result>
        Ok
      </result>
  </status>
  <collection type="User">
    <element>
  	  <data>
  		<username>
  		  fake user1
  		</username>
  		<email>
  		  fake.email1@fakedomain.com
  		</email>
  	  </data>
    </element>
  </collection>
  <collection type="UserGroup">
      <element>
        <data>
          <id>
            1
          </id>
          <group_name>
            Administrators
          </group_name>
          <role>
            admin
          </role>
        </data>
      </element>
      <element>
        <data>
          <id>
            2
          </id>
          <group_name>
            User
          </group_name>
          <role>
            standard
          </role>
        </data>
      </element>
    </collection>
    <collection type="TestCollection">
      <element>
        <data>
          <username>
            fake user 2
          </username>
          <name>
            fake name
          </name>
        </data>
      </element>
    </collection>
</response>
XML;
  }
  
  
  /**
   * Test getCollections
   *
   * Verifica l'accesso alle collections presenti all'interno del
   * result set
   */
  public function testGetElements()
  {
    $client = new Core_Result_Element($this->xmlResutlData);
    $elements = $client->getCollection()->getElements();
    $this->assertThat($elements, $this->isType('array'));
    $this->assertEquals(1, sizeof($elements));
  }
  
  
  /**
   * Test per recuper elementi dalla sezione <data> del result
   *
   *
   */
  public function testGetGenericData()
  {
    $client = new Core_Result_Element($this->xmlResutlData);
    $this->assertEquals('fake user1', $client->getCollection()->getElement()->getUsername());
    $this->assertEquals('fake.email1@fakedomain.com', $client->getCollection()->getElement()->getEmail());
    $this->assertEquals(NULL, $client->getFakeField());
  }

  /**
   * Test getCollections
   *
   * Verifica l'accesso alle collections presenti all'interno del
   * result set
   */
  public function testGetCollections()
  {
    $client = new Core_Result_Element($this->xmlResutlData);
    $collections = $client->getCollections();
    $this->assertThat($collections, $this->isType('array'));
    $this->assertEquals(3, count($collections));
    $this->assertEquals('User', $collections[0]->getType());
    $this->assertThat($collections[0], $this->isInstanceOf('Core_Result_Element'));
    $this->assertEquals('UserGroup', $collections[1]->getType());
    $this->assertThat($collections[1], $this->isInstanceOf('Core_Result_Element'));
    $this->assertEquals('TestCollection', $collections[2]->getType());
    $this->assertThat($collections[2], $this->isInstanceOf('Core_Result_Element'));
  }
  
  /**
   * Test getCollection
   *
   * Verifica l'accesso ad una specifica collection.
   *
   * @dataProvider getCollectionProvider
   * @param string $type
   */
  public function testGetCollection($type)
  {
    $client = new Core_Result_Element($this->xmlResutlData);
    $collection = $client->getCollection($type);
    $this->assertThat($collection, $this->isInstanceOf('Core_Result_Element'));
    $this->assertThat($collection->getElements(), $this->isType('array'));
    foreach ($collection->getElements() as $item)
    {
      $this->assertThat($item, $this->isInstanceOf('Core_Result_Element'));
      $this->assertEquals('element', $item->getName());
    }
  }

  /**
   * Data Provider per testGetCollection.
   *
   * @return array
   */
  public function getCollectionProvider()
  {
    return array(
    array('UserGroup'),
    array('TestCollection'),
    );
  }

  /**
   * Test checkStatus
   *
   * Verifica lo status nella richiesta
   *
   * @return unknown_type115
   * 
   */
  public function testCheckStatus()
  {
    $client = new Core_Result_Element($this->xmlResutlData);
    $this->assertEquals(true, $client->checkStatus());
  }

  /**
   * Test checkStatus
   *
   * Verifica il check di status per richieste che hanno uno
   * status non valido
   *
   * @expectedException Core_Api_Exception
   */
  public function testCheckStatusKoReponse()
  {
    $xmlData = <<<XML
<response>
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
XML;
    $client = new Core_Result_Element($xmlData);
    $client->checkStatus();
  }

  /**
   * Verifica il check di status per una richiesta
   * con status ok e messaggio di errore (warning o simile)
   *
   * Il metodo verifica solamente che venga triggerato un errore di runtime.
   * @internal Da un primo test sembra che l'error handler di PHPUnit non lanci eccezioni
   * di differenziate per errori di tipo E_USER_WARNING e E_USER_NOTICE (tutti vengono convertiti
   * in un'eccezzione di tipo PHPUnit_Framework_Error)
   * 
   * @expectedException PHPUnit_Framework_Error
   * @dataProvider statusFailedProvider
   */
  public function testCheckStatusOkWithErrorResponse($xmlData, $errorType)
  {
      $client = new Core_Result_Element($xmlData);
      $client->checkStatus();
  }
  
  /**
   * DataProvider per metodo testCheckStatusOkWithErrorResponse
   * 
   * Il metodo ritorna, oltre l'xml che dovrebbe generare un errore, anche 
   * il tipo di eccezione da intercettare. Il metodo di test non considera
   * il secondo parametro dato che, apparentemente, PHPUnit sembra non intercettare
   * correttamente gli errori E_USER_WARNING e E_USER_NOTICE
   * 
   * @return array
   */
  public function statusFailedProvider()
  {
    $xmlError = <<<XML
<response>
  <status>
    <result>ok</result>
    <error_detail>
      <error_level>1</error_level>
      <core_error_type>02</core_error_type>
      <core_error_code>101</core_error_code>
      <request_identifier>12609819110.11014200</request_identifier>
      <error_message><![CDATA[Error Message]]></error_message>
    </error_detail>
  </status>
</response>
XML;
    $xmlWarning = <<<XML
<response>
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
XML;
    $xmlNotice = <<<XML
<response>
  <status>
    <result>ok</result>
    <error_detail>
      <error_level>8</error_level>
      <core_error_type>02</core_error_type>
      <core_error_code>101</core_error_code>
      <request_identifier>12609819110.11014200</request_identifier>
      <error_message><![CDATA[Error Message]]></error_message>
    </error_detail>
  </status>
</response>
XML;
    $xmlExtraError = <<<XML
<response>
  <status>
    <result>ok</result>
    <error_detail>
      <error_level>12</error_level>
      <core_error_type>02</core_error_type>
      <core_error_code>101</core_error_code>
      <request_identifier>12609819110.11014200</request_identifier>
      <error_message><![CDATA[Error Message]]></error_message>
    </error_detail>
  </status>
</response>
XML;
    
    return array(
      array($xmlError, 'PHPUnit_Framework_Error'),
      array($xmlWarning, 'PHPUnit_Framework_Error_Warning'),
      array($xmlNotice, 'PHPUnit_Framework_Error_Notice'),
      array($xmlExtraError, 'PHPUnit_Framework_Error_Notice'),
    );
  }
}

