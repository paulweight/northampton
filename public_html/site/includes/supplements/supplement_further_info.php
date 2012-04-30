<!-- further info -->
<div class="supplement">
	<h3><?php print encodeHtml($record->title); ?></h3>
	<p><?php print nl2br($record->description); ?></p>
	<p><a href="<?php print encodeHtml($record->url); ?>"><?php print encodeHtml($record->urlText); ?></a></p>
</div>