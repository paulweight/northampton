<?php
	require_once('rupa/JaduRupaCollection.php');

	$allCollections = getAllRupaCollections();
?>
<div id="header">
<?php
	include_once('navigation_links.php');
?>
	<h1><a title="<?php print RUPA_INSTALLATION_NAME; ?> homepage" href="<?php print RUPA_HOME_URL; ?>"><span><?php print RUPA_INSTALLATION_NAME; ?></span></a></h1>

	<form name="searchAreaForm" id="searchAreaForm" method="get" action="<?php print RUPA_HOME_URL; ?>results.php">
		<fieldset>
			<p>
				<label for="googleSearchBox" class="hide_label">Search: </label>
				<input class="keyword_field" type="text" name="q" id="googleSearchBox" value="<?php print $htmlSafeQuery; ?>" size="35" />
				<input type="submit" name="googleSearchSubmit" value="Search" class="small_button" />
			</p>

			<p class="myprefs">
				<label for="searchGroup" class="hide_label">Search group: </label>
				<select class="select_group" name="collections[0]" id="searchGroup">
					<option value="all" <?php if (($_GET['collections'][0] == 'all') || ($_GET['collections'][0] == null)) { print 'selected="selected"'; } ?> >Search everything</option>
<?php
					foreach ($allCollections as $collection) {
?>
						<option value="<?php print $collection->collectionName; ?>" <?php if (($_GET['collections'][0] == $collection->collectionName)) { print 'selected="selected"'; } ?>><?php print $collection->friendlyName; ?></option>
<?php
					}
?>
					</option>
				</select>
				<span id="PrefsLink" class="arrw_down"><a title="Advanced Search" href="<?php print RUPA_HOME_URL; ?>advanced.php">Advanced search</a></span></p>		
		</fieldset>
	</form>
</div>
<!-- end of header div -->
