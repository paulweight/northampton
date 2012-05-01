<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="account, regstration, user, profile, register, change, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> user account change password" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Change password" />
	<meta name="DC.identifier" content="http://<?php print DOMAIN . encodeHtml($_SERVER['PHP_SELF']); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
		
<?php
	if ($showMessage) {
?>
	<h2>Your password has been changed successfully</h2>
<?php 
	} 
	else {
	if (isset($_GET['forced']) || isset($_POST['forced'])) {
?>
	<p>Your password has been reset and you must change it before you can proceed, please complete the details below. Your password must be <em>6-30</em> characters in length.</p>
<?php

	}
	else {
?>	
<p>If you would like to change your password, please complete the details below. Your password must be 6-30 characters in length.</p>
<?php 
	}
	if (isset($error_array['oldPassword'])) { 
?>
	<h2 class="warning">! Your old password has been entered incorrectly</h2>
<?php 
	} 
	else if (isset($error_array['passwordsDifferent'])) { 
?>
	<h2 class="warning">! Your new passwords do not match</h2>
<?php 
	}
	else if (isset($error_array['passwordLength'])) { 
?>
	<h2 class="warning">! Your new password is not of the desired length</h2>
	<p>Please enter a password that is 6-30 characters in length.</p>
<?php 
	}
 ?>
	<form class="basic_form xform" name="changepass" action="<?php print getSecureSiteRootURL() . buildNonReadableChangePasswordURL(); ?>" method="post" enctype="multipart/form-data">
<?php		
		if (isset($_GET['forced']) || isset($_POST['forced'])) {
?>		
			<input type="hidden" id="forced" name="forced" value="true"/>
<?php
		}
?>
		<fieldset>
			<legend>Change password</legend>
			<ol>
			<li>
				<label for="oldPassword">
					<?php if (isset($error_array['oldPassword'])) print "<strong>! ";?>
					Old password
					<?php if (isset($error_array['oldPassword'])) print "</strong>";?>
					<em>(required)</em>
				</label>
				<input id="oldPassword" type="password" name="oldPassword" size="15" maxlength="30" value="" />
			</li>
			<li>
				<label for="newpassword">
					<?php if (isset($error_array['passwordsDifferent']) || isset($error_array['passwordLength'])) print "<strong>! ";?>
					New password
					<?php if (isset($error_array['passwordsDifferent']) || isset($error_array['passwordLength'])) print "</strong>";?>
					<em>(required)</em>
				</label>
				<input id="newpassword" type="password" name="password" size="15" maxlength="30" value="" />
			</li>
			<li>
				<label for="passwordsDifferent">
					<?php if (isset($error_array['passwordsDifferent'])) print "<strong>! ";?>
					Confirm password
					<?php if (isset($error_array['passwordsDifferent'])) print "</strong>";?>
					<em>(required)</em>
				</label>
				<input id="passwordsDifferent" type="password" name="passwordConfirm" size="15" maxlength="30" value="" />
			</li>
			<li class="centre">
				<input type="submit" name="getPassword" value="Change my password" class="genericButton grey" />
			</li>
			</ol>
		</fieldset>
	</form>
<?php 
	} 
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
