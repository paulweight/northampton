<?php
	session_start();
	include_once('rupa/JaduRupaCollection.php');

	$collectionGroups = getAllRupaCollectionGroups();
	$orphanedCollections = getRupaCollections('groupID', DEFAULT_COLLECTION_GROUP_ID);
	
?>


<?php
	if (sizeof($collectionGroups) > 0) {
?>
	<table class="root" summary="Table of groups and subgroup collections for Google search">
		<thead>
		<tr>
			<th class="right_cell">Group</th>
			<th>Sites</th>
		</tr>
		</thead>
		<tbody>
<?php
		foreach ($collectionGroups as $group) {
			$childCollections = getRupaCollections('groupID', $group->id);
			if (sizeof($childCollections) > 0) {
?>
			<tr>
				<td class="right_cell"><?php print $group->name; ?>: </td>
				<td>
				<ul>
				<li>
					<label>
						<input
							type="checkbox" 
							name="selectAll"
							value="<?php print $collection->collectionName; ?>" 
							class="" 
							onclick="selectAllChildCollections(this, '<?php print $group->id; ?>')" 
						/>
						Select All
					</label>
				</li>						
<?php
					foreach ($childCollections as $childCollection) {
?>
					<li>
						<label>
							<input 
								type="checkbox" 
								name="collections[]"
								value="<?php print $childCollection->collectionName; ?>" 
								class="collection child_of_<?php print $group->id; ?>" 
							/>
							<?php print $childCollection->friendlyName; ?>
						</label>
					</li>
<?php
					}
?>
				</ul>
				</td>
			</tr>
<?php
			}
		}

		if (is_array($orphanedCollections) && !empty($orphanedCollections)) {
?>
			<tr>
				<td class="right_cell"><?php print DEFAULT_COLLECTION_GROUP_NAME; ?></td>
				<td>
				<ul>
				<li>
					<label>
						<input
							type="checkbox" 
							name="selectAll"
							value="<?php print $collection->collection_name; ?>" 
							class="" 
							onclick="selectAllChildCollections(this, 'default')" 
						/>
						Select All
					</label>
				</li>				
<?php
					foreach ($orphanedCollections as $orphanedCollection) {
?>
					<li>
						<label>
							<input 
								type="checkbox" 
								name="collections[]"
								value="<?php print $orphanedCollection->collectionName; ?>" 
								class="collection child_of_default" 
							/>
							<?php print $orphanedCollection->friendlyName; ?>
						</label>
					</li>
<?php
					}
?>

				</ul>
				</td>
			</tr>
<?php
		}
?>


			</tbody>
		</table>
<?php
	}
	else {
?>
		<p>No collections have been created</p>
<?php
	}
?>

	<br class="clear" />
<?php
	if (basename($_SERVER['SCRIPT_FILENAME']) == 'preferences.php') {
?>
	<p class="search_radios"><a href="#" onclick="saveCollectionPreferences();" class="faux_button">Save Preferences</a><br /><em id="savePreferencesMessage" style="display:none;"></em></p>
<?php
	}
?>
