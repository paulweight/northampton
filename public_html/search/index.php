<?php
	session_start();
	require_once('rupa/JaduRupaSeasoning.php');
	require_once('rupa/JaduRupaCollection.php');
	require_once('includes/login_header.php');
	require_once('rupa/JaduRupaAppliance.php');
	
	
	$allCollections = getAllRupaCollections();

	$defaultStylesheet = getSeasoningStylesheet(RUPA_STYLESHEET);

	define(PAGE_SEARCH_RESULT_COUNT, 10);
	define(MAXIMUM_NAV_PAGE_COUNT, 10);
	
	$liveAppliances = getRupaAppliances('live', true, '=');
	if (empty($liveAppliances)) {
		header('Location: http://'.DOMAIN.'/search/offline.php');
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<title><?php print RUPA_INSTALLATION_NAME; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<link rel="search" type="application/opensearchdescription+xml" href="http://<?php print DOMAIN; ?>/search/openSearch.php" title="<?php print RUPA_INSTALLATION_NAME; ?>" />
	<link rel="stylesheet" type="text/css" href="<?php print $defaultStylesheet->fullWebPath;?>" media="screen" />
	<link rel="Shortcut Icon" type="image/x-icon" href="<?php print SHARED_HOME_URL; ?>favicon.ico" />
 
	<script src="<?php print RUPA_HOME_URL; ?>javascript/rupa.js" type="text/javascript"></script>	
	<!--[if IE 6]>
	<link rel="stylesheet" type="text/css" href="<?php print RUPA_HOME_URL; ?>styles/ie6.css" media="screen" />
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
					<input class="keyword_field" type="text" name="q" id="googleSearchBox" value="<?php print $q;?>" />
				</p>
				<p class="home_submit">
					<input class="big_button" type="submit" name="googleSearchSubmit" value="Search" />
				</p>
				<p class="siteSelection">
					<label for="searchGroup" id="searchGroupLabel">Specific sites: </label>
					<select class="select_group" name="searchType" id="searchGroup">
						<option value="all">Search everything</option>
<?php
					foreach ($allCollections as $collection) {
?>
						<option value="<?php print $collection->id; ?>" ><?php print $collection->friendlyName; ?></option>
<?php
					}
?>
						</option>
					</select>
				</p>
			</fieldset>
		</form>
	</div>	
	<div id="drawer">
		<!-- Drawer -->	
		<?php include_once("includes/footer.php"); ?> 
	</div>
</body>
</html>
