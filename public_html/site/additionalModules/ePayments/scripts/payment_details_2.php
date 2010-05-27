<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("ePayments/JaduEpaymentsProducts.php");
	include_once("ePayments/JaduEpaymentsOrders.php");
	include_once("ePayments/JaduEpaymentsOrderItems.php");
	include_once("ePayments/JaduEpaymentsForms.php");
	include_once("ePayments/JaduEpaymentsFormsQuestions.php");
	include_once("ePayments/JaduEpaymentsUserForms.php");
	include_once("ePayments/JaduEpaymentsUserFormsAnswers.php");
	include_once("ePayments/JaduEpaymentsAccountBalances.php");
//	include_once("egov/JaduXFormsFormComponents.php");
//	include_once("egov/JaduXFormsFormValidation.php");
	
	include_once("xforms2/JaduXFormsFormComponents.php");
	include_once("xforms2/JaduXFormsFormValidation.php");

	//	Get user details if we have a logged in user.
	if (isset($_SESSION['userID']) && isset($orderItemID)) {
		$user = getUser($_SESSION['userID']);

		//	Retrieve all required objects
		$orderItem = getOrderItem($orderItemID);
		$order = getOrder($orderItem->orderID);
		$product = getProduct($orderItem->productID, true);
		$form = getProductForm($product->productFormID);
		$formQuestions = getAllProductFormQuestionsForForm($form->id);
		$userForm = getEpaymentsUserForm($orderItem->userProductFormID);

		$values_array = array();
		
		if (strlen($orderItem->referenceNumber) > 0) {
			$accountBalance = getAccountBalanceBy('referenceNumber', $orderItem->referenceNumber);
		}	
		
		//	Bounce this naughty user out!
		if ($order->userID != $user->id) {
			header("Location: payments.php");
			exit;
		}
		
		//	Submission of additional form has taken place.
		if (isset($_POST['formSubmit'])) {
			
			$error_array = array();
			$missing_array = array();
			
			if ($userForm == null) {
				$userFormID = newEpaymentsUserForm ($form->id, $user->id);
				$userForm = getEpaymentsUserForm($userFormID);
				
				//	Update the orderItem with the form id
				$orderItem->userProductFormID = $userFormID;
				updateOrderItem ($orderItem);
			}
			else {
				$userFormID = $userForm->id;
			}
			
			foreach($formQuestions as $question) {
				$component = getXFormsFormComponent($question->componentID);
				$field = $question->componentName;
				$values_array[$question->id] = $$field;
				
				if ($question->validationID != -1) {
					$validation = getXFormsFormValidation($question->validationID);
					$method = $validation->method;
					
					if ($$field == "" && $question->required == 1) {
						$missing_array[$question->id] = true;
					}
					else if ($$field != "") {
						$result = $method($$field);
						if ($result == false) 
							$error_array[$question->id] = true;
					}
				}
				else if (gettype($$field) == "array" && $question->required == 1 && sizeof($$field) == 0) {
					$missing_array[$question->id] = true;
				}
				else if ($$field == "" && $question->required == 1) {
					$missing_array[$question->id] = true;
				}
			}
			
			//	save the details
			if (sizeof($missing_array) == 0 && sizeof($error_array) == 0) {
				foreach($formQuestions as $questionPosition => $question) {
					$component = getXFormsFormComponent($question->componentID);
					$field = $question->componentName;
					
					//	This could be an array from checkbox set elements
					if (gettype($$field) == "array") {
						$userAnswerArray = getQuestionAnswerIfExists($userFormID, $question->id, -1, -1);
						
						//	delete all old and then resave the new
						if ($userAnswerArray != -1) {
							//	can get a singular object returned from getQuestionAnswerIfExists, so get hold of variables accordingly to call deleteXFormsQuestionAnswersForUserFormAndQuestion with.
							if (gettype($userAnswerArray) == "array") {
								deleteEpaymentsFormQuestionAnswersForUserFormAndQuestion ($userAnswerArray[0]->userFormID, $userAnswerArray[0]->questionID);
							} else {
								deleteEpaymentsFormQuestionAnswersForUserFormAndQuestion ($userAnswerArray->userFormID, $userAnswerArray->questionID);											
							}
						}
						foreach ($$field as $fieldAnswer) {
							newEpaymentsFormQuestionAnswer ($userFormID, $question->id, -1, -1, $fieldAnswer, $question->componentName, $questionPosition);
						}
					}
					else {
						$userAnswer = getQuestionAnswerIfExists($userFormID, $question->id, -1, -1);
						if ($userAnswer != -1) {
							updateEpaymentsFormQuestionAnswer ($userAnswer->id, $userFormID, $question->id, -1, -1, $$field, $question->componentName, $questionPosition);
						} else {
							newEpaymentsFormQuestionAnswer ($userFormID, $question->id, -1, -1, $$field, $question->componentName, $questionPosition);
						}
					}
				}
				
				//	Redirect the user to a confirmation of details page
				header("Location: payments_basket.php?orderID=$order->id");
				exit;
			}
		}
		
		//	Load in data from DB or from POST depending on scenario
		foreach($formQuestions as $question) {
			$value = "";
			$component = getXFormsFormComponent($question->componentID);
			$field = $question->componentName;
						
			if (isset($$field) && isset($_POST['formSubmit'])) {
				$value = $$field;
			}
			else {
				$userAnswer = getQuestionAnswerIfExists($userForm->id, $question->id, -1, -1);
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
			}

			$values_array[$question->id] = $value;
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
	<title><?php print $product->title;?> - Online payment - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include("../includes/meta.php"); ?>
	
	<!-- general metadata -->
	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments" />

	<!-- Dublin Core Metadata -->
	<meta name="DC.title" lang="en" content="<?php print $product->title;?> - Online payment - <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments" />
	
	<!-- IPSV / LGNL Metadata -->
	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council and democracy" /></head>
<body>
<?php include("../includes/opening.php"); ?>
<!-- #################################### -->
<!-- ########### MAIN CONTENT ########### -->

		<h1><?php print htmlentities($product->title);?></h1>
				
		<!-- Main Payment details review box -->
		<form name="mainDetails" action="payment_details_1.php" method="get" class="xform">
			<input type="hidden" name="orderItemID" value="<?php print $orderItemID;?>" />
			<fieldset>
				<legend>Payment details so far</legend>
				<?php if ($product->type == TYPE_BILLED) { ?>
				<span class="check_column_wrap"><label>Reference Number:</label> <?php print $orderItem->referenceNumber;?></span>
				<?php } ?>
	<?php
			if (isset($accountBalance) && $accountBalance != -1) {
	?>
				<span class="check_column_wrap">
					<label>Balance <?php if ($accountBalance->sign == '+') print 'Outstanding'; else print 'in Credit';?>:</label> &pound;<?php print $accountBalance->balance;?>
					
					<?php if ($countAvailableProducts == 1 && $accountBalance->sign == '+') { ?><a href="payment_details_1.php?productID=<?php print $relevantProducts[0]->id;?>">Pay this now</a><?php } ?>
				</span>
	<?php
			}
	?>						
				<span class="check_column_wrap"><label>Amount to pay:</label> &pound;<?php print $orderItem->netAmount;?></span>
				
				<input class="dangerbutton" type="submit"  name="remove" value="Remove" />
				<input class="jbutton" type="submit"  name="change" value="Change" />
				
			</fieldset>
		</form>
		<!-- END Payment details review box -->
				
		<!-- Product Mini form area -->
		<h2>Additional details</h2>
		<p class="first"><?php print nl2br($form->instructions);?></p>
				
	<?php
		if (sizeof($missing_array) > 0 || sizeof($error_array) > 0) {
	?>
			<h2 class="warning">You have errors in your form - Please check your answers</h2>
	<?php
		}
	?>
				
		<p class="first"><strong>Please note:</strong> Fields marked * are required.</p>
				
		<form name="additionalDetails" action="payment_details_2.php?orderItemID=<?php print $orderItemID;?>" method="post" class="jform">
			<fieldset id="mini_form">
				<legend><?php print $form->title;?></legend>
		<?php
			foreach ($formQuestions as $question) {
				$component = getXFormsFormComponent($question->componentID);
				$allOptions = getAllProductFormQuestionOptionsForQuestion($question->id);

				if (isset($missing_array[$question->id])) {
		?>
					<em>Error: you have not provided us with the below required field.</em>
		<?php
				} else if (isset($error_array[$question->id])) {
					$validation = getXFormsFormValidation($question->validationID);
		?>
					<em><?php print $validation->error;?></em>
		<?php
				}
		?>
				<span><label><?php print $question->question;?> <?php if ($question->required == 1) print "* ";?></label>
					<?php print buildProductFormComponentHTML($component, $question, $allOptions, $values_array, $error_array, $missing_array); ?>
				</span>
		<?php
			}
		?>
				<input class="jbutton" id="formSubmit" type="submit"  name="formSubmit" value="Continue" />
			</fieldset>
		</form>
		<!-- END Product Mini Form ID -->

		<!--  contact box  -->
		<?php include("../includes/contactbox.php"); ?>
		<!--  END contact box  -->

		<!-- ########### end of MAIN CONTENT ########### -->
		</div>
	</div>
	<!-- ########### EXTRA CONTENT ########### -->
	<div id="extramast"></div>
	<div id="rightbar" class="alt_rightbar">
	<?php include("../includes/rightcolumn_payments.php"); ?>
		<br class="clear" />
		
<!-- ###################################### -->
<?php include("../includes/closing.php"); ?>
</body>
</html>