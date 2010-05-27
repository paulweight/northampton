<?php 
	session_start();
	
	if (isset($_SESSION['stage'])) {	
		if ($_SESSION['stage'] == 2 || $_SESSION['stage'] == 3 || $_SESSION['stage'] == 4) {
			$_SESSION['stage'] = 3;
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
	include_once("utilities/JaduStatus.php");
//	include_once("marketing/JaduUsers.php");
	include_once("retail/JaduRetailProducts.php");
	include_once("retail/JaduRetailTax.php");	
	include_once("retail/JaduRetailDelivery.php");
	include_once("retail/JaduRetailCountries.php");
	include_once("retail/JaduRetailCountryToDelivery.php");
	include_once("retail/JaduRetailProductsToDelivery.php");
	include_once($HOME."/site/includes/retail/retail_delivery_address_cookie_functions.php");
	include_once($HOME."/site/includes/retail/retail_basket_cookie_functions.php");
	

	$COUNTRY = 0;
	$PRODUCT = 1;

	$delivery_address = new DeliveryAddress();
	$charge_tax_mode = $COUNTRY; // 0 = at country level, 1 = UK = at product level
	$country = getCountry($delivery_address->country);

	if (strtoupper($country->title) == "UNITED KINGDOM") {
		$charge_tax_mode = $PRODUCT;
	}
	
	if ($delivery_address->details[$USE_AS_INVOICE] == 'No') {
		$invoiceCountry = getCountry($delivery_address->invoiceCountry);
	}
	
	$basket = new Basket();
	$basket_items = $basket->items;

	$breadcrumb = 'retailOrderReview';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Order review - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="order review, shop, commerce, basket, products, buy, gifts, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> shopping" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Order review" />
	<meta name="DC.description" lang="en" content="The <?php print METADATA_GENERIC_COUNCIL_NAME;?> Order review" />
</head>
<body class="retail">
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

					
			<p class="first">Could you please check your order before continuing.</p>
			<p>When you are happy, please press <strong>"Proceed with Order"</strong>. You will then be taken to the payment details page</p>
			
			<!-- Purchase information -->
						<h2>Order Review</h2>
						<table>
							<tr>
								<th>Item</th>
								<th>Qty</th>
								<th>Delivery</th>
								<th>Price (inc VAT)</th>
							</tr>
<?php
							if ($basket->countItems() > 0) {

								$total_quantity = 0;

								$deliveries_used = array();
								$x = 0;
								foreach ($basket_items as $id => $item) {

									$product = getProduct($id);
									$total_quantity += $item->quantity;
?>
									<tr>
										<td><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_product_browse.php?product_id=<?php print $product->id;?>"><?php print $product->title; ?></a></td>
										<td><?php print $item->quantity;?></td>
										<td>&pound;<?php if ($x == 0) { print $basket->getDeliveryCost($id); } else { $deliveryCost = getDelivery($item->delivery_id); $delCost = $deliveryCost->additional * $item->quantity; print number_format($delCost, 2); } ?></td>
										<td>&pound;<?php print $product->getFormattedSellingPrice(true); ?></td>
									</tr>
<?php
									$x++;
								}
							}
?>
							<tr class="zebra">
								<td><strong>Totals</strong></td>
								<td><?php print $total_quantity;?></td>
								<td>&pound;<?php print $basket->getDeliveryCost();?></td>
								<td>&pound;<?php print $basket->getBasketProductCost(); ?></td>
							</tr>
							<tr class="highlightRow">
								<td colspan="3"><strong>Total cost including delivery</strong></td>
								<td><strong>&pound;<?php print $basket->getBasketOrderCost(); ?></strong></td>
							</tr>
						</table>
			<!-- END Purchase information -->

			<h2>Delivery details</h2>
						<table>
							<tr>
								<td>Name</td>
								<td><?php print $delivery_address->forename . ' ' . $delivery_address->surname;?></td>
							</tr>
							<tr>
								<td>E-mail</td>
								<td><?php print $delivery_address->emailAddress;?></td>
							</tr>
							<tr>
								<td>Delivery Address</td>
								<td><?php print $delivery_address->address;?></td>
							</tr>
							<tr>
								<td>County</td>
								<td><?php print $delivery_address->county;?></td>
							</tr>
							<tr>
								<td>Postcode</td>
								<td><?php print $delivery_address->postcode;?></td>
							</tr>
							<tr>
								<td>Country</td>
								<td><?php print $country->title;?></td>
							</tr>
							<tr>
								<td>Telephone</td>
								<td><?php print $delivery_address->telephone;?></td>
							</tr>
							<tr>
								<td>Fax</td>
								<td><?php print $delivery_address->fax;?></td>
							</tr>
							<tr class="zebra">
								<td colspan="2" >
									<a href="http://<?php print $DOMAIN;?>/site/scripts/retail_order_delivery_destination.php">Change these details</a>
								</td>
							</tr>
						</table>

						<form name="mainForm" action="http://<?php print $DOMAIN; ?>/site/scripts/retail_order_payment.php" method="post">
							<!--<input type="hidden" name="userID" value="<?php print $userID;  ?>" />-->
							<input type="hidden" name="tax_charge_mode" value="1" />
<?php
						$basket = new Basket();
						$basket_items = $basket->items;
						foreach($basket_items as $item) {
?>
							<input type="hidden" name="basketItem[<?php print $id ?>]['name']" value="<?php print $item->name; ?>" />
							<input type="hidden" name="basketItem[<?php print $id ?>]['quantity']" value="<?php print $item->quantity; ?>" />
							<input type="hidden" name="basketItem[<?php print $id ?>]['price']" value="<?php print $item->price; ?>" />
							<input type="hidden" name="basketItem[<?php print $id ?>]['delivery_id']" value="<?php print $item->delivery_id; ?>" />
<?php
						}
						$delivery_address = new DeliveryAddress();
?>
							<input type="hidden" name="deliveryAddress[0]" value="<?php print $delivery_address->details[0]; ?>" />
							<input type="hidden" name="deliveryAddress[1]" value="<?php print $delivery_address->details[1]; ?>" />
							<input type="hidden" name="deliveryAddress[2]" value="<?php print $delivery_address->details[2]; ?>" />
							<input type="hidden" name="deliveryAddress[3]" value="<?php print $delivery_address->details[3]; ?>" />
							<input type="hidden" name="deliveryAddress[4]" value="<?php print $delivery_address->details[4]; ?>" />
							<input type="hidden" name="deliveryAddress[5]" value="<?php print $delivery_address->details[5]; ?>" />
							<input type="hidden" name="deliveryAddress[6]" value="<?php print $delivery_address->details[6]; ?>" />

							<p class="text_align_center">
                                <input type="button" name="back" value="&laquo; Delivery Options" onclick="history.go(-1);" class="button" />
                                <input type="submit" name="submit" value="Proceed with Order &raquo;" class="button go" />
							</p>
						</form>

						<p class="note">Please note: Detailed security checks will be carried out on all transactions.</p>			

<!-- ###################################### -->
<?php include("../includes/closing_retail.php"); ?>