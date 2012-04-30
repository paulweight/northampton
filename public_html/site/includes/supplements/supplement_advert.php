<?php
	if (isset($record)) {
?>
		<div class="supplement">
			<img alt="<?php print encodeHtml($record->title); ?>" src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($record->imageFilename); ?>" />
			<p><a href="<?php print encodeHtml($record->url); ?>"><?php print encodeHtml($record->urlText); ?></a></p>
		</div>
<?php
	}
?>