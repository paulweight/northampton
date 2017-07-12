<?php
	require_once("JaduConstants.php");
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");

	/**
	* Constant to enable logging of all mail for debugging / testing
	*/
	define("LOG_ALL_MAIL", false);
	
	$verifyFailed = false;
	$displayCAPTCHA = false;
	
	if (defined('RECAPTCHA_PUBLIC_KEY') && RECAPTCHA_PUBLIC_KEY != '' &&
			defined('RECAPTCHA_PRIVATE_KEY') && RECAPTCHA_PRIVATE_KEY != '') {
				
		include_once('Service/ReCAPTCHA.php');
		$captcha = new Jadu_Service_ReCAPTCHA(RECAPTCHA_PUBLIC_KEY, RECAPTCHA_PRIVATE_KEY, $_SERVER['REMOTE_ADDR']);
		$displayCAPTCHA = true;

		if (isset($_POST['recaptcha_challenge_field']) && isset($_POST['recaptcha_response_field'])) {
			$response = $captcha->verify($_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
			$verifyFailed = !$response->isValid() && $response->isKeyValid();
		}
		else {
			$verifyFailed = true;
		}
	}
	
	// Set a timer - a human will take time to fill in the form
	if (!isset($_POST['sendFriend'])) {
		$timerStart = microtime(true);
		$realTimerStart = $timerStart;
		$timerString = dechex(intval($timerStart)) . sha1(DOMAIN);
		$timerHash = base64_encode($timerString);
	}
	elseif (isset($_POST['authCode'])) {
		$timerHash = $_POST['authCode'];
		$realTimerStart = microtime(true);
	}

	if (isset($_GET['link'])) {
		$link = getSiteRootURL() . base64_decode($_GET['link']);
	}

	$error_array = array();
	$spam_error_array = array();

	if (isset($_POST['sendFriend'])) {
		// Some validation
		if ($verifyFailed) {
			$error_array['recaptcha'] = true;
		}
		if (!preg_match("/^[-.,£$@&:;(\)\+\=\"\'\?\!a-zA-Z0-9\s]+$/", $_POST['name'])) {
			$error_array['name'] = true;
		}
		if (!preg_match('/^[0-9A-Za-z\.\-_]{1,127}@[0-9A-Za-z\.\-_]{1,127}$/', trim($_POST['email']))) {
			$error_array['email'] = true;
		}
		if (!preg_match('/^[0-9A-Za-z\.\-_]{1,127}@[0-9A-Za-z\.\-_]{1,127}$/', trim($_POST['friend']))) {
			$error_array['friend'] = true;
		}
		if ($_POST['friend'] == $_POST['email']) {
			$error_array['email'] = true;
			$error_array['friend'] = true;
		}
		// Prevent URLs in the message
		if (preg_match('/(https?:\/\/|ftp:\/\/|(www\.))[^\. ]*[\.]/i', $_POST['message'])) {
			$error_array['message'] = true;
		}
		// A hidden field used to determine whether a bot submitted the form, assuming bots don't execute JavaScript
		if (!isset($_POST['auth']) || $_POST['auth'] != md5(DOMAIN . date('Y'))) {
			$spam_error_array['auth'] = true;
		}
		
		// Validate link is from this server
		if (isset($link)) {
			$urlParts = parse_url($link);
			$urlHost = $urlParts['host'];
			if (isset($urlHost) && DOMAIN !== $urlHost) {
				$spam_error_array['link'] = true;
			}
		}
		
		// Sanitise user input
		function cleanPost(&$postIn) {
			$regex = '/(<|>|%3c|%3e|%0a|bcc:|cc:|to:|content-type:|mime-version:|content-transfer-encoding:|multipart\/mixed)/i';
			$postIn = preg_replace($regex, '_', $postIn);
		}
		
		// Clean up post variables
		array_walk($_POST, 'cleanPost');
		
		// Basic cookie check
		if (!isset($_COOKIE[session_name()])) {
			$spam_error_array['cookie'] = true;
		}
		
		// Check for session
		if (!isset($_SESSION['initiated']) || !$_SESSION['initiated']) {
			$spam_error_array['session'] = true;
		}
		
		// Timer Check
		$timerMin = 5;
		$timerMax = 5*60;
		if (!isset($_POST['authCode']) || empty($_POST['authCode'])) {
			$spam_error_array['timerSet'] = true;
		}
		else {
			$timerEnd  = intval(microtime(true));
			$timerStart = hexdec(str_replace(sha1(DOMAIN), '', base64_decode($_POST['authCode'])));
			$timerTotal = intval($timerEnd - $timerStart);
				
			if ($timerTotal < $timerMin || $timerTotal > $timerMax) {
				$spam_error_array['timerLimits'] = true;
			}
		}
		
		// log spam attempts
		if (LOG_ALL_MAIL == true || sizeof($spam_error_array) != 0) {
			$fh = fopen('../../../logs/spam.log', 'a');
			fwrite($fh, date('Y-m-d H:m:i') . PHP_EOL);
			fwrite($fh, 'Timer Start: ' . $realTimerStart .PHP_EOL);
			fwrite($fh, 'Time Taken: ' . $timerTotal .PHP_EOL);
			fwrite($fh, 'Post Data: ' . PHP_EOL . serialize($_POST) . PHP_EOL);
			fwrite($fh, 'Cookie Data: ' . PHP_EOL . serialize($_COOKIE) . PHP_EOL);
			fwrite($fh, 'Session Data: ' . PHP_EOL . serialize($_SESSION) . PHP_EOL);
			fwrite($fh, 'Errors: ' . PHP_EOL . serialize($spam_error_array) . PHP_EOL);
			fclose($fh);
		}

		if (sizeof($error_array) == 0 && sizeof($spam_error_array) == 0) {
			$HEADER = "From: " . $_POST['email'] . "\r\nReply-to: " . $_POST['email'] . "\r\nContent-Type: text/plain; charset=iso-8859-1;\r\n";
			$SUBJECT = $_POST['name'] . " has sent you a link";

			$MESSAGE = $_POST['name'] . " has sent you a link to the following content from ".METADATA_GENERIC_NAME." Online: " . $link;
			if ($_POST['message'] != "") { 
				$MESSAGE .= "\r\n\r\n" . $_POST['name'] . " has added the following message: " . $_POST['message'];
			}
			$MESSAGE .= "\r\n\r\nKind Regards,\r\n\r\n" . METADATA_GENERIC_NAME;

			mail($_POST['friend'], $SUBJECT, $MESSAGE, $HEADER);

		} 
		else {
			$name = $_POST['name'];
			$email = $_POST['email'];
			$message = $_POST['message'];
			$friend = $_POST['friend'];
		}
	}
	else {

		$name = "";
		$email = "";
		$message = "";
		$friend = "";

		if (!isset($_POST['name']) && Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
			$name = "$user->forename $user->surname";
		}
		if (!isset($_POST['email']) && Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
			$email = $user->email;
		}
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Email a friend';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Email a friend</span></li>';
	
	include("email_friend.html.php");
?>
