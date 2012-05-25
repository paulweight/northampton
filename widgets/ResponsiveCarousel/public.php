<?php 
	include_once('marketing/JaduAdverts.php');
	
	$middleAdverts = getMiddleAdvertsForUser($_SESSION['userID']);
	if (!empty($middleAdverts)) {
?>
	<div class="slidewrap" data-autorotate="2500">
	<span class="nw"> </span>
	<span class="ne"> </span>
	<span class="sw"> </span>
	<span class="se"> </span>
		<ul class="slider" id="sliderName">
<?php
		foreach ($middleAdverts as &$advert) {
?>
			<li class="slide">	
				<div class="crop">
<?php
			if (!empty($advert->imageURL)) {
?>
					<a href="<?php print encodeHtml($advert->url);?>">
						<img alt="<?php print encodeHtml($advert->title);?>" src="<?php print getSiteRootURL(); ?>/images/<?php print encodeHtml($advert->imageURL);?>" />
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
	<script type="text/javascript" src="<?php print getStaticContentRootURL() . '/site/javascript/widgets/responsive_carousel.min.js';?>"></script>
<?php
	}
?>