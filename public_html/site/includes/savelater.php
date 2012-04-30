<?php
	if ($app->submitted != 1) {
?>
	<div id="saveLater">
		<h3>Would you like to complete this form later?</h3>
		<p>You may save this application form at any time, and come back to complete it at a more convenient time. The incomplete application can be found on the 'Your account' page.</p>
		<p class="centre">
			<input class="graybutton" type="submit" name="saveExit" value="Save this for later" />
		</p>
	</div>
<?php
	}
?>