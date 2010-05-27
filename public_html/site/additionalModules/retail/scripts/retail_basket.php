<?php
    include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");
	session_start();
	
	$_SESSION['stage'] = 0;
	include_once("retail/JaduRetailProducts.php");
	include_once("retail/JaduRetailTax.php");
	include_once("retail/JaduRetailSavedForLater.php");
	include_once("retail/JaduRetailBasket.php");

	$error_array = array();

	// The operations on the two seperate cookies have been split up - if you
	// put them tgether things go mental for some unknown reason!!!
	$savedForLater = new SavedForlater();
	
	if (isset($_POST['action_sfl']) && $_POST['action_sfl'] == "ADD") {
		$savedForLater->addItem($id);
		$savedForLater->setTheCookie();
	}
	
	//	remove saved for laters if button pressed
	if (isset($_POST['sfl_remove'])) {
		if ($savedForLater->countItems() > 0) {
			if (sizeof($_POST['sfl_removes']) > 0) {
				foreach($_POST['sfl_removes'] as $id) {
					$savedForLater->removeItem($id);
				}
			}

			$savedForLater->setTheCookie();
		}
	}

	//	move saved for laters to basket if button pressed
	if (isset($_POST['sfl_move_to_basket'])) {
		if (sizeof($_POST['sfl_to_basket']) > 0) {
			foreach($_POST['sfl_to_basket'] as $id) {
				$savedForLater->removeItem($id);
			}
			$savedForLater->setTheCookie();
		}
	}	

	$sfl_items = $savedForLater->item_ids;

	$basket = new Basket();
	
	if (isset($_POST['action_basket']) && $_POST['action_basket'] == "ADD") {
		$basket->addItem($_POST['id'], $_POST['item'], $_POST['q'], $_POST['p']);
		$basket->setTheCookie();
	}

	//	remember the quantities
	if (isset($_POST['continue_shopping']) || isset($_POST['proceed'])) {
		if ($basket->countItems() > 0) {
			while (list($id, $attr) = each($basket->items) ) {
				$variable = $id . "_qty";

				if (preg_match("/^[0-9]+$/", $$variable)) {			
					$basket->updateItem($id, $$variable);
				}
				else {
					$error_array[$id] = true;
				}
			}
			$basket->setTheCookie();
		}
	}

	if (isset($_POST['update'])) {
		if ($basket->countItems() > 0) {

			if (sizeof($_POST['removes']) > 0) {
				foreach($_POST['removes'] as $id) {
					$basket->removeItem($id);
				}
			}
			$array = $basket->items; // dont ask me why but have to do this!
			while (list($id, $attr) = each($array) ) {
				$variable = $id . "_qty";

				if ($$variable < 1) {
					$basket->removeItem($id);
				}
				elseif (preg_match("/^[0-9]+$/", $$variable)) {			
					$basket->updateItem($id, $$variable);
				}
				else {
					$error_array[$id] = true;
				}
			}

			$basket->setTheCookie();
		}
	}

	//	move saved for laters to basket if button pressed
	if (isset($_POST['sfl_move_to_basket'])) {
		if (sizeof($_POST['sfl_to_basket']) > 0) {
			foreach($_POST['sfl_to_basket'] as $id) {
				$product = getProduct($id);

				$basket->addItem($id, $product->title, 1, $product->getFormattedSellingPrice(false));
			}	
			$basket->setTheCookie();
		}
	}

	$basket_items = $basket->items;

	//	redirects
	if (sizeof($error_array) == 0) {
		if (isset($_POST['continue_shopping'])) {
			header("Location: ./retail_products.php");
			exit;
		}
		if (isset($_POST['proceed'])) {
			header("Location: http://$DOMAIN/site/scripts/retail_order_delivery_destination.php");
			exit();
		}
	}

	$breadcrumb = 'retailBasket';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Shopping Basket - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="shopping, shop, commerce, basket, products, buy, gifts, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Shopping Basket" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Shopping Basket" />
	<meta name="DC.description" lang="en" content="The <?php print METADATA_GENERIC_COUNCIL_NAME;?> Shopping Basket" />
