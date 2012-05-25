<?php 
	include_once("marketing/JaduAdverts.php");
	
	$middleAdverts = getMiddleAdvertsForUser($_SESSION['userID']);
	
	if (count($middleAdverts) > 0) {
?>
<div class="middleAdvertWidget">
	<h2>Featured content</h2>
	<!-- Homepage middle advert -->
<?php            
		for ($count = 0; $count < count($middleAdverts); $count++) {
			$advert = $middleAdverts[$count];
?>
	<div class="feat_wrap">
<?php
			if (!empty($advert->imageURL)) {
?>
		<img alt="<?php print encodeHtml($advert->title);?>" src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($advert->imageURL);?>" style="float:<?php print $advert->imageLocation;?>;" />
<?php
			}
?>
		<h3><a href="<?php print htmlspecialchars($advert->url);?>"><?php print encodeHtml($advert->title);?></a></h3>
<?php
			if (!empty($advert->subtitle)) {
?>
		<h4><?php print encodeHtml($advert->subtitle);?></h4>
<?php
			}
?>
		<p><?php print encodeHtml($advert->content);?></p>
		<div class="clear"></div>
	</div>
<?php
		}
?>
	</div>
<?php
	}
?>