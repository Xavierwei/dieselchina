<div class="store-list-wrap">
<div class="headbar">
  <h1>店铺列表</h1>
</div>
<div class="full-text">
  <ul class="store-list">
    <?php foreach($stores as $store):?>
    <li><a href="/store-locator/store/<?php print $store['id'];?>"><?php print $store['city'];?><?php print $store['name'];?></a></li>
    <?php endforeach;?>
  </ul>
</div>
</div>