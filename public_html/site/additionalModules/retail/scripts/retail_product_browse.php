<?php
    session_start();
    include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");
	include_once("marketing/JaduUsers.php");
	
	include_once("retail/JaduRetailOrders.php");
	include_once("retail/JaduRetailOrderItems.php");
	include_once("retail/JaduRetailProducts.php");
	include_once("retail/JaduRetailProductReviews.php");
	include_once("retail/JaduRetailProductCodes.php");
	include_once("retail/JaduRetailManufacturers.php");
	include_once("retail/JaduRetailProductsToImages.php");
	include_once("retail/JaduRetailProductsToCategories.php");
	include_once("retail/JaduRetailCategoryTree.php");
	include_once("retail/JaduRetailAlsoBoughts.php");
	include_once("retail/JaduRetailProductsToProducts.php");
	include_once("retail/JaduRetailTax.php");
	include_once("retail/JaduRetailProductOptionEntries.php");
	include_once("retail/JaduRetailProductOptions.php");
	include_once("retail/JaduRetailProductsToDatasheets.php");
	include_once("retail/JaduRetailProductDatasheets.php");
	
	$MAX_ALSO_BOUGHTS = 3;

	if (isset($userID)) {
		$user = getUser($userID);
	}

	if (isset($_GET['product_id']) && is_numeric($_GET['product_id']) && $_GET['product_id'] > -1) {
		$product = getProduct($_GET['product_id']);
		
		$siblings = getProductSiblings($product->siblingID, 'online');
		
		if (isset($_GET['category'])) {
			$category = getRetailCategory($_GET['category_id']);
		}
				
		if ($product->status == "offline") {
			header("Location: $ERROR_REDIRECT_PAGE");
			exit();
		}
		
		$partNum1 = getCodeXForProduct(1, $product->id);
		$manufacturer = getManufacturer($product->manufacturer_id);

		//	Build up the catgories list - probably usually only one for well designed catalogues
		$categories = array();
		$pToCs = getAllProductToCategoryFromProductID($product->id);
		foreach ($pToCs as $pToC) {
			$cat = getRetailCategory($pToC->category_id);
			if ($cat->title != $INVISIBLE_FOLDER)
				$categories[] = $cat;
		}		
		
		$image_file = "no_image.gif";
		$image_title = "No Image Available";

		$images = getAllProductToImagesForProduct($product->id);
		$num_images = sizeof($images);
		if ($num_images >= 1) {
			$image = getImageByFilename($images[0]->image_filename);
			$image_file = $image->filename;
			$image_title = $image->title;
		}
		
		$relatedProducts = array();
		$relateds = getAllProductToProductFromProductID($product_id);	
		foreach ($relateds as $related) {
			if ($product_id == $related->product_id) {
				$id = $related->related_to_id;
			}
			else {
				$id = $related->product_id;
			}
			$relatedProducts[] = getProduct($id);
		}
		
		$productOptions = getProductOptions();
		$optionsUsed = array();
	    foreach ($productOptions as $option) {
	        foreach ($siblings as $s) {
	            if (isOptionAssignedToProduct($option->id, $s->id)) {
	                $optionsUsed[] = $option;
	                break;
	            }
	        }
	    }
	}
	else {
		header("Location: $ERROR_REDIRECT_PAGE");
		exit();
	}

	$breadcrumb = "retailProductBrowse";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $product->title;?> - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="<?php print $product->title; ?>, <?php print $category->title;?>, <?php print $manufacturer->title;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> <?php print $product->title; ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> shopping" />
	<meta name="DC.description" lang="en" content="<?php print $product->title; ?>, <?php print $manufacturer->title;?>" />

<?php
	print "<script type='text/javascript'>\n";
	print "var jsArray = new Array($num_images);\n";
	foreach ($images as $index => $arrayItem) {
		$image = getImageByFilename($arrayItem->image_filename);
		print "jsArray[" . $index . "] = \"" . $image->filename . "\";\n";
	}
	print "</script>\n";
?>
	
    <script type='text/javascript'>
    	var image_number = 0;	
	    function nextImage()
    	{
	    	image_number+=1;
	    	document.images.main_product_image.src = "../../images/" + jsArray[image_number];
	    	document.getElementById('prev_image_button').disabled = false;
	    	document.getElementById('prev_image_button').setAttribute('class', 'button');
	    	if (image_number == (jsArray.length-1)) {
				document.getElementById('next_image_button').disabled = true;
				document.getElementById('next_image_button').setAttribute('class', 'button disabled');
			}
    	}
	    function prevImage()
	    {   
	    	image_number-=1;
		    document.images.main_product_image.src = "../../images/" + jsArray[image_number];
    		document.getElementById('next_image_button').disabled = false;
    		document.getElementById('next_image_button').setAttribute('class', 'button');
	    	if (image_number == 0) {
	    		document.getElementById('prev_image_button').disabled = true;
	    		document.getElementById('prev_image_button').setAttribute('class', 'button disabled');
			}
	    }
    </script>
	
</head>
<body class="retail">
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
						
