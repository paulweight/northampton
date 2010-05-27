<?php
	include_once("JaduConstants.php");
	session_start();
	
	if (isset($_SESSION['stage'])) {	
		if ($_SESSION['stage'] == 4 || $_SESSION['stage'] == 5) {
			$_SESSION['stage'] = 5;
		}
		else {
			header("Location: http://$DOMAIN/site/scripts/retail_products.php");
			exit();
		}
	}
	else {
		header("Location: http://$DOMAIN/site/scripts/retail_products.php");
		exit();
	}
	
    include_once("JaduStyles.php");
	include_once($HOME."/site/includes/retail/retail_basket_cookie_functions.php");
	include_once($HOME."/site/includes/retail/retail_delivery_address_cookie_functions.php");
	include_once($HOME."/site/includes/retail/retail_saved_for_later_cookie_functions.php");
	include_once("retail/JaduRetailOrders.php");
	
	if (isset($_SESSION['orderID'])) {
		$orderID = $_SESSION['orderID'];
		$order = getOrder($orderID);
	}
	else {
		header("Location: http://$DOMAIN/site/scripts/retail_basket.php");
		exit;
	}
	
	$delivery_address = new DeliveryAddress();
	$basket = new Basket();
	$savedForLater = new SavedForLater();
	
	$basket->removeAll();
	$savedForLater->removeAll();
	$delivery_address->removeAllDetails();
	unset($_SESSION['stage']);
	
	$breadcrumb = 'retailOrderComplete';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Payment details</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="shop, commerce, basket, products, buy, gifts, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> shopping" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> shopping" />
	<meta name="DC.description" lang="en" content="The <?php print METADATA_GENERIC_COUNCIL_NAME;?> shopping" />
</head>
<body class="retail">
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php

	$testMode = RETAIL_PSP_TEST_MODE;
	if ($testMode == 1) {
		print "<p class=\"first\"><strong>Note:</strong> Account currently in test mode.</p>";
	}
	unset($testMode);
	
?>										
	<h2>Confirmation</h2>
	<p class="first"><em>Your order number is:</em> <strong><?php print $orderID; ?></strong></p>
	<p>An e-mail has been sent to you containing confirmation of this order and your VAT receipt.</p>
	<p><em>Order placed:</em> <?php print date('D d M Y H:i a', $order->date_ordered);?></p>
	<p><em>Products ex VAT:</em> &pound;<?php print $order->amount_before_tax;?></p>
	<p><em>Products inc VAT:</em> &pound;<?php print $order->amount_after_tax;?></p>
	<p><em>Delivery charge:</em> &pound;<?php print $order->delivery_cost;?></p>
	<p><em>Total Order Value:</em> &pound;<?php print number_format($order->delivery_cost + $order->amount_after_tax, 2);?></span></p>

	<form action="http://<?php print $DOMAIN; ?>/site/scripts/retail_index.php">
		<input type="button" value="Print Page" onclick="javascript:window.print();" class="button" />
	</form>

<!-- ###################################### -->
<?php include("../includes/closing_retail.php"); ?>