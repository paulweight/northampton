<?php
	require_once('includes/login_header.php');
	require_once('rupa/JaduRupaCollection.php');
	require_once('rupa/JaduRupaAppliance.php');
	
	$allCollections = getAllRupaCollections();

	define('PAGE_SEARCH_RESULT_COUNT', 10);
	define('MAXIMUM_NAV_PAGE_COUNT', 10);
	
	$liveAppliances = getRupaAppliances('live', true, '=');
	if (empty($liveAppliances)) {
		header('Location: http://'.DOMAIN.'/search/offline.php');
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<title><?php print RUPA_INSTALLATION_NAME; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="search" type="application/opensearchdescription+xml" href="<?php print RUPA_HOME_URL; ?>opensearch.php" title="<?php print RUPA_INSTALLATION_NAME; ?>">
	<link rel="stylesheet" type="text/css" href="<?php print RUPA_HOME_URL; ?>styles/<?php print encodeHtml(RUPA_STYLESHEET); ?>" media="screen">
	<link rel="Shortcut Icon" type="image/x-icon" href="<?php print getStaticContentRootURL(); ?>/favicon.ico">
 
	<script src="<?php print RUPA_HOME_URL; ?>javascript/rupa.js" type="text/javascript"></script>	
	<!--[if IE 6]>
	<link rel="stylesheet" type="text/css" href="<?php print RUPA_HOME_URL; ?>styles/ie6.css" media="screen">
	<![endif]-->	
</head>
<body id="rupa_home">
	<div id="container">

<?php
		include_once('includes/navigation_links.php');
?>

		<h1><span><?php print RUPA_INSTALLATION_NAME; ?></span></h1>

		<form name="searchAreaForm" id="searchAreaForm" method="get" action="<?php print RUPA_HOME_URL.'results.php'; ?>">
			<fieldset>
				<legend><?php print RUPA_INSTALLATION_NAME; ?></legend>
				<!-- Keyword -->
				<p class="home_keyword">
					<label for="googleSearchBox">Enter keyword</label>
					<input class="keyword_field" type="text" name="q" id="googleSearchBox" value="">
				</p>
				<p class="home_submit">
					<input class="big_button" type="submit" name="googleSearchSubmit" value="Search">
				</p>
			</fieldset>
		</form>
	</div>
<?php include_once("includes/footer.php"); ?> 
</body>
</html>
