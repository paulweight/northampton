<?php
	include_once("utilities/JaduStatus.php");
    session_start();
    
    if (isset($_SESSION['stage'])) {	
		if ($_SESSION['stage'] == 0 || $_SESSION['stage'] == 1 || $_SESSION['stage'] == 2) {
			$_SESSION['stage'] = 1;
		}
		else {
			header("Location: retail_products.php");
			exit();
		}
	}
	else {
		header("Location: retail_products.php");
		exit();
	}
    
    include_once("JaduStyles.php");
	//include_once("retail/JaduRetailCountries.php");
	include_once("retail/JaduRetailSelectedCountries.php");
	include_once($HOME."/site/includes/retail/retail_delivery_address_cookie_functions.php");

	//	If the cookie exists but for someone elses details then destroy the old one and create new
	$deliveryAddress = new DeliveryAddress();

	if (isset($_POST['continue'])) {

		$deliveryAddress->forename = trim($_POST['forename']);
		$deliveryAddress->surname = trim($_POST['surname']);
		$deliveryAddress->emailAddress = trim($_POST['emailAddress']);
		$deliveryAddress->address = trim($_POST['address']);
		$deliveryAddress->county = trim($_POST['county']);
		$deliveryAddress->postcode = strtoupper(trim($_POST['postcode']));
		$deliveryAddress->country = trim($_POST['country']);
		$deliveryAddress->telephone = str_replace(array('(',')','[',']','_','-','.',' ',','), '', $_POST['telephone']);
		$deliveryAddress->fax = trim($_POST['fax']);
		$deliveryAddress->useDeliveryAsInvoice = ($_POST['useDeliveryAsInvoice'] == '1') ? true : false;
		$deliveryAddress->invoiceName = trim($_POST['invoiceName']);
		$deliveryAddress->invoiceAddress = trim($_POST['invoiceAddress']);
		$deliveryAddress->invoiceTown = trim($_POST['invoiceTown']);
		$deliveryAddress->invoiceCounty = trim($_POST['invoiceCounty']);
		$deliveryAddress->invoicePostcode = trim($_POST['invoicePostcode']);
		$deliveryAddress->invoiceCountry = trim($_POST['invoiceCountry']);

		$missingFields = $deliveryAddress->validate();

		$deliveryAddress->setTheDeliveryCookie();

		if (sizeof($missingFields) == 0) {
			header("Location: $PROTOCOL$DOMAIN/site/scripts/retail_order_delivery_options.php");
			exit();
		} 
	}

	if (isset($_POST['back'])) {
		header("Location: http://$DOMAIN/site/scripts/retail_basket.php");
		exit();
	}

	$allCountries = getAllSelectedCountries();
	
	$breadcrumb = 'retailOrderDestination';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Delivery details - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="shop, commerce, basket, products, buy, gifts, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> shopping" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> shopping" />
	<meta name="DC.description" lang="en" content="The <?php print METADATA_GENERIC_COUNCIL_NAME;?> shopping" />
	
	<script type="text/javascript">

		function checkInvoiceAddressSelected()
		{
			if (document.getElementById('useDeliveryAsInvoice').options[document.getElementById('useDeliveryAsInvoice').selectedIndex].value == "0") {
				//	Show the form
				document.getElementById('showHideInvoiceAddress').style.display = "block";
			}
			else {
				//	Hide the form
				document.getElementById('showHideInvoiceAddress').style.display = "none";
			}
		}

	</script>
	
</head>
<body class="retail">
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

			
	<p class="first">Please enter the address where all items within your order should be delivered.</p>
			
<?php
	if (sizeof($missingFields) > 0) { 
?>
		<h2 class="warning">Please ensure fields marked with a <strong>!</strong> are entered correctly.</h2>

<?php
	}
