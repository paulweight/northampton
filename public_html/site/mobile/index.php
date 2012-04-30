<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");	
	
	if($_REQUEST['clearstyles']) {
		unset($_SESSION["switchstyle".$site->id]);
		header('Location: ../index.php');
	}
	else if (!empty($_REQUEST['switchstyle'])) {
  		$_SESSION["switchstyle".$site->id] = $_REQUEST["switchstyle"];
		$STYLESHEET = $_SESSION['switchstyle'.$site->id];
		header('Location: ../index.php');
	}
	
	$breadcrumb = 'mobile';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html<?php if (TEXT_DIRECTION == 'rtl') print ' dir="rtl"'; ?> xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Mobile devices</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
		
	<meta name="Keywords" content="handheld, pda, mobile, device, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - swicth to mobile version" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Mobile device version" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - swicth to mobile version" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<img src="<?php print getStaticContentRootURL(); ?>/site/mobile/mobile.jpg" alt="mobile phone" class="contentimage" />
	<p class="first">More and more people are browsing the web on mobile phones and other wireless devices.</p>
	<p>Use the links below to switch between the standard and handheld versions of <?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s website.</p>
	
	<ul class="list">
		<li><a href="<?php print getSiteRootURL(); ?>/site/mobile/index.php?switchstyle=generic/handheld.css">Enter the handheld version</a></li>
		<li><a href="<?php print getSiteRootURL(); ?>/site/mobile/index.php?clearstyles=standard">Back to the graphic version</a></li>
	</ul>
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>