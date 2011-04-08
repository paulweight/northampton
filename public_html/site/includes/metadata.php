	<link rel="search" type="application/opensearchdescription+xml" href="/openSearch.php" title="<?php print METADATA_GENERIC_COUNCIL_NAME; ?>" />

	<!-- general metadata -->
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="content-language" content="en" />
	<meta name="generator" content="http://www.jadu.net" />
	<meta name="robots" content="index,follow" />
	<meta name="revisit-after" content="2 days" />
	<meta name="Author-Template" content="Jadu CSS design" />
	<meta name="Author" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>" />
	<meta name="Publisher" content="<?php print METADATA_PUBLISHER;?>" />
	<meta name="Publisher-Email" content="<?php print METADATA_PUBLISHER_EMAIL;?>" />
	<meta name="Coverage" content="<?php print METADATA_COVERAGE;?>" />
	
	<!-- ICRA PICS label -->
	<?php print METADATA_ICRA_LABEL;?>

<?php
	$exclusions = array('documents_info.php', 'home_info.php', 'home_preview.php', 
		'meetings_info.php', 'news_article.php', 'services_info.php');
	
	if (!in_array(basename($_SERVER['PHP_SELF']), $exclusions)) {
?>	
	<!-- Dublin Core Metadata -->
	<meta name="DC.creator" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>" />
	<meta name="DC.date.created" lang="en" content="2005-04-01" />
	<meta name="DC.format" lang="en" content="text/html" />
	<meta name="DC.language" content="en" />
	<meta name="DC.publisher" lang="en" content="<?php print METADATA_PUBLISHER;?>" />
	<meta name="DC.rights.copyright" lang="en" content="<?php print METADATA_RIGHTS;?>" />
	<meta name="DC.coverage" lang="en" content="<?php print METADATA_COVERAGE;?>" />
	<meta name="DC.identifier" content="<?php print $PROTOCOL . DOMAIN . htmlentities($_SERVER['REQUEST_URI']); ?>" />

	<!-- eGMS Metadata -->
	<meta name="eGMS.status" lang="en" content="<?php print METADATA_STATUS;?>" />
	<meta name="eGMS.accessibility" scheme="WCAG" content="<?php print METADATA_ACCESSIBILITY;?>" />
<?
	}
?>
