<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("ePayments/JaduEpaymentsOrders.php");
	include_once("ePayments/JaduEpaymentsOrderItems.php");
	include_once("ePayments/JaduEpaymentsProducts.php");
	include_once("ePayments/JaduEpaymentsFinanceDetails.php");
	include_once("ePayments/JaduEpaymentsOrderPayees.php");
	
	if (isset($_SESSION['userID'])) {
		$order = getOrder($_POST['orderID']);
		$orderItems = getAllItemsForOrder($order->id);
		$orderPayee = getPayeeForOrder($order->id);
		$financeDetails = getFinanceDetails();
		
		$totalAmount = 0.00;
		foreach ($orderItems as $item) {
			$totalAmount += $item->grossAmount;
		}
		unset($item);
	}

	if (!isset($_SESSION['userID']) || $order->userID != $_SESSION['userID'] || ($order->status != ORDER_STATUS_PENDING && $order->status != ORDER_STATUS_COMPLETE)) {
		header ("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Payments archive - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include("../includes/meta.php"); ?>

	<!-- general metadata -->
	<meta name="Keywords" content="forms, form, application, archive, archives, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> online user forms archive" />

	<!-- Dublin Core Metadata -->
	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> form archive" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> online user forms archive" />
	
	<!-- IPSV / LGNL Metadata -->
	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council and democracy" />
</head>
<?php include("../includes/opening.php"); ?>
<!-- #################################### -->
<!-- ########### MAIN CONTENT ########### -->

		<h1>Payment receipt</h1>
					
		<form class="basic_form">
			<fieldset>
				<legend>Payment details</legend>
				<div>
				<ul>
					<li>Order Reference: <strong><?php print $order->id;?></strong></li>
					<li>Date of Payment: <?php print $order->getFormattedDate("d/m/Y H:i a", "dateSubmitted");?></li>
					<li>Total Amount Paid: <strong>&pound;<?php print number_format($totalAmount, 2);?></strong></li>
					<li>Payment Method: <?php print $order->cardType;?></li>
				</ul>
				</div>
			</fieldset>
				
			<fieldset>
				<legend>Itemised list</legend>
				<div>
			<?php
				foreach($orderItems as $counter => $item) {
					$product = getProduct($item->productID, true);
			?>
					<ul>
						<li><strong>Item <?php print $counter+1;?>: <?php print $product->title;?></strong></li>
						<?php if ($product->type == TYPE_BILLED) { ?>
						<li>Reference Number: <?php print $item->referenceNumber;?></li>
						<?php } ?>
						<li>Amount Paid: &pound;<?php print number_format($item->grossAmount,2);?></li>
					</ul>
			<?php
				}
			?>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>Payee details</legend>
					<div>
						<ul>
							<li>Name: <?php print "$orderPayee->title $orderPayee->forename $orderPayee->surname";?></li>
							<li>Telephone: <?php print $orderPayee->telephone;?></li>
							<?php if (strlen($orderPayee->fax) > 0) {?>
							<li>Fax:</div> <span class="amount"><?php print $orderPayee->fax;?></span><?php } ?>
							<li>Address: <br /> 
							<?php print $orderPayee->address1;?>
							<?php if (strlen($orderPayee->address2) > 0) { print '<br />'.$orderPayee->address2; } ?>
							<?php print '<br />'.$orderPayee->city;?>
							<?php print '<br />'.$orderPayee->postcode;?>
							</li>
						</ul>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>Our details</legend>
					<div>
						<ul>
							<li><strong><?php print $financeDetails->title;?></strong></li>
							<?php if (strlen($financeDetails->address) > 0) {?>
							<li>Address:<br />
							<?php print $financeDetails->address;?></li><?php } ?>
							<?php if (strlen($financeDetails->telephone) > 0) {?>
							<li>Telephone No: <?php print $financeDetails->telephone;?></li><?php } ?>
							<?php if (strlen($financeDetails->fax) > 0) {?>
							<li>Fax No: <?php print $financeDetails->fax;?></li><?php } ?>
							<?php if (strlen($financeDetails->email) > 0) {?>
							<li>Email: <a href="mailto:<?php print $financeDetails->email;?>"><?php print $financeDetails->email;?></a></li><?php } ?>
							<li>VAT number: <?php print $financeDetails->VAT_number;?></li>
						</ul>
					</div>
				</fieldset>
			</form>

			<!--  contact box  -->
			<?php include("../includes/contactbox.php"); ?>
			<!--  END contact box  -->
	
<!-- ###################################### -->
<?php include("../includes/closing.php"); ?>
</body>
</html>	