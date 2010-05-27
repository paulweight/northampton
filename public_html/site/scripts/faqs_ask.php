<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php"); 
	include_once("websections/JaduFAQ.php");
	
	$error = false;
	if (isset($_POST['question']) && isset($_POST['email']) && isset($_POST['submit'])) {
		$validation_array = array();
		$validation_array['question'] = preg_match('/^[-.,£$@&:;\/(\)\+\=\"\'\?\!a-zA-Z0-9\s]+$/', str_replace("\\", "" ,$_POST['question']));
		$validation_array['email'] = preg_match('/^[0-9A-Za-z\.\-_]{1,127}@[0-9A-Za-z\.\-_]{1,127}/', $_POST['email']);
				
		if (!in_array(false, $validation_array) ) {
			$error = false;
			newFAQ ($_POST['question'], $_POST['email']);
			
			$EMAIL_HEADER = "From: " . $_POST['email'] . "\r\nReply-to: " . $_POST['email'] . "\r\nContent-Type: text/plain; charset=iso-8859-1;\r\n";
			$EMAIL_MESSAGE = "A New question has arrived:\r\n\r\n" . $_POST['question'] ."\r\n\r\nPlease login to the Jadu Control Centre and complete this FAQ.\r\n";
			mail(FAQ_EMAIL_ADDRESS, "New FAQ arrived", $EMAIL_MESSAGE, $EMAIL_HEADER);
		}
		else
		{
			$error = true;
		}
	}

	$breadcrumb = 'faqAsk';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Ask us a question | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="FAQ, frequently asked question, ask, query, queries, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s ask the council a question feature" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Ask a Question" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s ask the council a question feature" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
        
	<p class="first">If there is anything you would like to ask us please use the form below. We will endeavour to reply as soon as possible.</p> 
	
<?php
	if (isset($validation_array) && !$validation_array['email']) { 		 
?>
	<h2 class="warning"><strong>!</strong> You must enter a valid <strong>email</strong> before sending your FAQ</h2>
<?php
	}
	if (isset($validation_array) && !$validation_array['question']) {
?>	
	<h2 class="warning"><strong>!</strong> You must enter a <strong>question</strong> before sending your FAQ</h2>
<?php
	}
	if (isset($validation_array) && !in_array(false, $validation_array) ) {
		print "<h2>Thank you for submitting your question</h2>";
	}
?>
        
	<!-- Post a question -->
	<form action="http://<?php print $DOMAIN;?>/site/scripts/faqs_ask.php" method="post" class="basic_form">
		<fieldset>
			<legend>Post us your question</legend>
			<p>
				<label for="email">
					<?php if (isset($validation_array) && !$validation_array['email']) print "<strong>! ";?>
					Your email <em>(required)</em>
					<?php if (isset($validation_array) && !$validation_array['email']) print "</strong>";?>
				</label>
				<input id="email" type="text" name="email" value="<?php if ((isset($validation_array) && !$validation_array['email']) || ($error)) print $_POST['email']; else print $user->email; ?>" class="field<?php if (isset($validation_array) && !$validation_array['email']) print " warning";?>" />
			</p>
			<p>
				<label for="question">
					<?php if (isset($validation_array) && !$validation_array['question']) print "<strong>! ";?>
					Question? <em>(required)</em>
					<?php if (isset($validation_array) && !$validation_array['question']) print "</strong>";?>
				</label>
				<textarea id="question" name="question" rows="3" cols="2" class="field<?php if (isset($validation_array) && !$validation_array['question']) print " warning";?>"><?php if ((isset($validation_array) && !$validation_array['question']) || ($error)) print str_replace("\\", "" ,$_POST['question']); ?></textarea>
			</p>
			<p class="centre">
				<input type="submit" value="Send Your Question" name="submit" class="button" />
			</p>
		</fieldset>
	</form>
            
	<p class="note">Note: Your question may be added to our FAQ database and used on this site.</p>
		
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
