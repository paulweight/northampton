<?php
	if (isset($record)) {
?>
	<div class="supplement image">
		<img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($record->imageFilename); ?>" alt="<?php print encodeHtml(getImageProperty($record->imageFilename, 'altText')); ?>" />
<?php
	if ($record->description != '') {
?>
	<p><?php print processEditorContent($record->description); ?></p>
<?php
	}
	if ($record->url != '' && $record->urlText != '') {
?>
		<p><a href="<?php print encodeHtml($record->url); ?>"><?php print encodeHtml($record->urlText); ?></a></p>
<?php
	}
?>
	</div>
<?php
	}
?>