<?php
	if (isset($_GET['loginRequired'])){
?>			
		<h2 class="warning">Please <a href="http://<?php print $DOMAIN; ?>/site/index.php?sign_in=true">Sign-in</a> to use this feature. If you are not already a member, please <a href="http://<?php print $DOMAIN;?>/site/scripts/register.php">register here</a>.</h2>
<?php
	}
?>
	<div class="content_box">
		<div class="byEditor">
<?php
	if ($num_images > 0) {
?>		
		<div class="productImage">
			<img name="main_product_image"  src="http://<?php print $DOMAIN; ?>/images/<?php print $image_file;?>" alt="<?php print $product->title . ' image';?>" />
<?php
		if ($num_images > 1) {
?>
			<p>
				<input type="button" class="button disabled" name="prev_image_button" id="prev_image_button" value="&laquo;" onclick="prevImage();" disabled />
				<input type="button" class="button" name="next_image_button" id="next_image_button" value="&raquo;" onclick="nextImage();"  />
			</p>
<?php
		}
?>
		</div>
<?php
	}
?>
		<?php print $product->description; ?>
			<div class="clear"></div>
		</div>
	</div>


	<div class="content_box">
	<h2>Specification</h2>
<?php
		
	if ($product->discount_price == "0.00") {
		print "<p><strong>Price:</strong> <span class='price'>&pound;" . $product->getFormattedSellingPrice() . "</span></p>";
	}
	else {						
		print "<p><strong>Discount Price:</strong> <span class='price'>&pound;" . $product->getFormattedSellingPrice() . "</span></p>";
		print "<p><strong>Usual Price:</strong> <s>&pound;" . $product->getFormattedPriceBeforeDiscount() . "</s></p>";
	}
	
	if(trim($manufacturer->title) != '') {
?>                
		<p><strong>Manufacturer:</strong> <?php print $manufacturer->title;?></p>
<?php
	}
	
	if($product->stock == 0) {
		print '<p><strong>Stock level:</strong> Not in stock</p>';
	}
	else {
		print '<p><strong>Stock level:</strong> In stock</p>';
	}
	$options = getOptionEntriesForProduct($product->id);
	if ($options) {
		foreach ($options as $entry){
			$option = getOption($entry->option_id);
?>
		<p><strong><?php print $option->option_name; ?>:</strong> <?php print $entry->value; ?></p>
<?php
		}
	}
?>
	</div>

	<div class="content_box">	
		<form action="http://<?php print $DOMAIN;?>/site/scripts/retail_basket.php" method="post" name="basketform" class="retail_form">
			<input type="hidden" name="q" value="1"/>
			<input type="submit" name="add_to_basket" value="Add to basket" class="button go" />
			<input type="hidden" name="action_basket" value="ADD" />
			<input type="hidden" name="id" value="<?php print $product->id;?>" />
			<input type="hidden" name="p" value="<?php print $product->getFormattedSellingPrice();?>" />
			<input type="hidden" name="item" value="<?php print $product->title;?>" />
		</form>
		<form action="http://<?php print $DOMAIN;?>/site/scripts/retail_wishlist.php" method="post" name="wishlistForm" class="retail_form">
			<input type="submit" name="add_to_wishlist" value="Add to Wish list" class="button" /> 
			<input type="hidden" name="id" value="<?php print $product->id;?>" />
			<input type="hidden" name="q" value="1" />
		</form>
		<form action="http://<?php print $DOMAIN;?>/site/scripts/retail_basket.php" method="post" name="saveForLaterForm" class="retail_form">
			<input type="submit" name="save_for_later" value="Save for later" class="button" />
			<input type="hidden" name="action_sfl" value="ADD" />
			<input type="hidden" name="id" value="<?php print $product->id;?>" />
		</form>
		<div class="clear"></div>
	</div>

