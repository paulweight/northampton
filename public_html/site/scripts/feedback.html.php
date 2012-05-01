<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="feedback, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="Send your feedback directly to <?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> feedback" />
	<meta name="DC.description" lang="en" content="Send your feedback directly to <?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	<script type="text/javascript">
	<!--
		function preSubmit()
		{
			document.getElementById('auth').value = '<?php print md5(DOMAIN . date('Y')); ?>';
		}
	-->
	</script>	
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
	
	<p>Use this form to send your comments or suggestions directly to the team.</p>
	
<?php
	if (isset($error_array['auth'])) {
?>
	<h2 class="warning">You must have javascript enabled to use submit feedback</h2>
<?php
	} 
	else if (sizeof($error_array) > 0) {
?>
	<h2 class="warning">Please check details highlighted with ! are entered correctly</h2>
<?php
	}
?>
	<form class="basic_form xform" action="<?php print getSiteRootURL() . buildNonReadableFeedbackURL(); ?>" method="post" enctype="multipart/form-data" onsubmit="preSubmit(); return true;">
		<fieldset>
			<input type="hidden" name="auth" id="auth" value="fail" />	
			<ol>
			<li>
				<label for="Salutation"> Salutation</label>
				<select name="salutation" id="Salutation">
					<option <?php if ($salutation == "Mr") print "selected='selected'"; ?> value="Mr">Mr</option>
					<option <?php if ($salutation == "Miss") print "selected='selected'"; ?> value="Miss">Miss</option>
					<option <?php if ($salutation == "Mrs") print "selected='selected'"; ?> value="Mrs">Mrs</option>
					<option <?php if ($salutation == "Ms") print "selected='selected'"; ?> value="Ms">Ms</option>
					<option <?php if ($salutation == "Dr") print "selected='selected'"; ?> value="Dr">Dr</option>
					<option <?php if ($salutation == "Other") print "selected='selected'"; ?> value="Other">Other</option>
				</select>
			</li>
			<li>
				<label for="Forename"><?php if (isset($error_array['forename'])) { ?><strong>! <?php } ?>Forename<?php if (isset($error_array['forename'])) { ?></strong><?php } ?> <em>(required)</em></label>
				<input id="Forename" type="text" name="forename" value="<?php print encodeHtml($forename); ?>" />
			</li>
			<li>
				<label for="Surname"><?php if (isset($error_array['surname'])) { ?><strong>! <?php } ?>Surname<?php if (isset($error_array['surname'])) { ?></strong><?php } ?> <em>(required)</em></label>
				<input id="Surname" type="text" name="surname" value="<?php print encodeHtml($surname); ?>" />
			</li>
			<li>
				<label for="Address"><?php if (isset($error_array['address'])) { ?><strong>! <?php } ?>Location<?php if (isset($error_array['address'])) { ?></strong><?php } ?> <em>(required)</em></label>
				<input id="Address" type="text" name="address" value="<?php print encodeHtml($address); ?>" />
			</li>
			<li>
				<label for="Email"><?php if (isset($error_array['email'])) { ?><strong>! <?php } ?>Email Address<?php if (isset($error_array['email'])) { ?></strong><?php } ?> <em>(required)</em></label>
				<input id="Email" type="text" name="email" value="<?php print encodeHtml($email); ?>" />
			</li>
			<li>
				<label for="Telephone"><?php if (isset($error_array['telephone'])) { ?><strong>! <?php } ?>Telephone<?php if (isset($error_array['telephone'])) { ?></strong><?php } ?> <em>(required)</em></label>
				<input id="Telephone" type="text" name="telephone" value="<?php print encodeHtml($telephone); ?>" />
			</li>
			<li>
				<label for="comments"><?php if (isset($error_array['comments'])) { ?><strong>! <?php } ?>Your comments<?php if (isset($error_array['comments'])) { ?></strong><?php } ?> <em>(required)</em></label>
				<textarea id="comments" name="comments" cols="2" rows="5"><?php print encodeHtml($comments); ?></textarea>
			</li>
			<li class="center">
				<input type="submit" value="Send your feedback" name="submit" class="genericButton grey" />
			</li>
			</ol>
		</fieldset>
	</form>

	<h2>Data Protection</h2>
	<p>The details you provide on this page will not be used to send unsolicited e-mail, and will not be sold to a 3rd party. <a href="<?php print getSiteRootURL() . buildTermsURL(); ?>">Privacy statement</a>.</p>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
