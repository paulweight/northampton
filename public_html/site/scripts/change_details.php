<?php
	include_once("utilities/JaduStatus.php");
	
	// Force onto SSL if required.
	if ((defined('SSL_ENABLED') && SSL_ENABLED) && PROTOCOL != 'https://') {
		header("Location: ".getSecureSiteRootURL().$_SERVER['REQUEST_URI']);
		exit;
	}
	
	include_once("JaduStyles.php"); 
		
	if (!Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
		header("Location: $ERROR_REDIRECT_PAGE");
		exit();
	}
	
	if (!Jadu_Service_User::getInstance()->canUpdateUser()) {
		header('Location: ' . buildUserHomeURL());
		exit();
	}

	include_once("marketing/JaduRegisterPreferences.php");
	include_once("marketing/JaduTargetingRules.php");
	
	$registrationFailed = false;
	$registerPreferences = new RegisterPreferences();
	$targetingRules1 = getTargetingRule(1);
	$targetingRules2 = getTargetingRule(2);
	
	$errors = array();
	
	// Initialise CSRF protection
	include_once('Security/CSRFToken.php');
	$csrfToken =  new Jadu_Security_CSRFToken(session_id(), getClientIPAddress(), CSRF_TOKEN_SALT);
	$csrfTokenError = false;
	
	if (isset($_POST['submit'])) {
		if (!$csrfToken->isValid(isset($_POST['__token']) ? $_POST['__token'] : null)) {
			$csrfTokenError = true;
		}
		else {
			$user->email = isset($_POST['email']) ? $_POST['email'] : '';
			$user->salutation = isset($_POST['salutation']) ? $_POST['salutation'] : '';
			$user->forename = isset($_POST['forename']) ? $_POST['forename'] : '';
			$user->surname = isset($_POST['surname']) ? $_POST['surname'] : '';
			if (isset($_POST['birthday']) && isset($_POST['dob_month']) && isset($_POST['dob_year'])) {
				$user->birthday = $_POST['birthday'] . '/' . $_POST['dob_month'] . '/' . $_POST['dob_year'];
			}
			$user->age = isset($_POST['age']) ? $_POST['age'] : '';
			$user->sex = isset($_POST['sex']) ? $_POST['sex'] : '';
			$user->occupation = isset($_POST['occupation']) ? $_POST['occupation'] : '';
			$user->company = isset($_POST['company']) ? $_POST['company'] : '';
			$user->address = isset($_POST['address']) ? $_POST['address'] : '';
			$user->city = isset($_POST['city']) ? $_POST['city'] : '';
			$user->county = isset($_POST['county']) ? $_POST['county'] : '';
			$user->postcode = isset($_POST['postcode']) ? $_POST['postcode'] : '';
			$user->country = isset($_POST['country']) ? $_POST['country'] : '';
			$user->telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
			$user->mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
			$user->fax = isset($_POST['fax']) ? $_POST['fax'] : '';
			$user->website = isset($_POST['website']) ? $_POST['website'] : '';
			if (isset($_POST['dataProtection']) && $_POST['dataProtection'] == 'yes') {
				$user->dataProtection = 1;
			}
			else {
				$user->dataProtection = 0;
			}

			$answers = array();
			if (isset($_POST['answers'])) {
				$answers = $_POST['answers'];
			}

			$errors = $user->getMissingFields($registerPreferences, $targetingRules1, $targetingRules2, $answers);

			unset($errors['occupation']);
			unset($errors['company']);
			unset($errors['county']);
			unset($errors['telephone']);
			unset($errors['mobile']);
			unset($errors['fax']);

			if (count($errors) == 0) {
				Jadu_Service_User::getInstance()->saveUser($user, $answers);
				header("Location: " . PROTOCOL . DOMAIN . buildUserHomeURL() . '?detailsChanged=true');
				exit();
			}
		}
	}

	$dob_array = explode('/', $user->birthday);
	$birthday = $dob_array[0];
	if (count($dob_array) == 3) {
		$dob_month = $dob_array[1];
		$dob_year = $dob_array[2];
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Change your details';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSecureSiteRootURL() . buildUserHomeURL() . '">Your account</a></li><li><span>Change your details</span></li>';
	
	include("change_details.html.php");
?>