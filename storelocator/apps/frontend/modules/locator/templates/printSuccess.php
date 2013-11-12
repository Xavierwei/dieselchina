<?php use_javascript('/assets/core/js/jquery-1.4.min.js'); ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script language="javascript">
      contents =  window.opener.document.getElementById("map");
      //$('#map').html($(map).html());
      
      document.innerHTML= contents.innerHTML;
      window.print();
    </script>
    

<div class="printLayout">
  <div id="map"></div>
  
</div>