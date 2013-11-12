<div class="store-detail">
	<div class="store-title">
		<?php if($isClosestStore):?>
    <h1>closest store:
    </h1>
    <?php else:?>
    <h1>store:</h1>
    <?php endif;?>
	</div>

	<div class="store-address">
		<h2 class="icon-location"><?php echo $currentStore->getCity()?>:</h2>
		<address>
			<span>
				<?php echo $currentStore->getPublicType()?> - <?php echo $currentStore->getName()?>
				<br/>
				<?php echo $currentStore->getAddress()?>
				<br/>
				<?php echo $currentStore->getZip()?> 
				<br/>
				<?php echo $currentStore->getTelf()?>
			</span>
		</address>
		
		<a onclick="trackWoodland('storelocator/wp_to_print');" class="arrow-pink-bg-black" href="javascript:print();">Print map</a>
	</div>
	<div class="store-hour">
	<?php if ($currentStore->getStoreExtraData()->getOpeningTimes(ESC_RAW) != NULL): ?>
    <h2 class="icon-open-time">OPENING TIMES</h2>
    <address>
      <span>
        <?php echo $currentStore->getStoreExtraData()->getOpeningTimes(ESC_RAW)->toString($currentStore->getStoreExtraData()->getTwotimeaday()); ?>
      </span>
    </address>
	<?php elseif ($currentStore->getStoreExtraData()->getInfo(ESC_RAW) != ''): ?>
		<h2 class="icon-open-time">OPENING TIMES</h2>
		<address>
			<span>
				<?php echo nl2br($currentStore->getStoreExtraData()->getInfo(ESC_RAW))?>
			</span>
		</address>
  <?php endif; ?>
	</div>
	
	<div class="store-finder">
		<a href="<?php echo proxy_url_for('@store_finder')?>">
			<img src="http://storage.diesel.com/stage/assets/core/img/btn-open-store-finder.gif" alt="Open Store Finder" />
		</a>
	</div>
</div>

<div class="map" id="map_canvas"></div>
