<table class="form_table" id="lb_widget_content">
	<tbody>
		<tr>
			<td class="label_cell">Navigation Widget Title</td>
			<td class="data_cell">
				<input type="text" id="nav_widget_title" size="12" maxlength="32" class="field" value="" />
			</td>
		</tr>
		<tr>
			<td class="label_cell"></td>
			<td class="data_cell"><input type="button" value="Add Link" class="button" onclick="addWidgetLink();" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<table class="list_table">
					<tbody id="nav_widget_links">
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
	<tfoot style="display:none;">
		<tr>
			<td class="label_cell">Title</td>
			<td class="data_cell"><input type="text" size="12" class="field" id="nav_link_title" value="" /></td>
		</tr>
		<tr>
			<td class="label_cell">Link</td>
			<td class="data_cell"><input type="text" size="12" class="field" id="nav_link" value="" /></td>
		</tr>
		<tr>
			<td class="label_cell"><input type="button" class="button" id="widgetLinkDelete" value="Delete Link" onclick="deleteWidgetLink()" /></td>
			<td class="data_cell"><input type="button" class="button" value="Save Link" onclick="saveWidgetLink();" /></td>
		</tr>
	</tfoot>
</table>