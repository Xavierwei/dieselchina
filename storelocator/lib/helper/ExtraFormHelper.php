<?php
/**
 * ExtraFormHelper
 * 
 * Contiene helper di utility per la generazione dei form
 * 
 * @package storelocator
 * @subpackage helper
 * 
 */

/**
 * Genera una select che al change aggiorna le option di $elementToUdate
 * 
 * @param $field Select che effettua la chiamata
 * @param $ajaxCall route della chiamata da effettuare
 * @param $elementToUdate select da aggiornare con il risultato della chiamata
 * @return unknown_type
 */
function dynamic_select_tag(sfFormField $field, $ajaxCall, sfFormField $elementToUdate, $attributes=array())
{
  $name = $field->getName();
	$elementId = $field->renderId();
	$updateId = $elementToUdate->renderId();
	$url = proxy_url_for($ajaxCall);
 
$script = <<<SCR
	function countryManager(){
		var comboValue = document.getElementById('$elementId').value;
	 
		jQuery.getJSON("$url",{ $name: comboValue, ajax: 'true'}, function(j){
			var options = '';
			jQuery.each(j, function(key, val){
				options += '<option value="' + key + '">' + val + '</option>';
			});
			
			jQuery("select#$updateId").html(options);
			jQuery("select#$updateId").appendTo(jQuery(".$updateId").parent('div.form-row'));
			jQuery("div.$updateId").remove();
			updateStoreByCity();
		});
	}
SCR;

  $script = javascript_tag($script);
  return $field->render($attributes) . $script;
}