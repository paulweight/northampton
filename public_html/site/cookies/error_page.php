<?php
	session_start();
	include_once("JaduConstants.php");
	
/*	if (isset($TestCookie)) {
		$string = "http://".$DOMAIN."/site/index.php";
    	header("Location: $string");
    	exit;
    } else {
    	setcookie ("TestCookie",'Test',(time()+60));
    }
 */ 
 	include("../includes/doctype.php"); 
?>
	<head>
		<title><?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
		
		<link rel="stylesheet" type="text/css" href="<?php print getStaticContentRootURL(); ?>/site/styles/generic/base.css" />
	</head>
	<body id="pageOfflive">
		<div>
		  <img src="../styles/css_img/logo.gif" alt="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />					
			<h1>You have arrived at this page for one of FOUR reasons:</h1>
			<ul>
				<li>We have detected that your browser has cookies turned off</li>
				<li>Your cookie has expired</li>
				<li>Your session has timed out</li>
				<li>You are not authorised to view this page</li>
			</ul>
			<p>Please activate cookies in your browser&#39;s preferences before continuing.</p>
			<p><a href="../index.php">Return to the site.</a></p>
		</div>
	</body>
</html>