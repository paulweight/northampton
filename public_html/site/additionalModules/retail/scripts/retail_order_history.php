<?php

    session_start();
    include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");
	include_once("marketing/JaduUsers.php");
	include_once("retail/JaduRetailProducts.php");
	include_once("retail/JaduRetailOrders.php");
	include_once("retail/JaduRetailOrderItems.php");
	include_once("retail/JaduRetailCardDetails.php");
	include_once("retail/JaduRetailDelivery.php");
	include_once("retail/JaduRetailCountries.php");
	include_once("retail/JaduRetailCountryToDelivery.php");
	include_once("retail/JaduRetailProductsToDelivery.php");
	include_once("retail/JaduRetailTempShoppingBasket.php");
	include_once("retail/JaduRetailTempShoppingBasketItems.php");

	$PER_PAGE = 5;

	if (isset($userID)) {
		$user = getUser($userID);

		$totalPages = getAllOrdersForUserPages ($userID, $PER_PAGE);
		
		if (!isset($page))
			$page = 1;
		elseif ($page > $totalPages)
			$page = $totalPages;
		elseif ($page < 1)
			$page = 1;

		$orders = getAllOrdersForUserLimitDate($userID, (($page-1) * $PER_PAGE), $PER_PAGE);
		
	} 
	else {
		header("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}
	
	$breadcrumb = 'retailOrderHistory';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Order History - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="order, history shop, commerce, basket, products, buy, gifts, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> shopping" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Order History" />
	<meta name="DC.description" lang="en" content="The <?php print METADATA_GENERIC_COUNCIL_NAME;?> Order History" />
</head>
<body class="retail">
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

			
<?php
		if (sizeof($orders) > 0) {
?>
			<p class="first">All the orders you have placed with us are listed below. You can view invoices for each order by clicking the invoice link provided.</p>
				
<!-- *********************** -->
<!--    Navigation table     -->
<!-- *********************** -->
					
					<h2>Completed orders</h2>
					<table>
						<tr>
							<td colspan="5">
<?php
			if ($page != 1) {
				$previousPage = $page-1;
				print "<a href=\"http://<?php print $DOMAIN;?>/site/scripts/retail_order_history.php?page=$previousPage\">previous</a> | ";		
			}
			for ($i = 1; $i<$totalPages+1; $i++) {
				if ($i == $page)
					print "$i | ";
				else {
					print "<a href=\"http://<?php print $DOMAIN;?>/site/scripts/retail_order_history.php?page=$i\">$i</a> | ";	 
				}
			}
			if ($page != $totalPages) {
				$nextPage = $page+1;
				print "<a href=\"http://<?php print $DOMAIN;?>/site/scripts/retail_order_history.php?page=$nextPage\">next</a>";
			}
?>
					</td>
				</tr>
<!-- *********************** -->
<!-- END of Navigation table -->
<!-- *********************** -->
				
				<tr>

<?php
					foreach($orders as $order) {
						$order_items = getAllOrderItemsForOrder($order->id);
?>						


				<td colspan="5"><strong>Web Order Ref: <?php print $order->id;?></strong></td>
			</tr>
			<tr>
				<td colspan="5"><strong>Ordered on: <?php print date('D d M y', $order->date_ordered);?></strong></td>
			</tr>
			<tr>
				<td>Item</td>
				<td>Quantity</td>
				<td>Price</td>
				<td>Delivery</td>
			</tr>
<?php
							foreach ($order_items as $order_item) {
								$product = getProduct($order_item->product_id);
?>
			<tr>
				<td><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_product_browse.php?product_id=<?php print $product->id;?>" class="copy"><?php print $product->title?></a></td>
				<td><?php print $order_item->quantity;?></td>
				<td>&pound;<?php print $order_item->cost_in_tax;?></td>
				<td>&pound;<?php print $order_item->cost_delivery;?></td>
			</tr>
<?php
							}
?>
			<tr>
				<td colspan="5">&raquo; <a href="http://<?php print $DOMAIN;?>/site/scripts/retail_invoice.php?orderID=<?=$order->id;?>" target="_blank">View Invoice</a> (external link)<br /><br /></td>
			</tr>

					
<?php
					}
?>

		</table>
		
<?php
	} 
	else {
?>
		<h2>You currently have no order history.</h2>

<?php
	}
?>
			
<!-- ###################################### -->
<?php include("../includes/closing_retail.php"); ?>