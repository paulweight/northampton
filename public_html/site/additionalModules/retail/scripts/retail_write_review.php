<?php

	session_start();
    include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");
//	include_once("marketing/JaduUsers.php");
	include_once("retail/JaduRetailProducts.php");
	include_once("retail/JaduRetailProductReviews.php");	
	
//	if (!isset($userID) || !isset($productID)) {
//		header("Location: $ERROR_REDIRECT_PAGE");
//		exit;
//	}
//	else {

//		$user = getUser($userID);
		$product = getProduct($productID);

		if (isset($submit)) {
			newProductReview($productID, $userID, $rating, $comments);
			
			header("Location: ./retail_product_browse.php?product_id=$productID");
			exit;
		}

//	}

	$breadcrumb = 'retailWriteReview';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Write a review - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="write, review, shop, commerce, basket, products, buy, gifts, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Write a review" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Write a review" />
	<meta name="DC.description" lang="en" content="The <?php print METADATA_GENERIC_COUNCIL_NAME;?> Write a review" />
</head>
<body class="retail">
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
				
	<p class="first">Please enter your review for the <span><?php print $product->title;?></span>.</p>
	<p>Once submitted, your review will be approved before final submission to the site.</p>
				

	<form action="" method="post" class="basic_form">
		<p>
			<label>Rating (out of 5): </label>
				<select name="rating" class="select">
<?php
				for ($i=0; $i<=5; $i+=0.5) {
					$i = number_format($i, 1, '.', '');
				print "<option value=\"$i\">$i</option>";
				}
?>
				</select>
		</p>
		<p><label>Comments:</label><textarea name="comments" rows="10" cols="2" class="field"><?php print $comments;?></textarea></p>

		<p class="center"><input type="submit" value="Submit Review" name="submit" class="button" /></p>
				
	</form>
			
<!-- ###################################### -->
<?php include("../includes/closing_retail.php"); ?>