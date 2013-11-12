<div id="wrapper">
   <?php include_partial('header', array('storeTable' => $storeTable)); ?>
    
    <div id="main">
        <section id="tabs" >
            <ul class="sprite-tabs">
                <li><a class="welcome" href="#" onclick="window.location.href='<?php echo url_for('@homepage', true); ?>'"><span>WELCOME</span></a></li>
                <li class="disabled"><a class="name-address" href="javascript:void(0);" disabled><span>NAME &amp; ADDRESS</span></a></li>
                <li class="disabled"><a class="opening-times" href="javascript:void(0);"><span>OPENING TIMES</span></a></li>
                <li class="disabled"><a class="store-news" href="javascript:void(0);"><span>STORE NEWS</span></a></li>
                <li class="ui-state-active"><a class="addstore" href="#"><span>ADD STORE</span></a></li>
            </ul>
            
            
            <div id="name-address" class="block">
            	
            	<?php include_partial('nameaddress', array("storeTable" => $storeTable, "countries" => $countries, 'types' => $storeTypes, 'plines' => $plines, 'statuses' => $statuses, 'cities' => $cities)); //, "extraDataTable" => $extraDataTable, "country" => $country, "shopType" => $shopType)) ?>
                
            </div>
            <div id="opening-times" class="block">
               
               
            </div>
            
            <div id="store-news" class="block">
            

                
            </div>

        </section>
        
    </div>
    
</div>

