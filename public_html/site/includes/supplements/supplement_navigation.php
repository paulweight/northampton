<?php
	if (isset($record)) {
?>
<div class="supplement navigation">
	<h3><?php print encodeHtml($record->title); ?></h3>
<?php
	if (is_array($record->urls) && sizeof($record->urls) > 0) {
?>
	<ul>
<?php
		foreach ($record->urls as $title => $url) {
?>
		<li><a href="<?php print encodeHtml($url); ?>"><?php print encodeHtml($title); ?></a></li>
<?php
		}
?>
	</ul>
<?php
	}
?>
</div>
<?php
}
?>