<br/>
<div class="longSelect">
  <select name="store" id="store">
    <option>Select your store</option>
  	<?php foreach ($stores as $s): ?>
      <?php echo '<option value="'. $s->getId() .' - '.$s->getArea() . ' - ' . $s->getCountry() . ' - ' . $s->getCity() . ' - ' . $s->getName() . ' - ' . $s->getAddress() .'">'. $s->getArea() . ' - ' . $s->getCountry() . ' - ' . $s->getCity() . ' - ' . $s->getName() . ' - ' . $s->getAddress() .'</option>' ; ?>
    <?php endforeach; ?>
  </select>
</div>

<script type="text/javascript">
<!--
  $(function() {
    $('#store').change (function () {
      if ($('#store').val() != "Select your store") {
        $('#sendStoreSupport').show();
      }//if
      else {
        $('#sendStoreSupport').hide();
      }//else
    });
  });
//-->
</script>