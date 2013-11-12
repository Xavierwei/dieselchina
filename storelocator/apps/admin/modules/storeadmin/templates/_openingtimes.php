<form method="post" id="formOpeningTimes" action="<?php echo url_for('storeadmin/openingTimes'); ?>">
    <fieldset>
        <!-- opening-time-no -->
        <div class="group">
            <input type="radio" id="opening-time-no" name="opening-time" value="false" <?php if (!$store->hasTimes()): ?>checked="checked" <?php endif; ?> >
            <label for="opening-time-no">I prefer to not specify operating hours.</label>
        </div>
        <!-- opening-time-no -->
        
        <!-- opening-time-yes -->
        <div class="group">
            <input type="radio" id="opening-time-yes" name="opening-time" value="true" <?php if ($store->hasTimes()): ?>checked="checked" <?php endif; ?>>
            <label for="opening-time-yes">My operating hours are:</label>
        </div>
        <!-- opening-time-yes -->
    </fieldset>
    
    <fieldset class="weekdays">
   
    	<?php include_partial('openingday', array("timesOpenClose" => $timesOpenClose, "day" => "Mon", 
    	                        'times' => $timestable->getTimes('monday'))); ?>
  
      <?php include_partial('openingday', array("timesOpenClose" => $timesOpenClose, "day" => "Tue", 
    	                        'times' => $timestable->getTimes('tuesday'))); ?>
  
      <?php include_partial('openingday', array("timesOpenClose" => $timesOpenClose, "day" => "Wed", 
    	                        'times' => $timestable->getTimes('wednesday'))); ?>
                              
      <?php include_partial('openingday', array("timesOpenClose" => $timesOpenClose, "day" => "Thu", 
    	                        'times' => $timestable->getTimes('thursday'))); ?>
  
      <?php include_partial('openingday', array("timesOpenClose" => $timesOpenClose, "day" => "Fri", 
    	                        'times' => $timestable->getTimes('friday'))); ?>
                              
      <?php include_partial('openingday', array("timesOpenClose" => $timesOpenClose, "day" => "Sat", 
    	                        'times' => $timestable->getTimes('saturday'))); ?>
                              
      <?php include_partial('openingday', array("timesOpenClose" => $timesOpenClose, "day" => "Sun", 
    	                        'times' => $timestable->getTimes('sunday'))); ?>
                              
        
        
        <!-- check morning and afternoon -->
       <div style="padding-bottom : 15px">
        	
           <input type="checkbox" id="twotimeforday" name="twotimeforday" <?php echo $store->hasTwoTimesADay() ? "checked='checked'" : "" ?> />&nbsp;&nbsp;
	        <label for="twotimeforday">I' d like to enter two sets of hours for a single day</label>
            
        </div>
        <!-- note -->
        
        <!-- note -->
        <?php /* <div>
        	
            <label class="textArea" style="float:left">Note:</label>
            
            <textarea class="note" name="times_info"><?php echo $extraDataTable -> getTimes_notes();?></textarea>
            
        </div> */ ?>
        <!-- note -->
    
    </fieldset>
    <input type="hidden" name="slug" value="<?php echo $store->getSlug() ?>"/>
    <button type="button" id="saveOpening" name="save" title="save">save</button>
        
    <div id="resultMsgOpening"><p></p></div>
    
</form>