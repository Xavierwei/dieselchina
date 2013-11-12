<?php use_helper('JavascriptBase')?>
<?php use_javascript('/assets/core/js/tamingselect.js')?>
<?php include_partial('head_js', array('currentStore' => $currentStore))?>
<?php slot('head_stylesheets'); ?>
<style type="text/css" media="screen">
	
	
	.newsPar{
	
		display:none;
		text-align : justify;
		padding-top:10px;
	
	}
	
	#cs.news .content .news-preview .newsPar p{
	
		line-height:120%;
	
	}
	
</style>
<?php end_slot();?>
<div id="main" class="store-facebook">
  <?php include_partial('facebook_top', array('isClosestStore' => $isClosestStore, 'currentStore' => $currentStore, 'city' => $city, 'country' => $country, 'storesJSON' => $storesJSON))?>

  <div id="cs" class="news">
    <div class="head">
      <h2><?php if( $currentStore != null ):?><?php echo $currentStore->getCity()?> store news<?php endif;?></h2>
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
          <?php $i++;?>
        <?php endforeach;?>
        <a onclick="trackWoodland('storelocator/wp_to_viewall');" class="link view-all" href="<?php echo url_for_satellite_be('/redirect/'.$storeId.'/news')?>">View all</a>
        <a onclick="trackWoodland('storelocator/wp_to_followshop');" class="link follow-shop" href="<?php echo url_for_satellite_be('/redirect/'.$storeId.'/follow')?>">Follow this shop</a>
      </div>
    <?php else:?>        
      <img src="<?php echo asset_absolute_path("/assets/core/img/storelocator_nonews_fb.jpg");?>" alt="We're with stupid" />
    <?php endif;?>
  </div>
  
</div>
