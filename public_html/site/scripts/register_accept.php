<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");

	if (!isset($_SESSION['userID'])) {
		header("Location: $ERROR_REDIRECT_PAGE");
		exit();
	}
	
	$breadcrumb = 'registerAccept';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
	<title>Registration completed | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="account, regstration, user, profile, register, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Registration completed" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Registration completed" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Registration completed" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
	
	<h2>Your account has now been set up</h2>
	
	<p class="first">You will notice that a new link has appeared called <a href="http://<?php print $DOMAIN;?>/site/scripts/user_home.php">Your Account</a> at the top of the site. From here you can access your account page at all times or sign out.</p>
	
	<p>From the Your Account page you can view your interactions with the Councils website.</p>
	
	<p>From here you can also change your registration details and password at any time.</p>
			
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>