<?php
	$productsToDatasheets = getAllProductToDatasheetsForProduct($product->id);
	if (sizeof($productsToDatasheets) > 0) {
?>
	<div class="content_box">
		<h2>Further information</h2>
		<ul class="list">
<?php
		foreach ($productsToDatasheets as $productToDatasheet) {
			$datasheet = getProductDatasheet($productToDatasheet->datasheet_id);
			print '<li><a href="http://'.$DOMAIN.'/downloads/'.$datasheet->filename.'">'.$datasheet->title.'</a></li>';
		}
?>
		</ul>
	</div>
<?php
	}

	if (sizeof($siblings) > 1) {
?>
	<div class="content_box">
	<h2 id="alternative_specs">Alternatives</h2>
	
	<!-- alternative specification table -->
	<table summary="product alternatives" class="specification_table">
		<tr>
<?php
		foreach ($optionsUsed as $option) {
?>
		    <th><?php print $option->option_name; ?></th>
<?php
	    }
?>
			<th>Price</th>
			<th>Qty</th>
			<th>Add to Basket</th>
		</tr>
<?php
		foreach ($siblings as $sibling){
			if ($sibling->status == 'online') {
				$siblingOptions = getOptionEntriesForProduct($sibling->id);
				if (sizeof($siblingOptions) > 0) {
					$infoURL = 'http://' . $DOMAIN . '/site/scripts/retail_product_browse.php?product_id=' . $sibling->id;
?>
					<tr>
<?php
				    foreach ($optionsUsed as $option) {
?>
			            <td>
							<a href="<?php print $infoURL; ?>"><?php if ($sibling->id == $product_id) print '<strong>'; ?><?php print $siblingOptions[$option->id]->value; ?><?php if ($sibling->id == $product_id) print '</strong>'; ?></a>
						</td>
<?php
			        }
?>
				        <td>
							<a href="<?php print $infoURL; ?>"><?php if ($sibling->id == $product_id) print '<strong>'; print "&pound;".$sibling->getFormattedSellingPrice(); if ($sibling->id == $product_id) print '</strong>'; ?></a>
						</td>

				        <form action="http://<?php print $DOMAIN; ?>/site/scripts/retail_basket.php" method="post" name="basketform">									        	
			     		<td>
							<input type="text" name="q" size="2" value="1" class="field" />
				    	</td>
					    <td>
							<input type="hidden" name="action_basket" value="Add" />
							<input type="hidden" name="id" value="<?php print $sibling->id;?>" />
							<input type="hidden" name="p" value="<?php print $sibling->getFormattedSellingPrice();?>" />
							<input type="hidden" name="item" value="<?php print $sibling->title;?>" />
	            			<input name="add_to_basket" value="Add" type="submit" class="button" />    
					    </td>
				        </form>
				    </tr>
<?php
				}
			}
		}
?>
	</table>
	</div>
<?php
	}
?>

<!-- end specification table -->
				
<?php
	if (sizeof($relatedProducts) > 0) {
?>
	<div class="content_box">
	<h2>Related Products</h2>
		<ul class="list">
<?php		
		foreach ($relatedProducts as $index => $relatedProduct) {
?>
		<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/retail_product_browse.php?product_id=<?php print $relatedProduct->id;?>"><?php print $relatedProduct->title;?></a></li>
<?php
		}		
?>
		</ul>
	</div>
<?php
	}
?>			

	<div class="content_box">
	<h2 id="reviews">Reviews</h2>
<?php
	if (!productHasReview ($product->id)) {
?>
	<p class="rating"><img src="http://<?php print $DOMAIN;?>/site/images/retail/rating.gif" alt="rating star" class="rating" />	<a href="http://<?php print $DOMAIN; ?>/site/scripts/retail_write_review.php?productID=<?php print $product_id;?>">Be the first to review this product</a></p>
<?php
	}
	else if (productHasReview ($product->id)) {
		print '<p class="rating">';
		$rating = getProductReviewAverageRating ($product->id);
		$rating = number_format($rating, 2, '.', '');
		$rating_array = explode(".", $rating);
		print "<strong>Average Rating: </strong>";
		for ($i = 0; $i < $rating_array[0]; $i++) {
?>
	<img src="http://<?php print $DOMAIN;?>/site/images/retail/rating.gif" alt="One star" class="rating" />
<?php
		}
		if ($rating_array[1] >= 25 && $rating_array[1] < 75) {
?>
	<img src="http://<?php print $DOMAIN;?>/site/images/retail/rating_half.gif" alt="Half a star" class="rating" />
<?php
		}
		else if ($rating_array[1] >= 75) {
?>
	<img src="http://<?php print $DOMAIN;?>/site/images/retail/rating.gif" alt="One star" class="rating" />
<?php
		}
		if (isset($userID)) {
?>
		 - <a href="http://<?php print $DOMAIN; ?>/site/scripts/retail_write_review.php?productID=<?php print $product_id;?>">Write a Review</a>
<?php
		}
		print '</p>';
	}	

			$productReviews = getAllLiveProductReviewsForProduct($product_id);
			foreach ($productReviews as $review) {
				$reviewer = getUser($review->user_id);
?>
				<div class="download_box">
				<p><strong>Rating: </strong>
<?php
				$rating = number_format($review->rating, 2, '.', '');
				$rating_array = explode(".", $rating);
				for ($i = 0; $i < $rating_array[0]; $i++) {
					print "<img src=\"http://" . $DOMAIN . "/site/images/retail/rating.gif\" alt=\"rating star\" class=\"rating\" />";
				}
				if ($rating_array[1] == 50) {
					print "<img src=\"http://" . $DOMAIN . "/site/images/retail/rating_half.gif\" alt=\"rating star\" class=\"rating\" />";
				} 
?>
				</p>

<?php
				if ($review->comments != "") { 
?>
					<p><strong>Comments:</strong> <?php print nl2br ($review->comments);?></p>
					
				
<?php
 				}
 ?>
 				<p class="date">Date: <?php $date = strtotime($review->date_reviewed); print date("D d M Y H:i a", $date);?></p>	
 <?php
 				print '</div>';
			}
?>
	</div>

<!-- ###################################### -->
<?php include("../includes/closing_retail.php"); ?>