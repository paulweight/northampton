<?php 
	include_once('marketing/JaduAdverts.php');
	
	$rightAdverts = getRightAdvertsForUser($_SESSION['userID']);
	
	if (sizeof($rightAdverts) > 0) {
?>
<div class="rightAdvertWidget">
<?php
		foreach ($rightAdverts as $advert) {
?>
			<div class="adwrap">
				<a href="<?php print encodeHtml($advert->url);?>"><img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($advert->imageURL);?>" alt="<?php print encodeHtml($advert->title);?>" />
				<span><?php print encodeHtml($advert->title);?></span></a>
			</div>
<?php
		}
?>
</div>
<?php
	}
?>