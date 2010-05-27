<?php
	if (isset($record)) {
?>
	<div class="rightSuppNav">
	<h2><?php print $record->title; ?></h2>
	<ul class="navWidget">
<?php
	$i = 1;
		foreach ($record->urls as $title => $url) {
			if($i == 1) {
?>
		<li><a href="<?php print $url; ?>"><?php print $title; ?></a></li>
<?php
			}
			else {
?>
		<li><a href="<?php print $url; ?>"><?php print $title; ?></a></li>
<?php
			}
			$i++;
		}
?>
	</ul>
	</div>
<?php
	}
?>