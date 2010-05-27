<?php
	session_start();
	include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");
	include_once("marketing/JaduUsers.php");
	
	include_once("marketing/JaduAdverts.php");
	include_once("retail/JaduRetailProductsToCategories.php");
	include_once("retail/JaduRetailCategoryTree.php");
	include_once("retail/JaduRetailProducts.php");
	include_once("retail/JaduRetailProductReviews.php");
	include_once("retail/JaduRetailProductCodes.php");
	include_once("retail/JaduRetailManufacturers.php");
	include_once("retail/JaduRetailProductsToImages.php");
	include_once("retail/JaduRetailTax.php");
	include_once("retail/JaduRetailProductOptionEntries.php");
	include_once("retail/JaduRetailProductOptions.php");

	include("../includes/lib.php");

	$category_id = $_GET['category_id'];

	if (isset($userID)) {
		$user = getUser($userID);
	}
	
	if (isset($category_id) && is_numeric($category_id)) {
		$category = getRetailCategory($category_id);
		$categories = getRetailCategories($category_id);
		
		if(sizeof($categories) > 0) {
			$splitArray = splitArray($categories);
		}

		$products = getAllProductToCategoryFromCategoryID($category_id);
		
		$onlineProducts = array();
		$clones = array();
		foreach ($products as $productID) {
			$productInfo = getProduct($productID->product_id);

			if(!in_array($productInfo->siblingID, $clones) && $productInfo->siblingID != -1) {
				$onlineProducts[$productInfo->id] = $productInfo;
				$clones[] = $productInfo->siblingID;
			}
			
			if($productInfo->siblingID == -1) {
				$onlineProducts[$productInfo->id] = $productInfo;
			}
		}
	}
	else {
		header("Location: ./retail_index.php");
		exit();
	}
		
	$breadcrumb = "retailProducts";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $category->title;?> - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

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
	if(trim($category->description) != '') {
?>
	<p class="first">
<?php 
		if(trim($category->imageURL) != '') {
			print '<img src="http://'.$DOMAIN.'/images/'.$category->imageURL.'" alt="" class="float_right" />';
		}
?>
		<?php print nl2br($category->description); ?>
	</p>
<?php
	}
?>
		<div class="clear"></div>
<?php
	if (sizeof($splitArray['left']) > 0 || sizeof($splitArray['right']) > 0) {
?>
		<div class="cate_info">
			<h2>Product Categories</h2>

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
<?php
	}
	if (count($onlineProducts) > 0) {
?>
	
	<h2>Available Products</h2>	
<?php 
	foreach ($onlineProducts as $productInfo) {
        $cloned = getProductSiblings($productInfo->siblingID);
		$tax = getTax($productInfo->tax_id);
	    $manufacturer = getManufacturer($productInfo->manufacturer_id);
	    $images = array();
	    $images = getAllProductToImagesForProduct($productInfo->id);
?>
	<div class="featuredProduct featuredProductCategory">

<?php 
	if(sizeof($images) > 0) {
?>
			<img  src="http://<?php print $DOMAIN;?>/images/<?php print $images[0]->image_filename; ?>" alt="x" />
<?php
	}
?>
	
<!-- Product Description -->

		<p><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_product_browse.php?product_id=<?php print $productInfo->id;?>&category_id=<?php print $category->id;?>"><?php print $productInfo->title ;?></a> - 
			<span class="price">&pound;<?php print $productInfo->getFormattedSellingPrice(); ?></span></p>
			
			<!-- <?php print substr($productInfo->description, '0', '250'); ?>... -->


<!-- Product Options and Clones -->
		
		<form action="http://<?php print $DOMAIN;?>/site/scripts/retail_product_browse.php?category_id=<?php print $cat->id;?>" method="get">
			<input type="hidden" name="category_id" value="<?php print $_GET['category_id']; ?>" />
			<input type="hidden" name="product_id" value="<?php print $productInfo->id; ?>" />
			<p class="clear text_align_right"><input type="submit" value="View details &raquo;" class="button go" /></p>
		</form>
	</div>
<?php
	}
	}
	else {
?>
	<p class="first">There are no products currently available in this category.</p>
<?php
	}
?>
<!-- Hot Products -->			
<?php
	$count = 0;
	if (sizeof($category_array) > 0) {
		$hot_products_used = array();
		//foreach ($category_array as $index => $cat) {
		//	if (categoryHasProductsOnline ($cat->id)) {
				$hot_product = getHottestProductInCategory ($cat->id);
				
				if (!in_array($hot_product->id, $hot_products_used)) {
				
				$hot_products_used[] = $hot_product->id;
				
				//	set this to missing image filename
				$image_file = "no_image.gif";
				$image_title = "No Image Available";
	
				$images = getAllProductToImagesForProduct($hot_product->id);
				$num_images = sizeof($images);
				if ($num_images >= 1) {
					$num = rand(0, $num_images-1);
					$image = getImageByFilename($images[$num]->image_filename);
					$image_file = $image->filename;
					$image_title = $image->title;
				}
?>
				<div class="boxed">
<?php
		        if ($num_images > 0){
?>
					<div class="contentimage">	
						<a href="http://<?php print $DOMAIN ?>/site/scripts/retail_product_browse.php?category_id=<?php print $cat->id; ?>&product_id=<?php print $hot_product->id;?>"><img src="http://<?php print $DOMAIN; ?>/images/<?php print $image_file; ?>" alt="<?php print $image_title; ?>" /></a>
					</div>
<?php
			    }
?>					
    			    <h3><a href="http://<?php print $DOMAIN ?>/site/scripts/retail_product_browse.php?category_id=<?php print $cat->id; ?>&product_id=<?php print $hot_product->id;?>"><?php print $hot_product->title;?></a></h3>
					<p><?php print substr(nl2br($hot_product->description),0,200); ?>...</p>
					<div class="clear"></div>
				</div>
<?php	
				}
			//}
		//}
	}
?>

<!-- ###################################### -->
<?php include("../includes/closing_retail.php"); ?>