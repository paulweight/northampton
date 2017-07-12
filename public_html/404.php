<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduConstants.php");
	include_once("JaduStyles.php");
	
	if (INSTALLATION_TYPE == GALAXY) {
		header("Location: http://$DOMAIN/404.php");
		exit();
	}
	
	if (!isset($_GET['request'])) {
		$request = urldecode($_SERVER['REQUEST_URI']);
		header("HTTP/1.0 404 Not Found");
	}
	else {
		$request = $_GET['request'];
	}
	
	$fileExtension = mb_substr(strrchr($request, '.'), 1);
	
	if ($fileExtension != '') {
		$request = str_replace($fileExtension, '', $request);
	}
	
	$terms = explode("/", $request);
	
	$searchQuery = encodeHtml(trim(implode($terms, ' ')));
	
	$breadcrumb = '404';
	
	include("./site/includes/doctype.php");
?>
<head>
	<title><?php print encodeHtml(METADATA_GENERIC_NAME);?> - Page not found</title>
	<?php include_once($HOME . "site/includes/stylesheets.php"); ?>
	<?php include_once($HOME . "site/includes/metadata.php"); ?>

	<meta name="Keywords" content="Accessibility, dda, disability discrimination act, disabled access, access keys, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS);?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME);?> Accessibility features" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME);?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include($HOME."site/includes/opening.php"); ?>
<!-- #################################### -->

		<p>For one reason or another (mis-typed URL, faulty referral from another site, out-of-date search engine listing or we simply deleted a file) the page you were after is not here - this site has recently undergone a major re-working, so that might explain why you got this page instead.</p>
		
		<p>We apologise for any inconvenience caused.</p>

		<form class="basic_form" action="<?php print getSiteRootURL() . '/improve_search'; ?>" method="get">
			<input type="hidden" name="pckid" value="1610317951" autocomplete="off">
			<input type="hidden" name="aid" value="471434" autocomplete="off">
			<p>
				<input type="text" maxlength="256" name="sw" class="field" value="<?php print isset($searchQuery) ? encodeHtml($searchQuery) : ''; ?>" autocomplete="off" />
				<input type="submit" name="btnG" value="Search" class="genericButton grey" />
			</p>
		</form>

<!-- ########## MAIN STRUCTURE ########## -->
<?php include($HOME . "site/includes/closing.php"); ?>