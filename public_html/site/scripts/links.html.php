<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="links, web resources, related, useful, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> provides an extensive resource of web links to related web sites and organisations of interest and help to both local citizens and the general public" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Links and web resources" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> provides an extensive resource of web links to related web sites and organisations of interest and help to both local citizens and the general public" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
	<p>We have a growing database of resources and external links that you may find useful. Select a category from the list below.</p>
	<p>Note: <?php print encodeHtml(METADATA_GENERIC_NAME); ?> are not responsible for the content of external internet sites.</p>


<?php
		if(sizeof($categoriesList) > 0) {
?>
		<div class="cate_info">
			<h2>Categories</h2>
		
			<ul class="list">
<?php 
			foreach($categoriesList as $categoryItem) {
?>
				<li><a href="<?php print getSiteRootURL() . buildLinksURL(); ?>#cat<?php print encodeHtml($categoryItem->id);?>"><?php print encodeHtml($categoryItem->title);?></a></li>
<?php
			}
?>
			</ul>
		</div>
<?php
		}
?>

<?php
	foreach($categoriesList as $categoryItem) {
?>
	<h3 id="cat<?php print (int) $categoryItem->id;?>"><?php print encodeHtml($categoryItem->title);?></h3>
	<ul>
<?php
		$links = getLinksInCategory($categoryItem->id);
		foreach ($links as $link) {
?>
		<li>
			<h4 id="link<?php print encodeHtml($link->id);?>"><a href="<?php print encodeHtml($link->url);?>"><?php print encodeHtml($link->title);?></a></h4>
<?php
			if (($link->description)!="") {
				print nl2br(encodeHtml($link->description));
			}
?>
		</li>
<?php
		}
?>
	</ul>
<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>