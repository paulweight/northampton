<table class="form_table" id="lb_widget_content">
	<tbody>
		<tr>
			<td class="label_cell">Multimedia</td>
			<td class="data_cell">
				<input type="hidden" name="itemID" id="itemID" value="" />
				<input type="button" class="button" value="Multimedia Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&mediaMode=multimedia&mediaType=2,3');" />
			</td>
		</tr>
		<tr>
			<td class="label_cell">Preview</td>
			<td class="data_cell"><div id="multimediaItemPreview"></div></td>
			<script type="text/javascript">
				new Ajax.Request(SECURE_JADU_PATH + '/lightboxes/image_manager/image_manager_get_multimedia_properties.php', {
					asynchronous: true,
					method: 'post',
					parameters: 'itemID=' + $F('itemID'),
					onSuccess : function(transport) {
						var data = transport.responseText.evalJSON(true);
						if (data.id != -1) {
							if (data.mediaType == 2 || data.mediaType == 3) {
								var flashvars = {};

								if (data.mediaType == 3) {
									previewWidth = 160;
									previewHeight = 20;

									flashvars = {
										file: '/multimedia/'+data.id+'/'+data.id+'.mp3',
										autostart: 'false'
									}
								}
								else {
									previewWidth = data.previewWidth;
									previewHeight = data.previewHeight + 20;

									flashvars = {
										file: '/multimedia/'+data.id+'/'+data.id+'.flv',
										image: data.previewImage,
										autostart: 'false'
									}
								}

								swfobject.embedSWF('/site/javascript/mediaplayer/player.swf', 'multimediaItemPreview', previewWidth, previewHeight, '9.0.0', '', flashvars);
							}
						}
					}
				});
			</script>
		</tr>
	</tbody>
</table>