?>	

		<form action="http://<?php print $DOMAIN; ?>/site/scripts/retail_order_delivery_destination.php" name="mainForm" method="post" class="basic_form">
		
			<!-- <input type="hidden" name="useDeliveryAsInvoice" value="1" /> -->

			<fieldset>
			<!-- step 1 -->
			<legend>Confirm your details</legend>
			
			<p>
				<label for="forename">Forename:</label> <input type="text" id="forename" name="forename" value="<?php print $deliveryAddress->forename;?>" class="field" />
			</p>
			<p>
				<label for="surname">Surname:</label> <input type="text" id="surname" name="surname" value="<?php print $deliveryAddress->surname;?>" class="field" />
			</p>
			<p>
				<label for="emailAddress">Email Address:</label> <input type="text" name="emailAddress" id="emailAddress" value="<?php print $deliveryAddress->emailAddress;?>" class="field" />
			</p>
			</fieldset>

			<fieldset>
			<legend>The delivery address</legend>
			<p class="slim">This must be the same as the card holder address.</p>	

			<!-- address component -->
			<p>
				<label>
				<?php if ($missingFields['address']) print '<strong>! '; ?>
				Delivery Address
				<? if ($missingFields['address']) print '</strong>'; ?>
				<em>(required)</em>
				</label>
				<textarea name="address" tabindex="3" class="field" cols="2" rows="3"><?php print $deliveryAddress->address; ?></textarea>
			</p>
			<!-- END address component -->
					
			<!-- Town/city -->
			<p>
				<label>
				<?php if ($missingFields['county']) print '<strong>! '; ?>
				County
				<? if ($missingFields['county']) print '</strong>'; ?>
				<em>(required)</em>
				</label>
				<input type="text" name="county" tabindex="4" class="field" value="<?php print $deliveryAddress->county; ?>" />
			</p>
			<!-- END Town/city -->
			
			<!-- post code component -->
			<p>
				<label>
				<?php if ($missingFields['postcode']) print '<strong>! '; ?>
				Post code
				<? if ($missingFields['postcode']) print '</strong>'; ?>
				<em>(required)</em>
				</label>
				<input type="text" name="postcode" tabindex="5" class="field" value="<?php print $deliveryAddress->postcode; ?>" />
			</p>
			<!-- END post code component -->
		
				<!-- country component -->
				
<?php
				if (sizeof($allCountries) == 1) {
					print '<input type="hidden" name="country" value="' . $allCountries[0]->id . '" />';
					print '<div><label>We currently only deliver to </label>' . $allCountries[0]->title . '</div>';
				}
				else {
?>
				<p>
					<label>
					<?php if ($missingFields['country']) print '<strong>! '; ?>
					Country
					<? if ($missingFields['country']) print '</strong>'; ?>
					<em>(required)</em>
					</label>
					<select name="country" size="1" class="field">
<?php
					foreach($allCountries as $ctry) {
?>
						<option value="<?php print $ctry->id;?>" 
<?php 
							if ($ctry->id == $deliveryAddress->invoiceCountry) {
								print ' selected="selected"';
							}
							else if (empty($deliveryAddress->invoiceCountry) && $ctry->id == '242') {
								print ' selected="selected"';
							}
?> 
						
						><?php print $ctry->title; ?></option>
<?php
					}
?>
					</select>
				</p>
<?php
				}
