<?php

/**
 * ItemsMapGenerator permette di generare un XML partendo da un array di array in cui ogni elemento ha la seguente struttura:
 *
 *   - type
 *   - url
 *   - image
 *   - id
 *   - name
 *   - text
 *
 * @author  dtorresan
 * @package sfCoreClientPlugin
 */
class ItemsMapGenerator
{

  /**
   * Ritorna un XML sulla base dell'array passato
   *
   * @return string
   */
  static public function toXML($items)
  {
    $xml = ArrayToXML::toXml($items, 'items', 'item', array('text'));
    return self::fixCDATA( $xml );
  }


  /**
   * Metodo che effettua un fix dei cdata in quanto SimpleXMLElement non gestisce i CDATA
   * 
   * @see https://students.kiv.zcu.cz/doc/php5/manual/cs/ref.simplexml.php.html#77263
   * 
   * @param $string
   * @return unknown_type
   */
  static public function fixCDATA($string) {
    $find[]     = '&lt;![CDATA[';
    $replace[] = '<![CDATA[';
     
    $find[]     = ']]&gt;';
    $replace[] = ']]>';

    return $string = str_replace($find, $replace, $string);
  }


}
