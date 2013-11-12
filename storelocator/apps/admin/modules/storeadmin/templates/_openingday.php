<div class="group">
        	
          
	<span calss="morning">
	
        <label><?php echo $day; ?>:</label>
        
        <select class="<?php echo $times->getDay() ?> from" name="times[<?php echo $times->getDay() ?>][from]">
        	<?php foreach ($timesOpenClose as $key => $value): ?>
        		<?php echo '<option value="'. $key .'" ' . (( $times->getTime(StoreDayTimes::FROM) == $key) ? 'selected="selected"' : '') .'>'. $value .'</option>' ; ?>
          <?php endforeach; ?>
        </select>
        
        <span>-</span>
        
        <select class="<?php echo $times->getDay() ?> to" name="times[<?php echo $times->getDay() ?>][to]">
          <?php foreach ($timesOpenClose as $key => $value): ?>
        	  <?php echo '<option value="'. $key .'" ' . (($times->getTime(StoreDayTimes::TO) == $key) ? 'selected="selected"' : '') .'>'. $value .'</option>' ; ?>
			    <?php endforeach; ?>
		
        </select>

        <input type="checkbox" id="<?php echo $times->getDay() ?>-closed" class="store-closed" name="times[<?php echo $times->getDay() ?>][closed]" <?php if ( $times->isClosed()) :?>  checked="checked" <?php endif;?>>
        <label for="<?php echo $times->getDay() ?>-closed">Closed</label>
    
    </span>
    
    <span class="afternoon">
	
        <select class="<?php echo $times->getDay() ?> frompm" name="times[<?php echo $times->getDay() ?>][frompm]">
          <?php foreach ($timesOpenClose as $key => $value): ?>
        		<?php echo '<option value="'. $key .'" ' . (($times->getTime(StoreDayTimes::FROMPM)== $key) ? 'selected="selected"' : '') .'>'. $value .'</option>' ; ?>
			    <?php endforeach; ?>
        </select>
        
        <span>-</span>
        
        <select class="<?php echo $times->getDay() ?> topm" name="times[<?php echo $times->getDay() ?>][topm]">
          <?php foreach ($timesOpenClose as $key => $value): ?>
        		<?php echo '<option value="'. $key .'" ' . (($times->getTime(StoreDayTimes::TOPM) == $key) ? 'selected="selected"' : '') .'>'. $value .'</option>' ; ?>
			    <?php endforeach; ?>
        </select>
        
        <input type="checkbox" id="<?php echo $times->getDay() ?>-closed" class="store-closed" name="times[<?php echo $times->getDay() ?>][closed-ev]" <?php if ( $times->isClosed(true)) :?>  checked="checked" <?php endif;?>>
        <label for="<?php echo $times->getDay() ?>-closed">Closed</label>
        
	</span>
	
	<?php if ($times->getDay() == "monday") {?>
		
		&nbsp;&nbsp;<img src="/assets/store-admin/img/copy.jpg" id="copytimes" />
	
	<?php } ?>
</div>
