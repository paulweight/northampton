<?php
	session_start();
	include_once("JaduConstants.php");
	
/*	if (isset($TestCookie)) {
		$string = getSiteRootURL()."/site/index.php";
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
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="<?php print getStaticContentRootURL(); ?>/site/styles/generic/base.css" />
	</head>
	<body id="pageOfflive">
		<div>
		  <img src="../styles/css_img/offline-logo.jpg" alt="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />					
			<h1>You have arrived at this page for one of four reasons:</h1>
			<ol>
				<li>We have detected that your browser has cookies turned off</li>
				<li>Your cookie has expired</li>
				<li>Your session has timed out</li>
				<li>You are not authorised to view this page</li>
			</ol>
			<p>Please activate cookies in your browser&#39;s preferences before continuing.</p>
			<p class="centre"><a href="../index.php">Return to the site.</a></p>
		</div>
	</body>
</html>