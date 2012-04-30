<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");

	if (isset($_GET['link'])) {
		$link = 'http://' . DOMAIN . base64_decode($_GET['link']);
	}
	else {
		$link = $_POST['link'];
	}

	$error_array = array();

	if (isset($_POST['sendComment'])) {

		//	Some validation
		if (trim($_POST['message']) == "") { 
			$error_array['message'] = true;
		}
		if ($_POST['auth'] == 'fail' || $_POST['auth'] != md5($DOMAIN.date('Y'))) {
			$error_array['auth'] = true;
		}		
		//	end validation

		if (sizeof($error_array) == 0) {
		
			if($_POST['email'] !='') {
				$emailAdr = $_POST['email'];
			}
			else {
				$emailAdr = DEFAULT_EMAIL_ADDRESS;
			}
			
			if($_POST['name'] !='') {
				$emailName = $_POST['name'];
			}
			else {
				$emailName = DEFAULT_EMAIL_ADDRESS;
			}

			$HEADER = "From: " . $emailAdr . "\r\nReply-to: " . $emailAdr . "\r\nContent-Type: text/plain; charset=iso-8859-1;\r\n";
			$SUBJECT = $emailName . " has sent comments";

			$MESSAGE = $emailName . " has sent you comments on the following content from ".METADATA_GENERIC_NAME." Online: " . html_entity_decode($link);
			if ($_POST['message'] != "") { 
				$MESSAGE .= "\r\n\r\n Comments: " . $_POST['message'];
			}

			mail(DEFAULT_EMAIL_ADDRESS, $SUBJECT, $MESSAGE, $HEADER);

		} else {
			$name = $_POST['name'];
			$email = $_POST['email'];
			$message = $_POST['message'];
		}
	}
	else {
		$name = "";
		$email = "";
		$message = "";

		if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
			if (!isset($_POST['name'])) {
				$name = "$user->forename $user->surname";
			}
			if (!isset($_POST['email'])) {
				$email = $user->email;
			}
		}
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Send us your comments';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Send us your comments</span></li>';
	
	include("pageComments.html.php");
?>