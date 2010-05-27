<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");

	$breadcrumb = 'rssAbout';	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>About RSS news feed | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="news, rss, rich site summary, feed, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> RSS News Feed - Really Simple Syndication" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>  RSS news feed" />
	<meta name="DC.identifier" content="http://<?php print $DOMAIN.$_SERVER['PHP_SELF'];?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
	<h2>What is RSS?</h2>
		
	<p><abbr title="Really Simple Syndication">RSS</abbr> (Really Simple Syndication) is a simple way to keep up to date with the latest news on a web site without actually having to visit the web site.</p>
	
	<p>You simply use an RSS reader (in most cases available free of charge), which you install onto your PC or Mac. </p>
	
	<p>The RSS reader will automatically download the latest news from your favourite selected web sites. So you can keep up to date easily, and instantly.</p>
	
	<h3>Getting started with RSS</h3>
	
	<p>Firstly, you need an RSS reader for your PC or Mac. There are many different readers out there - have a look at the <a href="http://www.google.com/Top/Computers/Software/Internet/Clients/WWW/Feed_Readers/" >Google list here</a>, where there are dozens listed.
	</p>
	<p>Some free web browsers, such as <a href="http://www.opera.com/">Opera</a>, actually have an RSS reader built into the browser.</p>
	
	<p>Once you have downloaded and installed an RSS reader, copy the link at the bottom of the <a href="http://<?php print $DOMAIN;?>/site/scripts/news_index.php">News Homepage</a> to copy into your RSS reader.</p>
	<p>It looks like this:</p>
	
	<p>
		<a href="http://<?php print $DOMAIN;?>/site/scripts/rss.php"><img src="http://<?php print $DOMAIN;?>/site/images/xml.gif" alt="RSS Newsfeed" /></a> <a href="http://<?php print $DOMAIN;?>/site/scripts/rss.php">RSS version</a>
	</p>
	
	<p>The BBC have excellent support for RSS, including some good resources and information. </p>
	<p>All their news is published by category - <a href="http://news.bbc.co.uk/1/hi/help/rss/default.stm">View the BBC's RSS section</a>.</p>
		
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>