<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	include_once("planXLive/JaduPlanXProposalsMaps.php");

	$map = getProposalsMap($_GET['mapID']);
	$breadcrumb = 'planxLpMap'
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Planning Policy Maps</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of documents and pages organised within the following categories, Environment, Planning" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> online information | Environment | Planning" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of documents and pages organised within the following categories, Environment, Planning" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

</head>

<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
		
	<p class="first"><?php print $map->description; ?></p>
	<p><a href="http://<?php print $DOMAIN; ?>/planx_downloads/<?php print $map->map_filename; ?>">Download this map</a></p>

<?php
	if (!empty($map->key_filename)) {            
?>
	<p><a href="http://<?php print $DOMAIN; ?>/planx_downloads/<?php print $map->key_filename; ?>">Download the key file</a></p>
<?php
	}
?>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/closing.php"); ?>