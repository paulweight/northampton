<table class="form_table" id="lb_widget_content">
	<tbody>
		<tr>
			<td class="label_cell">Image</td>
			<td class="data_cell">
				<input type="hidden" id="img_src" value="" onchange="$('img_srci').src = 'http://' + DOMAIN + '/images/' + this.value;" />
				<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=img_srci&imageFilenameID=img_src');" />
			</td>
		</tr>
		<tr class="generic_action">
			<td class="label_cell">Preview</td>
			<td class="data_cell"><img id="img_srci" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" width="100%" /></td>
		</tr>
		<tr>
			<td class="label_cell">Click through link</td>
			<td class="data_cell"><input id="link_href" value="" size="12" type="text" class="field" /></td>
		</tr>
		<tr>
			<td class="label_cell">Link Text</td>
			<td class="data_cell"><input id="link_text" value="" size="12" type="text" class="field" /></td>
		</tr>
	</tbody>
</table>