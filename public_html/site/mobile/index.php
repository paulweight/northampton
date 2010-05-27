<?php
	session_start();
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");	
	$breadcrumb = 'mobileAbout';
	
	if($_REQUEST['clearstyles']) {
		unset($_SESSION["switchstyle"]);
		header('Location: ../index.php');
	}
	else if (!empty($_REQUEST['switchstyle'])) {
  		$_SESSION["switchstyle"] = $_REQUEST["switchstyle"];
		$STYLESHEET = $_SESSION['switchstyle'];
		header('Location: ../index.php');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Mobile devices</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
		
	<meta name="Keywords" content="handheld, pda, mobile, device, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Mobile device version" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<img src="http://<?php print $DOMAIN;?>/site/mobile/mobile.jpg" alt="mobile phone" class="contentimage" />
	<p class="first">More and more people are browsing the web on mobile phones and other wireless devices.</p>
	<p>Use the mobile edition of <?php print METADATA_GENERIC_COUNCIL_NAME;?>'s website on your phone or PDA for information about Council services.</p>
	
	<ul class="list">
		<li><a href="http://<?php print $DOMAIN;?>/site/mobile/index.php?switchstyle=generic/handheld.css">Enter the handheld version</a></li>
		<li><a href="http://<?php print $DOMAIN;?>/site/mobile/index.php?clearstyles=standard">Back to the graphic version</a></li>
	</ul>
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>