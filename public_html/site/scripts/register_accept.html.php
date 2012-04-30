<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="account, regstration, user, profile, register, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Registration completed" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Registration completed" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Registration completed" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
	
	<h2>Your account has now been set up</h2>
	
	<p>You will notice that a new link has appeared called <a href="<?php print getSecureSiteRootURL() . buildUserHomeURL() ;?>">Your Account</a> at the top of the page. From here you can access your account page at all times or sign out.</p>
	
	<p>From the Your Account page you can view your interactions with this website.</p>
	
	<p>From here you can also change your registration details and password at any time.</p>

			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>