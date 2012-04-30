<?php
	include_once('lib.php');
	include(HOME . "site/includes/structure/breadcrumb.php");
	
	include_once('websections/JaduAnnouncements.php');
	$liveUpdate = getLiveAnnouncement();

	if (basename($_SERVER['SCRIPT_NAME']) == 'index.php' || Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
    	include_once('websections/JaduTrackedURLs.php');
    	include_once('websections/JaduTrackedURLResults.php');
    }

    $trackedURLs = array();

    if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {

    	if (isset($_REQUEST['trackedURLRead'])) {
    	    $trackedURLResult = getTrackedURLResult(Jadu_Service_User::getInstance()->getSessionUserID(), $_REQUEST['trackedURLID']);
    	    confirmTrackedURLRead($trackedURLResult->id);
    	}
    	elseif (isset($_REQUEST['trackedURLID']) && is_numeric($_REQUEST['trackedURLID'])) {
    	    $trackedURL = getTrackedURL($_REQUEST['trackedURLID']);

    	    $trackedURLResult = getTrackedURLResult(Jadu_Service_User::getInstance()->getSessionUserID(), $trackedURL->id);

    	    if ($trackedURLResult->id == -1) {
                $trackedURLResult->userID = Jadu_Service_User::getInstance()->getSessionUserID();
                $trackedURLResult->trackedURLID = $trackedURL->id;
                newTrackedURLResult($trackedURLResult);
    	    }
    	    elseif ($trackedURLResult->confirmedRead > 0) {
    	        unset($trackedURL);
    	    }
    	}
    	
    	if (basename($_SERVER['SCRIPT_NAME']) == 'index.php') {
            if (!Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
    	        $trackedURLs = getAllTrackedURLs(true);
            }
            else {
                $trackedURLs = getAllUnreadTrackedURLsForUser(Jadu_Service_User::getInstance()->getSessionUserID());
            }
        }
	}
	$hideColumn = (boolean) hideColumn();

	$script = basename($_SERVER['SCRIPT_NAME']);
?>
<!--[if lt IE 7]> <body class="ie6<?php if (($script != "documents_info.php" && $hideColumn == false ) || ($script == "documents_info.php" && $pageStructure->id != '2')) { print ' threeCol'; } else { print ' twoCol'; } ?>"> <![endif]-->
<!--[if IE 7]>    <body class="ie7<?php if (($script != "documents_info.php" && $hideColumn == false ) || ($script == "documents_info.php" && $pageStructure->id != '2')) { print ' threeCol'; } else { print ' twoCol'; } ?>"> <![endif]-->
<!--[if IE 8]>    <body class="ie8<?php if (($script != "documents_info.php" && $hideColumn == false ) || ($script == "documents_info.php" && $pageStructure->id != '2')) { print ' threeCol'; } else { print ' twoCol'; } ?>"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <body <?php if (($script != "documents_info.php" && $hideColumn == false ) || ($script == "documents_info.php" && $pageStructure->id != '2')) { print ' class="threeCol"'; } else { print ' class="twoCol"'; } ?>"> <!--<![endif]-->

<div id="wrapper">
		<?php include(HOME . "site/includes/structure/header.php"); ?>
						
<?php
/* ANNOUNCMENTS */
	if ($liveUpdate->id != '' && $liveUpdate->id != -1) {
?>
					<div id="announcement">
						<div class="h2"><?php print encodeHtml($liveUpdate->title); ?></div>
						<p><?php print encodeHtml($liveUpdate->content); ?></p>
<?php
		if ($liveUpdate->url != '') {
?>
						<p><a href="<?php print encodeHtml($liveUpdate->url); ?>"><?php print encodeHtml($liveUpdate->linkText); ?></a>.</p>
<?php
		}
?>
					</div>
<?php
	}
	// tracked url
	if (isset($trackedURL) && $trackedURL->id != -1) {
?>
	<div id="trackedURL">
    	<div class="h2">Have you read this page?</div>
    	<p><?php print encodeHtml($trackedURL->title); ?></p>
    	<p><?php print $trackedURL->description; ?></p>
    	<form action="<?php print getSiteRootURL(); ?>/site/" method="post" enctype="multipart/form-data">
    	    <input type="hidden" name="trackedURLID" value="<?php print (int) $trackedURL->id; ?>" />
    	    <input type="submit" name="trackedURLRead" value="I have read this page" />
    	</form>
	</div>
<?php
	}
	elseif (sizeof($trackedURLs) > 0) {
?>
	<div id="trackedURL">
    	<div class="h2">Have you read these pages?</div>
    	<ul>
<?php
        foreach ($trackedURLs as $trackedURL) {
            $trackedURLID = 'trackedURLID=' . $trackedURL->id;
            if (mb_strpos($trackedURL->url, '?') === false) {
                $trackedURLID = '?' . $trackedURLID;
            }
            else {
                $trackedURLID = '&' . $trackedURLID;
            }
?>
            <li><a href="<?php print encodeHtml($trackedURL->url . $trackedURLID); ?>"><?php print encodeHtml($trackedURL->urlText); ?></a></li>
<?php
        }
?>
    	</ul>
	</div>
<?php
    }
?>

<?php
/*  
	BREADCRUMB
	* The breadcrumb can be updated by editing site/inclides/structure/breadcrumb.php
*/
	if (!isset($indexPage) || !$indexPage) {
?>
					<div id="breadcrumb">
<?php
		if (!empty($MAST_BREADCRUMB)) {
?>		
						<!-- Breadcrumb --><!-- googleoff:all -->
						<ul><?php print $MAST_BREADCRUMB; ?></ul>
						<!-- END Breadcrumb --><!-- googleon:all -->
<?php
	    }
	    else {
?>
						<!-- Breadcrumb --><!-- googleoff:all -->
						<ul><li class="bc_end"><a href="<?php print getSiteRootURL(); ?>"><em>You are here:</em> Home</a></li></ul>
						<!-- END Breadcrumb --><!-- googleon:all -->
<?php
	    }
?>
					</div>
<?php
	}
?>

<?php
/*  
	PRIMARY COLUMN
	* To show/hide this column, edit site/includes/lib.php
*/
	$hideColumn = (boolean) hideColumn();
	
	if (($script != "documents_info.php" && $hideColumn == false ) || ($script == "documents_info.php" && $pageStructure->id != '2')) {
		include(HOME . "/site/includes/structure/column.php");
	}
?>

<?php
/* CONTENT */
?>
				<div id="content">
				
<?php
	if (!isset($indexPage) || !$indexPage) {
?>					
					<h1><?php print encodeHtml($MAST_HEADING); ?></h1>					
<?php
	}
	else {
?>
					<h1 class="hidden"><?php print encodeHtml($MAST_HEADING); ?></h1>
<?php
	}
?>