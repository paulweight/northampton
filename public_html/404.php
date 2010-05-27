<?php
    include_once("utilities/JaduStatus.php");
    include_once("JaduConstants.php");
	include_once("JaduStyles.php");
	
	header("HTTP/1.0 404 Not Found"); 
   
    $request = urldecode($_SERVER['REQUEST_URI']);
   
    $fileExtension = substr(strrchr($request, '.'), 1);

    if ($fileExtension != '') {
	$request = str_replace($fileExtension, '', $request);
    }
    
    $request = str_replace(array('<', '>', '=', '.', ',', '=', '?', '&', ';', '%', '/'), '/', $request);
    $request = str_replace(array('site/', 'scripts/'), '', $request);
    
    $terms = explode("/", $request);
    
    $searchQuery = htmlentities(trim(implode($terms, ' ')));
    
	$breadcrumb = '404';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Page not found</title>
	<?php include_once($HOME . "site/includes/stylesheets.php"); ?>
	<?php include_once($HOME . "site/includes/metadata.php"); ?>

	<meta name="Keywords" content="Accessibility, dda, disability discrimination act, disabled access, access keys, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Accessibility features" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("site/includes/opening.php"); ?>
<!-- #################################### -->

		<p class="first">For one reason or another (mis-typed URL, faulty referral from another site, out-of-date search engine listing or we simply deleted a file) the page you were after is not here - this site has recently undergone a major re-working, so that might explain why you got this page instead.</p>
		
		<p>We apologise for any inconvenience caused.</p>

		<form class="basic_form" method="get" action="http://<?php print $DOMAIN ?>/site/scripts/google_results.php">
			<p>
				 <input type="text" name="q" maxlength="256" value="<?php print $searchQuery; ?>" class="field" />
				 <input type="submit" name="btnG" value="Search" class="button" />
			</p>
		</form>
		

	<!-- The Contact box -->
	<?php include($HOME . "site/includes/contactbox.php"); ?>

<!-- ########## MAIN STRUCTURE ########## -->
<?php include($HOME . "site/includes/closing.php"); ?>