<?php
	if (isset($record)) {
?>
<div class="supplementAdvert">
	<a href="<?php print $record->url; ?>">
		<img alt="<?php print getImageProperty($record->imageFilename, 'altText'); ?>" src="http://<?php print $DOMAIN . '/images/' . $record->imageFilename; ?>" />
		<span><?php print $record->description; ?></span>
		<?php print $record->urlText; ?>
	</a>
</div>
<?php
	}
?>