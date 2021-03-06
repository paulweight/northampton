<?php 
	include_once('marketing/JaduAdverts.php');
	include_once('marketing/JaduListFilters.php');
	
	$middleAdverts = getMiddleAdvertsForUser($_SESSION['userID']);
	if (!empty($middleAdverts)) {
?>
	<div class="slidewrap">
	<span class="nw"> </span>
	<span class="ne"> </span>
	<span class="sw"> </span>
	<span class="se"> </span>
		<ul class="slider">
<?php
		foreach ($middleAdverts as $advertKey => &$advert) {
?>
			<li class="slide">
				<div class="crop">
<?php
			if (!empty($advert->imageURL)) {
?>
					<a href="<?php print encodeHtml($advert->url);?>">
						<img <?php print $advertKey == 0 ? '' : 'class="lazy"';?> title="<?php print $advertKey == 0 ? '' : 'Image: ' . getSiteRootURL() .'/images/' . encodeHtml($advert->imageURL);?>" alt="<?php print encodeHtml($advert->title);?>" src="<?php print $advertKey == 0 ? getSiteRootURL() . '/images/' . encodeHtml($advert->imageURL) : '/site/images/imageLoading.gif';?>" />
						<object><noscript><div><img alt="<?php print encodeHtml($advert->title);?>" src="<?php print getSiteRootURL() . '/images/' . encodeHtml($advert->imageURL);?>" /></div></noscript></object>
					</a>
<?php
			}
?>
					<div class="copy">
						<h2><a href="<?php print encodeHtml($advert->url);?>"><?php print encodeHtml($advert->title);?></a></h2>
<?php
			if (!empty($advert->subtitle)) {
?>
						<h3><?php print encodeHtml($advert->subtitle);?></h3>
<?php
			}
?>
						<p><?php print encodeHtml($advert->content);?></p>
					</div>
				</div>
			</li>
<?php
		}
?>
		</ul>
	</div>
<?php
		if (!isset($indexPage) || !$indexPage) {
?>
	<script type="text/javascript" src="<?php print getStaticContentRootURL() . '/site/javascript/widgets/responsive_carousel.min.js';?>"></script>
<?php
		}
	}
?>