<?php

/**
 * itemsMap actions.
 *
 * @package    itemsMap
 * @author     Denis Torresan
 */
class BaseitemsMapActions extends sfActions
{

  /**
   * Il contenuto dell'xml da renderizzare
   * 
   * @var string
   */
  protected $xmlContent;

  /**
   * Metodo preExecute. Imposta il content type corretto
   * per questo modulo (xml)
   * @see lib/vendor/symfony/lib/action/sfAction#preExecute()
   */
  public function preExecute()
  {
    $this->getResponse()->setHttpHeader('Content-Type','text/xml; charset=utf-8');
  }

  /**
   * PostExecute
   * 
   * Elimina il layout e imposta $this->xmlContent come contenuto della pagina
   *
   */
  public function postExecute()
  {
    $this->getResponse()->setContent($this->getResponse()->getContent().$this->xmlContent);
    $this->setLayout(false);
  }
  
  /**
   * Questa action in base ai valori ritornati dal metodo getItemsMap() genera un output XML
   * corrispondente al formato itemsmap.xml
   *
   * @param sfRequest $request
   * @return unknown_type
   */
  public function executeItemsMapXml(sfRequest $request)
  {
    $this->xmlContent = ItemsMapGenerator::toXML($this->getItemsMap());
    return sfView::NONE;
  }

  /**
   * Questo metodo ritorna un array contenente tutti gli items da cui generare la itemsmap.xml.
   *
   * Tale metodo (che qui viene popolato come demo) dovr� essere reimplementato in ogni sito satellite
   * per ritornare gli items relativi al sito specifico.
   *
   * L'array di mappa dovr� essere array di array, in cui ogni elemento interno dovr� avere questa struttura:
   *
   *   - type
   *   - url
   *   - image
   *   - id
   *   - name
   *   - text
   *
   * @return unknown_type
   */
  protected function getItemsMap(){

    //array con valori di test
    $items = array(
    array(
          'type'  => 'type1',
          'sub_type' => 'sub type1',
          'url'   => '/path/page1',
          'image' => 'http://storage.diesel.com/img/img1.jpg',
          'id'    => '123',
          'name'  => 'Content 1',
          'text'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras et iaculis diam. Cras bibendum mollis sem ut mattis. In ut hendrerit mi. Suspendisse congue feugiat neque at placerat.',
          'filter_a' => 'value1',
          'filter_b' => 'value2',
          'filter_c' => 'value3',
          'filter_d' => 'value4',
          'filter_e' => 'value5',
    ),
    array(
          'type'  => 'type2',
          'sub_type' => 'sub type2',
          'url'   => '/path/page2',
          'image' => 'http://storage.diesel.com/img/img2.jpg',
          'id'    => '456',
          'name'  => 'Content 2',
          'text'  => 'Quisque eget enim dui. Proin sed orci metus. Duis consectetur mi vel nibh dapibus a mattis augue iaculis. Aliquam vulputate tempor magna.',
          'filter_a' => 'value1',
          'filter_b' => 'value2',
          'filter_c' => 'value3',
    ),
    array(
          'type'  => 'type3',
          'sub_type' => 'sub type3',
          'url'   => '/path/page3',
          'image' => 'http://storage.diesel.com/img/img3.jpg',
          'id'    => '789',
          'name'  => 'Content 3',
          'text'  => 'Duis sed ante dui. Morbi sollicitudin, turpis sed tristique faucibus, tortor enim congue diam, eu ultrices arcu tellus id risus. Mauris mattis auctor lorem, at accumsan ligula hendrerit sit amet. Donec sapien augue, blandit eget placerat sit amet, tempus non eros.',
          'filter_a' => 'value1',
          'filter_b' => 'value2',
          'filter_c' => 'value3',
    )
    );

    return $items;
  }
}
