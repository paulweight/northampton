<?php
	include_once('websections/JaduHomepageBanners.php');
	include_once('library/JaduStringFunctions.php');

	$homepageBanners = getAllHomepageBanners(true);
	if (sizeof($homepageBanners) > 0) {
		shuffle($homepageBanners);
		$homepageBanner = $homepageBanners[0];
?>
<!-- random homepageBanner image -->
<div class="randomimageWidget">	
	<div class="banner">
<?php 				
		if ($homepageBanner->href != '') {
			print '<a href="'.encodeHtml($homepageBanner->href).'">';
		}
?>
	<img src="<?php print getStaticContentRootURL();?>/images/<?php print $homepageBanner->imageURL;?>" alt="<?php print encodeHtml($homepageBanner->title);?>" />
	
<?php  					
		if ($homepageBanner->href != '') {
			print '</a>';
		}
?>
	</div>
</div>
<!-- END random homepageBanner image -->
<?php
	}
?>
