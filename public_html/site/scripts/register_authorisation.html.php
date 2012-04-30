<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="account, regstration, user, profile, register, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Registration" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Registration" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Registration" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
<?php
	if (isset($_GET['new']) || $sentAuthEmail) {
?>
	<h2>Thank you</h2>
	<p>An email has been sent to <?php print encodeHtml($_GET['email']); ?>.</p>
	<p>Please click on the link in the email to complete your registration.</p>
	
<?php
	}
	elseif (isset($_GET['auth'])) {
?>
	<h2>The authorisation failed</h2>
<?php
	}
	elseif (isset($_GET['authFailed'])) {
?>
	<p>You have not confirmed your registration.</p>
		<p>You have two options:</p>
	<ul>
		<li><a href="<?php print buildRegisterAuthURL(); ?>?sendAuthEmail=<?php print encodeHtml($_GET['authFailed']); ?>">Send an authorisation email to <?php print encodeHtml($_GET['authFailed']); ?></a></li>
		<li><a href="<?php print getSiteRootURL() . buildRegisterURL(); ?>">Register again</a></li>
	</ul>
<?php
	}
	elseif (isset($_GET['sendAuthEmail']) && !$sentAuthEmail) {
?>
	<p>The email address <?php print encodeHtml($_GET['sendAuthEmail']); ?> was not found.</p>
<?php
	}
?>	

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>