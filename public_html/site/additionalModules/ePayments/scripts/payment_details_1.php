<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("ePayments/JaduEpaymentsProducts.php");
	include_once("ePayments/JaduEpaymentsTax.php");
	include_once("ePayments/JaduEpaymentsCheckDigits.php");
	include_once("ePayments/JaduEpaymentsProductMappings.php");
	include_once("ePayments/JaduEpaymentsOrders.php");
	include_once("ePayments/JaduEpaymentsOrderItems.php");
	include_once("ePayments/JaduEpaymentsForms.php");
	include_once("ePayments/JaduEpaymentsPlanning.php");
	include_once("xforms2/JaduXFormsFormEpaymentsIntegration.php");


	$PLANNING_MODE = false;
	$XFORMS_MODE = false;
	
	
	//	Get user details if we have a logged in user.
	if (isset($_SESSION['userID'])) {
		$user = getUser($_SESSION['userID']);
	}
	
	//	If We are coming in form a url used form the basket.
	if (isset($_GET['orderItemID']) || (isset($_POST['submit']) && isset($_POST['orderItemID']))) {
		$orderItem = getOrderItem($orderItemID);
		$order = getOrder($orderItem->orderID);
		$productID = $orderItem->productID;
		
		//	Bounce this naughty user out!
		if ($order->userID != $user->id) {
			header("Location: payments.php");
			exit;
		}
		
		$savedAmount = $orderItem->netAmount;
		$savedRefNumber = $orderItem->referenceNumber;
	}
	
	if (isset($productID) && ereg("^[0-9]+$", $productID)) {
		$product = getProduct($productID, true);
		
		//if we are dealing with the product planning which is neither live nor not live
		if ($productID == EPAYMENTS_PLANNING_PRODUCT_ID) {
			$product = getPlanningProduct($productID);
				$error = verifyRequest($_GET);
				if ($error != "") {
					header('Location: '.PLANNING_PORTAL_LOGIN.'?error='.$error.'&callbackURL='.$_GET['callbackURL'].'&paymentRef='.$_GET['paymentRef']);
					exit;
				}	
				$amount = $_POST['amount'] = $_GET['amountDue'];
				$savedRefNumber = $_POST['refNumber'] = $_GET['paymentRef'];
			//set a flag to tell the rest of the script we are in planning mode
			$PLANNING_MODE = true;
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
		else {
			$xformMappings = getAllXFormsFormEpaymentsIntegrations ('productID', $product->id);
			if (sizeof($xformMappings) > 0) {
				header("Location: payments.php");
				exit;
			}
		}
		
		if ($product != -1) {
			$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
			$categoryID = getFirstCategoryIDForItemOfType (EPAYMENTS_PRODUCT_CATEGORIES_TABLE, $productID, "LGCL");
			$currentCategory = $lgclList->getCategory($categoryID);
			$dirTree = $lgclList->getFullPath($categoryID);
			
			if (isset($_POST['remove']) || isset($_GET['remove'])) {

				include_once("ePayments/JaduEpaymentsUserForms.php");
				include_once("ePayments/JaduEpaymentsUserFormsAnswers.php");

				$userForm = getEpaymentsUserForm($orderItem->userProductFormID);
				if ($userForm != null) {
					deleteEpaymentsUserForm($userForm->id);
					deleteAllEpaymentsFormQuestionAnswersForUserForm($userForm->id);
				}
				deleteOrderItem($orderItemID);
				
				header("Location: payment_details_1.php?productID=$productID");
				exit;
			}
			else if (isset($_POST['submit']) && isset($_POST['amount'])) {
				
				if (!isset($savedAmount))
					$savedAmount = "0.00";
				if (!isset($savedRefNumber))
					$savedRefNumber = "";
				
				$testResult = false;
				$amountResult = false;
				
				if (isset($_POST['refNumber']) && $product->type == TYPE_BILLED) {

					//	Check digit validation
					$allProductToCheckDigits = getAllProductToCheckDigitsForProduct($product->id);
					if (sizeof($allProductToCheckDigits) > 0) {
						foreach ($allProductToCheckDigits as $pToCD) {
							$testResult = referenceNumberTest ($pToCD->checkDigitID, $_POST['refNumber'], false); // verbose flag off
							if ($testResult === true) {
								$savedRefNumber = $_POST['refNumber'];
								break;
							}
						}
					}
				}
				else {
					$testResult = true;
				}
				
				//	Amount validation
				if (preg_match("/^[0-9]+[.]?[0-9]{0,2}$/", $_POST['amount'])) {

					//	ensure it is correctly formatted now as they could enter '10', '10.' or '10.00'
					$savedAmount = number_format($_POST['amount'], 2, '.', '');

					if ($product->amountMode == AMOUNT_MODE_RANGE) {
						if ($savedAmount >= $product->minAmount && $savedAmount <= $product->maxAmount) {
							$amountResult = true;
						}
					}
					else {
						$amountResult = true;
					}
				}
				else {
					$savedAmount = $_POST['amount'];
				}
				
				//	If weve not had any problems, then store the relevant information into the DB, and progress a level
				if ($testResult === true && $amountResult === true) {

					$usersCompilingOrders = getUsersOrdersOfState ($_SESSION['userID'], ORDER_STATUS_INCOMPLETE);
					//filter out the one with planning in if it exists, so that we dont chuck a payment for something
					//non-planning like into its order basket, which should be singular
					//if we have a product thats not planning
					if ($productID != EPAYMENTS_PLANNING_PRODUCT_ID) {
						$filterOrders = array();
						foreach ($usersCompilingOrders AS $checkOrder) {
							$dismissItem = false;
							$checkItems = getAllItemsForOrder($checkOrder->id);
							foreach ($checkItems AS $checkItem) {
								if ($checkItem->productID == EPAYMENTS_PLANNING_PRODUCT_ID) {
									$dismissItem = true;
								}
							}	
							if (!$dismissItem) {
								$filterOrders[] = $checkOrder;
							}
						}
					}
				
					$usersCompilingOrders = $filterOrders;
					//dont add the order here if we are in planning mode, and therefore have a planning order to do
					if (sizeof($usersCompilingOrders) < 1 && !$PLANNING_MODE && !$XFORMS_MODE) {
						
						$order = new Order();
						$order->userID = $_SESSION['userID'];
						$order->status = ORDER_STATUS_INCOMPLETE;
						$orderID = newOrder($order);
						$order = getOrder($orderID);
					}
					else {
						$order = $usersCompilingOrders[0];
						$orderID = $order->id;
					}
					
					//////////////////	
								
					if ($PLANNING_MODE) {
						//if we are in planning mode, we need a seperate order, so that it lives outside the basket
						//first check that there isnt a order in progress for planning portal, before we create a new special one. 
						//for it. if there is, destroy it so that we dont end up with serveral incomplete orders.
						$checkOrders = getUsersOrdersOfState($_SESSION['userID'], ORDER_STATUS_INCOMPLETE);
						foreach ($checkOrders AS $checkOrder) {
							$checkItems = getAllItemsForOrder($checkOrder->id);
							foreach ($checkItems AS $checkItem) {
								if ($checkItem->productID == EPAYMENTS_PLANNING_PRODUCT_ID) {
									//destroy this order and associated items
									deleteOrder($checkOrder->id);
									deleteAllItemsInOrder($checkOrder->id);
									break;
								}
							}
						}
	
						//if we are in planning mode, we need a seperate order, so that it lives outside the basket
						//so create a seperate one here. The product is added below this.
						$order = new Order();
						$order->userID = $_SESSION['userID'];
						$order->status = ORDER_STATUS_INCOMPLETE;
						$orderID = newOrder($order);
						$order = getOrder($orderID);
						
					}
					else if ($XFORMS_MODE) {

						include_once("xforms2/JaduXFormsUserForms.php");
						include_once("xforms2/JaduXFormsFormBranchingRoutes.php");
						include_once("xforms2/JaduXFormsFormEpaymentsIntegration.php");
				
						$userForm = getXFormsUserForm($userFormID);
//						$branchingRoute = determineXFormsFormBranchingRouteFromPageOrder ($userForm->formID, $userForm->pagePath);
						$branchingRoute = determineXFormsFormBranchingRouteFromUserPath ($userForm->formID, $userForm->pagePath);

						if ($branchingRoute != -1) {
							$epaymentMapping = getXFormsFormEpaymentsIntegration('routeID', $branchingRoute->id);
							if ($epaymentMapping != -1) {

								//if we are in planning mode, we need a seperate order, so that it lives outside the basket
								//first check that there isnt a order in progress for planning portal, before we create a new special one. 
								//for it. if there is, destroy it so that we dont end up with serveral incomplete orders.
								$checkOrders = getUsersOrdersOfState($_SESSION['userID'], ORDER_STATUS_INCOMPLETE);
								foreach ($checkOrders AS $checkOrder) {
									$checkItems = getAllItemsForOrder($checkOrder->id);
									foreach ($checkItems AS $checkItem) {
										if ($checkItem->productID == $epaymentMapping->productID) {
											//destroy this order and associated items
											deleteOrder($checkOrder->id);
											deleteAllItemsInOrder($checkOrder->id);
											break;
										}
									}
								}
			
								//if we are in planning mode, we need a seperate order, so that it lives outside the basket
								//so create a seperate one here. The product is added below this.
								$order = new Order();
								$order->userID = $_SESSION['userID'];
								$order->status = ORDER_STATUS_INCOMPLETE;
								$orderID = newOrder($order);
								$order = getOrder($orderID);
							}
						}
					}
					
					/////////////////
				
										
					//	Deal with The order Item object creation / update
					$isNewOrderItem = true;
					$allOrderitems = getAllItemsForOrder ($orderID);
					if (sizeof($allOrderitems) > 0) {
						foreach ($allOrderitems as $item) {
							if ($item->productID == $productID && $item->referenceNumber == $savedRefNumber) {
								$isNewOrderItem = false;
								$orderItemID = $item->id;
							}
						}
					}
					
					if ($isNewOrderItem === true) {
						//	Deal with the new OrderItem
						$orderItem = new OrderItem();
						$orderItem->orderID = $orderID;
						$orderItem->productID = $productID;
						$orderItem->quantity = 1;
						$orderItem->referenceNumber = $savedRefNumber;
						$orderItem->netAmount = $savedAmount;
						$orderItem->grossAmount = $savedAmount;
						
						$orderItemID = newOrderItem($orderItem);
					}
					else {
						$orderItem->referenceNumber = $savedRefNumber;
						$orderItem->netAmount = $savedAmount;
						$orderItem->grossAmount = $savedAmount;

						updateOrderItem($orderItem);
					}
					
					//	Decide where we need to go next
					if ($product->productFormID > 0) {
						header("Location: payment_details_2.php?orderItemID=$orderItemID");
						exit;
					}
					else {
						//build the request if in planning mode
						if ($PLANNING_MODE) {
							foreach ($EPAYMENTS_PLANNING_REQUEST AS $key=>$value) {
								$action .= '&'.$key.'='.$_GET[$key];
							} 
							//redirect straight to payee details page as we have no need for a basket
							header("Location: payments_payee_details.php?orderID=$orderID&planning=true".$action);
							exit;
						}
						//	If its an XForms integrated ePayment - go straight to payee details
						else if ($XFORMS_MODE) {
							//redirect straight to payee details page as we have no need for a basket
							header("Location: payments_payee_details.php?orderID=$orderID&userFormID=".$_POST['userFormID']);
							exit;
						}
						else {
							//go to the basket if we are not in planning mode
							header("Location: payments_basket.php?orderID=$orderID".$action);
							exit;
						}
					}
				}
			}
			else if (isset($_GET['referenceNumber'])) {
				$savedRefNumber = $_GET['referenceNumber'];
			}
		}
		else {
			//	throw the user out to the payments homepage.
			header("Location: payments.php");
			exit;
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
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?>, online payments, <?php print $product->title;?></title>
	<?php include("../includes/meta.php"); ?>
	
	<!-- general metadata -->
	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments" />

	<!-- Dublin Core Metadata -->
	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments" />
	
	<!-- IPSV / LGNL Metadata -->
	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council and democracy" />

	<script type="text/javascript">
		function confirmCancel(address, url) 
		{
			if (confirm("Are you sure you wish to cancel your payment? Click OK to confirm, or Cancel to stay on this page")) {
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
	<div id="middle">
		
		<h1><?php print $product->title;?></h1>
				
<?php
	if (!empty($product->description)) {
?>				
		<p><?php print nl2br($product->description);?></p>
				
<?php
	}

	//	Can only view the form if we are dealing with a logged in user.
	if (isset($_SESSION['userID'])) {
		if (isset($_POST['submit'])){
			if ($testResult === false) {
				if (empty($_POST['refNumber'])) {
	?>
		<h2 class="warning">Please enter your Reference number</h2>
	<?php
				} else {
	?>
		<h2 class="warning">The Reference number <?php print $_POST['refNumber'];?> was not recognised. Please try again</h2>
	<?php
				}
			}
			else if ($amountResult === false) {
				if (empty($_POST['amount'])) {
	?>
		<h2 class="warning">Please enter the amount you wish to pay</h2>
	<?php
				} else {
	?>
		<h2 class="warning">The amount you have entered <?php print $_POST['amount'];?> is not valid. Please try again</h2>
	<?php
				}
			}
		}

		if (!$PLANNING_MODE && !$XFORMS_MODE) {
			print '<p class="first"><strong>Please note:</strong> All fields provided are required.</p>';
		}
	?>

	<?php
		//add the query string for a planning product to the end of the form action, if we are in planning mode
		if ($PLANNING_MODE) {
			$action = '?';
			foreach ($EPAYMENTS_PLANNING_REQUEST AS $key=>$value) {
				$action .= $key.'='.$_GET[$key].'&';
			} 				
		}
	?>		
									
		<form name="details" action="payment_details_1.php<?php print $action; ?>" class="xform" method="post">
			<input type="hidden" name="productID" value="<?php print $productID;?>" />

		<?php
			$taxMessage = '';
			if ($product->amountMode == AMOUNT_MODE_FIXED) { 
				$tax = getTax($product->taxID);
				if ($product->taxMode == TAX_MODE_EXCLUDING)
					$taxMessage = ' (exc tax at ' . $tax->rate*100 . "%)";
				else if ($product->taxMode == TAX_MODE_INCLUDING)
					$taxMessage = ' (inc tax at ' . $tax->rate*100 . "%)";							
			}
			
			if (isset($_GET['orderItemID']) || (isset($_POST['submit']) && isset($_POST['orderItemID']))) {
		?>
			<input type="hidden" name="orderItemID" value="<?php print $orderItemID;?>" />
		<?php
			}
			
			if (isset($_GET['userFormID'])) {
		?>
			<input type="hidden" name="userFormID" value="<?php print $userFormID;?>" />
		<?php
			}
		?>
					
			<fieldset>
				<legend>Details</legend>
				<?php if ($product->type == TYPE_BILLED || $PLANNING_MODE == true) { ?>
				
				<p>
					<label for="refNumber"><?php if ((isset($_POST['submit']) && $testResult === false)) { print "<strong>! Reference number:</strong>"; } else { print "Reference number:"; } ?></label><? if ($PLANNING_MODE == true) { ?> <? print $savedRefNumber ?> <input type='hidden' name='refNumber' value="<? print $savedRefNumber ?>"/> <? } else { ?><input type="text" name="refNumber" id="refNumber" class="xfields" value="<?php print $savedRefNumber;?>" /><? } ?>
				</p>

				<?php } ?>
				<p>
					<label for="amount"><?php if (isset($_POST['submit']) && $testResult === true && $amountResult === false) { print "<em>! Amount to pay &pound;:</em>"; } else { print "Amount to pay:"; } ?></label>
					<?php if ($product->amountMode == AMOUNT_MODE_FIXED) { ?><em>&pound;<?php print $product->setAmount . $taxMessage; ?></em><input type="hidden" name="amount" id="amount" value="<?php print $product->setAmount;?>" /><?php } else if ($PLANNING_MODE == true) { ?> <?php print $amount ?><input type="hidden" name="amount" id="amount" value="<?php print $amount;?>" /><?php } else { ?> <strong class="pounds">&pound;</strong><input type="text" name="amount" id="amount" class="xfields" value="<?php print $savedAmount;?>" /><?php } ?>
				</p>

				<p><input type="submit" class="button" name="submit" value="Continue" />
				<?php if (isset($_GET['orderItemID']) || (isset($_POST['submit']) && isset($_POST['orderItemID']))) { ?>
				<input class="dangerbutton" type="submit"  name="remove" value="Remove" />
				</p><?php } ?> 


		<?php
			if ($PLANNING_MODE) {
		?>
			<!-- START cancel button -->
			<span id="cancelPlanning">
			<?php $cancelURL = $_GET['callbackURL'].'?success=C&paymentRef='.$_GET['paymentRef']; 
			   $cancelURL = urlencode($cancelURL);
			?>
			<input type="button" class="button" name="cancel" value="Cancel Payment" onClick="confirmCancel(<?php print "'".$cancelURL."','".PLANNING_CANCEL_REDIRECT."'" ?>)" />
			</span>
			<!-- END cancel button -->
		<?php
			}
		?>
			</fieldset>
		</form>


		<?php
			} else {
		?>
				
		<h2 class="warning">You must be registered and logged in to proceed any further</h2>
				
		<?php
			}
		?>


	</div><!-- End of Middle -->

	<div id="secondarycontent">
	<?php include("../includes/rightcolumn_payments.php"); ?>
	</div>
	
<!-- ###################################### -->
<?php include("../includes/closing.php"); ?>
</body>
</html>