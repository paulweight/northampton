<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("ePayments/JaduEpaymentsBalancesHomepage.php");
	include_once("ePayments/JaduEpaymentsAccountBalances.php");
	include_once("ePayments/JaduEpaymentsAccountBalanceAccessLog.php");
	include_once("ePayments/JaduEpaymentsAccountBalanceFileHistory.php");
	include_once("ePayments/JaduEpaymentsProducts.php");
	
	$homepage = new EpaymentsBalancesHomepage();
	$mostRecentImport = getMostRecentlyFileImportedDetails();
	
	if (isset($_POST['find']) && isset($_POST['referenceNumber'])) {
		$accountBalance = getAccountBalanceBy('referenceNumber', $_POST['referenceNumber']);
		
		$found = EPAYMENTS_ACCOUNT_BALANCE_FOUND;
		if ($accountBalance == -1) {
			$found = EPAYMENTS_ACCOUNT_BALANCE_NOT_FOUND;
		}
		
		newAccountBalanceAccessLog ($_SESSION['userID'], $_POST['referenceNumber'], $found);
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Online balances - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include("../includes/meta.php"); ?>
	
	<!-- general metadata -->
	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Account balances" />

	<!-- Dublin Core Metadata -->
	<meta name="DC.title" lang="en" content="Online balances - <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Account balances" />
	
	<!-- IPSV / LGNL Metadata -->
	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council and democracy" />
</head>
<body>
<?php include("../includes/opening.php"); ?>
<!-- #################################### -->
<!-- ########### MAIN CONTENT ########### -->
				
		<h1>Online balances</h1>

<?php
	//	if there are no records entered, show a maintenance message
	if (getTotalNumberAccountBalaceRecords() <= 0) {
?>
		<p>The system is currently down for scheduled maintenance. Please come back in a couple of hours.</p>
<?php
	//	Only allow balance enquiries for logged in users.
	}
	else if (isset($_SESSION['userID'])) {
?>
		<p>All Account balances were last updated on: <strong><?php print date("d/m/Y", $mostRecentImport->fileDateCreated);?> at <?php print date("H:i a", $mostRecentImport->fileDateCreated);?></strong>.</p>
		<form id="balances" action="http://<?php print $DOMAIN; ?>/site/scripts/balances.php" method="post" class="basic_form">
			<fieldset>
				<legend>Account details</legend>
<?php
		if (isset($_POST['find']) && $accountBalance == -1) {
?>
				<h2 class="warning">Please enter a valid Reference number</h2>
<?php
		}
					
		if (isset($_POST['find']) && $accountBalance != -1) {
			$relevantProducts = getAllProductsWithFundCode($accountBalance->fund, true);
			
			$countAvailableProducts = 0;
			if (sizeof($relevantProducts) > 0) {
				foreach ($relevantProducts as $prod) {
					if ($prod->type == TYPE_BILLED) {
						$countAvailableProducts++;
					}
				}
			}
?>
				<input type="hidden" name="referenceNumber" value="" />
				<p>Reference Number: <strong><?php print $referenceNumber;?></strong></p>
				<p>Balance <?php if ($accountBalance->sign == '+') print 'Outstanding'; else print 'in Credit';?>: <strong>&pound;<?php print $accountBalance->balance;?></strong></p>
<?php
			if ($countAvailableProducts == 1) { 
?>
				<p><strong><a href="http://<?php print $DOMAIN; ?>/site/scripts/payment_details_1.php?productID=<?php print $relevantProducts[0]->id;?>&amp;referenceNumber=<?php print $referenceNumber;?>">Make a payment</a></strong></p>
<?php
			}
?>
				<p class="smallbuttons"><input type="submit" class="button" name="change" id="change" value="Change" /></p>
<?php
		}
		else {
?>
				<p><label for="referenceNumber">Reference Number (required)</label><input class="field" type="text" id="referenceNumber" name="referenceNumber" value="<?php print $referenceNumber;?>" size="15" maxlength="14" /></p>
				<p><input type="submit" class="button" name="find" id="find" value="Find" /></p>
<?php
		}
?>
			</fieldset>
		</form>
<?php
	}
	else {
?>
		<p><strong>Please note:</strong> You must be registered and logged in to make a balance enquiry.</p>
<?php
	}
?>

		<div class="fromEditor">
			<?php print $homepage->content;?>
		</div>
		
		<!-- The Contact box -->
		<?php include("../includes/contactbox.php"); ?>
	
<!-- ###################################### -->
<?php include("../includes/closing.php"); ?>
</body>
</html>