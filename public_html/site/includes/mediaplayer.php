<div id="multimedia_player_<?php print $this->id; ?>_<?php print $nonce; ?>"><p>Unable to load the multimedia item</p></div>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
<script type="text/javascript">
	<!--
	"undefined"==typeof swfobject&&document.write(unescape("%3Cscript type='text/javascript' src='<?php print getStaticContentRootURL() . '/site/javascript/swfobject.js'; ?>'%3E%3C/script%3E"));
	//-->
</script>
<script type="text/javascript">
flashvars = {
<?php
	if ($this->isVideo()) {
		$width = $this->width;
		$height = $this->height;
		if ($size !== null) {
			include_once("JaduImages.php");
			list($width, $height) = scaleImg($this->width, $this->height, $size);
		}
		$height += 20;
?>
	file: "http://<?php print DOMAIN . '/multimedia/' . $this->id . '/' . $this->id . '.flv'; ?>",
	image: "http://<?php print DOMAIN . $this->getThumbnail(max($width, $height)); ?>",
<?php
	}
	else {
		$width = $size;
		$height = 20;
?>
	file: "http://<?php print DOMAIN . '/multimedia/' . $this->id . '/' . $this->id . '.mp3'; ?>",
<?php
	}
?>
	autostart: <?php print $autostart ? 'true' : 'false'; ?> 
};
swfobject.embedSWF('/site/javascript/mediaplayer/player.swf', 'multimedia_player_<?php print $this->id; ?>_<?php print $nonce; ?>', <?php print (int) $width; ?>, <?php print (int) $height; ?>, '9.0.0', '', flashvars, false, false);
</script>