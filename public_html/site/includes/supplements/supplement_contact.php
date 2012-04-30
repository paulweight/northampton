<?php
	include_once('JaduImages.php');
?>
<!-- Contact -->
<div class="vcard supplement">
<h3><?php print encodeHtml($record->title); ?></h3>

<?php
	if (!empty($record->imageFilename)) {
?>
	<img class="float_left" alt="<?php print encodeHtml(getImageProperty($record->imageFilename, 'altText')); ?>" src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($record->imageFilename); ?>" />
<?php
	}
	if(!empty($record->description)) {
?>
	<p><?php print processEditorContent($record->description); ?></p>
<?php	
	}
	if(!empty($record->address)) {
?>
	<p><strong>Address:</strong> <span class="adr"><?php print nl2br(encodeHtml($record->address));?></span></p>
<?php 
	}
	if(!empty($record->telephone)) {
?>
	<p><strong>Tel:</strong> <span class="tel"><?php print encodeHtml($record->telephone); ?></span></p>
<?php
	}
	if(!empty($record->fax)) {
?>
	<p class="tel"><strong class="type">Fax:</strong> <?php print encodeHtml($record->fax); ?></p>
<?php
	}
	if(!empty($record->email)) {
?>
	<p><strong>Email:</strong> <a href="mailto:<?php print encodeHtml($record->email); ?>" class="email"><?php print encodeHtml($record->email); ?></a></p>
<?php
	}
	if(!empty($record->urls)) {
?>
	<ul class="list">
<?php
		foreach ($record->urls as $key => $value) {
?>
		<li><a href="<?php print encodeHtml($value); ?>"><?php print encodeHtml($key); ?></a></li>
<?php
		}
?>
	</ul>
<?php
	}
?>
</div>