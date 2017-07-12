<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="forms, form, application, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_NAME;?> online forms - <?php print encodeHtml($form->title);?>" />
	
	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_NAME . ' - ' . encodeHtml($form->title); ?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_NAME;?> Online forms - <?php print encodeHtml($form->title); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
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
		<div class="lead_item">
			<p><strong>Please note:</strong> You must be signed in to use the online forms. Please <a href="<?php print getSecureSiteRootURL() . buildSignInURL(); ?>">sign-in</a>.</p>
			<p>If you are not a member, then please <a href="<?php print getSecureSiteRootURL() . buildRegisterURL();?>">Register here</a>. If you have forgotten your password then use our <a href="<?php print getSiteRootURL() . buildForgotPasswordURL() ;?>">Password reminder</a>.</p>
			<p>If you do not have an email address or do not want to register, please send your comments on our <a href="<?php print getSiteRootURL() .buildFeedbackURL() ;?>">Contact Form</a>.</p>
		</div>

<?php
	} 
	else if ($numberSubmitsError) {
?>
		<h2 class="warning">Please note: You have submitted this form the maximum number of times.</h2>
		<p>Please contact customer services for further assistance.</p>
		
<?php
	} 
	else if ($alreadySubmittedError) {
?>
		<h2 class="warning">Please note: You have already submitted this particular version of your form.</h2>

<?php
	} 
	else if ($cookiesError && $pageNumber > 0) {
		// Data is leaked if cookies are disabled!
?>
		<h2 class="warning">Please note: This section of the site requires the use of cookies. Please reconfigure your browser to accept cookies or this section of the website will not work for you.</h2>

<?php
	}
	else {
?>

		<!-- Progress bar -->
<?php 
		if ($form->progressBar == 1) { 
?>			
			<p>Form progress: <strong><?php print (int) $progressPercentage;?>%</strong> - Page <strong><?php print encodeHtml($pageNumber+1);?></strong> of <strong><?php print $form->numberOfPages+2;?></strong></p>
			<div id="progressbar">
				<img src="<?php print getStaticContentRootURL(); ?>/site/images/poll_bar.png" alt="progress <?php print (int) $progressPercentage;?>%" width="<?php print (int) $progressPercentage;?>%" />
			</div>
<?php 
		}
		else {
?>
			<p>Page <strong><?php print encodeHtml($pageNumber+1);?></strong> of <strong><?php print $form->numberOfPages+2;?></strong></p>
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
			<form method="post" enctype="multipart/form-data" action="<?php print ((defined('SSL_ENABLED') && SSL_ENABLED) ? getSecureSiteRootURL() : getSiteRootURL()) . buildNonReadableXFormsURL($_GET['formID']) ;?>" class="basic_form xform">
				<fieldset>
					<input type="hidden" name="formID" value="<?php print (int) $formID;?>" />
					<input type="hidden" name="pageID" value="<?php print (int) $pageID;?>" />
					<input type="hidden" name="pageNumber" value="<?php print (int) $pageNumber;?>" />
<?php
		}
		
		if ($pageNumber > 0 && $pageNumber <= $form->numberOfPages) {
?>
			<h3>Page <?php print encodeHtml($pageNumber+1);?> - <?php print encodeHtml($page->title); ?></h3>
			<p><?php print nl2br($page->instructions); ?></p>
<?php
			$totalQuestions = sizeof($allQuestions);
			if ($totalQuestions > 0) {	
				foreach($allQuestions as $question) {
					$component = getXFormsFormComponent($question->componentID);
?>
			<p>Question <?php print encodeHtml($question->number); ?>:<br />
					
<?php
					if($question->componentID == '3' || $question->componentID == '4' ) {
?>
				<span class="label">
<?php
					}
					else {
?>
				<label for="<?php print encodeHtml($question->componentName); ?>">
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
						print "<a href=\" \" class=\"help\" title=\"Help\">Help:<span class=\"tooltip\"> " . encodeHtml($question->help) . "</a></span>";
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
							$missing_string = '<p><span class="star">You haven\'t provided us with the following required information:</span></p><ol>';
							for ($i = 0; $i < $conglomerate->rows; $i++) {
								foreach ($allFields as $field) {
									if (isset($missing_array[$question->id][$field->componentName.$i])) {
										$missing_string .= '<li> Row <strong>' . ($i+1) . '</strong>, Column <strong>' . encodeHtml($field->label) . '</strong>.</li>';
										$print_missing = true;
									}
									else if (isset($error_array[$question->id][$field->componentName.$i])) {
										$validation = getXFormsFormValidation($field->validationID);
										$error_string .= '<li> Row <strong>' . ($i+1) . '</strong>, Column <strong>' . encodeHtml($field->label) . '</strong>, ' . encodeHtml($validation->error) . '</li>';
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
								print '<p><span class="star">' . encodeHtml($validation->error) . '</span></p>';
							}
						}
					}
				}
			}
		}
		else if ($pageNumber == 0) {
			//	Instructions page printed.
			print '<div class="lead_item">'.$form->instructions.'</div>';
		}
		else if ($formSuccessfullyCompleted) {
?>
			<p>Your form will be processed as soon as possible.</p>
<?php 
			if ($user->id > 0) {
?>
			<p>An email has been sent to you confirming our receipt of your form.</p>
<?php
			}
		}
		else {
			print "<p>Pressing \"Submit Form\" below will send this form to ". encodeHtml(METADATA_GENERIC_NAME)." for processing.</p>";
		}

		if (!$formSuccessfullyCompleted)	{
			if ($pageNumber == ($form->numberOfPages+1)) {
			//	Completion page printed.
?>
			<p class="centre">
				<input type="submit" class="genericButton grey" name="commit" value="Submit Form" />
				<input type="submit" class="genericButton grey" name="back" value="&laquo; Back" />
			</p>
<?php
			} 
			else {
?>	
			<p class="centre">
				<input type="submit" class="genericButton grey" name="next" value="Save and Continue &raquo;" />
<?php
				if ($pageNumber > 0) {
?>
				<input type="submit" class="genericButton grey" name="back" value="&laquo; Back" />
				<input type="reset" class="genericButton grey" name="reset" value="Reset" />
<?php
				}
?>
			</p>
<?php
			}
		}

		if ($formSuccessfullyCompleted !== true) {
?>
		</fieldset>
	</form>
	


<?php
		}
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
