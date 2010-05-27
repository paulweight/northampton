<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");  
	include_once("websections/JaduTerms.php");
	
	$terms = new Terms();
	$breadcrumb = 'terms';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Terms and disclaimer | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="terms, disclaimer, legal, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="Terms and disclaimers for <?php print METADATA_GENERIC_COUNCIL_NAME;?> Online" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Terms and disclaimers" />
	<meta name="DC.description" lang="en" content="Terms and disclaimers for <?php print METADATA_GENERIC_COUNCIL_NAME;?> Online" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
<?php
	if ($terms->summary != "") { 
?>
	<p class="first"><?php print $terms->summary;?></p>
<?php 
	} 

	if ($terms->imageURL != "") { 
?>
	<img src="http://<?php print $DOMAIN;?>/images/<?php print $terms->imageURL?>" class="main_image" alt="<?php print getImageProperty($terms->imageURL, 'altText'); ?> " />
<?php 
	} 
?>	
	<div class="byEditor">	
		<?php print $terms->content;?>
	</div>
		
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
