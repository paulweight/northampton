<?php
    include_once("utilities/JaduStatus.php");
	include_once("JaduConstants.php");
	include_once("JaduStyles.php");
	include_once("ePayments/JaduEpaymentsFinanceDetails.php");
	include_once("ePayments/JaduEpaymentsConfiguration.php");
	include_once("ePayments/JaduEpaymentsOrders.php");
	include_once("ePayments/JaduEpaymentsOrderItems.php");
	include_once("ePayments/JaduEpaymentsOrderPayees.php");
	include_once("ePayments/JaduEpaymentsPlanning.php");

	$PLANNING_MODE = false;
	$XFORMS_MODE = false;

	//deterimine if we are in planning mode
	if ($_GET['planning'] == 'true') {
		$PLANNING_MODE=true;	
		//validate the query string
		$error = verifyRequest($_GET);
		if ($error != "") {
			header('Location: '.PLANNING_PORTAL_LOGIN.'?error='.$error.'&callbackURL='.$_GET['callbackURL'].'&paymentRef='.$_GET['paymentRef']);
			exit;
		}
	}
	
	if (isset($_GET['userFormID']) || isset($_POST['userFormID'])) {
		include_once("xforms2/JaduXFormsUserForms.php");
						
		$userForm = getXFormsUserForm($userFormID);
		if ($_SESSION['userID'] == $userForm->userID) {
			$XFORMS_MODE = true;
		}
		else {
			header("Location: payments.php");
			exit;
		}			
	}


	//	A function to output the label, and highlight the lable if this field
	//	has been found to have any errors, either from PSP or from our validation
	function showField ($label, $field)
	{
		global $error_array, $response_error_array;
		if ($error_array[$field]) {
			print '<em>! '. $label . '</em>';
		} else {
			print $label;
		}
	}
	
	//	Do some checking on the orderID, to amke sure have come from a different 
	//	pages GET or this pages POST.
	$orderID = -1;
	if (isset($_POST['orderID']))
		$orderID = $_POST['orderID'];
	else if (isset($_GET['orderID']))
		$orderID = $_GET['orderID'];	
	
	//	Get user details if we have a logged in user.
	if (isset($_SESSION['userID']) && $orderID > 0) {
		$user = getUser($_SESSION['userID']);
		$order = getOrder($orderID);
		$orderItems = getAllItemsForOrder($order->id);
		
		//	Bounce this naughty user out!
		if ($order->userID != $user->id) {
			header("Location: payments.php");
			exit;
		}
		
		$payee = getPayeeForOrder($order->id);
		if (!isset($_POST['savePayee'])) {
			if ($payee == null) {
				$_POST['title'] = $user->salutation;
				$_POST['forename'] = $user->forename;
				$_POST['surname'] = $user->surname;
				
				//	In the user dtaabses, address is stored as one large entity.
				//	Split as best as can.
				$addressStrings = split(",\n", $user->address);
				$_POST['address1'] = trim($addressStrings[0]);
				$_POST['address2'] = trim($addressStrings[1]);
				
				$_POST['city'] = $user->city;
				$_POST['postcode'] = $user->postcode;
				$_POST['emailAddress'] = $user->email;
				$_POST['telephone'] = $user->telephone;
				$_POST['fax'] = $user->fax;
			}
			else {
				$_POST['title'] = $payee->title;
				$_POST['forename'] = $payee->forename;
				$_POST['surname'] = $payee->surname;
				$_POST['address1'] = $payee->address1;
				$_POST['address2'] = $payee->address2;
				$_POST['city'] = $payee->city;
				$_POST['postcode'] = $payee->postcode;
				$_POST['emailAddress'] = $payee->email;				
				$_POST['telephone'] = $payee->telephone;
				$_POST['fax'] = $payee->fax;
				$_POST['comments'] = $payee->comments;
			}
		}
		else if (isset($_POST['savePayee'])) {
			$error_array = array();
			if ($payee == null) {
				$payee = new OrderPayee();
				$payee->orderID = $order->id;
				$payee->title = $_POST['title'];
				$payee->forename = $_POST['forename'];
				$payee->surname = $_POST['surname'];
				$payee->address1 = $_POST['address1'];
				$payee->address2 = $_POST['address2'];
				$payee->city = $_POST['city'];
				$payee->postcode = $_POST['postcode'];
				$payee->email = $_POST['emailAddress'];
				$payee->telephone = $_POST['telephone'];
				$payee->fax = $_POST['fax'];
				$payee->comments = $_POST['comments'];
				
				$error_array = validateOrderPayee($payee);
				if (sizeof($error_array) == 0) {
					newOrderPayee ($payee);

					//	Redirect to card details page avec planning details					
					if ($PLANNING_MODE) {
						//build the planning query string
						foreach ($EPAYMENTS_PLANNING_REQUEST AS $key=>$value) {
							$action .= '&'.$key.'='.$_GET[$key];
						} 
						header("Location: ".EPAYMENTS_SECURE_PAYMENT_AREA."payments_provider.php?orderID=$order->id&session_id=".session_id()."&planning=true".$action);
						exit;
					}
					else if ($XFORMS_MODE) {
						//	Redirect to card details page
						header("Location: ".EPAYMENTS_SECURE_PAYMENT_AREA."payments_provider.php?orderID=$order->id&userFormID=".$_POST['userFormID']."&session_id=".session_id());
						exit;
					}
					else {
						//	Redirect to card details page
						header("Location: ".EPAYMENTS_SECURE_PAYMENT_AREA."payments_provider.php?orderID=$order->id&session_id=".session_id());
						exit;
					}
				}
			}
			else {
				$payee->title = $_POST['title'];
				$payee->forename = $_POST['forename'];
				$payee->surname = $_POST['surname'];
				$payee->address1 = $_POST['address1'];
				$payee->address2 = $_POST['address2'];
				$payee->city = $_POST['city'];
				$payee->postcode = $_POST['postcode'];
				$payee->email = $_POST['emailAddress'];
				$payee->telephone = $_POST['telephone'];
				$payee->fax = $_POST['fax'];		
				$payee->comments = $_POST['comments'];

				$error_array = validateOrderPayee($payee);
				if (sizeof($error_array) == 0) {
					updateOrderPayee($payee);
					//	Redirect to card details page avec planning details
					if ($PLANNING_MODE) {
						//build the planning query string
						foreach ($EPAYMENTS_PLANNING_REQUEST AS $key=>$value) {
							$action .= '&'.$key.'='.$_GET[$key];
						} 
						header("Location: ".EPAYMENTS_SECURE_PAYMENT_AREA."payments_provider.php?orderID=$order->id&session_id=".session_id()."&planning=true".$action);
						exit;
					}
					else if ($XFORMS_MODE) {
						//	Redirect to card details page
						header("Location: ".EPAYMENTS_SECURE_PAYMENT_AREA."payments_provider.php?orderID=$order->id&userFormID=".$_POST['userFormID']."&session_id=".session_id());
						exit;
					}
					else {
						//	Redirect to card details page
						header("Location: ".EPAYMENTS_SECURE_PAYMENT_AREA."payments_provider.php?orderID=$order->id&session_id=".session_id());
						exit;
					}
				}
			}
		}		
	} 
	else {
		//	throw the user out to the payments homepage.
		header("Location: payments.php");
		exit;
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Payee details - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
<?php
	//if ($PLANNING_MODE || $XFORMS_MODE) {
?>
		<!-- <link rel="stylesheet" type="text/css" href="<?php print EPAYMENTS_SECURE_PAYMENT_AREA;?>payment.css" media="screen" /> -->
<?php
	//} else {
?>
	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY.$STYLESHEET;?>" media="screen" />
<?php
	//}
?>
	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY;?>print.css" media="print" />
	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY;?>handheld.css" media="handheld" />
	<link rel="Shortcut Icon" type="image/x-icon" href="http://<?php print $DOMAIN;?>/site/favicon.ico" />
	<link rel="ToC" href="http://<?php print $DOMAIN;?>/site/scripts/site_map.php" />
	<!-- general metadata -->
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="content-language" content="en" />
	<meta name="generator" content="http://www.jadu.co.uk" />
	<meta name="robots" content="index,follow" />
	<meta name="revisit-after" content="2 days" />
	<meta name="Author-Template" content="Jadu CSS design" />
	<meta name="Publisher" content="<?php print METADATA_PUBLISHER;?>" />
	<meta name="Publisher-Email" content="<?php print METADATA_PUBLISHER_EMAIL;?>" />
	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments - Services Basket, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments - Services Basket" />
	<meta name="Coverage" content="Worldwide" />
	<!-- ICRA PICS label -->
	<?php print METADATA_ICRA_LABEL;?>
	<!-- Dublin Core Metadata -->
	<meta name="DC.creator" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>" />
	<meta name="DC.date.created" lang="en" content="01-06-2004" />
	<meta name="DC.format" lang="en" content="text/html" />
	<meta name="DC.language" content="en" />
	<meta name="DC.publisher" lang="en" content="<?php print METADATA_PUBLISHER;?>" />
	<meta name="DC.rights.copyright" lang="en" content="<?php print METADATA_RIGHTS;?>" />
	<meta name="DC.coverage" lang="en" content="<?php print METADATA_COVERAGE;?>" />
	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments - Services Basket" />
	<meta name="DC.identifier" content="http://<?php print $DOMAIN.$_SERVER['PHP_SELF'];?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments - Services Basket" />
	<!-- eGMS Metadata -->
	<meta name="eGMS.status" lang="en" content="<?php print METADATA_STATUS;?>" />
	<meta name="eGMS.subject.category" lang="en" scheme="GCL" content="Local government" />
	<meta name="eGMS.subject.keyword" lang="en" scheme="LGCL" content="Council, government and democracy" />
	<meta name="eGMS.accessibility" scheme="WCAG" content="<?php print METADATA_ACCESSIBILITY;?>" />
	<script>
			
	function confirmCancel(address, url) 
	{
		var answer = confirm("Are you sure you wish to cancel your payment? Click OK to confirm, or Cancel to stay on this page")
		if (answer) {
			window.location = url+"?redirect="+address;
		} else {
			return;
		}
	}
			
	</script>
</head>
<body>
<?php include("../includes/opening.php"); ?>
<!-- #################################### -->
<!-- ########### MAIN CONTENT ########### -->
	
		<h1>Payee details</h1>
		
<?php
	$testMode = getEpaymentsConfigurationValueForVariable(EPAYMENTS_TEST_MODE);
	if ($testMode == 1) {
		print "<h2 class=\"warning\">Note: Account currently in test mode.</h2>";
	}
	unset($testMode);

	if (isset($_POST['savePayee']) && sizeof($error_array) > 0) {
?>
		<h2 class="warning">Invalid details supplied. Please try again</h2>
<?php
	}
?>

		<p><strong>Please Note:</strong> All personal details that you provide below are encrypted for your privacy.</p>
				
	<?php
		//send over the fact we are in planning mode in the form request and the now well travelled query string
		if ($PLANNING_MODE) {
			$action = '?planning=true';
			//build the planning query string
			foreach ($EPAYMENTS_PLANNING_REQUEST AS $key=>$value) {
				$action .= '&'.$key.'='.$_GET[$key];
			} 
		}
	?>				
		<form action="./payments_payee_details.php<? print $action; ?>" class="basic_form" method="post">
			<input type="hidden" id="orderID" name="orderID" value="<?php print $orderID;?>" />
			
	<?php
		if ($XFORMS_MODE) {
	?>
			<input type="hidden" id="userFormID" name="userFormID" value="<?php print $userFormID;?>" />
	<?php
		}
	?>
			
			<fieldset>			
				<legend>Payee details</legend>
				<p>
					<label for="title"><?php showField('Title (required)','title');?></label>
					<select id="title" name="title" class="field">
						<option value="Mr" <?php if ($_POST['title']=="Mr") print "selected";?>>Mr</option>
						<option value="Miss" <?php if ($_POST['title']=="Miss") print "selected";?>>Miss</option>
						<option value="Mrs" <?php if ($_POST['title']=="Mrs") print "selected";?>>Mrs</option>
						<option value="Ms" <?php if ($_POST['title']=="Ms") print "selected";?>>Ms</option>
						<option value="Dr" <?php if ($_POST['title']=="Dr") print "selected";?>>Dr</option>
					</select>
				</p>
				
				<p>
					<label for="forename"><?php showField('Forename (required)','forename');?></label>
					<input type="text" id="forename" name="forename" value="<?php print $_POST['forename'];?>" class="field" />
				</p>
				
				<p>
					<label for="surname"><?php showField('Surname (required)','surname');?></label>
					<input type="text" id="surname" name="surname" value="<?php print $_POST['surname'];?>" class="field" />
				</p>
				
				<p>
					<label for="address1"><?php showField('Address 1 (required)','address1');?></label>
					<input type="text" id="address1" name="address1" value="<?php print $_POST['address1'];?>" class="field" />
				</p>
				
				<p>
					<label for="address2"><?php showField('Address 2','address2');?></label>
					<input type="text" id="address2" name="address2" value="<?php print $_POST['address2'];?>" class="field" />
				</p>
				
				<p>
					<label for="city"><?php showField('City (required)','city');?></label>
					<input type="text" id="city" name="city" value="<?php print $_POST['city'];?>" class="field" />
				</p>
				
				<p>
					<label for="postcode"><?php showField('Postcode (required)','postcode');?></label>
					<input type="text" id="postcode" name="postcode" value="<?php print $_POST['postcode'];?>" class="field" />
				</p>
				
				<p>
					<label for="emailAddress"><?php showField('Email address (required)','email');?></label>
					<input type="text" id="emailAddress" name="emailAddress" value="<?php print $_POST['emailAddress'];?>" class="field" />
				</p>
				
				<p>
					<label for="telephone"><?php showField('Telephone Number (required)','telephone');?></label>
					<input type="text" id="telephone" name="telephone" value="<?php print $_POST['telephone'];?>" class="field" />
				</p>
				
				<p>
					<label for="fax"><?php showField('Fax Number','fax');?></label>
					<input type="text" id="fax" name="fax" value="<?php print $_POST['fax'];?>" class="field" />
				</p>
				
				<p>
					<label for="comments"><?php showField('Comments','comments');?></label>
					<textarea id="comments" name="comments" class="field"><?php print $_POST['comments'];?></textarea>
				</p>
				
				<p>
					<input type="submit" class="button" id="savePayee" name="savePayee" value="Continue" />
				</p>
				
			<?php
				if ($PLANNING_MODE) {
			?>
				<!-- START cancel button -->
				
			<?php
				$cancelURL = $_GET['callbackURL'].'?success=C&paymentRef='.$_GET['paymentRef']; 
				$cancelURL = urlencode($cancelURL);
			?>
			
			<p><input type="button" class="button"  name="cancel" value="Cancel payment" onClick="return confirmCancel(<?php print "'".$cancelURL."','".PLANNING_CANCEL_REDIRECT."'" ?>)" /></p>
			<!-- END cancel button -->
			<?php
				}
			?>

			</fieldset>
		</form>
	
<!-- ###################################### -->
<?php include("../includes/closing.php"); ?>
</body>
</html>