<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> - <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="news, rss, rich site summary, feed, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> RSS News Feed - Really Simple Syndication" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>  RSS news feed" />
	<meta name="DC.identifier" content="http://<?php print DOMAIN . encodeHtml($_SERVER['PHP_SELF']); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
	<h2>What is RSS?</h2>
		
	<p><abbr title="Really Simple Syndication">RSS</abbr> (Really Simple Syndication) is a simple way to keep up to date with the latest news on a web site without actually having to visit the web site.</p>
	
	<p>You simply use an RSS reader (in most cases available free of charge), which you install onto your PC or Mac. </p>
	
	<p>The RSS reader will automatically download the latest news from your favourite selected web sites. So you can keep up to date easily, and instantly.</p>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>