<?php
include_once('multimedia/JaduMultimediaItems.php');
if (intval('%ITEMID%') > 0) {
	$item = getMultimediaItem(intval('%ITEMID%'));
	if ($item) {
		$homepageDesigner = (bool) preg_match('#^/jadu#i', $_SERVER['REQUEST_URI']);
		$maxSize = 320;
?>
<div class="widget_banner">
	<h2><?php print encodeHtml($item->title); ?></h2>
<?php
		if ($homepageDesigner) {
			if ($item->isAudio()) {
?>
				<img src="<?php print PROTOCOL . DOMAIN; ?>/jadu/images/audio_placeholder.png" alt="<?php print encodeHtml($item->title); ?>" />
<?php
			}
			else if ($item->isVideo()) {
?>
				<img src="<?php print $item->getThumbnail($maxSize); ?>" alt="<?php print encodeHtml($item->title); ?>" />
<?php
			}
		}
		else {
			print $item->renderMediaPlayer();
		}
?>
</div>
<?php
	}
}
?>