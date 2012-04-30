	<form action="<?php print getSiteRootURL() . "/site/scripts/events_info.php"; ?>" method="post" enctype="multipart/form-data" class="basic_form">
	<p>
		<label for="seewhatson">See what&#39;s on</label>
		<select name="period" class="field" id="seewhatson">
			<option value="thisWeek" selected="selected">What&#39;s On When</option>
			<option value="thisWeek">This Week</option>
			<option value="nextWeek">Next Week</option>
			<option value="thisMonth">This Month</option>
			<option value="nextMonth">Next Month</option>
			<option value="full">Full List</option>
		</select>
		<input type="submit" class="button" value="Go" />
		<span class="clear"></span>
	</p>
	</form>
	
	<form action="<?php print getSiteRootURL() . "/site/scripts/events_info.php"; ?>" method="post" enctype="multipart/form-data" class="basic_form">
	<p>
		<label for="placestogo">Places to go</label>
		<select name="location" class="field" id="placestogo">
		<option value="#">What&#39;s On Where</option>
<?php
		$locations = getLocations();
		foreach ($locations as $location) {
?>
			<option value="<?php print encodeHtml($location); ?>"><?php print encodeHtml($location); ?></option>
<?php
		}
?>
		</select>
		<input type="submit" class="button" value="Go" />
	</p>
	</form>
	