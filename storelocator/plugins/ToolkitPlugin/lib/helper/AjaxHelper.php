<?php
/**
 * Genera il JS necessario a ricaricare un blocco al 
 * document.ready
 * @param unknown_type $url
 * @param unknown_type $cssBlockSelector
 * @return unknown_type
 */
function update_block($url, $cssBlockSelector)
{
  use_helper('JavascriptBase');
  $jsCode =<<<JSC
  $(window).load(function() {
    \$.ajax({type:'POST',dataType:'html',success:function(data, textStatus){\$('{$cssBlockSelector}').replaceWith(data);refreshFonts();},url:'{$url}'}) 
  }
  );
JSC;

  return javascript_tag($jsCode);
}