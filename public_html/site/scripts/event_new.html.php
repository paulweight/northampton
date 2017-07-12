<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="event,<?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="Submit your event directly to <?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> event submission" />
	<meta name="DC.description" lang="en" content="Submit your event directly to <?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript">
	<!--
		function preSubmit()
		{
			document.getElementById('auth').value = '<?php print md5(DOMAIN . date('Y')); ?>';
		}
	
	function toggleUntilInput(value) {
	
		if (value == "1day") {
			document.getElementById('untilInput').style.display = 'none';
		} 
		else {
			document.getElementById('untilInput').style.display = 'block';
			document.getElementById('endDate').focus();
		}
	}
	-->
	
	</script>
	
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
		<p>Submit your event using the form below.</p>
<?php
	if (isset($error_array['auth'])) {
?>
	<h2 class="warning">You must have javascript enabled to submit an event</h2>
<?php
	} 
	else if (sizeof($error_array) > 0) {
?>
	<h2 class="warning">Please check details highlighted with ! are entered correctly</h2>
<?php
	}
?>
	<form class="basic_form xform" enctype="multipart/form-data" action="<?php print getSiteRootURL() . buildNonReadableNewEventURL(); ?>" method="post" onsubmit="preSubmit(); return true;" >
		<fieldset>
			<input type="hidden" name="auth" id="auth" value="fail" />	
			<ol>
			<li>
				<label for="title"><?php if (isset($error_array['title'])) { ?><strong>! <?php } ?>Event Title<?php if (isset($error_array['title'])) { ?></strong><?php } ?> <em>(required)</em></label>
				<input id="title" type="text" name="title" value="<?php print encodeHtml($title); ?>" />
			</li>
			<li>
				<label for="startDate"><?php if (isset($error_array['startDate'])) { ?><strong>! <?php } ?>Start Date <?php if (isset($error_array['startDate'])) { ?></strong><?php } ?> <em>(required)</em><em><?php print encodeHtml(FORMAT_DATE_INPUT_EXAMPLE); ?></em></label>
				<input id="startDate" type="text" name="startDate" value="<?php (!empty($startDate)) ? print encodeHtml($startDate) : print ''; ?>" onblur="document.eventsForm.endDate.value = this.value" />
			</li>
			<li>
				<label for="interval"><?php if (isset($error_array['interval'])) { ?><strong>! <?php } ?>Duration of event<?php if (isset($error_array['interval'])) { ?></strong><?php } ?></label>
				<select name="interval" onchange="toggleUntilInput(this.value)">
					<option value="1day">Just this day</option>
					<option value="day">Daily</option>
					<option value="weekly">Weekly</option>
					<option value="fortnight">Fortnightly</option>
					<option value="monthByDay">On this day every month</option>
					<option value="monthByDate">On this date every month</option>
				</select>
			</li>
			<li id="untilInput" style="display: none;">
				<label for="endDate">End Date: <em>(required)</em><em><?php print encodeHtml(FORMAT_DATE_INPUT_EXAMPLE); ?></em></label>  
				<input id="endDate" type="text" name="endDate" value="<?php (!empty($endDate)) ? print encodeHtml($endDate) : print ''; ?>" maxlength="10" />
			</li>
			<li>
				<label for="start"><?php if (isset($error_array['startTime'])) { ?><strong>! <?php } ?>Event start time<?php if (isset($error_array['startTime'])) { ?></strong><?php } ?> <?php print encodeHtml(FORMAT_TIME_INPUT_EXAMPLE); ?></label>
				<input id="start" type="text" name="startTime" value="<?php (!empty($startTime))? print encodeHtml($startTime): print ''; ?>" size="5" />				
			</li>
			<li>
				<label for="finish"><?php if (isset($error_array['endTime'])) { ?><strong>! <?php } ?>Event end time<?php if (isset($error_array['endTime'])) { ?></strong><?php } ?> <?php print encodeHtml(FORMAT_TIME_INPUT_EXAMPLE); ?></label>
				<input id="finish" type="text" name="endTime" value="<?php (!empty($endTime))? print encodeHtml($endTime): print ''; ?>" size="5" />
			</li>
			<li>
				<label for="location"><?php if (isset($error_array['location'])) { ?><strong>! <?php } ?>Location<?php if (isset($error_array['location'])) { ?></strong><?php } ?> <em>(required)</em></label>
				<input id="location" type="text" name="location" value="<?php print encodeHtml($location); ?>" />
				<span class="clear"></span>
			</li>
			<li>
				<label for="cost"><?php if (isset($error_array['cost'])) { ?><strong>! <?php } ?>Cost<?php if (isset($error_array['cost'])) { ?></strong><?php } ?> <em>&pound; (required)</em></label>
				<input id="cost" type="text" name="cost" value="<?php (isset($cost))? print encodeHtml($cost): print '0.00' ?>" />
			</li>
			<li>
				<label for="summary"><?php if (isset($error_array['summary'])) { ?><strong>! <?php } ?>Summary<?php if (isset($error_array['summary'])) { ?></strong><?php } ?> <em>(required)</em></label>
				<textarea name="summary" rows="5" cols="30"><?php print encodeHtml($summary); ?></textarea>
			</li>
			<li>
				<label for="description"><?php if (isset($error_array['description'])) { ?><strong>! <?php } ?>Description<?php if (isset($error_array['description'])) { ?></strong><?php } ?></label>
				<textarea name="description" rows="5" cols="30"><?php print encodeHtml($description); ?></textarea>
			</li>
			<li class="image">
				<label for="image"><?php if (isset($error_array['image'])) { ?><strong>! <?php } ?>Supporting Image<?php if (isset($error_array['image'])) { ?></strong><?php } ?> JPEG, GIF, PNG only</label>
				<input type="file" name="image" id="imageUpload" />
			</li>	
			<li class="centre">
				<input type="submit" value="Submit your event" name="submit" class="genericButton grey" />
			</li>
			</ol>
		</fieldset>
	</form>

	<h2>Data Protection</h2>
	<p>The details you provide on this page will not be used to send unsolicited e-mail, and will not be sold to a 3rd party. <a href="<?php print getSiteRootURL() . buildTermsURL(); ?>">Privacy statement</a>.</p>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>