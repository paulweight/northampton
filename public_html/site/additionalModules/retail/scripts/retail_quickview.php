<?php
	session_start();
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	
	include_once("websections/JaduContact.php");	

//	include_once("marketing/JaduUsers.php");
//	include_once("marketing/JaduAdverts.php");
	include_once("retail/JaduRetailOrders.php");
	include_once("retail/JaduRetailOrderItems.php");
	include_once("retail/JaduRetailProducts.php");
	include_once("retail/JaduRetailProductReviews.php");
	include_once("retail/JaduRetailProductCodes.php");
	include_once("retail/JaduRetailManufacturers.php");
	include_once("retail/JaduRetailProductsToImages.php");
//	include_once("retail/JaduRetailProductImages.php");
	include_once("retail/JaduRetailTax.php");	

	$address = new Address;

	//	Configuration Constants
	$CHART_SIZE = 10;
	$PER_PAGE = 10;
	$MAX_PER_PAGE = 50;
	$STEP_PER_PAGE = 10;
	$TEXT_ONLY = "text";
	$TEXT_AND_IMAGES = "images";

//	if (isset($userID)) {
//		$user = getUser($userID);
//	}

	$product_array = array();

	// =================================
	// Decide How we are using this page
	// =================================
	if (isset($chart)) {
		switch($chart) {
			case 1 :
				$page_title = "Top Sellers";
//				$temp_items = getTopXOrderedItemsEverThatCurrentlyOnline($CHART_SIZE);
//				foreach ($temp_items as $row)
//					$product_array[] = getProduct($row[0]);

				$product_array = getTopXSoldItemsEverOnline($CHART_SIZE);
				break;
			case 2 :
				$page_title = "New Products";
				$product_array = getTopXMostRecentlyAddedOnlineProducts($CHART_SIZE);
				break;
			case 3 :
				$page_title = "Discounts";
				$tmp_array = getAllDiscountedProducts();
				if (sizeof($tmp_array) < 10) {
					$rand_keys = array_rand($tmp_array, sizeof($tmp_array));
				}
				else {
					$rand_keys = array_rand($tmp_array, 10);
				}
				foreach ($rand_keys as $key) {
					$product_array[] = $tmp_array[$key];
				}
				break;
			default :
				header("Location: $ERROR_REDIRECT_PAGE");
				exit;
				break;
		}
	} 
	else if (isset($search_products)) {
		switch($search_products) {
			case 1 :
				//	Search By Title / Description
				$page_title = "Search by description";
				break;
			case 2 :
				//	Search By Part Numbers
				$page_title = "Search by part number";
				break;
			case 3 :
				//	Search By Manufacturer
				$page_title = "Search by manufacturer";
				break;
			default :
				header("Location: $ERROR_REDIRECT_PAGE");
				exit;
				break;
		}
	} 
	else {
		//header("Location: $ERROR_REDIRECT_PAGE");
		//exit;
	}
	// =================================

	//	set the defaults
	if (!isset($per_page))
		$per_page = $PER_PAGE;
	if (!isset($mode))
		$mode = $TEXT_AND_IMAGES;
	
	//	work out number of pages
 	$total = sizeof($product_array);
 	$total_pages = (int) ($total / $per_page);
 	if ($total % $per_page != 0) { 
 		$total_pages++;
 	}
 		
	if (!isset($page) || $page < 1)
		$page = 1;
	elseif ($page > $total_pages)
		$page = $total_pages;
	
	if ($total_pages > 1)
		$product_array = array_slice ($product_array, (($page-1) * $per_page), $per_page);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $page_title;?> - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="<?php print $page_title;?>, shop, commerce, basket, products, buy, gifts, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - <?php print $page_title;?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> shopping" />
	<meta name="DC.description" lang="en" content="The <?php print METADATA_GENERIC_COUNCIL_NAME;?> - <?php print $page_title;?>" />
</head>
<body class="retail">
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

			<h2><span><?php print $page_title;?></span></h2>
						
			<p class="first"><?php print METADATA_GENERIC_COUNCIL_NAME;?>'s <?php print $page_title;?>.</p>
						
			<?php
			foreach ($product_array as $index => $product) {
//				$partNum1 = getCodeXForProduct(1, $product->id);
				$manufacturer = getManufacturer($product->manufacturer_id);
				$tax = getTax($product->tax_id);
				
				$tax_rate = $tax->rate;
										
				$price = "";
				if ($product->discount_price == "0.00") {
					$product_cost = $product->price;
					$price = number_format($product->price + ($product->price * $tax_rate), 2, '.', '');
				} 
				else {
					$price_before_discount = number_format($product->price + ($product->price * $tax_rate), 2, '.', '');
					
					$price = number_format($product->discount_price + ($product->discount_price * $tax_rate), 2, '.', '');
				}	
?>
			
<?php
			
				$image_file = "no_image.gif";
				$image_title = "No Image Available";

				$images = getAllProductToImagesForProduct($product->id);
				$num_images = sizeof($images);
				if ($num_images >= 1) {
					$num = rand(0, $num_images-1);
					$image = getImageByFilename($images[$num]->image_filename);
					$image_file = $image->filename;
					$image_title = $image->title;
				}
?>
	
			<div class="search_result">
				
			
				<h3>N&ordm;<?php print (($page-1)*$per_page)+$index+1;?>.  <a title="<?php print $product->title;?>" href="retail_product_browse.php?product_id=<?php print $product->id;?>"><?php print $product->title;?></a></h3>
<?php
			if ($num_images > 0) {
?>
				<div class="contentimage">
					<a href="retail_product_browse.php?product_id=<?php print $product->id;?>"><img src="http://<?php print $DOMAIN; ?>/images/<?php print $image_file;?>" alt="<?php print $image_title;?>"  /></a>
				</div>
<?php
			}
?>
				<p><strong>Manufacturer:</strong> <?php print $manufacturer->title;?></p>
				
					
			 
<?php
					if ($product->discount_price == "0.00") {
						print "<p><strong>Price:</strong> &pound;$price"."</p>";
					}
					else {
//						$price = $product->discount_price;
						print "<p><strong>Discount price:</strong> &pound;$price"."</p>";
						
						print "<p><strong>Usual price:</strong> <s>&pound;$price_before_discount"."</s></p>";

						$saving = number_format($price_before_discount - $price,2,'.','');
						print "<p><strong>A saving of:</strong> &pound;$saving"."</p>";
					}
?>

					
<?php
	if ($product->telephoneOnly) {
?>
					<p>To order this product please call <?php print $address->telephone;?></p>
	
<?php		
	}
	else {
?>					
					<p>			
					<form action="./retail_basket.php" method="post" name="basketForm" class="jform">
						<input type="submit" name="add_to_basket" value="Add to Basket" class="button">
						<input type="hidden" name="action_basket" value="ADD">
						<input type="hidden" name="q" value="1">
						<input type="hidden" name="id" value="<?php print $product->id;?>">
						<input type="hidden" name="p" value="<?php print $price;?>">
						<input type="hidden" name="item" value="<?php print $product->title;?>">
					</form>
					</p>
<?php
	}
?>
				<div class="clear"></div>
			</div>
			
<?php 
									}
?>
				
<!-- ###################################### -->
<?php include("../includes/closing_retail.php"); ?>