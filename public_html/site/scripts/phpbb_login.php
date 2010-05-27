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
	include_once('JaduDatabaseConstants.php');
	include_once('ext/adodb/adodb.inc.php');
    
	$jaduDB = NewADOConnection(DB_DBMS);
	if (DB_USE_DSN) {
		$jaduDB->Connect(DB_DSN, DB_USERNAME, DB_PASSWORD, DB_NAME);		
	}
	else {
		$jaduDB->Connect(DB_HOST.DB_PORT, DB_USERNAME, DB_PASSWORD, DB_NAME);		
	}
	$jaduDB->SetFetchMode(ADODB_FETCH_ASSOC);

	$includePath = ini_get('include_path');
	$pos = strrpos($includePath,'jadu');
	$homeDir = substr_replace($includePath, '', $pos, strlen('jadu'));
	$homeDir = str_replace('\\','/',$homeDir);
	$homeDir = str_replace('//','/',$homeDir);
	define('HOME_DIR', $homeDir);

	// PHPBB 3 Constants and includes
	define("IN_LOGIN", true);
	define('IN_PHPBB', true);
	$phpbb_root_path = HOME_DIR . '/phpBB/';
	$phpEx = 'php';
	include($phpbb_root_path . '/common.php');

	// Start session management
	$user->session_begin();
	$auth->acl($user->data);
	$user->setup('viewforum');

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
		$authenticate = '';

        	$query = "SELECT forename, surname, email, password " .
                	 "FROM JaduUsers " .
	                 "WHERE id = '" . $_SESSION['userID'] . "'";

        	$result = $jaduDB->Execute($query);

		if (!$result->EOF) {
			$email = strtolower($result->fields['email']);
			$username = $result->fields['forename'] . ' ' . $result->fields['surname'];
			$password = $result->fields['password'];

			$query = 'SELECT username FROM phpbb3_users WHERE user_email LIKE \''.$email.'\';';
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

	// logout of phpbb
	if (isset($_GET['logout'])) {
		$user->session_kill();
		$user->session_begin();

        	header('Location: /site/index.php');
		exit();
	}
?>
