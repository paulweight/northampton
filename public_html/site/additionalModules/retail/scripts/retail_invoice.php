<?php
	session_start();
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
	include_once("retail/JaduRetailCompanyDetails.php");
	include_once("retail/JaduRetailTax.php");
	
	if (isset($orderID)) {
		$order = getOrder($orderID);
		$order_items = getAllOrderItemsForOrder($order->id);
		$card_details = getCardDetails($order->card_details_id);
	}
	
	if (isset($userID)) {
		$user = getUser($userID);
		if ($user->forename != "" && $user->surname != "") {
			if ($user->salutation != "")
				$name = $user->salutation . " " . $user->forename . " " . $user->surname;
			else
				$name = $user->forename . " " . $user->surname;
		}
		else
			$name = $user->email;
	}
	
	$company_details = getCompanyDetails();
	
?>

<html>
	<head>
		<title>Invoice</title>
		
		<style>
			BODY
			{
				COLOR: #000;
				text-decoration: none;
				font-family: Verdana, Tahoma, Arial, Helvetica, Sans-Serif;
				font-size: 12px;
			}
			.header
			{
				color: #333;
				text-decoration: none;
				font-weight: bold;
				font-family: Verdana, Tahoma, Arial, Helvetica, Sans-Serif;
				font-size: 19px;
			}
			
			.subheader
			{
				color: #333;
				text-decoration: none;
				font-weight: bold;
				font-family: Verdana, Tahoma, Arial, Helvetica, Sans-Serif;
				font-size: 16px;
			}	
		</style>	
	</head>
	
	<body>
		<br>
		<span class="header">Invoice from <?php print $company_details->title;?></span><br>
		<br>
		<hr>
		<span class="subheader">Your details</span><br>
		<hr>
		Name: <b><?php print $name;?></b><br>
		<br>
		<u>Invoice Address:</u><br>
		<?php print $order->invoice_address;?><br>
		<?php print $order->invoice_postcode;?><br>
		<?php print $order->invoice_country;?><br>
		<br>
		<u>Delivery Address:</u><br>
		<?php print $order->delivery_address;?><br>
		<?php print $order->delivery_postcode;?><br>
		<?php print $order->delivery_country;?><br>
		<br>
		Telephone: <b><?php print $order->contact_telephone;?></b><br>
		Fax: <b><?php print $order->contact_fax;?></b><br>
		<br>
		<hr>
		<span class="subheader">Order details</span><br>
		<hr>
		Date ordered: <b><?php print date('D d M Y H:i a', $order->date_ordered);?></b><br>
		Order reference no: <b><?php print $order->id;?></b><br>
		Products: <br>
		
	<?php
		foreach ($order_items as $order_item) {
			$product = getProduct($order_item->product_id);
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
			print "<b>$product->title</b> (qty: $order_item->quantity@£$price) = £$order_item->cost_in_tax + £$order_item->cost_delivery Delivery<br>";
		}
	?>

		<br>
		<hr>
		<span class="subheader">Payment details</span><br>
		<hr>
		<!--Card type: <b><?php print $card_details->CC_type;?></b><br>-->
		Amount excluding Tax: <b>&pound;<?php print $order->amount_before_tax;?></b><br>
		Tax payable: <b>&pound;<?php print ($order->amount_after_tax - $order->amount_before_tax);?></b><br>
		Amount including Tax: <b>&pound;<?php print $order->amount_after_tax;?></b><br>
		Delivery charges made: <b>&pound;<?php print $order->delivery_cost;?></b><br>
		<br>
		Total paid: <b>&pound;<?php print ($order->amount_after_tax + $order->delivery_cost);?></b><br>
		<br>
		<hr>
		<span class="subheader">Company details</span><br>
		<hr>
		<?php print $company_details->title;?><br>
		<?php print $company_details->address;?><br>
		<?php print $company_details->telephone;?><br>
		<?php print $company_details->fax;?><br>
		VAT Number: <?php print $company_details->VAT_number;?><br>
		<br>
	</body>
</html>