<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduAppliedCategories.php");
	include_once("JaduCategories.php");
	include_once("websections/JaduContact.php");
	include_once("egov/JaduXFormsForm.php");
	include_once("egov/JaduXFormsFormPage.php");
	include_once("egov/JaduXFormsFormInterfaceFunctions.php");
	include_once("egov/JaduXFormsUserForms.php");
	include_once("egov/JaduXFormsUserQuestionAnswers.php");
	
	define("DEFAULT_INCOMPLETE_FORM_IDENTIFIER", "-999999999");
	
	//	Variable intitialisations
	$cookiesError = false;
	$loginError = false;
	$notLiveError = false;
	$numberSubmitsError = false;
	$alreadySubmittedError = false;
	$formSuccessfullyCompleted = false;
	
	$allQuestions = array();
	$error_array = array();
	$missing_array = array();
	$values_array = array();
	
	if (isset($_GET['formID']) && is_numeric($_GET['formID']) && $_GET['formID'] > 0) {
		$form = getXFormsForm($_GET['formID'], true);
		
		setcookie ("TestCookie", "Test", 0, "/", substr($DOMAIN,strpos($DOMAIN,'.')));
		if (!isset($TestCookie)) {
			$cookiesError = true;
			$progressPercentage = 0;
		}
		
		if ($form->live != 1) {
			$notLiveError = true;
			$progressPercentage = 0;
		}
		else if ((!isset($_SESSION['userID']) || $_SESSION['userID'] == '') && $form->allowUnregistered == 0) {
			$loginError = true;
			$progressPercentage = 0;
		}
		else {
			
			//	A pageNumber value of 0 will be used to represent Form Instructions.
			if (!isset($pageNumber) || $pageNumber == '') {
				$pageNumber = 0;
				$pageID = -1;
			}
			
			$newSessionStarted = false;
			
			if (isset($_SESSION['userID'])) {
				$user = getUser($_SESSION['userID']);
			}
			else {
				$user = new User();
				$user->email = "Unregistered user";
			}

			$incompleteIdentifier = DEFAULT_INCOMPLETE_FORM_IDENTIFIER;
			if ($user->id > 0) {
				$incompleteIdentifier = $user->id;
			}
			else if (isset($_SESSION['unregisteredUserID'])) {
				$incompleteIdentifier = $_SESSION['unregisteredUserID'];
			}
			
			$userForm = getIncompleteFormIfExistsForUser($incompleteIdentifier, $form->id);
						
			//	Will just have moved from instructions to page 1.
			if (isset($_POST['next']) && $pageNumber == 0 && $userForm->id <= 0) {
				$userFormID = newXFormsUserForm($form->id, $incompleteIdentifier);
				$userForm = getXFormsUserForm($userFormID);
				
				//	Force in a userID of the negative value of the userFormID as its unique.
				if ($incompleteIdentifier == DEFAULT_INCOMPLETE_FORM_IDENTIFIER) {
					$_SESSION['unregisteredUserID'] = '-'.$userFormID;
					updateXFormsUserForm($userForm->id, $userForm->formID, $_SESSION['unregisteredUserID'], $userForm->completed, $userForm->comments, $userForm->status);
					$userForm = getXFormsUserForm($userFormID);
					$newSessionStarted = true;
				}
			}
			
			$userFormID = $userForm->id;
			
			//	from instructions to page 1.
			if (isset($_POST['next']) && $pageNumber == 0) {
				$pageNumber++;
				unset($_POST['next']);
			}
			
			if ((isset($_POST['next']) || isset($_POST['commit'])) && $userForm->id <= 0 && $newSessionStarted == false) {
				//	will meet this condition if the user pressed back after submitting then try submit again
				$alreadySubmittedError = true;
				
				//dummy stuff to make the page look ok
				$pageNumber += 1;
				$progressPercentage = 100;
			}
			else if (isset($_POST['commit'])) {
				//	Complete the userForm object. so next time creates new instead of updates
				setXFormsUserFormComplete($userForm->id, 1);
				incrementFormRequests($form->id);
				
				//	send an email to the form maintainer if was set up for this.
				if (strpos($form->action, "email") !== false) {
					$NEWHEADER = "From: nbcforms@nbcforms.gov.uk\nReply-to: nbcforms@nbcforms.gov.uk\nContent-Type: text/html; charset=iso-8859-1\n";
					$SUBJECT = "Form completion: $form->title";
					$MESSAGE = createHTMLEmail (getXFormsUserForm($userFormID), $user->email, $form);
					mail($form->emailTo, $SUBJECT, $MESSAGE, $NEWHEADER);
				}

				//	email the user a receipt - if it is a registered user that is!
				if ($user->id > 0) {
					$SUBJECT = XFORMS_RECEIPT_SUBJECT;
					$MESSAGE = str_replace("ONLINE_FORM", $form->title, XFORMS_RECEIPT_BODY);
					$MESSAGE = str_replace("USER_FORM_ID", $userFormID, $MESSAGE);
					mail($user->email, $SUBJECT, $MESSAGE, $HEADER);
				}
				
				session_unregister('unregisteredUserID');
				unset($_SESSION['unregisteredUserID']);
				unset($userForm);
				
				$formSuccessfullyCompleted = true;
				$progressPercentage = 100;
			}
			else {
				
				if ($pageNumber > 0) {
					$page = getXFormsFormPageFromPageNumber($form->id, $pageNumber);
					$pageID = $page->id;
					$allQuestions = getAllXFormsFormQuestionsForFormPage($pageID);
					
					if (isset($_POST['next'])) {
						foreach($allQuestions as $question) {
						
							$component = getXFormsFormComponent($question->componentID);
							if ($component->isConglomerate == 1) {
								$conglomerate = getXFormsFormConglomerateForQuestion ($question->id);
								$allFields = getAllXFormsFormConglomerateElementsForConglomerate($conglomerate->id);
				
								for ($i = 0; $i < $conglomerate->rows; $i++) {
									foreach ($allFields as $field) {
										$comp = getXFormsFormComponent($field->componentID);
			
										$value = htmlentities($_POST[$field->componentName][$i], ENT_QUOTES, 'UTF-8');
										$where = $field->componentName.$i;
										$values_array[$question->id][$where] = $value;
										
										if ($i < $conglomerate->requiredRows) {
				
											if ($field->validationID != -1) {
												$validation = getXFormsFormValidation($field->validationID);
												$method = $validation->method;
												
												if ($value == "" && $field->required == 1) {
													$missing_array[$question->id][$where] = true;
												}
												else if ($value != "") {
													$result = $method($value);
													if ($result == false) 
														$error_array[$question->id][$where] = true;
												}
											}
											else if ($value == "" && $field->required == 1) {
												$missing_array[$question->id][$where] = true;
											}
										
										} else {
										
											if ($field->validationID != -1) {
												$validation = getXFormsFormValidation($field->validationID);
												$method = $validation->method;
												if ($value != "") {
													$result = $method($value);
													if ($result == false) 
														$error_array[$question->id][$where] = true;
												}
											}
										}
									}
								}
							}
							else {
								
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
								else if (gettype($$field) == "array" && $question->required == 1 && sizeof($$field)==0) {
									$missing_array[$question->id] = true;
								}
								else if ($$field == "" && $question->required == 1) {
									$missing_array[$question->id] = true;
								}
							}
						}
						
						//	save the details and move on a page
						if (sizeof($missing_array) == 0 && sizeof($error_array) == 0) {
							
							//	Figure out the position this answer should be placed at in userAnswers table
							$questionPositionInEntireForm = 0;
							for ($pageLoopCounter = 1; $pageLoopCounter <= ($pageNumber-1); $pageLoopCounter++) {
								$pageLoopedTo = getXFormsFormPageFromPageNumber($formID, $pageLoopCounter);
								$allPageLoopedQuestions = getAllXFormsFormQuestionsForFormPage($pageLoopedTo->id);
								$questionPositionInEntireForm += sizeof($allPageLoopedQuestions);
							}
							
							foreach($allQuestions as $questionPosition => $question) {
								
								$questionPosition += $questionPositionInEntireForm;
								
								$component = getXFormsFormComponent($question->componentID);
								if ($component->isConglomerate == 1) {
									$conglomerate = getXFormsFormConglomerateForQuestion ($question->id);
									$allFields = getAllXFormsFormConglomerateElementsForConglomerate ($conglomerate->id);
									
									for ($i = 0; $i < $conglomerate->rows; $i++) {
										foreach ($allFields as $field) {
											$value = htmlentities($_POST[$field->componentName][$i], ENT_QUOTES, 'UTF-8');
											$identifier = $question->componentName . ":" . $field->componentName;

											//	if we have an array (probably through a set of checkbox's)
											if (gettype($value) == "array") {
												$userAnswerArray = getQuestionAnswerIfExists($pageID, $userFormID, $question->id, $field->id, $i);
												
												//delete all old and then resave the new
												if ($userAnswerArray != -1) {
													deleteXFormsQuestionAnswersForUserFormAndQuestionElement ($userAnswerArray[0]->userFormID, $userAnswerArray[0]->questionID, $field->id, $i);
												}
												foreach ($value as $fieldAnswer) {										
													newXFormsQuestionAnswer ($pageID, $userFormID, $question->id, $field->id, $i, $fieldAnswer, $identifier, $questionPosition);
												}
											}
											else {
												$userAnswer = getQuestionAnswerIfExists($pageID, $userFormID, $question->id, $field->id, $i);
												if ($userAnswer != -1) {
													updateXFormsQuestionAnswer ($userAnswer->id, $pageID, $userFormID, $question->id, $field->id, $i, $value, $identifier, $questionPosition);
												} else {
													newXFormsQuestionAnswer ($pageID, $userFormID, $question->id, $field->id, $i, $value, $identifier, $questionPosition);
												}
											}
										}
									}
								}
								else {
									$field = $question->componentName;
									
									//	This could be an array from checkbox set elements
									if (gettype($$field)=="array") {
										$userAnswerArray = getQuestionAnswerIfExists($pageID, $userFormID, $question->id, -1, -1);
										
										//	delete all old and then resave the new
										if ($userAnswerArray != -1) {
											//	can get a singular object returned from getQuestionAnswerIfExists, so get hold of variables accordingly to call deleteXFormsQuestionAnswersForUserFormAndQuestion with.
											if (gettype($userAnswerArray) == "array") {
												deleteXFormsQuestionAnswersForUserFormAndQuestion ($userAnswerArray[0]->userFormID, $userAnswerArray[0]->questionID);
											} else {
												deleteXFormsQuestionAnswersForUserFormAndQuestion ($userAnswerArray->userFormID, $userAnswerArray->questionID);											
											}
										}
										foreach ($_POST[$field] as $fieldAnswer) {
											newXFormsQuestionAnswer ($pageID, $userFormID, $question->id, -1, -1, $fieldAnswer, $question->componentName, $questionPosition);
										}
									}
									else {
										$userAnswer = getQuestionAnswerIfExists($pageID, $userFormID, $question->id, -1, -1);
										if ($userAnswer != -1) {
											updateXFormsQuestionAnswer ($userAnswer->id, $pageID, $userFormID, $question->id, -1, -1, $$field, $question->componentName, $questionPosition);
										} else {
											newXFormsQuestionAnswer ($pageID, $userFormID, $question->id, -1, -1, $$field, $question->componentName, $questionPosition);
										}
									}
								}
							}
							
							$pageNumber++;
							$values_array = array();
						}
					}			
					else if (isset($_POST['back'])) {
						$pageNumber--;
						$values_array = array();
					}
					
					//	setup the values
					if ($pageNumber <= $form->numberOfPages+1) {
						$page = getXFormsFormPageFromPageNumber($form->id, $pageNumber);
						$pageID = $page->id;
						$allQuestions = getAllXFormsFormQuestionsForFormPage($pageID);
						
						foreach($allQuestions as $question) {
			
							$component = getXFormsFormComponent($question->componentID);
							if ($component->isConglomerate == 1) {
								$conglomerate = getXFormsFormConglomerateForQuestion ($question->id);
								$allFields = getAllXFormsFormConglomerateElementsForConglomerate($conglomerate->id);
				
								for ($i = 0; $i < $conglomerate->rows; $i++) {
									foreach ($allFields as $field) {
										if (isset($_POST[$field->componentName][$i]) && $pageID == $_POST['pageID']) {
											$value = htmlentities($_POST[$field->componentName][$i], ENT_QUOTES, 'UTF-8');
										}
										else {
											$userAnswer = getQuestionAnswerIfExists($pageID, $userFormID, $question->id, $field->id, $i);
											if (gettype($userAnswer) == "array") {
												$value = array();
												foreach ($userAnswer as $ua) {
													$value[] = $ua->answer;
												}
											}
											else if ($userAnswer != -1) {
												$value = $userAnswer->answer;
											}
										}
										$where = $field->componentName.$i;
										$values_array[$question->id][$where] = $value;
									}
								}
							}
							else {
								$field = $question->componentName;
								if (isset($_POST[$field]) && $pageID == $_POST['pageID']) {
									$value = $_POST[$field];
								}
								else {
									$value = "";
									$userAnswer = getQuestionAnswerIfExists($pageID, $userFormID, $question->id, -1, -1);
									if ($userAnswer != -1) {
										if (gettype($userAnswer) == "array") {
											$value = array();
											foreach ($userAnswer as $ua) {
												$value[] = $ua->answer;
											}
										}
										else {
											$value = $userAnswer->answer;
										}
									}
								}

								$values_array[$question->id] = $value;
							}
						}
					}
				
				}
				$progressPercentage = round(($pageNumber / ($form->numberOfPages+1)) * 100, 0);
			}
		}
	}
	
	if (isset($_GET['formID']) && !is_numeric($_GET['formID'])) {
		$notLiveError = true;	
	}
	
	include_once("JaduStyles.php");
	$categoryID = getFirstCategoryIDForItemOfType (XFORMS_FORM_CATEGORIES_TABLE, $formID, "LGCL");
	$contentType = getAppropriateContentTypeFromURL ($_SERVER['PHP_SELF']);
	$STYLESHEET = getAppropriateStylesheet ($categoryID, BESPOKE_CATEGORY_LIST_NAME, $contentType->id);
	
	$breadcrumb = 'xformsForm';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $form->title;?> | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="forms, form, application, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> online forms - <?php print $form->title;?>" />
	
	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME . ' - ' . $form->title;?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online forms - <?php print $form->title;?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($notLiveError) {
?>
		<h2 class="warning">Sorry, this form is not accessible to the public at this time.</h2>

<?php
	} 
	else if ($loginError) {
?>		
		<div class="display_box">
			<p class="first"><strong>Please note:</strong> You must be signed in to use the online forms. Please <a href="http://<?php print $DOMAIN;?>/site/scripts/signin.php">sign in</a>.</p>
			<p class="first">If you are not a member, then please <a href="http://<?php print $DOMAIN;?>/site/scripts/register.php">create an account here</a>. If you have forgotten your password then use our <a href="http://<?php print $DOMAIN;?>/site/scripts/forgot_password.php">Password reminder</a>.</p>
			<p>If you do not have an email address or do not want to register, please send your comments on our <a href="http://<?php print $DOMAIN;?>/site/scripts/feedback.php">Contact Form</a>.</p>
		</div>

<?php
	} 
	else if ($numberSubmitsError) {
?>
		<h2 class="warning">Please note: You have submitted this form the maximum number of times.</h2>
		<p class="first">Please contact customer services for further assistance.</p>
		
<?php
	} 
	else if ($alreadySubmittedError) {
?>
		<h2 class="warning">Please note: You have already submitted this particular version of your form.</h2>

<?php
	} 
	else {
?>

		<!-- Progress bar -->
<?php 
		if ($form->progressBar == 1) { 
?>			
			<p class="first">Form progress: <strong><?php print $progressPercentage;?>%</strong> - Page <strong><?php print $pageNumber+1;?></strong> of <strong><?php print $form->numberOfPages+2;?></strong></p>
			<div id="progressbar">
				<img src="<?php print $PROTOCOL.$DOMAIN;?>/site/images/poll_bar.png" alt="progress <?php print $progressPercentage;?>%" width="<?php print $progressPercentage;?>%" />
			</div>
<?php 
		}
		else {
?>
			<p class="first">Page <strong><?php print $pageNumber+1;?></strong> of <strong><?php print $form->numberOfPages+2;?></strong></p>
<?php
		}
?>
		<!-- END Progress bar -->

<?php
		if ($formSuccessfullyCompleted) {
?>
			<h2>Thank you for completing this form.</h2>
<?php
		} 
		else {
?>		
			<form method="post" action="<?php print $PROTOCOL.$DOMAIN;?>/site/scripts/xforms_form.php?formID=<?php print $_GET['formID'];?>" class="basic_form xform">
				<fieldset>
					<input type="hidden" name="formID" value="<?php print $formID;?>" />
					<input type="hidden" name="pageID" value="<?php print $pageID;?>" />
					<input type="hidden" name="pageNumber" value="<?php print $pageNumber;?>" />
<?php
		}
		
		if ($pageNumber > 0 && $pageNumber <= $form->numberOfPages) {
?>
			<h3>Page <?php print $pageNumber+1;?> - <?php print $page->title; ?></h3>
			<p class="first"><?php print nl2br($page->instructions); ?></p>
<?php
			$totalQuestions = sizeof($allQuestions);
			if ($totalQuestions > 0) {	
				foreach($allQuestions as $question) {
					$component = getXFormsFormComponent($question->componentID);
?>
			<p>Question <?php print $question->number;?>:<br />
					
<?php
					if($question->componentID == '3' || $question->componentID == '4' ) {
?>
				<span class="label">
<?php
					}
					else {
?>
				<label for="<?php print $question->componentName; ?>">
<?php
					}

					if (isset($missing_array[$question->id]) || isset($error_array[$question->id])) { 
						print '<strong>! ';
					}
					print $question->question;
					if (isset($missing_array[$question->id]) || isset($error_array[$question->id])) { 
						print '</strong>';
					}
					if ($question->required == 1) {
						print ' <em>(required)</em>';
					}

					if($question->componentID == '3' || $question->componentID == '4' ) {
?>
				</span>
<?php
					}
					else {
?>
				</label>
<?php
					}

					if ($question->help != '') {
						print "<span class=\"help\">Help: $question->help</span>";
					}
?>
				<br />
<?php 	
					print buildComponentHTML($component, $question, $values_array, $error_array, $missing_array);
?>
				</p>
<?php
					if (isset($error_array[$question->id]) || isset($missing_array[$question->id])) {
		
						//	Error Messages
						if ($component->isConglomerate == 1) {
							$conglomerate = getXFormsFormConglomerateForQuestion($question->id);
							$allFields = getAllXFormsFormConglomerateElementsForConglomerate($conglomerate->id);
			
							$print_errors = false;
							$print_missing = false;
							
							$error_string = '<span class="star">Invalid information:</span><ol>';
							$missing_string = '<p><span class="star">You havent provided us with the following requistar information:</span></p><ol>';
							for ($i = 0; $i < $conglomerate->rows; $i++) {
								foreach ($allFields as $field) {
									if (isset($missing_array[$question->id][$field->componentName.$i])) {
										$missing_string .= '<li> Row <strong>' . ($i+1) . '</strong>, Column <strong>' . $field->label . '</strong>.</li>';
										$print_missing = true;
									}
									else if (isset($error_array[$question->id][$field->componentName.$i])) {
										$validation = getXFormsFormValidation($field->validationID);
										$error_string .= '<li> Row <strong>' . ($i+1) . '</strong>, Column <strong>' . $field->label . '</strong>, ' . $validation->error . '</li>';
										$print_errors = true;
									}							
								}
							}
							
							if ($print_missing) {
								print $missing_string . '</ol>';
							}
							if ($print_errors) {
								print $error_string . '</ol>';
							}
						}
						else {
							if (!isset($missing_array[$question->id]) && isset($error_array[$question->id])) {
								$validation = getXFormsFormValidation($question->validationID);
								print '<p><span class="star">' . $validation->error . '</span></p>';
							}
						}
					}
				}
			}
		}
		else if ($pageNumber == 0) {
			//	Instructions page printed.
			print '<div class="display_box">'.$form->instructions.'</div>';
		}
		else if ($formSuccessfullyCompleted) {
?>
			<p class="first">Your form will be processed as soon as possible.</p>
<?php 
			if ($user->id > 0) {
?>
			<p class="first">An email has been sent to you confirming our receipt of your form.</p>
<?php
			}
		}
		else {
			print "<p class=\"first\">Pressing \"Submit Form\" below will send this form to ". METADATA_GENERIC_COUNCIL_NAME." for processing.</p>";
		}

		if (!$formSuccessfullyCompleted)	{
			if ($pageNumber == ($form->numberOfPages+1)) {
			//	Completion page printed.
?>
			<p class="centre">
				<input type="submit" class="button" name="back" value="&laquo; Back" />
				<input type="submit" class="button" name="commit" value="Submit Form" />
			</p>
<?php
			} 
			else {
?>	
			<p class="centre">
<?php
				if ($pageNumber > 0) {
?>
				<input type="submit" class="button" name="back" value="&laquo; Back" />
				<input type="reset" class="button" name="reset" value="Reset" />
<?php
				}
?>
				<input type="submit" class="button" name="next" value="Save and Continue &raquo;" />
			</p>
<?php
			}
		}

		if ($formSuccessfullyCompleted !== true) {
?>
		</fieldset>
	</form>
	
	<p>If you have <a href="http://<?php print $DOMAIN; ?>/site/scripts/register.php">registered</a> with our web site and are signed in you can leave a form at any time to complete later or check all your saved and completed forms from Your Account page.</p>

<?php
		}
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>