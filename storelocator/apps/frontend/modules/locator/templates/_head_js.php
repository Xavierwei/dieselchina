<?php slot("head_javascripts");?>
<script type="text/javascript">
  
    // <![CDATA[
    var map,image,shadow,myOptions;
    
    <?php if( $currentStore != null ):?>
    function initialize() {
      var latlng = new google.maps.LatLng(<?php echo $currentStore->getLatitude()?>, <?php echo $currentStore->getLongitude()?>);
      
      image = new google.maps.MarkerImage('<?php echo asset_absolute_path("/assets/core/img/marker_store.png");?>',
      new google.maps.Size(32, 43),
      new google.maps.Point(0,0),
      new google.maps.Point(0, 43));
      shadow = new google.maps.MarkerImage('<?php echo asset_absolute_path("/assets/core/img/marker_store_shadow.png");?>',
      new google.maps.Size(40, 50),
      new google.maps.Point(0,0),
      new google.maps.Point(0, 50));

      myOptions = {
        zoom: 15,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };

      map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
      
      var marker = new google.maps.Marker({
          position: latlng,
          map: map,
          shadow: shadow,
          icon: image
      });
      
      var contentString = '<p><b><?php echo $currentStore->getCity();?></b><br/>'+
        '<?php echo $currentStore->getPublicType();?> - <?php echo $currentStore->getName()?><br/>'+
        '<?php echo $currentStore->getAddress()?><br/>'+
        '<?php echo $currentStore->getZip()?><br/>'+
        '<?php echo $currentStore->getTelf()?></p>';

      var infowindow = new google.maps.InfoWindow({
          content: contentString
      });
      
      google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map,marker);
      });
    }
    jQuery(document).ready(function() {
      initialize();
    });
    <?php else: ?>
    function initialize(stores) {
      
      image = new google.maps.MarkerImage('<?php echo asset_absolute_path("/assets/core/img/marker_store.png");?>',
      new google.maps.Size(32, 43),
      new google.maps.Point(0,0),
      new google.maps.Point(0, 43));
      shadow = new google.maps.MarkerImage('<?php echo asset_absolute_path("/assets/core/img/marker_store_shadow.png");?>',
      new google.maps.Size(40, 50),
      new google.maps.Point(0,0),
      new google.maps.Point(0, 50));

      myOptions = {
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };

      map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

      setStores(stores);
    }
    jQuery(document).ready(function() {
      $.getJSON('<?php echo proxy_url_for('@get-markers?country='.$country.'&city='.$city); ?>', 
          function(stores) {
            initialize(stores);
          }
      );
    });
    <?php endif;?>

    var setStores = function setStores(stores) {
      var bounds = new google.maps.LatLngBounds();
      $(stores).each(function() {
        var latlng = new google.maps.LatLng(this.latitude,this.longitude);
        bounds.extend(latlng);
        var marker = new google.maps.Marker({
          position: latlng,
          map: map,
          shadow: shadow,
          icon: image
        });
        var contentString = '<p><b>'+ this.city + ' </b><br/>'+
        this.public_type + ' - ' + this.name+'<br/>'+
        this.address+'<br/>'+
        this.zip+'<br/>'+
        this.telf+
        this.info +
        '</p>';
        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });
      
        google.maps.event.addListener(marker, 'click', function() {
          infowindow.open(map,marker);
        });
      });
      map.fitBounds(bounds);
    }
  // ]]>
  </script>
<?php end_slot();?>