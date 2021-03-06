<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="services, a-z, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Full A to Z listing alphabetically details of all services in your area" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Glossary" />
	<meta name="DC.identifier" content="http://<?php print DOMAIN . encodeHtml($_SERVER['PHP_SELF']); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<ul class="alphabeticNav">
<?php
$arrList = array_merge(array('0-9'), range('A','Z'));
foreach ($arrList as $value) {
	if($value != '0-9'){
?>
	<li class="genericButton grey"><?php if (isset($validLetters[$value])) { ?><a href="<?php print getSiteRootURL() . buildAToZIndexURL($value);?>"><?php print $value; ?></a><?php } else { ?><span><?php print $value; ?></span><?php } ?></li>
<?php
	}
}
?>
</ul>
<div class="clear"></div>
	<h2>Information that begins with <?php print encodeHtml($startsWith); ?></h2>
			<!-- Returned list -->
<?php 
		if (sizeof($servicesList) > 0) {
?>
	
	<ul  class="list icons services">
	
	<?php 
				foreach ($servicesList as $service) {
	?>
		<li><a href="<?php print 'http://' . DOMAIN . buildAZServiceURL($service->id); ?>" ><?php print encodeHtml($service->title); ?></a></li>
	<?php
				}
	?>
	</ul>

<?php
		}
		else {
?>
	<h2>Sorry, there are no services under <?php print encodeHtml($startsWith); ?></h2>
<?php
		}
?>

	<?php include("../includes/services_live_search.php") ?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>