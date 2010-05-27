<div class="supplementContact">
	<ul>
		<li>
		
	<?php
		if (!empty($record->imageFilename)) {
	?>
			<img alt="<?php print getImageProperty($record->imageFilename, 'altText'); ?>" src="http://<?php print $DOMAIN;?>/images/<?php print $record->imageFilename ?>" />
	<?php
		}
	?>
		
		<span><?php print $record->title; ?></span></li>
	<?php
		if(!empty($record->description)) {
	?>
		<li><?php print $record->description;?></li>
	<?php	
		}
		if(!empty($record->address)) {
	?>
		<li><strong>Address:</strong> <?php print nl2br($record->address);?></li>
	<?php 
		}
		if(!empty($record->telephone)) {
	?>
		<li><strong>Tel:</strong> <?php print $record->telephone;?></li>
	<?php
		}
		if(!empty($record->fax)) {
	?>
		<li><strong>Fax:</strong> <?php print $record->fax;?></li>
	<?php
		}
		if(!empty($record->email)) {
	?>
		<li><strong>Email:</strong> <a href="mailto:<?php print $record->email;?>"><?php print $record->email;?></a></li>
	<?php
		}
		if(!empty($record->urls)) {
	?>
		
	<?php
			foreach ($record->urls as $key => $value) {
	?>
		<li><a href="<?php print $value ?>"><?php print $key ?></a></li>
	<?php
			}
	?>
	
	<?php
		}
	?>
	</ul>
</div>