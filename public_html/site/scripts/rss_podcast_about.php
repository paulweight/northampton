<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");

	$breadcrumb = 'rssPodcastAbout';	
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
			
    <h2>What is a Podcast feed?</h2>
	<p>A Podcasting feed is the method of distributing multimedia files, such as audio programs or music videos, over the Internet for playback on mobile devices and personal computers. Podcasts are distributed using either the <a href="http://<?php print $DOMAIN;?>/site/scripts/rss_about.php" title="What is an RSS feed?">RSS or Atom syndication formats</a>.</p>
	<p>Podcasters' web sites may also offer direct download or streaming of their files. However, a podcast is distinguished by its ability to be downloaded automatically using software capable of reading RSS or Atom feeds.</p>
	<p>Usually the podcast features one type of &quot;show&quot;, with new episodes either sporadically or at planned intervals, such as daily or weekly. In addition, there are podcast networks that feature multiple shows on the same feed. One can listen to a podcast either on a computer or on a mobile audio device (such as an iPod, or other MP3 players).</p>

	<p>Podcasting's essence is about creating content (audio or video) for an audience that wants to listen or watch when they want, where they want, and how they want.</p>
	<p>A popular program for managing podcasts of both audio, and video, is <a href="http://www.apple.com/itunes/" title="Get iTunes">iTunes</a>.</p>
		
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>