?>
				<!-- END country component -->
				
				<!-- telephone component -->
				<p>
					<label>
					<?php if ($missingFields['telephone']) print '<strong>! '; ?>
					Telephone
					<? if ($missingFields['telephone']) print '</strong>'; ?>
					<em>(required)</em>
					</label>
					<input type="text" name="telephone" tabindex="6" class="field" value="<?php print $deliveryAddress->telephone;?>" />
				</p>
				<!-- END telephone component -->
					
				<!-- fax component -->
				<p>
					<label>Fax</label>
					<input type="text" name="fax" tabindex="7" class="field" value="<?php print $deliveryAddress->fax;?>" />
				</p>
				<!-- END fax component -->
		
					<div><label>
					<?php if ($missingFields['useDeliveryAsInvoice']) print '<strong>! '; ?>
					Use the delivery address as the invoice address?
					<? if ($missingFields['deliveryAddressAsInvoiceAddress']) print '</strong>'; ?>
					<em>(required)</em>
					</label>
						<select name="useDeliveryAsInvoice" id="useDeliveryAsInvoice" class="select" onChange="javascript:checkInvoiceAddressSelected();" tabindex="6">
							<option value="1" <?php if ($deliveryAddress->useDeliveryAsInvoice) print 'selected'; ?>>Yes</option>
							<option value="0" <?php if (!$deliveryAddress->useDeliveryAsInvoice) print 'selected'; ?>>No</option>
						</select>
					</div>

			</fieldset>
			
			<!-- ##### END delivery address form ##### -->
			
			<div id="showHideInvoiceAddress" name="showHideInvoiceAddress" style="<?php if ($deliveryAddress->useDeliveryAsInvoice == false) { print 'display:block;'; } else { print 'display:none;'; } ?>">

			<!-- ##### invoice address form ##### -->
			<fieldset>
			<legend>The invoice address</legend>
			    
		  			<!-- invoice name -->
					<p>
						<label>
						<?php if ($missingFields['invoiceName']) print '<strong>! '; ?>
						Name on invoice
						<?php if ($missingFields['invoiceName']) print '</strong>'; ?>
						<em>(required)</em>
						</label>
						<input type="text" name="invoiceName" tabindex="1" class="field" value="<?php print $deliveryAddress->invoiceName; ?>" /></p>
					<!-- END invoice name -->
					
					<!-- invoice address -->
					<p>
						<label>
						<?php if ($missingFields['invoiceAddress']) print '<strong>! '; ?>
						Invoice Address
						<?php if ($missingFields['invoiceAddress']) print '</strong>'; ?>
						<em>(required)</em>
						</label>
						<textarea name="invoiceAddress" tabindex="2" class="field" cols="2" rows="3"><?php print $deliveryAddress->invoiceAddress; ?></textarea></p>
					<!-- END invoice address -->
					
					<!-- invoice county -->
					<p>
						<label>
						<?php if ($missingFields['invoiceCounty']) print '<strong>! '; ?>
						County
						<?php if ($missingFields['invoiceCounty']) print '</strong>'; ?>
						<em>(required)</em>
						</label>
						<input type="text" name="invoiceCounty" tabindex="3" class="field" value="<?php print $deliveryAddress->invoiceCounty;?>" /></p>
					<!-- END invoice county -->
					
					<!-- invoice postcode -->
					<p>
						<label>
						<?php if ($missingFields['invoicePostcode']) print '<strong>! '; ?>
						Postcode
						<?php if ($missingFields['invoicePostcode']) print '</strong>'; ?> 
						<em>(required)</em>
						</label>
						<input type="text" name="invoicePostcode" tabindex="3" class="field" value="<?php print $deliveryAddress->invoicePostcode;?>" /></p>
					<!-- END invoice postcode -->

					<!-- invoice country -->
<?php
					if (sizeof($allCountries) == 1) {
						print '<input type="hidden" name="invoiceCountry" value="'.$allCountries[0]->id.'" />';
						print '<p><label>We currently only deliver to </label>'.$allCountries[0]->title.'</p>';
					}
					else {
?>
					<p>
						<label>
						<?php if ($missingFields['country']) print '<strong>! '; ?>
						Country
						<? if ($missingFields['country']) print '</strong>'; ?>
						<em>(required)</em>
						</label>
						<select name="invoiceCountry" size="1" class="field">
<?php
						foreach($allCountries as $ctry) {
?>
							<option value="<?php print $ctry->id;?>" 
<?php 
							if ($ctry->id == $deliveryAddress->invoiceCountry) {
								print ' selected="selected"';
							}
							else {
								if ($ctry->id == '242') {
									print ' selected="selected"';
								}
							}
?> 
							><?php print $ctry->title; ?></option>
<?php
						}
?>
						</select>
					</p>
<?php
					}
?>
				</fieldset>
			<!-- ##### END invoice address form ##### -->
			</div>
			
			<!-- ##### form buttons ##### -->
			<p class="text_align_center">
				<input type="submit" value="&laquo; Return to Basket" name="back" class="button" />
				<input type="submit" value="Proceed with Order &raquo;" name="continue" class="button go" />
			</p>
			<!-- ##### END form buttons ##### -->
				
				
		</form>

<!-- ###################################### -->
<?php include("../includes/closing_retail.php"); ?>