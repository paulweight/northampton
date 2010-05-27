<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("JaduMetadata.php");
	include_once("websections/JaduFeedManager.php");	

	$allRSSItems = getAllRSSItemsForNews(); 

	$viewMode = false;
	if (isset($_GET['view'])) {
		if ($_GET['view'] == 'feed') {
			
			if (!isset($_GET['feedID']) || !is_numeric($_GET['feedID'])) {
				header("Location: http://$DOMAIN/site/index.php");
				exit();				
			}
			
			$viewMode = true;
			$RSSItem = getRSSItem($_GET['feedID']);

			if ($RSSItem == null) {
				header("Location: http://$DOMAIN/site/index.php");
				exit();
			}
			
			$RSSTitle = getRSSTitle($RSSItem->url);
			$parsedRSSItems = parseRSS($RSSItem->url);
		}
		else {
			header("Location: http://$DOMAIN/site/index.php");
			exit();
		}
	}

	$title = METADATA_GENERIC_COUNCIL_NAME . " RSS Feeds";
	
	$breadcrumb = 'viewFeeds';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $RSSItem->name;?> | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="news, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />	
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> news feed directory" />

	<meta name="DC.title" lang="en" content="<?php print $title;?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s latest news directory" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->	

<?php
	if ($viewMode) {
?>
		<h2><?php print $RSSTitle;?></h2>	
<?php
		$i = 1;
	
		foreach ($parsedRSSItems as $parsedRSSItem) {
			if ($i > $RSSItem->noItems && ($RSSItem->noItems != 0)) {
				break;
			}
			print '<div class="content_box">';
			print '<h3 class="extLinks"><span><a href="'.$parsedRSSItem['link'].'">'.$parsedRSSItem['title'].'</a></span></h3>';
			print '<p class="date">'.$parsedRSSItem['date'].'</p>';
			$description = $parsedRSSItem['description'];
			$description = html_entity_decode($description);
			print '<p>'.strip_tags($description).'</p>';
			print '</div>';
			$i++;
		}

	}

	else {
?>
		<div class="cate_info">
		
		<p class="first">Choose a feed from below.</p>
		
<?php
		foreach ($allRSSItems as $RSSItem) {
			print '<ul class="list">';
			print '<li><a href="http://'.$DOMAIN .'/site/scripts/view_feeds.php?view=feed&amp;feedID='.$RSSItem->id.'">'.$RSSItem->name.'</a></li>';
			print '</ul>';
		}
?>
		</div>
<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>