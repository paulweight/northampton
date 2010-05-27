<?php
	if (isset($record)) {
?>
	<div class="cate_info">
		<h2><?php print $record->title; ?></h2>
		<ul class="list">
<?php
		foreach ($record->urls as $title => $url) {
?>
			<li><a href="<?php print $url; ?>"><?php print $title; ?></a></li>
<?php
		}
?>
		</ul>
<?php
	}
?>
	</div>