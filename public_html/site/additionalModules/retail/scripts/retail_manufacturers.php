<?php
	session_start();
	include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");
	include_once("marketing/JaduUsers.php");

	include_once("retail/JaduRetailProducts.php");
	include_once("retail/JaduRetailManufacturers.php");
	
	if (isset($userID)) {
		$user = getUser($userID);
	}
	
	if (isset($_GET['manufacturer_id'])) {
		$manufacturer = getManufacturer($_GET['manufacturer_id']);
	}
	
	$allManufacturers = getAllManufacturers();
	
	$breadcrumb = 'retailManufacturers';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Products - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="shop, commerce, basket, products, buy, gifts, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> shopping" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - <?php print $category->title;?>" />
	<meta name="DC.description" lang="en" content="The <?php print METADATA_GENERIC_COUNCIL_NAME;?> - <?php print $category->title;?>" />
</head>
<body class="retail">
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
		if ($manufacturer->id > 0) {

			$allProductsForManufacturer = getAllProductsForManufacturer($manufacturer->id);
?>
				<h2><?php print $manufacturer->title;?></h2>
	

<?php
			if ($manufacturer->image != "") { 
?>
				<?php if (!empty($manufacturer->url)) { ?><a href="<?php print $manufacturer->url;?>" ><?php } ?>
				<img alt="<?php print $manufacturer->title;?> logo" src="http://<?php print $DOMAIN;?>/images/<?php print $manufacturer->image;?>" class="float_right" />
				<?php if (!empty($manufacturer->url)) { ?></a><?php } ?>
<?php 
			}

			if (!empty($manufacturer->description)) {
?>
				<h3>Details</h3>
				
				<p><?php print nl2br($manufacturer->description);?></p>
<?php
			}
?>

			<div class="content_box">
				<h3>Stocked Products</h3>
				<ul class="list">
<?php
				$clones = array();
				foreach ($allProductsForManufacturer as $manufacturerProduct) {
					
					if($manufacturerProduct->siblingID != '-1' && !in_array($manufacturerProduct->siblingID, $clones)) {
						print '<li><a href="http://'.$DOMAIN.'/site/scripts/retail_product_browse.php?product_id='.$manufacturerProduct->id.'" >'.$manufacturerProduct->title.'</a></li>';
						$clones[] = $manufacturerProduct->siblingID;
					}
					else if ($manufacturerProduct->siblingID == '-1') {
						print '<li><a href="http://'.$DOMAIN.'/site/scripts/retail_product_browse.php?product_id='.$manufacturerProduct->id.'" >'.$manufacturerProduct->title.'</a></li>';
					}
				}
?>
				</ul>
			</div>
<?php
		}
		else {
?>
				<h2>Stocked Manufacturers</h2>
				
				<p class="first">We stock products from:</p>
				
				<div class="content_box">
                     <ul class="list">
<?php
                    foreach ($allManufacturers as $manufacturer) {
                        print '<li><a href="http://'.$DOMAIN.'/site/scripts/retail_manufacturers.php?manufacturer_id='.$manufacturer->id.'" title="'.$manufacturer->title.'">'.$manufacturer->title.'</a></li>';
                    }
?>
                    </ul>
				</div>
<?php
		}
?>

<!-- ###################################### -->
<?php include("../includes/closing_retail.php"); ?>