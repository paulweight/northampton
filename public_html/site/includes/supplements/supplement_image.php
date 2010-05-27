<?php
	if (isset($record)) {
?>
<div class="supplementImage">
	<img alt="<?php print getImageProperty($record->imageFilename, 'altText'); ?>" src="http://<?php print $DOMAIN . '/images/' . $record->imageFilename; ?>" />

<?php
	if ($record->description != '') {
?>

	<p><?php print $record->description; ?></p>
	
<?php
	}
	if ($record->url != '' && $record->urlText != '') {
?>
	<p><a href="<?php print $record->url; ?>"><?php print $record->urlText; ?></a></p>
<?php
	}
?>
</div>
<?php
	}
?>
