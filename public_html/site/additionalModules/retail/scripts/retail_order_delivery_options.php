<?php
	include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");
	session_start();
	
	if (isset($_SESSION['stage'])) {	
		if ($_SESSION['stage'] == 1 || $_SESSION['stage'] == 2 || $_SESSION['stage'] == 3) {
			$_SESSION['stage'] = 2;
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
	
	include_once("retail/JaduRetailProducts.php");	
	include_once("retail/JaduRetailDelivery.php");
	include_once("retail/JaduRetailCountries.php");
	include_once("retail/JaduRetailCountryToDelivery.php");
	include_once("retail/JaduRetailProductsToDelivery.php");

	include_once($HOME."/site/includes/retail/retail_delivery_address_cookie_functions.php");
	

	$delivery_address = new DeliveryAddress();
	
	$country = getCountry($delivery_address->country);

	// get all delivery methods that are selectable for the users chosen delivery country
	$countriesToDeliveries = getAllCountryToDeliveryForCountry ($delivery_address->country);
	$countriesDeliveryMethods = array();
	foreach ($countriesToDeliveries as $cToD) {
		$countriesDeliveryMethods[] = $cToD->delivery_id;
	}

	include_once($HOME."/site/includes/retail/retail_basket_cookie_functions.php");

	//$user = getUser($userID);

	$basket = new Basket();
	$basket_items = $basket->items;

	if (isset($_POST['continue'])) {
		$error_array = array();
		if (sizeof($basket_items) > 0) {
			foreach($basket_items as $id => $item) {
				$holder = "select_delivery_" . $id;
				$basket->updateItemDelivery($id, $_POST[$holder]);
				if ($_POST[$holder] == -1) {
					$error_array[$id] = true;
				}
			}
			$basket->setTheCookie();
			$basket_items = $basket->items;
		}

		if (sizeof($error_array) == 0) {
			header("Location: http://$DOMAIN/site/scripts/retail_order_review.php");
			exit();
		}
	}

	if (isset($_POST['back'])) {
		header("Location: http://$DOMAIN/site/scripts/retail_basket.php");
		exit();
	}

	$breadcrumb = 'retailOrderDelivery';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Delivery options - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="Delivery options, shop, commerce, basket, products, buy, gifts, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> shopping" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Delivery options" />
	<meta name="DC.description" lang="en" content="The <?php print METADATA_GENERIC_COUNCIL_NAME;?> Delivery options" />

	<script type="text/javascript" src="<?php print $PROTOCOL . $DOMAIN ;?>/javascript_functions.js"></script>

	<script type="text/javascript">

		function change_delivery_cost ()
		{
			<?php
				if (sizeof($basket_items) > 0) {
					$count = 0;
					$select_string = "";
					$span_array = "";
					$qty_array = "";
					foreach($basket_items as $id =>$item) {
						$count++;

						$select_string .= "\"select_delivery_".$id."\"";
						$span_string .= "\"delivery_of_".$id."\"";
						$qty_string .= $item->quantity;
					
						if ($count < sizeof($basket_items)) {
							$select_string .= ",";
							$span_string .= ",";
							$qty_string .= ",";
						}
					}
				}
			?>

			select_array = [<?php print $select_string;?>];
			span_array = [<?php print $span_string;?>];
			qty_array = [<?php print $qty_string;?>];

			total_cost = 0.00;
			for (loop_count = 0; loop_count < select_array.length; loop_count++) {

				select = document.getElementById(select_array[loop_count]);
				qty = qty_array[loop_count];
				delivery_id = select.options[select.selectedIndex].value;
				
				found = false;
				for (i = 0; i < loop_count; i++) {
					second_select = document.getElementById(select_array[i]);

					if ((second_select.options[second_select.selectedIndex].value == select.options[select.selectedIndex].value) && (second_select != select)) {
						found = true;
					}
				}
		
				if (delivery_id != -1) {

					if (!found) {

						if (qty > 1) {
							cost = (qty-1) * parseFloat(document.getElementById('additional_' + delivery_id).value) + parseFloat(document.getElementById("single_"+delivery_id).value);
							cost = cost * 1;
						}
						else {
							cost = parseFloat(document.getElementById("single_" + delivery_id).value);
							cost = cost *1;
						}
					}
					else {
						cost = (qty) * parseFloat(document.getElementById("additional_" + delivery_id).value);
						cost = cost * 1;
					}				
				}
				else {
					cost = parseFloat(0.00);

				}
			
				total_cost = (total_cost+cost);
				cost = cost.toFixed(2);
				document.getElementById(span_array[loop_count]).innerHTML = cost;
			}

			document.getElementById('total_cost_span').innerHTML = total_cost.toFixed(2);
		}
	</script>
	
</head>
<body class="retail" onload="change_delivery_cost()">
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

					
			<p class="first">Please select from the following options how each of the items you are about to order should be delivered.</p>
			
			<form action="http://<?php print $DOMAIN; ?>/site/scripts/retail_order_delivery_options.php" name="mainForm" method="post" class="basic_form"> 

			<!-- step 2 -->
			<h2>Delivery details</h2>
				<table>
					<tr>
						<th>Title</th>
						<th>QTY</th>
						<th>Method</th>
						<th>Delivery Cost</th>
					</tr>
<?php
							if (sizeof($basket_items) > 0) {

								$deliveries_used = array();
								$total_amount = "0.00";
								foreach ($basket->items as $id => $item) {

									$product = getProduct($id);

									//	Get products Delivery methods
									$productsDeliveries = array();
									$productsActualDeliveries = array();
									$pToDs = getAllProductToDeliveryFromProductID($product->id);

									foreach ($pToDs as $pToD) {
										$productsDeliveries[] = $pToD->delivery_id;
										$productsActualDeliveries[] = getDelivery($pToD->delivery_id);

									}
									
									//	get all AVAILABLE delivery methods for this current product worked
									//	out by intersection between the countrys delivery methods with
									//	the products delivery methods - if none - then just show the products methods
									$available_deliveries = array();
									foreach ($countriesDeliveryMethods as $country_del_id) {
										if (in_array ($country_del_id, $productsDeliveries)) {
											$delivery = getDelivery($country_del_id);
											$available_deliveries[] = $delivery;
										}
									}
									
									if (sizeof($available_deliveries) == 0) {
										$available_deliveries = $productsActualDeliveries;
										mail($DEFAULT_EMAIL_ADDRESS, "Product Delivery Error Notice", "A user has just tried to purchase the product \"$product->title\" (id = $product->id) When it is to be delivered to \"$country->title\". The user was default displayed all the product delivery methods, but you should change its setup for future useage.");
									}

									foreach ($available_deliveries as $del) {

										print "<input type=\"hidden\" id=\"single_$del->id\" name=\"single_$del->id\" value=\"$del->single\">";
										print "<input type=\"hidden\" id=\"additional_$del->id\" name=\"additional_$del->id\" value=\"$del->additional\">";
									}

									$amount = "0.00";

									if ($item->delivery_id != -1 && $item->delivery_id != "") {
										$del = getDelivery($item->delivery_id);

										if (in_array($del->id, $deliveries_used)) {
											$amount = ($item->quantity * $del->additional);
										}
										else {
											$amount = $del->single;
											if (($item->quantity - 1) > 0)
												$amount += (($item->quantity - 1) * $del->additional);
										}
//										$amount += ($amount * $del->tax_rate);
										$amount = number_format($amount, 2, '.', '');
										$deliveries_used[] = $del->id;										
									}
									$total_amount += $amount;
?>
									<tr>
										<td><a href="./retail_product_browse.php?product_id=<?php print $product->id; ?>" class="copy"><?php print $product->title; ?></a></td>
										<td><?php print $item->quantity; ?></td>
										<td>
											<select style="width:120px" name="select_delivery_<?php print $id; ?>" id="select_delivery_<?php print $id; ?>" onchange="change_delivery_cost()" class="field">
												<option selected="selected" value="-1">Choose Method</option>
											<?php
												foreach ($available_deliveries as $delivery) {
													if ($delivery->id == $item->delivery_id) {
														print "<option value=\"$delivery->id\" selected>$delivery->title</option>";
													}
													else {
														print "<option value=\"$delivery->id\">$delivery->title</option>";										
													}
												}
											?>
											</select> <?php if ($error_array[$id]) print "<span class=\"red\">X</span>"; else print "<span class=\"red\">*</span>";?>
										</td>
										<td>&pound;<span id="delivery_of_<?php print $id; ?>" name="delivery_of_<?php print $id; ?>"><?php print $basket->getDeliveryCost($id); ?></span></td>
									</tr>
<?php

								}
								$total_amount = number_format($total_amount, 2, '.', '');
?>
								<tr>
									<td colspan="3" align="right"><b>Total: </b></td>
									<td>&pound;<span id="total_cost_span" name="total_cost_span"><?php print $basket->getDeliveryCost($id); ?></span></td>
								</tr>
<?php
							}
							unset ($basket);
?>

						</table>
				
				<p class="text_align_center">
                    <input type="submit" value="&laquo; Return to Basket" name="back" class="button" />
                    <input type="submit" value="Proceed with Order &raquo;" name="continue" class="button go" />
				</p>
				</form>
				
<!-- ###################################### -->
<?php include("../includes/closing_retail.php"); ?>