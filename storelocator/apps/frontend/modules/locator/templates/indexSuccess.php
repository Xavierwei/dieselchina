<?php use_helper('JavascriptBase')?>
<?php use_javascript('/assets/core/js/tamingselect.js')?>
<?php include_partial('head_js', array('currentStore' => $currentStore))?>
<?php slot('head_stylesheets'); ?>
<style type="text/css" media="screen">
	
	div.store-new address{
		float:none;
	}
	
	div.store-new .store-hour{
		height:auto;
	}
	
	div.store-new address {
    
 		width: 265px;
	
	}
	
	div.store-new address .times {
  
 		font-size: 15px;

	}
	
	.newsPar{
	
		display:none;
		width : 800px;
		text-align : justify;
		padding-top:10px;
	
	}
	
	.store-new div#cs.news div.content div.news-preview .newsPar p{
	
		line-height:120%;
	
	}
	
</style>
<?php end_slot();?>

<div id="main" class="store-new">
<?php if( $currentStore != null):?>
  <?php include_partial('top', array('isClosestStore' => $isClosestStore, 'currentStore' => $currentStore, 'city' => $city, 'country' => $country, 'storesJSON' => $storesJSON))?>
<?php endif;?>
  <div id="cs" class="news">
    <div class="head">
      <h2><?php if( $currentStore != null ):?><?php echo $currentStore->getCity()?> store news<?php endif;?></h2>
      <?php if( $currentStore != null ):?>
      <div id="store-edit">
      </div>
      <?php javascript_tag();?>
      $.ajax({
        url:  '<?php echo proxy_url_for('@ajax_edit_store_link?id='.$currentStore->getId().'&slug='.$currentStore->getSlug())?>',
        success: function(data){
          $('#store-edit').html(data);
        }
      });
      <?php end_javascript_tag();?>
      <?php endif; ?>
    </div>
    <?php if(sizeof($storeNews) > 0 ):?>
      <div class="content">
        <?php $i=0; ?>
        <?php $rawSN = $sf_data->getRaw('storeNews');?>
        <?php foreach ( $storeNews as $news ):?>
          <div class="news-preview">
            <p><?php echo $news->getTitle()?></p>
            <span><?php echo getHumanCreatedAt($news->getCreatedAt());?> Ago</span>
            <a onclick="trackWoodland('storelocator/wp_to_read');" class="" href="javascript:$('#par-<?php echo $rawSN[$i]->getId();?>').toggle(500);void(0);">
            	<img src="http://storage.diesel.com/stage/assets/core/img/readMore.jpg" alt="Read More" />
            </a>
            <div class="newsPar" id="par-<?php echo $rawSN[$i]->getId();?>"><?php echo $rawSN[$i]->getParagraph(); ?></div>
          </div>
          <?php $i++; ?>
        <?php endforeach;?>
        
        <?php  /*?><a onclick="trackWoodland('storelocator/wp_to_viewall');" class="link view-all" href="<?php echo url_for_satellite_be('/redirect/'.$storeId.'/news')?>">View all</a>
        <a onclick="trackWoodland('storelocator/wp_to_followshop');" class="link follow-shop" href="<?php echo url_for_satellite_be('/redirect/'.$storeId.'/follow')?>">Follow this shop</a>
        */ ?>
      </div>
    <?php else:?>
    	<?php 
				$appContext = proxy_get_appcontext();
				if( $appContext != "" ){
					$appContext = "-" . $appContext;
				}
    	?>
      <img src="<?php echo asset_absolute_path("/assets/core/img/storelocator" . $appContext . "_nonews.jpg");?>" alt="We're with stupid" />
    <?php endif;?>
  </div>
  
</div>
