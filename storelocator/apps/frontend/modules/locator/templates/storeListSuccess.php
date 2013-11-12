<div class="headbar">
  <h1>Store list</h1>
</div>
<div class="full-text">
  <ul class="store-list">
    <?php foreach($stores as $store):?>
    <li><a href="<?php echo proxy_url_for('@store_detail?id='.$store->getId().'&slug='.$store->getSlug())?>"><?php echo $store->getName()?></a></li>
    <?php endforeach;?>
  </ul>
</div>