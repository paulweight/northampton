<?php
	if (isset($record)) {
?>
		<div class="advert">
			<img alt="<?php print encodeHtml($record->title); ?>" src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($record->imageFilename); ?>" />
			<a class="button red" href="<?php print encodeHtml($record->url); ?>"><span><?php print encodeHtml($record->urlText); ?></span></a>
		</div>
<?php
	}
?>