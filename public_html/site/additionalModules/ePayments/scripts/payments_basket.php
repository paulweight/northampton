<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("ePayments/JaduEpaymentsProducts.php");
	include_once("ePayments/JaduEpaymentsProductMappings.php");
	include_once("ePayments/JaduEpaymentsOrders.php");
	include_once("ePayments/JaduEpaymentsOrderItems.php");
	include_once("ePayments/JaduEpaymentsForms.php");
	include_once("ePayments/JaduEpaymentsFormsQuestions.php");
	include_once("ePayments/JaduEpaymentsUserForms.php");
	include_once("ePayments/JaduEpaymentsUserFormsAnswers.php");
	include_once("ePayments/JaduEpaymentsAccountBalances.php");
	include_once("ePayments/JaduEpaymentsPlanning.php");
//	include_once("egov/JaduXFormsFormComponents.php");
//	include_once("egov/JaduXFormsFormValidation.php");
	
	include_once("xforms2/JaduXFormsFormComponents.php");
	include_once("xforms2/JaduXFormsFormValidation.php");
	
	//	Get user details if we have a logged in user (which we should have).
	if (isset($_SESSION['userID']) && isset($_GET['orderID'])) {
		$user = getUser($_SESSION['userID']);
		$order = getOrder($_GET['orderID']);
		$orderItems = getAllItemsForOrder($_GET['orderID']);
		
		if ($order->userID != $user->id) {
			//	throw the attempted look at somebody else basket out to the payments homepage.
			header("Location: payments.php");
			exit;
		}
	}
	else {
		//	throw the user out to the payments homepage.
		header("Location: payments.php");
		exit;
	}
	
	//	Make sure that all forms are OK
	$totalAmount = 0.00;
	$incompleteForms = array();
	foreach ($orderItems as $orderItem) {
	
		$values_array = array();
		$error_array = array();
		$missing_array = array();
		
		$product = getProduct($orderItem->productID, true);
		
		if ($product != -1) {
			$totalAmount += $orderItem->grossAmount;
			
			if ($product->productFormID > 0) {
				$form = getProductForm($product->productFormID);
				$formQuestions = getAllProductFormQuestionsForForm($form->id);
				$userForm = getEpaymentsUserForm($orderItem->userProductFormID);
	
				foreach ($formQuestions as $question) {
					$component = getXFormsFormComponent($question->componentID);
	//				$field = $question->componentName;
					$userAnswer = getQuestionAnswerIfExists($userForm->id, $question->id, -1, -1);
		
					//	Get the value
					$value = "";
					if ($userAnswer != -1) {
						if (gettype($userAnswer) == "array") {
							$value = array();
							foreach ($userAnswer as $ua) {
								$value[] = $ua->answer;
							}
						} else {
							$value = $userAnswer->answer;
						}
					}
					$values_array[$question->id] = $value;
					
					//	Do the validation
					if ($question->validationID != -1) {
						$validation = getXFormsFormValidation($question->validationID);
						$method = $validation->method;
						
						if ($value == "" && $question->required == 1) {
							$missing_array[$question->id] = true;
						}
						else if ($value != "") {
							$result = $method($value);
							if ($result == false) 
								$error_array[$question->id] = true;
						}
					}
	//				else if (gettype($$field) == "array" && $question->required == 1 && sizeof($$field) == 0) {
	//					$missing_array[$question->id] = true;
	//				}
					else if ($value == "" && $question->required == 1) {
						$missing_array[$question->id] = true;
					}
				}
				
				if (sizeof($missing_array) > 0 || sizeof($error_array) > 0) {
					$incompleteForms[$userForm->id] = true;
				}
			}
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Services basket - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include("../includes/meta.php"); ?>
	
	<!-- general metadata -->
	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments" />

	<!-- Dublin Core Metadata -->
	<meta name="DC.title" lang="en" content="Services basket - <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments" />
	
	<!-- IPSV / LGNL Metadata -->
	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council and democracy" /></head>
</head>
<body>
<?php include("../includes/opening.php"); ?>
<!-- #################################### -->
<!-- ########### MAIN CONTENT ########### -->
	<!-- <div id="middle"> -->
	
		<h1>Services basket</h1>

	<?php				
		if (sizeof($incompleteForms) > 0) {
	?>
		<h2 class="warning">Please ammend the additional details of those items highlighted below</h2>
	<?php
		} else {
	?>
		<p>Please take this opportunity to <strong>review your services basket</strong>, before being transferred to our secure payment area, where your payment details will be collected.</p>

		<p>Your total order value must exceed &pound;0.00.</p>
	<?php
		}
	?>

		<p>You can <strong>pay multiple bills</strong> at any one time, simply return to the <a href="payments.php">payments list</a>, and enter the relevant details.</p>
				
	<?php
		foreach($orderItems as $orderItem) {
			$product = getProduct($orderItem->productID, true);
			//if we are dealing with the product planning which is neither live nor not live
			if ($orderItem->productID == EPAYMENTS_PLANNING_PRODUCT_ID) {
				$product = getPlanningProduct($orderItem->productID);
				$error = verifyRequest($_GET);
				if ($error != "") {
					$product=-1;	
				}
			}
			if ($product == -1) {
				$product = getProduct($orderItem->productID);
				$userForm = getEpaymentsUserForm($orderItem->userProductFormID);
				
				//	Do some cleaning up
				deleteOrderItem ($orderItem->id);
				if ($userForm != null) {
					deleteEpaymentsUserForm ($userForm->id);
					deleteAllEpaymentsFormQuestionAnswersForUserForm ($userForm->id);
				}
	?>
		
			<h2><?php print $product->title;?></h2>
			<p><strong>This item is not currently available. It has now been removed from your basket.</strong></p>

	<?php 
				unset($product);
			}
			else {
				$form = getProductForm($product->productFormID);
				$formQuestions = getAllProductFormQuestionsForForm($form->id);
				$userForm = getEpaymentsUserForm($orderItem->userProductFormID);
				
				if (strlen($orderItem->referenceNumber) > 0) {
					$accountBalance = getAccountBalanceBy('referenceNumber', $orderItem->referenceNumber);
				}
	?>
			
				
					
		<!-- Main Payment details review box -->
		<form name="mainDetails" action="payment_details_1.php" method="get" class="basic_form">
			<fieldset>
				<legend><?php print $product->title;?></legend>
					
				<input type="hidden" name="orderItemID" value="<?php print $orderItem->id;?>" />
						
				<?php if ($product->type == TYPE_BILLED) { ?>
				<p>Reference Number: <strong><?php print $orderItem->referenceNumber;?></strong></p>
				<?php } ?>
				
	<?php
				if (isset($accountBalance) && $accountBalance != -1) {
	?>
				<p>Balance <?php if ($accountBalance->sign == '+') print 'Outstanding'; else print 'in Credit';?>: <strong>&pound;<?php print $accountBalance->balance;?></strong>
					<?php if ($countAvailableProducts == 1 && $accountBalance->sign == '+') { ?> <a href="payment_details_1.php?productID=<?php print $relevantProducts[0]->id;?>">Pay this now</a><?php } ?>
				</p>
	<?php
				}
	?>
				<p>Amount to pay: <strong>&pound;<?php print $orderItem->grossAmount;?></strong></p>
				<p class="smallbuttons">
					<input class="button" type="submit" name="change" value="Change" />
					<input class="dangerbutton" type="submit" name="remove" value="Remove" />
				</p>
				
			</fieldset>
		</form>
		<!-- END Payment details review box -->


<?php
			if ($product->productFormID > 0) {
?>
		<!-- Additional Payment details review box -->
		<form name="additionalDetails" action="payment_details_2.php" method="get" class="basic_fom">
			<input type="hidden" name="orderItemID" value="<?php print $orderItem->id;?>" />
			<fieldset>
				<legend><?php print $form->title;?></legend>
<?php 
				if ($incompleteForms[$userForm->id]) {
?>
				<p><em>Incomplete:</em> <?php print $form->title;?></p>
<?php
				} else {
?>
				<p><?php print $form->title;?></p>
<?php
				}

				foreach ($formQuestions as $question) {
					$userAnswer = getQuestionAnswerIfExists($userForm->id, $question->id, -1, -1);
?>
				<p><?php print $question->question;?>: <?php print $userAnswer->answer;?></p>
<?php
				}
?>
				<p class="smallbuttons"><input class="button" type="submit" name="change" value="Change" /></p>
			</fieldset>
		</form>
		<!-- END Additional Payment details review box -->
<?php
			}
?>
	
<?php
		}
	}
?>
				
		<p>The Council uses the facilities of a leading e-Commerce company, <a href="http://www.secpay.com/">SecPay</a>, to process your credit/debit card transaction. <strong>The Council does not have access to, or store your card details</strong>.</p>

<?php
	//	Only let them continue if we are satisfied that all forms have been completed
	if (sizeof($incompleteForms) == 0 && $totalAmount > 0.00) {
?>
		<form name="pay" action="payments_payee_details.php" method="post" class="basic_form">
			<fieldset>
				<p>Once you believe your basket to be correct, please
				<input type="hidden" id="orderID" name="orderID" value="<?php print $order->id;?>" />
				<input class="button" type="submit"  name="continue" value="Continue" />
			</fieldset>
		</form>
<?php
	}
?>

		<!--  contact box  -->
		<?php include("../includes/contactbox.php"); ?>
		<!--  END contact box  -->	

	<!-- </div> End of Middle 

	<div id="secondarycontent">
	<?php include("../includes/rightcolumn_payments.php"); ?>
	</div> -->
	
<!-- ###################################### -->
<?php include("../includes/closing.php"); ?>
</body>
</html>