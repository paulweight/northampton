<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php"); 
	include_once("websections/JaduFAQ.php");
	
	$error = false;
	if (isset($_POST['question']) && isset($_POST['email']) && isset($_POST['submit'])) {
		$validation_array = array(
			'email' => true,
			'question' => true,
		);
		if (empty($_POST['question'])) {
			$validation_array['question'] = false;
		}
		if (!preg_match('/^[0-9A-Za-z\.\-_]{1,127}@[0-9A-Za-z\.\-_]{1,127}$/', trim($_POST['email']))) {
			$validation_array['email'] = false;
		}
				
		if (!in_array(false, $validation_array) ) {
			$error = false;
			newFAQ ($_POST['question'], $_POST['email']);
			
			$EMAIL_HEADER = "From: " . $_POST['email'] . "\r\nReply-to: " . $_POST['email'] . "\r\nContent-Type: text/plain; charset=iso-8859-1;\r\n";
			$EMAIL_MESSAGE = "A New question has arrived:\r\n\r\n" . $_POST['question'] ."\r\n\r\nPlease login to the Jadu Control Centre and complete this FAQ.\r\n";
			mail(FAQ_EMAIL_ADDRESS, "New FAQ arrived", $EMAIL_MESSAGE, $EMAIL_HEADER);
		}
		else {
			$error = true;
		}
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Ask a question';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a  href="' . getSiteRootURL() . buildFAQURL().'">Frequently asked questions</a></li><li><span>Ask a question</span></li>';
	
	include("faqs_ask.html.php");
?>