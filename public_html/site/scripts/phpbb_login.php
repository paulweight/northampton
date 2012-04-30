<?php
	/**
	* A user has been logged into Jadu so also log them into phpbb
	*
	* PHPBB has cache and user classes so functionality normaill found
	* in JaduConstants and JaduCache has been replicated here.
	*
	* PHPBB also has a $db object so the database has to be connected to 
	* separately from JaduADODB
	*/

	session_start();
	require_once('Config/Manager.php');
	require_once('utilities/JaduCrypt.php');
	include_once('ext/adodb/adodb.inc.php');
	
	/* PHPBB initilise code */

	define('IN_PHPBB', true);
	
	$includePath = ini_get('include_path');
	$pos = mb_strrpos($includePath,'jadu');
	$homeDir = substr_replace($includePath, '', $pos, mb_strlen('jadu'));
	$homeDir = str_replace('\\','/',$homeDir);
	$homeDir = str_replace('//','/',$homeDir);
	define('HOME_DIR', $homeDir);
	
	$phpbb_root_path = HOME_DIR . 'phpBB3/';
	$phpEx = substr(strrchr(__FILE__, '.'), 1);
	require($phpbb_root_path . 'common.' . $phpEx);
	require($phpbb_root_path . 'includes/functions_user.' . $phpEx);
	require($phpbb_root_path . 'includes/functions_module.' . $phpEx);
	
	
	// Start session management
	$user->session_begin();
	$auth->acl($user->data);
	$user->setup('ucp');
	
	
	/* ********* JADU initialise code ******
	* We have to get the config items this way as there are classes named such as 'cache' which are already declared in PHPBB3
	*/
	$configManager = new Jadu_Config_Manager(HOME_DIR . 'var/config');
	$systemConfig = $configManager->getConfig('system');
	if ($systemConfig !== null) {
		foreach ($systemConfig->toArray() as $name => $value) {
			$name = strtoupper($name);
			if (!defined($name)) {
				define($name, $value);
			}
		}
	}

	// create DB connection to CMS
	$jaduDB = NewADOConnection(DB_DBMS);
	if (DB_USE_DSN) {
		$jaduDB->Connect(DB_DSN, DB_USERNAME, DB_PASSWORD, DB_NAME);		
	}
	else {
		$jaduDB->Connect(DB_HOST.DB_PORT, DB_USERNAME, DB_PASSWORD, DB_NAME);		
	}
	$jaduDB->SetFetchMode(ADODB_FETCH_ASSOC);
	
	
	// logout of phpbb
	if (isset($_GET['logout'])) {
		if ($user->data['user_id'] != ANONYMOUS) {
			$user->session_kill();
			$user->session_begin();
		}
		header('Location: /site/index.php');
		exit();
	}

	if (isset($_GET['redirect']) && $_GET['redirect'] == 'viewtopic.php') {
    		header('Location: ../site/index.php?sign_in=true&refer=viewtopic.php%3fp='.$_GET['p']);
        	exit();
	}
	else if (isset($_GET['redirect']) && $_GET['redirect'] == 'posting.php') {
    		header('Location: ../site/index.php?sign_in=true&refer=posting.php%3fmode='.$_GET['mode'].'%3f'.(isset($_GET['t'])?'t='.$_GET['t']:'f='.$_GET['f']));
        	exit();
	}
	else if (isset($_GET['redirect']) && $_GET['redirect'] == 'profile.php') {
    		header('Location: ../site/index.php?sign_in=true&refer=profile.php%3fmode=editprofile');
        	exit();
	}

	// Login attempt from Jadu detected so attempt to finish auth
	if (isset($_GET['login'])) {
		// get the password from the passed in parameter and descrypt using tripleDES
		$threeDES = new TripleDES(DES_ENCRYPTION_MYSQL_KEY);
		$password = trim($threeDES->decrypt(str_replace(' ','+',$_GET['p'])));
			
		$authenticate = '';

        	$query = "SELECT forename, surname, email, password " .
                	 "FROM JaduUsers " .
	                 "WHERE id = '" . $_SESSION['userID'] . "'";

        	$result = $jaduDB->Execute($query);

		if (!$result->EOF) {
			$email = mb_strtolower($result->fields['email']);
			$username = $result->fields['forename'] . ' ' . $result->fields['surname'];

			$query = 'SELECT username FROM phpbb_users WHERE user_email LIKE \''.$email.'\';';
			$result = $jaduDB->Execute($query);
			if (!$result->EOF) {
				$username = $result->fields['username'];
			}
			
			$validation = login_db($username, $password);
			
			$valid = $validation['status'];

			if ($valid != 3) {
				header('Location: /site/index.php');
				exit();
			}

			$authenticate = $auth->login($username, $password);
		}

		if ($_REQUEST['referrer'] != "") {
        		header('Location: '.urldecode($_REQUEST['referrer']));
			exit();
		}
	        else {
        		header('Location: /site/index.php');
			exit();
		}
	}	
?>