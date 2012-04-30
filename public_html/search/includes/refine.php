<?php
	include_once('rupa/JaduRupaCollection.php');

	$allCollections = getAllRupaCollections();


	if (sizeof($allCollections) > 0) {
?>
	<table class="root" summary="Table of groups and subgroup collections for Google search">
		<thead>
		<tr>
			<th class="right_cell">Group</th>
			<th>Sites</th>
		</tr>
		</thead>
		<tbody>

			<tr>
				<td class="right_cell">Site: </td>
				<td>
				<ul id="collections">
				<li>
					<label>
						<input
							type="checkbox" 
							name="selectAll"
							id="selectAll"
							value="<?php print encodeHtml($collection->collectionName); ?>" 
							class="" 
							onclick="selectAllChildCollections(this)" 
						/>
						Select All
					</label>
				</li>						
<?php
			foreach ($allCollections as $collection) {
			
?>
					<li>
						<label>
							<input 
								type="checkbox" 
								name="sites[]"
								value="<?php print encodeHtml($collection->collectionName); ?>" 
								onclick="unselectSelectAll(this)"
							/>
							<?php print encodeHtml($collection->friendlyName); ?>
						</label>
					</li>
<?php
					}
?>
				</ul>
				</td>
			</tr>
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
