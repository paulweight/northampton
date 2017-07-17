<?php 
	include_once("utilities/JaduStatus.php");
	
	// Force onto SSL if required.
	if ((defined('SSL_ENABLED') && SSL_ENABLED) && PROTOCOL != 'https://' && isset($_GET['sign_in'])) {
		header("Location: ". getSecureSiteRootURL() . $_SERVER['REQUEST_URI']);
		exit;
	}
	
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("websections/JaduContact.php");

	// for homepages
	include_once("websections/JaduHomepages.php");	
	include_once("websections/JaduHomepageWidgetsToHomepages.php");		
	include_once("websections/JaduHomepageWidgets.php");		
	include_once("websections/JaduHomepageWidgetSettings.php");

	$allIndependantHomepages = getAllHomepagesIndependant(HOMEPAGE_VISIBLE, true, 1, 0);
	if (count($allIndependantHomepages) > 0) {
		$homepage = getHomepage($allIndependantHomepages[0]->id, true);
		if ($homepage->id != -1) {
			// Following commented out for ref:20131223-16
			/*
			if ($homepage->stylesheet != '') {
				if(!isset($_GET['previewstyle']) && !isset($_GET['switchstyle']) && !isset($_SESSION['switchstyle'.$site->id])) {
					$STYLESHEET = $homepage->stylesheet;
				}
			}
			*/
			$homepageSections = array();
			foreach ($homepage->getWidgetsToHomepages() as $content) {
				if (!isset($homepageSections[$content->positionY])) {
					$homepageSections[$content->positionY] = array();
				}
				if ($content->stackPosition > 0) {
					if (!isset($homepageSections[$content->positionY][$content->positionX])) {
						$homepageSections[$content->positionY][$content->positionX] = array();
					}
					$homepageSections[$content->positionY][$content->positionX][] = $content;
				}
				else {
					$homepageSections[$content->positionY][] = $content;
				}
			}
			
			if ($homepage->stylesheet != '' && $STYLESHEET != 'generic/handheld.css' && !isset($_GET['previewstyle']) && !isset($_GET['switchstyle']) && !isset($_SESSION['switchstyle'.$site->id])) {
				$STYLESHEET = $homepage->stylesheet;
			}
		}
	}
	
	if (isset($_GET['logout'])) {
		Jadu_Service_User::getInstance()->logSessionOut();
		unset($user);
		
		if (defined('PHPBB_INTEGRATION') && PHPBB_INTEGRATION == true) {
		    header('Location: ' . getSiteRootURL() . '/site/scripts/phpbb_login.php?logout=true');
		    exit();
	    }

		header('Location: ' . getSiteRootURL());
	    exit();
	} 
	else if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
		unset($_GET['sign_in']);
	}
	if (isset($_GET['loginFailed'])) {
		$_GET['sign_in'] = 'true';
	}
	
	$indexPage = true;
	$address = new Address();
	$breadcrumb = 'indexPage';
	
	include("index.html.php");
?>
