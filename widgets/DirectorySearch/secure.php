<?php
	include_once('JaduConstants.php');
	include_once('directoryBuilder/JaduDirectories.php');
	
	$directories = getAllDirectories(-1, 1);
?>
<table class="form_table" id="lb_widget_content">
<tbody>
	<tr>
		<td class="label_cell">Heading</td>

		<td class="data_cell"><input id="title" value="" size="12" class="field" type="text"></td>
	</tr>
	<tr>
		<td class="label_cell">Description</td>
		<td class="data_cell"><textarea id="description" rows="3" cols="43"></textarea></td>
	</tr>
	<tr>
		<td class="label_cell">Directory</td>
		<td class="data_cell">
			<select id="directoryid">
				<option value="-1">Choose a directory.</option>
<?php
	foreach ($directories as $directory) {
?>
				<option value="<?php print $directory->id; ?>"><?php print $directory->name; ?></option>
<?php
	}
?>
			</select>
		</td>
	</tr>	
</tbody>
</table>