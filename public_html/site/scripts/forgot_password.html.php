<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="password, reminder, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Forgotten password reminder" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Password reminder" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Forgotten password reminder" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (isset($_POST['getPassword']) && $error) {
?>
	<h2 class="warning">! Please check your email address is entered correctly</h2>
<?php
	}
	if (isset($_POST['getPassword']) && !$error) {
?>
	<h2>Thank you</h2>
	<p>An email has been sent to <em><?php print encodeHtml($_POST['email']); ?></em> containing a new password.</p>
<?php 
	} 
	else { 
?>
	<p>If you have forgotten your password, please enter your email address below. A new password will then be emailed to you straight away.</p>

	<form name="getpass" action="<?php print getSecureSiteRootURL() . buildNonReadableForgotPasswordURL(); ?>" method="post" enctype="multipart/form-data">
		<fieldset>
			<legend>Please enter your email address</legend>
			<ol>
			<li>
				<label for="forgot_email">
<?php 
	if (isset($_POST['getPassword']) && $error) {
		print "<strong>! Your email address</strong>";
	}
	else {
		print 'Your email address';
	}
?>
				</label>
				<input id="forgot_email" type="text" name="email" class="field" autocomplete="off" />
			</li>
			<li>
				<input type="submit" name="getPassword" value="Remind me" />
			</li>
			</ol>
		</fieldset>
	</form>
<?php 
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
