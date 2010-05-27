<?php
	session_start();
	include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");

	include_once("retail/JaduRetailProductCampaigns.php");
	include_once("retail/JaduRetailProducts.php");
	include_once("retail/JaduRetailProductsToImages.php");
	include_once("retail/JaduRetailTax.php");

	include_once('retail/JaduRetailCategoryTree.php');

	include("../includes/lib.php");

	$allCategories = getRetailCategories();
	
	$splitArray = splitArray($allCategories);

	$homepageProductHeaders = getAllProductCampaigns();
	
	$breadcrumb = 'retailIndex';
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

	<div class="cate_info">

	<h2>Recommended products</h2>
	<h3>What's new in the <?php print METADATA_GENERIC_COUNCIL_NAME; ?> shop.</h3>
	
	
<?php
	foreach($homepageProductHeaders as $header) {
		$products = getAllProductsToCampaignsForCampaign($header->id);
		foreach ($products as $product) {
			$product = getProduct($product->product_id);
			if ($product->status == 'online') {
				$images = array();
				$images = getAllProductToImagesForProduct($product->id);
?>
		<div class="featuredProduct">
<?php 
	if(sizeof($images) > 0) {
?>
			<a href="http://<?php print $DOMAIN; ?>/site/scripts/retail_product_browse.php?product_id=<?php print $product->id;?>"><img src="http://<?php print $DOMAIN; ?>/images/<?php print $images[0]->image_filename; ?>" alt="<?php print $image_title; ?>" /></a>
<?php
	}
?>
			<p><?php print $header->title; ?>: <br /><a href="http://<?php print $DOMAIN; ?>/site/scripts/retail_product_browse.php?product_id=<?php print $product->id;?>"><?php print htmlentities($product->title); ?></a> 
<?php
				if($product->discount_price != '0.00') {
?>
			<br/><span class="price">&pound;<?php print $product->getFormattedSellingPrice();?></span>
			<br/><s>&pound;<?php print $product->getFormattedPriceBeforeDiscount();?></s>
<?php
				}
				else {
?>
			<br/><span class="price">&pound;<?php print $product->getFormattedSellingPrice();?></span>
<?php
				}
?>
<?php		
		//$tax = getTax($product->tax_id);
		///$tax_rate = $tax->rate;
?>
		</p>
		<div class="clear"></div>
	</div>
<?php
			}
		}

	}
?>
		<div class="clear"></div>
	</div>
	<div class="cate_info">

		<h2>More to explore:</h2>
		<ul class="list info_left">
<?php
		foreach($splitArray['left'] as $category) {
?>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_products.php?category_id=<?=$category->id;?>"><?php print $category->title; ?></a></li>
<?php
		}
?>
		</ul>
    
<?php
		if(sizeof($splitArray['right']) > 0) {
?>
		<ul class="list info_right">
<?php
			foreach($splitArray['right'] as $category) {
?>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_products.php?category_id=<?=$category->id;?>"><?php print $category->title; ?></a></li> 
<?php
			}
		}	
?>
		</ul>
		<div class="clear"></div>
	</div>
<!-- ###################################### -->
<?php include("../includes/closing_retail.php"); ?>