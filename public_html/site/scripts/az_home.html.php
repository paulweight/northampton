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

	<h2>Our most popular services</h2>
<?php
			if (is_array($tags) && sizeof($tags) > 0) {
?>
	<p>View as a <a href="<?php print getSiteRootURL() . buildAToZURL(); ?>?view=list">List</a> or a <a href="<?php print getSiteRootURL() . buildAToZURL(); ?>?view=cloud">Cloud</a>.</p>
<?php
			}
		
		if($view == 'list') {
?>
	<!-- Top Services List -->
	<ul class="list icons services">
<?php
		if (sizeof($topServices) > 0) {
			$count = 0;
			foreach ($topServices as $id => $requests) {
?>
		<li>
			<a href="<?php print getSiteRootURL() . buildAZServiceURL($id, true, $services[$id]->title); ?>"><?php print encodeHtml($services[$id]->title); ?></a>
		</li>
<?php
				if ($count++ > $topServicesToShow) {
					break;
				}
			}
		}
?>
	</ul>
<?php
	}
	else if ($view == 'cloud') {
?>
	<!-- Services tag cloud -->
		<?php
			if (is_array($tags)) {
				foreach ($tags as $id => $value) {
					$size = $min_size + (($value - $min_qty) * $step);
		?>
	<a href="<?php print getSiteRootURL() . buildAZServiceURL($id, true, $services[$id]->title); ?>" style="font-size:<?php print (int) $size; ?>%" ><?php print encodeHtml($services[$id]->title); ?></a>  
		<?php
				}
			}
		?>
<?php
	}
?>

	<?php include("../includes/services_live_search.php") ?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>