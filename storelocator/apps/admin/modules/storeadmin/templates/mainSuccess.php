<div id="wrapper">
   <?php include_partial('header'); ?>
    
    <div id="main">
        <section id="tabs" class="edit">
            <ul class="sprite-tabs">
                <li><a id="wel" class="welcome" href="#" onclick="window.location.href='<?php echo url_for('@homepage', true); ?>'; return false;"><span>WELCOME</span></a></li>
                <li><a class="name-address" href="#name-address"><span>NAME &amp; ADDRESS</span></a></li>
                <li><a class="opening-times" href="#opening-times"><span>OPENING TIMES</span></a></li>
                <li><a class="store-news" href="#store-news"><span>STORE NEWS</span></a></li>
                <li><a id="addst" class="addstore" href="#" onclick="window.location.href='<?php echo url_for('@newstore', true); ?>'; return false;"><span>ADD STORE</span></a></li>
                
            </ul>
            
            
            <div id="name-address" class="block">
            	
            	<?php include_partial('nameaddress', array("storeTable" => $storeTable, "countries" => $countries, 'types' => $storeTypes, 'plines' => $plines, 'statuses' => $statuses, 'cities' => $cities)); //, "extraDataTable" => $extraDataTable, "country" => $country, "shopType" => $shopType)) ?>
                
            </div>
            <div id="opening-times" class="block">
               
               	<?php include_partial('openingtimes', array("store" => $storeTable, "timesOpenClose" => $timesOpenClose, 'timestable' => $timestable)); //, "extraDataTable" => $extraDataTable, 'timestable' => $timestable)) ?>
               
            </div>
            
            <div id="store-news" class="block">
            
            	<?php include_partial('storenews', array("store" => $storeTable, "news" => $storeTable->getSlStoreNews())) ?>
                
            </div>

        </section>
        
    </div>
    
</div>

