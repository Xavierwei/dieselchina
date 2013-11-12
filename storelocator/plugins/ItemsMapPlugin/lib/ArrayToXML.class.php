<?php
/**
 * Class to translate PHP Array element into XML and vice versa.
 *
 * Modify by Denis Torresan
 *
 * @author    Marco Vito Moscaritolo
 * @copyright GPL 3
 * @tutorial  http://mavimo.org/varie/array_xml_php
 * @example   index.php
 * @version   0.8
 */
class ArrayToXML {
  /**
   * @staticvar string - String to use as key for node attributes into array
   * @todo      Convert this into a value settable from user
   */
  const attr_arr_string = 'attributes';
  /**
   * The main function for converting to an XML document.
   * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
   *
   * @static
   * @param  array $data
   * @param  string $rootNodeName - what you want the root node to be - defaults to items.
   * @param  string $itemNodeName - what you want the element node to be - defaults to item.
   * @param  SimpleXMLElement $xml - should only be used recursively
   * @return string XML
   */
  public static function toXml($data, $rootNodeName = 'items', $itemNodeName = 'item', $cdataTags = array(), &$xml = NULL) {
    if (is_null($xml)) {
      $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?><" . $rootNodeName . "/>");
    }

    // loop through the data passed in.
    foreach($data as $key => $value) {
      // if numeric key, assume array of itemNodeName elements
      if (is_numeric($key)) {
        $key = $itemNodeName;
      }
      // Check if is attribute
      if($key == ArrayToXML::attr_arr_string) {
        // Add attributes to node
        foreach($value as $attr_name => $attr_value) {
          $xml->addAttribute($attr_name, $attr_value);
        }
      } else {
        // delete any char not allowed in XML element names
        $key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

        // if there is another array found recrusively call this function
        if (is_array($value)) {

          // create a new node unless this is an array of elements
          $node = ArrayToXML::isAssoc($value) ? $xml->addChild($key) : $xml;

          // recrusive call - pass $key as the new rootNodeName
          ArrayToXML::toXml($value, $rootNodeName, $key, $cdataTags, $node);
        } else {
          if( in_array( $key, $cdataTags ) ){
            self::addCdataElement($xml, $key, $value);
          }
          else{
            $xml->addChild($key, $value);
          }
        }
      }
    }
    // pass back as string. or simple xml object if you want!
    return $xml->asXML();
  }


  /**
   * Thanks to Roberto Butti.
   * @see Core_Generator_XmlFactory
   * @param $node
   * @param $nodename
   * @param $cdata_text
   * @return unknown_type
   */
  private static function addCdataElement($node, $nodename, $cdata_text) {
    $node = $node->addChild($nodename); //Added a nodename to create inside the function
    $node = dom_import_simplexml($node);
    $no = $node->ownerDocument;
    $node->appendChild($no->createCDATASection($cdata_text));
    return $node;
  }


  /**
   * The main function for converting to an array.
   * Pass in a XML document and this recrusively loops through and builds up an array.
   *
   * @static
   * @param  string $obj - XML document string (at start point)
   * @param  array  $arr - Array to generate
   * @return array - Array generated
   */
  public static function toArray( $obj, &$arr = NULL ) {
    if ( is_null( $arr ) )   $arr = array();
    if ( is_string( $obj ) ) $obj = new SimpleXMLElement( $obj );

    // Get attributes for current node and add to current array element
    $attributes = $obj->attributes();
    foreach ($attributes as $attrib => $value) {
      $arr[ArrayToXML::attr_arr_string][$attrib] = (string)$value;
    }

    $children = $obj->children();
    $executed = FALSE;
    // Check all children of node
    foreach ($children as $elementName => $node) {
      // Check if there are multiple node with the same key and generate a multiarray
      if($arr[$elementName] != NULL) {
        if($arr[$elementName][0] !== NULL) {
          $i = count($arr[$elementName]);
          ArrayToXML::toArray($node, $arr[$elementName][$i]);
        } else {
          $tmp = $arr[$elementName];
          $arr[$elementName] = array();
          $arr[$elementName][0] = $tmp;
          $i = count($arr[$elementName]);
          ArrayToXML::toArray($node, $arr[$elementName][$i]);
        }
      } else {
        $arr[$elementName] = array();
        ArrayToXML::toArray($node, $arr[$elementName]);
      }
      $executed = TRUE;
    }
    // Check if is already processed and if already contains attributes
    if(!$executed && $children->getName() == "" && !isset ($arr[ArrayToXML::attr_arr_string])) {
      $arr = (String)$obj;
    }
    return $arr;
  }

  /**
   * Determine if a variable is an associative array
   *
   * @static
   * @param  array $obj - variable to analyze
   * @return boolean - info about variable is associative array or not
   */
  private static function isAssoc( $array ) {
    return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
  }
}