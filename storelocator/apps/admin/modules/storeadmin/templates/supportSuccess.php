<div id="wrapper">
	<?php include_partial('header'); ?>
	
  <div id="main">
	  <article>
	    <div id="support">
	      <h1>SUPPORT</h1>

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
        <button type="button" id="sendSupport" name="save" title="send"  class="send">send</button>
   	    <div id="resultMsgSupport"><p></p></div>
      </div>
		</article>
	</div>
</div>
