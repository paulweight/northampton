<?php
	include_once("utilities/JaduStatus.php");
	
	// Force onto SSL if required.
	if ((defined('SSL_ENABLED') && SSL_ENABLED) && PROTOCOL != 'https://') {
		header("Location: ".getSecureSiteRootURL().$_SERVER['REQUEST_URI']);
		exit;
	}
	
	include_once("JaduStyles.php");
	include_once("marketing/JaduRegisterPreferences.php");
	include_once("marketing/JaduTargetingRules.php");
	include_once("marketing/JaduUsers.php");
	include_once('marketing/JaduPHPBB3.php');

	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
		header('Location: ' . buildChangeDetailsURL());
		exit();
	}
	
	if (!Jadu_Service_User::getInstance()->canRegisterUser()) {
		header('Location: ' . buildSignInURL());
		exit();
	}
	
	$registrationFailed = false;
	$registerPreferences = new RegisterPreferences();
	$targetingRules1 = getTargetingRule(1);
	$targetingRules2 = getTargetingRule(2);
	
	$user = new User();
    
	if (isset($_POST['submit'])) {
		$user->email = isset($_POST['reg_email']) ? $_POST['reg_email'] : '';
		$user->password = isset($_POST['reg_password']) ? $_POST['reg_password'] : '';
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
			
		$errors = $user->getMissingFields($registerPreferences, $targetingRules1, $targetingRules2, $answers, true);

		unset($errors['occupation']);
		unset($errors['company']);
		unset($errors['county']);
		unset($errors['telephone']);
		unset($errors['mobile']);
		unset($errors['fax']);

		if (!isset($_POST['email_conf']) || $user->email != $_POST['email_conf']) {
			$errors['emailsNotSame'] = true;
		}
		
		if (mb_strlen($user->password) < 6 || mb_strlen($user->password) > 30) {
			$errors['password'] = true;
		}
		
		if (!isset($_POST['password_conf']) || $user->password != $_POST['password_conf']) {
			$errors['passwordMismatch'] = true;
		}
	
		if (sizeof($errors) == 0) {
			if (!$registerPreferences->emailAuthentication) {
				$user->authenticated = AUTHENTICATED;
			}
			
			// create a phpbb user if necessary
			if (defined('PHPBB_INTEGRATION') && PHPBB_INTEGRATION == true) {
				newPHPBBUser($user);
			}

			$user->id = Jadu_Service_User::getInstance()->saveUser($user, $answers);

			if ($user->isAuthenticated()) {
				Jadu_Service_User::getInstance()->logSessionIn($user->id);
				header("Location: " . getSecureSiteRootURL() . buildRegisterAcceptURL());
				exit();
			}
			
			header("Location: " . buildRegisterAuthURL() . "?email=$user->email&new=true");
			exit();
		}
		else {
		    $registrationFailed = true;
		}
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Register';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Register</span></li>';
	
	include("register.html.php");
?>