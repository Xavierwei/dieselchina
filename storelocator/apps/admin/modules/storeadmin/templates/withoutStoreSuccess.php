<div id="wrapper">

  <?php include_partial('header', array('storeTable' => $storeTable)); ?>
	
	<div id="main">
	    <article>
	        <h1>HELP - FAQ</h1>
	        
	        <h2>Doubts/perplexities/worries?</h2>
	        <p>Non hai uno store</p>
	        
	       

            <h2>If you do not find your store write us a message below</h2>
            <form method="post" id="formSupport" action="<?php echo url_for('storeadmin/sendSupport'); ?>">
              <h2>Object</h2>
              <div>
                  <input type="text" data-error="Insert object" value="" name="objectSupport" class="required" id="objectSupport">
              </div>
              <h2>Request</h2>
              <!-- note -->
              <div>
                  <textarea data-error="Insert request" class="infoSupport required" name="requestSupport"></textarea>
              </div>
              <!-- note -->
            </form>
	    </article>
	</div>
</div>

<script type="text/javascript">
<!--
  $(function() {
    $('#country').change (function () {
    	$.ajax({
    		  url: '<?php echo url_for('storeadmin/getAjaxStores') ?>',
          data: 'country=' +  $('#country').val() ,
    		  success: function(data) {
    		    $('#ajax_stores').html(data);
    		    $('select').selectBox();
    		  }
    		}); 
    });
  });
//-->
</script>