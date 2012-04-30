<?php
	require_once('rupa/JaduRupaCollection.php');

	$allCollections = getAllRupaCollections();
?>
<div id="header">
<?php
	include_once('navigation_links.php');
?>
	<h1><a href="<?php print RUPA_HOME_URL; ?>"><span><?php print encodeHtml(RUPA_INSTALLATION_NAME); ?></span></a></h1>

	<form name="searchAreaForm" id="searchAreaForm" method="get" action="<?php print RUPA_HOME_URL; ?>results.php">
		<fieldset>
				<legend>Search</legend>
				<label for="googleSearchBox" class="hide_label">Search: </label>
				<input class="keyword_field" type="text" name="q" id="googleSearchBox" value="<?php if (isset($htmlSafeQuery)) { print $htmlSafeQuery; } ?>" size="35" />
				<input type="submit" name="googleSearchSubmit" value="Search" class="small_button" />
			
				<label for="searchGroup" class="hide_label">Search group: </label>
				<select class="select_group" name="sites[0]" id="searchGroup">
					<option value="all" <?php if (($_GET['sites'][0] == 'all') || ($_GET['sites'][0] == null)) { print 'selected="selected"'; } ?> >Search everything</option>
<?php
					foreach ($allCollections as $collection) {
?>
						<option value="<?php print encodeHtml($collection->collectionName); ?>" <?php if (($_GET['sites'][0] == $collection->collectionName)) { print 'selected="selected"'; } ?>><?php print encodeHtml($collection->friendlyName); ?></option>
<?php
					}
?>
				</select>
				<span id="PrefsLink" class="arrw_down"><a title="Advanced Search" href="<?php print RUPA_HOME_URL; ?>advanced.php">Advanced search</a></span>		
		</fieldset>
	</form>
</div>
<!-- end of header div -->