</head>
<body class="retail">
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

			<form action="http://<?php print $DOMAIN;?>/site/scripts/retail_basket.php" method="post" name="mainForm">

	    		<!-- Basket starts here -->
	    		
<?php			
			if ($basket->countItems() > 0) {
				if (!isset($userID)){	
?>
			<!--<div id="loginmessage">
					<p class="first">You must sign-in to proceed any further with your order.  If you are not already a member, please <a title="Register here." href="http://<?php print $DOMAIN;?>/site/scripts/register.php">register here</a>.</p>
			</div>-->
<?php
					}
?>

			<p class="first">You can compile products in your basket and place your order any time you wish. To proceed with your order, click the 'Proceed' button. </p>
				<table>
					<tr>
						<th>Title</th>
						<th>Price</th>
						<th>QTY</th>
						<th>Remove</th>
					</tr>
<?php
	    			foreach($basket_items as $id => $item) {
	    				$product = getProduct($id);
?>
								<tr>
	    							<td><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_product_browse.php?product_id=<?php print $product->id; ?>"><?php print $product->title; ?></a></td>
									<td>&pound;<?php print $product->getFormattedSellingPrice(); ?></td>
									<td>
	    								<input type="text" name="<?php print $id; ?>_qty" size="3" border="0" maxlength="3" value="<?php print $item->quantity; ?>" class="field">
		                                    <?php if ($error_array[$id] == true) { print "<font color=\"red\"><b>X</b></font>"; } ?>
									</td>
									<td><input type="checkbox" name="removes[]" value="<?php print $id;?>"></td>
								</tr>
<?php
					}
?>
	    						<tr>
	    							<td colspan="4" class="text_align_right">
										<input type="submit" name="update" value="Update" class="button" />
									</td>
								</tr>
							</table>

                            <p class="text_align_center">
								<input type="submit" name="continue_shopping" value="&laquo; Continue Shopping" onclick="return checkQuantities()" class="button" />
								<input type="submit" name="proceed" value="Proceed with Order &raquo;" class="button go" />
                            </p>
	
<?php
			} 
			else {
?>
						<h3>Your shopping basket is currently <span>empty</span>.</h3>
<?php
			}
?>

						<!-- Basket ends here -->

<?php
			if ($savedForLater->countItems() > 0) {
?>

	    		<h2>Items Saved For Later</h2>
	    		<p class="first">Your 'saved for laters' will be saved for 90 days from when you last used them.</p>
	    		
				<table>
    				<tr>
	    				<th>Title</th>
						<th>Price</th>
						<th>Move to Basket</th>
						<th>Remove</th>
					</tr>
<?php
				if (is_array($sfl_items)) {
	    		foreach ($sfl_items as $id) {
						$product = getProduct($id);
?>
						<tr>
							<td><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_product_browse.php?product_id=<?php print $product->id;?>"><?php print $product->title; ?></a></td>
							<td >&pound;<?php print $product->getFormattedSellingPrice(); ?>
							</td>
							<td><input type="checkbox" name="sfl_to_basket[]" value="<?php print $id; ?>"></td>
							<td><input type="checkbox" name="sfl_removes[]" value="<?php print $id; ?>"></td>
						</tr>
<?php
					}
				}
?>
					<tr>
	    				<td colspan="4" class="text_align_center">
							<input type="submit" name="sfl_remove" value="Remove" class="button" />
							<input type="submit" name="sfl_move_to_basket" value="Move to Basket" class="button" />
    					</td>
					</tr>
				</table>

<?php
			}
?>
	</form>
	
<!-- ###################################### -->
<?php include("../includes/retail/closing_retail.php"); ?>