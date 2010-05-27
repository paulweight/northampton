<?php
	session_start();
	require_once('JaduConstants.php');
	require_once('rupa/JaduRupaGoogle.php');
	require_once('rupa/JaduRupaUser.php');
	require_once('rupa/JaduRupaUserLogin.php');
	require_once('rupa/JaduRupaSearchLog.php');
	require_once('rupa/JaduRupaSeasoning.php');
	require_once('rupa/JaduRupaUserPreferences.php');
	require_once('rupa/JaduRupaGSACollection.php');
	require_once('rupa/JaduRupaAppliance.php');
	
	include_once('includes/login_header.php');

	$liveAppliances = getRupaAppliances('live', true, '=');
	if (empty($liveAppliances)) {
		header('Location: http://'.DOMAIN.'/search/offline.php');
	}
	
	$defaultStylesheet = getSeasoningStylesheet(STYLESHEET);
	
	define(PAGE_SEARCH_RESULT_COUNT, 10);
	define(MAXIMUM_NAV_PAGE_COUNT, 10);

	if($frontend == '') {
		$frontend = 'default_frontend';
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<title><?php print RUPA_INSTALLATION_NAME; ?> - Preferences</title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<link rel="search" type="application/opensearchdescription+xml" href="http://<?php print DOMAIN; ?>/search/openSearch.php" title="<?php print METADATA_GENERIC_COUNCIL_NAME; ?>" />
	<link rel="stylesheet" type="text/css" href="<?php print $defaultStylesheet->fullWebPath;?>" media="screen" />
	<link rel="Shortcut Icon" type="image/x-icon" href="<?php print RUPA_HOME_URL; ?>favicon.ico" />
	
	<script src="<?php print RUPA_HOME_URL; ?>javascript/rupa.js" type="text/javascript"></script>	
	<!--[if gte IE 5.5]><![if lt IE 7]>
		<style type="text/css">
			div#content_browse {
				right: auto; bottom: auto;
				left: expression( ( 0 - content_browse.offsetWidth + ( document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.clientWidth ) + ( ignoreMe2 = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft ) ) + 'px' );
				top: expression( ( -35 - content_browse.offsetHeight + ( document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight ) + ( ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop ) ) + 'px' );
			}
		</style>
	<![endif]><![endif]-->
</head>
<body id="rupa_admin">
<?php include_once('includes/search_mast.php'); ?>

<div id="trackrefine">
<h2>My Preferences</h2>
		<form name="searchAreaForm" id="searchAreaForm" method="get" action="<?php print RUPA_HOME_URL; ?>scripts/google_results.php" class="refine_search_form">
			<fieldset>
				<legend>Search <?php print SYSTEM_NAME; ?></legend>
				
				<span>
					<label for="search_type">Default site:</label>
					<select class="select_group wide" id="search_type">
						<option value="all" <?php if ($userPrefs->search_type == 'all' || empty($userPrefs->search_type)) print 'selected="selected"'; ?>>Search everything</option>
						<option value="prefs" <?php if ($userPrefs->search_type == 'prefs') print 'selected="selected"'; ?>>Use my preferences</option>
<?php
					foreach ($allCollections as $collection) {
?>
						<option value="<?php print $collection->id; ?>" <?php if ($userPrefs->search_type == $collection->id) print 'selected="selected"'; ?>><?php print $collection->friendly_name; ?></option>
<?php
					}
?>
					</select>
				</span>
			</fieldset>
			<fieldset>
				<!-- Refine search -->
				<?php include_once("includes/refine.php"); ?>
	
			</fieldset>
		</form>
</div>	
		<!-- Draw -->	
		<?php include_once("includes/drawer.php"); ?> 

</body>
</html>
