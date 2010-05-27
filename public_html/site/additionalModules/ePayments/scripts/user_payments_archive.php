<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("ePayments/JaduEpaymentsProducts.php");
	include_once("ePayments/JaduEpaymentsOrders.php");
	include_once("ePayments/JaduEpaymentsOrderItems.php");

	if (isset($_SESSION['userID'])) {
		$completedOrders = getUsersOrdersOfState ($_SESSION['userID'], ORDER_STATUS_COMPLETE);
	} else {
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
	<meta name="Keywords" content="online, payments, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments" />

	<!-- Dublin Core Metadata -->
	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>  Online payments" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>  Online payments" />
	
	<!-- IPSV / LGNL Metadata -->
	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council and democracy" />
</head>
<?php include("../includes/opening.php"); ?>
<!-- #################################### -->
<!-- ########### MAIN CONTENT ########### -->
				
		<h1>Your completed online payments</h1>
				
		<?php
			if (sizeof($completedOrders) > 0) {
		?>
			<?php
				foreach ($completedOrders as $index => $order) {
				    $orderItems = getAllItemsForOrder($order->id);
					$totalAmount = 0.00;
			?>
		<form class="basic_form" action="./user_order_receipt.php" method="post">
			<fieldset>
				<legend>Order Reference: <?php print $order->id;?></legend>
					<p>Date of Payment: <?php print $order->getFormattedDate("d/m/Y H:i a", "dateSubmitted");?></p>
					<p>Payment Method: <?php print $order->cardType;?></p>
					<div>
			<?php
					foreach ($orderItems as $counter => $item) {
						$product = getProduct($item->productID, true);
						$totalAmount += $item->grossAmount;
			?>
						<ul>
							<li><strong>Item <?php print $counter+1;?>: <?php print $product->title;?></strong></li>
							<?php if ($product->type == TYPE_BILLED) { ?>
							<li>Reference Number: <?php print $item->referenceNumber;?></li>
							<?php } ?>
							<li>Amount Paid: <strong>&pound;<?php print number_format($item->grossAmount,2);?></strong></li>
						</ul>
			<?php
					}
			?>
					</div>
					<p>Total Amount Paid: &pound;<?php print number_format($totalAmount, 2);?></p>
				<input type="hidden" name="orderID" value="<?php print $order->id;?>" />
				<p><input type="submit" class="button" value="View Receipt" name="receipt" /></p>
			</fieldset>
		</form>
					
			<?php
				}
			?>
		<?php
			} else {
		?>
		<p><strong>You do not currently have any completed online payments</strong>.</p>
		<?php
			}
		?>		
				
		<!--  contact box  -->
		<?php include("../includes/contactbox.php"); ?>
		<!--  END contact box  -->
		
<!-- ###################################### -->
<?php include("../includes/closing.php"); ?>
</body>
</html>