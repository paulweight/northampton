<?php
    session_start();
	include_once("utilities/JaduStatus.php");

	if (isset($_SESSION['stage'])) {	
		if ($_SESSION['stage'] == 3 || $_SESSION['stage'] == 4) {
			$_SESSION['stage'] = 4;
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
    include_once("retail/JaduRetailOrders.php");
    include_once("retail/JaduRetailOrderItems.php");
    include_once("retail/JaduRetailOrderNames.php");
	include_once("retail/JaduRetailCountries.php");
	include_once("retail/JaduRetailCardDetails.php");
	include_once("retail/JaduRetailProducts.php");
	include_once("retail/JaduRetailTax.php");	
	include_once("retail/JaduRetailDelivery.php");
	include_once("retail/JaduRetailCountries.php");
	include_once("retail/JaduRetailCountryToDelivery.php");
	include_once("retail/JaduRetailProductsToDelivery.php");
	include_once("retail/JaduRetailSECPaySOAP.php");
	include_once("../includes/retail/retail_delivery_address_cookie_functions.php");
	include_once("../includes/retail/retail_basket_cookie_functions.php");

	define (CARD_DATE_YEAR_RANGE, 5);

	include_once("websections/JaduContact.php");
	
	$basket = new Basket();
	$basket_items = $basket->items;	
	
	$delivery_address = new DeliveryAddress();

	if($delivery_address->details[$USE_AS_INVOICE] == 'No') {
		$addr['name'] = $delivery_address->invoiceName;
		$addr['address'] = $delivery_address->invoiceAddress;
		$addr['town'] = $delivery_address->invoiceTown;
		$addr['postcode'] = $delivery_address->invoicePostcode;
		$addr['country'] = $delivery_address->invoiceCountry;
		$addr['county'] = $delivery_address->invoiceCounty;
	}
	else {
		$addr['name'] = $delivery_address->forename . " " . $delivery_address->surname;
		$addr['address'] = $delivery_address->address;
		$add['town'] = $delivery_address->town;
		$addr['postcode'] = $delivery_address->postcode;
		$addr['country'] = $delivery_address->country;
		$addr['county'] = $delivery_address->county;
	}
	$addr['telephone'] = $delivery_address->telephone;
	$addr['email'] = $delivery_address->emailAddress;
	$addr['fax'] = $delivery_address->fax;
	


	$address = new Address();

	//	A function to output the label, and highlight the lable if this field
	//	has been found to have any errors, either from PSP or from our validation
	function showField ($label, $field)
	{
		global $error_array, $response_error_array;
		if ($error_array[$field] || $response_error_array[$field]) {
			print '<em>! '. $label . ' </em>';
		} 
		else {
			print $label;
		}
	}

	//	Format the date correctly
	function formatDate ($month, $year)
	{
		if (strlen($month) == 2 && strlen($year) == 4) {
			return $month . '/' . substr($year, 2, 2);
		}

		return "";
	}
	
	//	Initialisations
	$amount = 0.00;
	$description = "";
	$billingAddress = "";
	//$financeDetails = getFinanceDetails();

	//	Do some checking on the orderID, to amke sure have come from a different 
	//	pages GET or this pages POST.
/*	$orderID = -1;
	if (isset($_POST['orderID'])) {
		$orderID = $_POST['orderID'];
	}
	else if (isset($_GET['orderID'])) {
		$orderID = $_GET['orderID'];
	}*/

	//if (/*isset($_SESSION['userID']) && */$orderID > 0) {
	//	$order = getOrder($orderID);

		if ($basket != null) {
		
			$description = "<order class='com.secpay.seccard.Order'><orderLines class='com.secpay.seccard.OrderLine'>";	
			
			foreach ($basket_items as $id => $item) {
				$str = substr($item->name, 0, 25);
				$description .= "<OrderLine><prod_code>$str";
				$description .= "</prod_code><item_amount>$item->price</item_amount><quantity>$item->quantity</quantity></OrderLine>";
			}
			
			$deliveryCost = $basket->getDeliveryCost();
			
			$description .= "<OrderLine><prod_code>Delivery";
			$description .= "</prod_code><item_amount>$deliveryCost</item_amount><quantity>1</quantity></OrderLine>";			
			$description .=  "</orderLines></order>";

			//$orderPayee = getPayeeForOrder($order->id);

			//	Get the amount and compile the description from the orderItems
	/*		$description = "<order class='com.secpay.seccard.Order'><orderLines class='com.secpay.seccard.OrderLine'>";	

			foreach ($orderItems as $item) {
				$prod = getProduct($item->productID, true);
				if ($prod != -1) {
					$amount += $item->grossAmount;
					$description .= "<OrderLine><prod_code>$prod->title";
					if (strlen($item->referenceNumber) > 0) {
						$description .= " (Reference: " . $item->referenceNumber . ")";
					}

					$description .= "</prod_code><item_amount>$item->grossAmount</item_amount><quantity>$item->quantity</quantity></OrderLine>";
				}
			}
			$description .=  "</orderLines></order>";
*/
			$amount = $basket->getBasketOrderCost();
			$amount = number_format($amount, 2);
			//unset($item);

		}
	

	//	Throw the user out to the payments homepage if they are accessing an
	//	order that is not their own or has already been processed in some way,
	//	or if someone seems to have gotten here without being logged in or providing
	//	an orderID.
/*	if ( $orderID < 1 || $order == null || $order->userID != $user->id || $order->status > ORDER_STATUS_INCOMPLETE) {
	//	header("Location: http://$DOMAIN/site/scripts/retail_basket.php");
	//	exit();
	}*/

	if (isset($_POST['continue'])) {

		//	================================
		//	clean and collect the paramaters
		//	================================

		//	Remove any ' ' or '-' characters that the user may enter in the card number
		$_POST['cardNumber'] = strtr($_POST['cardNumber'], array('-'=>'',' '=>''));
		
		//	Clean the dates up
		$_POST['cardExpiryDate'] = formatDate($_POST['cardExpiryMonth'], $_POST['cardExpiryYear']);
		$_POST['cardStartDate'] = formatDate($_POST['cardStartMonth'], $_POST['cardStartYear']);
		
		//	Switch and Solo Notes for SecPay
		//	Does not always have an issue number, but if this is the card type pass
		//	a value of 0 if no issue number is available. 
		//	Issue number should be null if not a Switch card.
		if ($_POST['cardType'] == 'Switch' || $_POST['cardType'] == 'Solo') {
			if (strlen(trim($_POST['cardIssueNumber'])) == 0) {
				$_POST['cardIssueNumber'] = '0';
			}
		} 
		else {
			$_POST['cardIssueNumber'] = '';
		}
		
		// Generate the billing addresss as XML
		$billingAddress  = "<billing class='com.secpay.seccard.Address'>";
		$billingAddress .= "<name>".$addr['name']."</name>";
		$billingAddress .= "<addr_1>".$addr['address']."</addr_1>";
		$billingAddress .= "<addr_2>". '' ."</addr_2>";
		$billingAddress .= "<city>".$addr['town']."</city>";
		$billingAddress .= "<post_code>".$addr['postcode']."</post_code>";
		$billingAddress .= "<tel>".$addr['telephone']."</tel>";
		$billingAddress .= "<email>".$addr['email']."</email>";
		$billingAddress .= "</billing>";
		
		$shippingAddress ="<shipping class='com.secpay.seccard.Address'> ";
  		$shippingAddress .="<name>".$delivery_address->forename . " " . $delivery_address->surname."</name>"; 
  		$shippingAddress .="<addr_1>".$delivery_address->address."</addr_1>"; 
  		$shippingAddress .="<addr_2>". '' ."</addr_2>"; 
  		$shippingAddress .="<city>".$delivery_address->town."</city>"; 
  		$shippingAddress .="<country>".$delivery_address->country."</country>"; 
  		$shippingAddress .="<post_code>".$delivery_address->postcode."</post_code>"; 
  		$shippingAddress .="<tel>".$delivery_address->telephone."</tel>"; 
  		$shippingAddress .="<email>".$delivery_address->emailAddress."</email>"; 
		$shippingAddress .="</shipping>"; 

		//strip the comma from the amount
		$amount = str_replace (",", "", $amount);
		
		$order = new Order();
	
		$order->id = '';
		$order->merchants_own_id = -1;
		
		
		if (isset($_SESSION['userID'])) {
			$order->user_id = $_SESSION['userID'];
			//print_r($user);
		}
		else {
			//Necessary to instantiate a 'fake' user for the order process to 
			//hold address information as ordering does not require sign-in at the moment
			$user = new RetailOrderNames();
			$user->email = $delivery_address->emailAddress;
			$user->delivery_forename = $delivery_address->forename;
			$user->delivery_surname = $delivery_address->surname;
			if ($delivery_address->details[$USE_AS_INVOICE] == 'No') {
				$user->invoice_name = $delivery_address->forename . ' ' . $delivery_address->surname;
			}
			else {
				$user->invoice_name = $delivery_address->invoiceName;
			}
					
			$order->user_id = $user->insert();
		}
		if ($delivery_address->details[$USE_AS_INVOICE] == 'No') {
			$order->invoice_address = $delivery_address->invoiceAddress . "\n" . $delivery_address->invoiceCounty;
			$order->invoice_postcode = $delivery_address->invoicePostcode;
			$order->invoice_country =$delivery_address->invoiceCountry;
		}
		else {
			$order->invoice_address = $delivery_address->address . "\n" . $delivery_address->county;
			$order->invoice_postcode = $delivery_address->postcode;
			$order->invoice_country =$delivery_address->country;
		}
		$order->contact_telephone = $delivery_address->telephone;
		$order->contact_fax = $delivery_address->fax;
		$order->delivery_address = $delivery_address->address . "\n" . $delivery_address->county;
		$order->delivery_postcode = $delivery_address->postcode;
		$order->delivery_country = $delivery_address->country;
		$order->amount_before_tax = $basket->getBasketProductCost(false);
		$order->amount_after_tax = $basket->getBasketProductCost(true);
		$order->delivery_cost = $basket->getDeliveryCost();
		$order->date_ordered = "";
		$order->date_completed = "";
		$order->status = -2;
		$order->total_refunded = 0;

		
		$transactionParamaters = array(
			'ip' 				=> $_SERVER['REMOTE_ADDR'],
			'cardHolder' 		=> $_POST['cardHolder'],
			'cardNumber' 		=> $_POST['cardNumber'],
			'cardType' 			=> $_POST['cardType'],
			'amount' 			=> $amount,
			'currency'			=> '', //$order->currency,
			'cardExpiryDate'	=> $_POST['cardExpiryDate'],
			'cardStartDate'		=> $_POST['cardStartDate'],
			'cardIssueNumber'	=> $_POST['cardIssueNumber'],
			'cardCV2'			=> $_POST['cardCV2'],
			'description'		=> $description,
			'billing'			=> $billingAddress,
			'shipping'			=> $shippingAddress
		);

		$errors = validateTransactionRequest ($transactionParamaters);
		
		if (sizeof($errors) == 0) {
			$COUNTRY = 0;
			$PRODUCT = 1;
			
			$charge_tax_mode = $COUNTRY; // 0 = at country level, 1 = UK = at product level
			$country = getCountry($delivery_address->country);

			if (strtoupper($country->title) == "UNITED KINGDOM") {
				$charge_tax_mode = $PRODUCT;
			}

			if ($delivery_address->details[$USE_AS_INVOICE] == 'No') {
				$invoiceCountry = getCountry($delivery_address->invoiceCountry);
			}

			$orderID = newOrder($order);

			$order->card_details_id = newCardDetails ($order->user_id, $_POST['cardNumber'], $_POST['cardHolder'], formatDate($_POST['cardStartMonth'], $_POST['cardStartYear']), formatDate($_POST['cardExpiryMonth'], $_POST['cardExpiryYear']), trim($_POST['cardIssueNumber']), $_POST['cardType'], $_POST['cardCV2']);

			//COMMENTED OUT FOR DEMO DO IT DUNT ACTUALL DO OWT LIKE - ROBM
			//$transactionParamaters['orderID'] = $orderID;
			//$response = createTransactionRequestEnvelope ($transactionParamaters);	
	
			//if ($response != -1) {
				//$response_errors = parsePSPReturnCode ($response['code']);
				//if (sizeof($response_errors) == 0 && $response['valid'] == 'true') {
					$x = 0;
					foreach ($basket_items as $id => $item) {
						$product = getProduct($id);
						$delivery = getDelivery($item->delivery_id);
						if ($x == 0) {
							$delCost = $delivery->single;
							$delCost += $delivery->additional * ($item->quantity - 1);
							newOrderItem ($orderID, $product->id, $item->quantity, $item->delivery_id, $product->getFormattedSellingPrice(false), $product->getFormattedSellingPrice(true), $delCost);
						}
						else {
							$delCost = $delivery->additional * ($item->quantity);
							newOrderItem ($orderID, $product->id, $item->quantity, $item->delivery_id, $product->getFormattedSellingPrice(false), $product->getFormattedSellingPrice(true), $delCost);						
						}
						$x++;
					}
					setOrderStatusFinshOrderProcess($orderID, 0);
					$_SESSION["orderID"] = $orderID;
					header("Location: http://$DOMAIN/site/scripts/retail_order_complete.php");
					exit;
				//}
			//}
		}
	}
	
	$breadcrumb = 'retailOrderPayment';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Payment details - <?php  print METADATA_GENERIC_COUNCIL_NAME;?> </title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="payment, shop, commerce, basket, products, buy, gifts, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Payment details" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Payment details" />
	<meta name="DC.description" lang="en" content="The <?php print METADATA_GENERIC_COUNCIL_NAME;?> Payment details" />
</head>
<body class="retail">
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php

	$testMode = RETAIL_PSP_TEST_MODE;
	if ($testMode == 1) {
		print "<p class=\"first\"><strong>Note:</strong> Account currently in test mode. Test Card details type: Mastercard, number: 5404000000000001</p>";
	}
	unset($testMode);
	
?>

<?php
	//	Display any appropriate error messages
	if ( isset($_POST['continue']) && (sizeof($errors) > 0 || sizeof($response_errors) > 0 || $response == -1)) {
		if ($response_errors['communicationDown'] || $response == -1) {
			$showForm = false;
?>
		<h3 class="warning">We are currently experiencing connectivity problems. Please try again later.</h3>
<?php
		}
		else if ($response_errors['duplicatedPayment']) {
			$showForm = false;
?>
		<h3 class="warning">This payment has already been placed!</h3>
<?php
		}
		else if ($response_errors['missingFields']) {
?>
		<h3 class="warning">We are missing some required information. Please complete your details!</h3>
<?php
		}
		else if ($response_errors['merchant']) {
?>
		<h3 class="warning">There appears to be a problem with the configuration of the merchant account.</h3>
<?php
		}
		else {
			print_r($response_errors);
?>
		<h3 class="warning">Invalid details supplied. Please try again.</h3>
<?php
		}
	}

	if (sizeof($errors) > 0) {

?>
		<h3 class="warning">Please ensure fields marked with a <strong>!</strong> are entered correctly.</h3>
<?php
	}
?>

	<p class="first">This transaction is encrypted for your privacy.</p>

	<p>None of the details you enter on this page are stored at <?php print METADATA_GENERIC_COUNCIL_NAME;?>. If you leave this page now, you will have to enter your card details again.</p>

		<form action="http://<?php print $DOMAIN; ?>/site/scripts/retail_order_payment.php" name="mainForm" method="post" class="basic_form">

			<fieldset>
			<!-- step 1 -->
			<legend>Card details</legend>

			<p>
				<label for="cardHolder">
				<?php if ($errors[cardHolder] == 1) { print("<strong>!"); } ?>
				Card Holder Name
				<?php if ($errors[cardHolder] == 1) { print("</strong>"); } ?>
				<em> (required) </em>
				</label>
				<input type="text" id="cardHolder" name="cardHolder" title="The full name as shown on the card" value="<?php print $_POST['cardHolder'];?>" class="field" />
			</p>
			<p>
				<label for="cardType">
				<?php if ($errors[cardType] == 1) { print("<strong>!"); } ?>
				Card Type
				<?php if ($errors[cardType] == 1) { print("</strong>"); } ?>
				<em> (required) </em>
				</label>
				<select id="cardType" name="cardType" class="select" title="The type of card">
					<option value="-1" selected="selected">Please choose ...</option>
					<option value="Master Card" <?php if ($_POST['cardType'] == "Master Card") print 'selected="selected"';?>>Master Card</option>
					<option value="Delta" <?php if ($_POST['cardType'] == "Delta") print 'selected="selected"';?>>Visa Debit, Delta or Connect</option>
					<option value="Visa" <?php if ($_POST['cardType'] == "Visa") print 'selected="selected"';?>>Visa</option>
					<!-- Require Issue -->
					<option value="Switch" <?php if ($_POST['cardType'] == "Switch") print 'selected="selected"';?>>Switch / UK Maestro</option>
					<option value="Solo" <?php if ($_POST['cardType'] == "Solo") print 'selected="selected"';?>>Solo</option>
					<!-- End Require Issue -->
				</select>
			</p>
			<p>
				<label for="cardNumber">
				<?php if ($errors[cardNumber] == 1) { print("<strong>!"); } ?>
				Card Number
				<?php if ($errors[cardNumber] == 1) { print("</strong>"); } ?>
				<em> (required) </em>
				</label>
				<input type="text" id="cardNumber" name="cardNumber"  value="<?php print $_POST['cardNumber'];?>" class="field"  maxlength="19" />
			</p>
			<p>
				<label for="cardExpiryMonth">
				<?php if ($errors[cardExpiryDate] == 1) { print("<strong>!"); } ?>
				Expiry Date
				<?php if ($errors[cardExpiryDate] == 1) { print("</strong>"); } ?>
				<em> (required) </em>
				</label>
				<select id="cardExpiryMonth" name="cardExpiryMonth" class="dob" >
					<option value=""></option>
<?php
					for ($i = 1; $i <= 12; $i++) {
						$val = str_pad($i, 2, '0', STR_PAD_LEFT);
						$selected = "";
						if ($val == $_POST['cardExpiryMonth']) {
							$selected = ' selected="selected"';
						}
						print "<option value=\"$val\"$selected>$val</option>";
					}
?>
				</select>
				<select id="cardExpiryYear" name="cardExpiryYear" class="dob" >
					<option value=""></option>
<?php
					$currentYear = date("Y");
					for ($i=$currentYear; $i <= ($currentYear + CARD_DATE_YEAR_RANGE); $i++) {
						$selected = "";
						if ($i == $_POST['cardExpiryYear']) {
							$selected = ' selected="selected"';
						}
						print "<option value=\"$i\"$selected>$i</option>";
					}
?>
				</select> (MM/YYYY)
			</p>
			<p>
				<label for="cardStartMonth">
				<?php if ($errors[cardStartDate] == 1) { print("<strong>!"); } ?>
				Start Date
				<?php if ($errors[cardStartDate] == 1) { print("</strong>"); } ?>
				</label>
				<select id="cardStartMonth" name="cardStartMonth" class="dob">
					<option value=""></option>
				<?php
					for ($i=1; $i <= 12; $i++) {
						$val = str_pad($i, 2, '0', STR_PAD_LEFT);
						$selected = "";
						if ($val == $_POST['cardStartMonth']) {
							$selected = ' selected="selected"';
						}
						print "<option value=\"$val\"$selected>$val</option>";
					}
				?>
				</select>

				<select id="cardStartYear" name="cardStartYear" class="dob">
					<option value=""></option>
				<?php
					$currentYear = date("Y");
					for ($i=$currentYear; $i >= ($currentYear - CARD_DATE_YEAR_RANGE); $i--) {
						$selected = "";
						if ($i == $_POST['cardStartYear']) {
							$selected = ' selected="selected"';
						}
						print "<option value=\"$i\"$selected>$i</option>";
					}
				?>
				</select> (MM/YYYY)
			</p>
			<p>
				<label for="cardIssueNumber">
				<?php if ($errors[cardIssueNumber] == 1) { print("<strong>!"); } ?>
				Issue No. (Switch / Solo only)
				<?php if ($errors[cardIssueNumber] == 1) { print("</strong>"); } ?>
				</label>
				<input type="text" id="cardIssueNumber" name="cardIssueNumber" title="The issue number displayed on your card (where applicable)" value="<?php print $_POST['cardIssueNumber'];?>" maxlength="2" class="field" />
			</p>
			<p>
				<label for="cardCV2">
				<?php if ($errors[cardCV2] == 1) { print("<strong>!"); } ?>
				CV2 security code
				<?php if ($errors[cardCV2] == 1) { print("</strong>"); } ?>
				<em> (required) </em>
				</label><input type="text" id="cardCV2" name="cardCV2" title="The CV2 or Securty Code (3 digit number) as displayed near the signature strip on your card" value="<?php print $_POST['cardCV2'];?>" maxlength="4" class="field" />
			</p>
			</fieldset>

			<!-- ##### form buttons ##### -->
			<p class="center">
				<input type="button" value="&laquo; Return to Basket" name="back" onclick="window.location = 'http://<?php print $DOMAIN; ?>/site/scripts/retail_basket.php';" class="button " />
				<input type="submit" value="Continue &raquo;" name="continue" class="button go" />
			</p>
			<!-- ##### END form buttons ##### -->

		</form>

<!-- ###################################### -->
<?php include("../includes/closing_retail.php"); ?>