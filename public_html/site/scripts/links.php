<?php 
	if (isset($_GET['selectLinks'])) {
		header("Location: links.php#link".$_GET['selectLinks']);
		exit();
	}

	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduLinks.php");
	
	$categoriesList = getAllLinkCategories();
	$linksList = getAllLinks();	
	
	$breadcrumb = 'links';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>External links and web resources | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="links, web resources, related, useful, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> provides an extensive resource of web links to related web sites and organisations of interest and help to both local citizens and the general public" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Links and web resources" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> provides an extensive resource of web links to related web sites and organisations of interest and help to both local citizens and the general public" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
	<p class="first">Resources and external links from the web that you may find useful. Select a category from the list below.</p>
	<p class="note">Note: <?php print METADATA_GENERIC_COUNCIL_NAME;?> are not responsible for the content of external internet sites.</p>

<?php
	$leftCategories = array();
	$rightCategories = array();
	for ($i = 0; $i < count($categoriesList); $i++) {
		if ($i % 2 == 0)
			$leftCategories[] = $categoriesList[$i];
		else
			$rightCategories[] = $categoriesList[$i];
	}
?>
	<div class="cate_info">

	<?php
		if (sizeof($leftCategories) > 0) {
	?>
		<ul class="info_left">
		<?php 
			foreach($leftCategories as $categoryItem) {
		?>
			<li><a href="http://<?php print $DOMAIN.$_SERVER['PHP_SELF']."?".htmlspecialchars($_SERVER['QUERY_STRING']);?>#cat<?php print htmlspecialchars($categoryItem->id);?>"><?php print htmlspecialchars($categoryItem->title);?></a></li>
		<?php
			}
		?>
		</ul>
	<?php
		}
	?>

	<?php
		if (sizeof($rightCategories) > 0) {
	?>
		<ul class="info_right">
		<?php 
			foreach($rightCategories as $categoryItem) {
		?>
			<li><a href="http://<?php print $DOMAIN.$_SERVER['PHP_SELF']."?".htmlspecialchars($_SERVER['QUERY_STRING']);?>#cat<?php print htmlspecialchars($categoryItem->id);?>"><?php print htmlspecialchars($categoryItem->title);?></a></li>
		<?php
			}
		?>
		</ul>
	<?php
		}
	?>
	</div>

<?php
	foreach($categoriesList as $categoryItem) {
?>
	<h2 class="extLinks" id="cat<?php print $categoryItem->id;?>"><?php print htmlspecialchars($categoryItem->title);?></h2>
	<p class="extLinkp"><a rel="nofollow" href="http://<?php print $DOMAIN; ?>/site/scripts/links.php#mast">Jump to the top</a></p>
<?php
		$links = getLinksInCategory($categoryItem->id);
		foreach ($links as $link) {
?>
		<div class="extLinks">
			<h3 id="link<?php print htmlspecialchars($link->id);?>"><span><a href="<?php print htmlspecialchars($link->url);?>"><?php print htmlspecialchars($link->title);?></a></span></h3>
<?php
			if (($link->description)!="") {
				print htmlspecialchars(nl2br($link->description));
			}
?>
		</div>
<?php
		}
	}
?>

	<p class="note">Note: <?php print METADATA_GENERIC_COUNCIL_NAME;?> are not responsible for the content of external internet sites.</p>
	
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>