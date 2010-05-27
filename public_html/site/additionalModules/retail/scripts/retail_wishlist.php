<?php
	session_start();
    include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");
	
	include_once("marketing/JaduUsers.php");
	include_once("retail/JaduRetailProducts.php");
	include_once("retail/JaduRetailWishLists.php");
	include_once("retail/JaduRetailWishListItems.php");
	include_once("retail/JaduRetailTax.php");
	
	
	if (isset($userID)) {
		$user = getUser($userID);
		
		$wishlist = getWishListFromUserID($userID);
		
		if ($wishlist->id == -1 || $wishlist->id == "") {
			$wishlistID = newWishList($userID);
			$wishlist = getWishList($wishlistID);			
			
		}
			
		$wishlistID = $wishlist->id;
				
		if (isset($id) && isset($q)) {
			if (isProductInWishList($wishlistID, $id)) {
				$add = increaseWishListItemQuantity($wishlistID, $id, $q);
			}
			else {
				newWishListItem($wishlistID, $id, $q);
			}
		}
		
		if (isset($update) || isset($continue_shopping)) {

			$wishListItems = getAllWishListItems ($wishlistID);

			if (sizeof($wishListItems) > 0) {
				if (sizeof($removes) > 0) {
					foreach($removes as $id) {
						deleteWishListItem($id);
					}
				}
				foreach($wishListItems as $item) {
					$variable = $item->id . "_qty";
					updateWishListItem($item->id, $wishlistID, $item->product_id, $$variable);
				}
			}
		}
		
		$wishListItems = getAllWishListItems($wishlistID);
				
		if (isset($proceed)) {
			header("Location: ./retail_wishlist_tell_friends.php");
			exit;
		}
		else if (isset($continue_shopping)) {
			header("Location: ./retail_products.php");
			exit;
		}
	}
	else {
	    $backTo = $_SERVER['HTTP_REFERER'] . "&loginRequired=true";
		header("Location: $backTo");
		exit;
	}

	$breadcrumb = 'retailWishlist';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Wishlist - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="wishlist, shop, commerce, basket, products, buy, gifts, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Wishlist" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Wishlist" />
	<meta name="DC.description" lang="en" content="The <?php print METADATA_GENERIC_COUNCIL_NAME;?> Wishlist" />
</head>
<body class="retail">
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	
	<form action="http://<?php print $DOMAIN;?>/site/scripts/retail_wishlist.php" method="post" name="mainForm" class="basic_form">		
<?php
	if (sizeof($wishListItems) > 0) {					
?>						
		<p class="first">You can compile products in your wishlist and email it to family and friends any time you wish.</p>
		
		<table>
			<tr>
				<th>Title</th>
				<th>Price</th>
				<th>QTY</th>
				<th>Remove</th>
			</tr>
<?php
		foreach($wishListItems as $item) {
			$product = getProduct($item->product_id);

			$tax = getTax($product->tax_id);
?>
			<tr>
				<td><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_product_browse.php?product_id=<?=$product->id;?>"><?php print $product->title?></a></td>
				<td>&pound;
					<?php
						if ($product->discount_price == "0.00") {
							print number_format($product->price + ($product->price * $tax->rate), 2, '.', '');
						} else {
							print number_format($product->discount_price + ($product->discount_price * $tax->rate), 2, '.', '');
						}
					?>
				</td>
				<td><input type="text" name="<?=$item->id;?>_qty" size="3" border="0" maxlength="3" value="<?php print $item->quantity;?>" class="field"></td>
				<td><input type="checkbox" name="removes[]" value="<?php print $item->id;?>"></td>
			</tr>
<?php
		}
?>
			<tr>
				<td colspan="4" align="right">
					<input type="submit" name="update" value="Update" class="button" />
				</td>
			</tr>
		</table>
	
		<p class="center">
			<input type="submit" name="continue_shopping" value="&laquo; Continue Shopping" class="button" />
			<input type="submit" name="proceed" value="Tell Friends &raquo;" class="button" />
		</p>
	
<?php
	} 
	else {
?>	
		<h2>Your Wishlist is currently empty.</h2>	
<?php
	}
?>	
	</form>

<!-- ###################################### -->
<?php include("../includes/closing_retail.php"); ?>