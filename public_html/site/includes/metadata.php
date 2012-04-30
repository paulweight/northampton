	<link rel="search" type="application/opensearchdescription+xml" title="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>" href="<?php print getSiteRootURL(); ?>/site/scripts/opensearch.php" />

	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" /> 

	<!-- general metadata -->
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="generator" content="http://www.jadu.net" />
	<meta name="robots" content="index,follow" />
	<meta name="revisit-after" content="2 days" />
	<meta name="Author-Template" content="Jadu CSS design" />
	<meta name="Author" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />
	<meta name="Publisher-Email" content="<?php print encodeHtml(METADATA_PUBLISHER_EMAIL); ?>" />
	<meta name="Coverage" content="<?php print encodeHtml(METADATA_COVERAGE); ?>" />

<?php
	$exclusions = array('documents_info.php', 'home_info.php', 'home_preview.php', 
		'meetings_info.php', 'news_article.php', 'services_info.php', 
		'directory_record.php', 'directory_home.php');
	
	if (isset($_GET['eventID']) && $_GET['eventID'] >= 0) {
		$exclusions[] = 'events_info.php';
	}
	
	//	Only XFP has Metadata
	if (defined('XFORMS_PROFESSIONAL_VERSION')) {
		$exclusions[] = 'xforms_form.php';
	}
	
	if (!in_array(basename($_SERVER['PHP_SELF']), $exclusions)) {
?>
	<meta http-equiv="content-language" content="en" />

	<!-- Dublin Core Metadata -->
	<meta name="DC.creator" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />
	<meta name="DC.date.created" lang="en" scheme="DCTERMS.W3CDTF" content="2005-04-01" />
	<meta name="DC.format" lang="en" scheme="DCTERMS.IMT" content="text/html" />
	<meta name="DC.language" scheme="DCTERMS.ISO639-1" content="en" />
	<meta name="DC.publisher" lang="en" content="<?php print encodeHtml(METADATA_PUBLISHER); ?>" />
	<meta name="DC.rights.copyright" lang="en" content="<?php print encodeHtml(METADATA_RIGHTS); ?>" />
	<meta name="DC.coverage" lang="en" content="<?php print encodeHtml(METADATA_COVERAGE); ?>" />
	<meta name="DC.identifier" scheme="DCTERMS.URI" content="<?php print PROTOCOL . DOMAIN . encodeHtml($_SERVER['REQUEST_URI']); ?>" />

	<!-- eGMS Metadata -->
	<meta name="eGMS.status" lang="en" content="<?php print encodeHtml(METADATA_STATUS); ?>" />
<?php
	}
?>

	<meta name="eGMS.accessibility" scheme="WCAG" content="<?php print encodeHtml(METADATA_ACCESSIBILITY); ?>" />
