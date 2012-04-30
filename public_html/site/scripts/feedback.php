<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	
	$salutation = "";
	$forename = "";
	$surname = "";
	$address = "";
	$postcode = "";
	$country = "";
	$email = "";
	$telephone = "";
	$comments = "";
	
	$error_array = array();
	
	if (isset($_POST['submit'])) {
		if ($_POST['forename'] == "") {
			$error_array['forename'] = true;
		}
		if ($_POST['surname'] == "") {
			$error_array['surname'] = true;
		}
		if ($_POST['address'] == "") {
			$error_array['address'] = true;
		}
		if ($_POST['email'] == "" || !preg_match('/^[0-9A-Za-z\.\-_]{1,127}@[0-9A-Za-z\.\-_]{1,127}$/', trim($_POST['email']))) {
			$error_array['email'] = true;
		}
		if ($_POST['telephone'] == "") {
			$error_array['telephone'] = true;
		}
		if ($_POST['comments'] == "") {
			$error_array['comments'] = true;
		}
		if ($_POST['auth'] == 'fail' || $_POST['auth'] != md5(DOMAIN . date('Y'))) {
			$error_array['auth'] = true;
		}
		
		if (sizeof($error_array) == 0) {
			$headerEmail = $_POST['email'];
			if (empty($headerEmail)) {
				$headerEmail = DEFAULT_EMAIL_ADDRESS;
			}
			
			$HEADER = "From: $headerEmail\r\nReply-to: $headerEmail\r\nContent-Type: text/plain; charset=iso-8859-1;\r\n";
			$SUBJECT = $DOMAIN. " feedback enclosed.";
			$MESSAGE = "Please find here some feedback from the " . DOMAIN . " website.\n\n";

			$CONTACT_STRING = "";
			if ($_POST['forename'] != "" || $_POST['surname'] != "") {
				$CONTACT_STRING .= "Provided by: " . $_POST['salutation'] . " " . $_POST['forename'] . " " . $_POST['surname'] . "\n";
			}
			if ($_POST['address'] != "") {
				$CONTACT_STRING .= "Location: " . nl2br($_POST['address']). "\n";
			}
			if ($_POST['country'] != "" && $_POST['country'] != -1) {
				$CONTACT_STRING .= "Country: " . $_POST['country']. "\n";
			}
			if ($_POST['postcode'] != "") {
				$CONTACT_STRING .= "Postcode: " . $_POST['postcode']. "\n";
			}
			if ($_POST['email'] != "") {
				$CONTACT_STRING .= "Email: " . $_POST['email']. "\n";
			}
			if ($_POST['telephone'] != "") {
				$CONTACT_STRING .= "Telephone: " . $_POST['telephone']. "\n";
			}
			
			if ($CONTACT_STRING != "") {
				$MESSAGE .= "CONTACT DETAILS\n" . $CONTACT_STRING;
			}
			
			if ($_POST['comments'] != "") {
				$MESSAGE .= "COMMENTS\n" . nl2br($_POST['comments']) . "\n";
			}

			mail(DEFAULT_EMAIL_ADDRESS, $SUBJECT, $MESSAGE, $HEADER);		
			header('Location: ' . buildThanksURL());
			exit();

		}
	}

	if (!isset($_POST['submit']) && Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
		$user = Jadu_Service_User::getInstance()->getSessionUser();
		$salutation = $user->salutation;
		$forename = $user->forename;
		$surname = $user->surname;
		$address = $user->address;
		$postcode = $user->postcode;
		$country = $user->country;
		$email = $user->email;
		$telephone = $user->telephone;
		$comments = "";
	}
	elseif (isset($_POST['submit'])) {
		$salutation = isset($_POST['salutation']) ? $_POST['salutation'] : '';
		$forename = isset($_POST['forename']) ? $_POST['forename'] : '';
		$surname = isset($_POST['surname']) ? $_POST['surname'] : '';
		$address = isset($_POST['address']) ? $_POST['address'] : '';
		$postcode = isset($_POST['postcode']) ? $_POST['postcode'] : '';
		$country = isset($_POST['country']) ? $_POST['country'] : '';
		$email = isset($_POST['email']) ? $_POST['email'] : '';
		$telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
		$commands = isset($_POST['comments']) ? $_POST['comments'] : '';
	}

	// Breadcrumb, H1 and Title
		$MAST_HEADING = 'Your feedback';
		$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a  href="' . getSiteRootURL() . buildContactURL() .'" >Contact us</a></li><li><span>Your feedback</span></li>';

	include("feedback.html.php");
?>