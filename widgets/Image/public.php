<div class="imageWidget"><?php
	if ('%IMG_SRC%' != '' && '%IMG_SRC%' != '%IMG_SRC'.'%' && '%IMG_SRC%' != 'http://'.DOMAIN.'/images/') {
	
		if (function_exists('getStaticContentRootURL')) {
			$widgetImgURL = getStaticContentRootURL() . '/images/%IMG_SRC%';
		}
		else {
			// pre 1.9
			if (isset($preview_mode) && $preview_mode) {
				$widgetImgURL = str_replace($SECURE_SERVER . '/images/', 'http://' . DOMAIN . '/images/', '%IMG_SRC%');
				$PROTOCOL = 'http://';
			}
			else {
				$widgetImgURL = str_replace($SECURE_SERVER . '/images/', $PROTOCOL . DOMAIN . '/images/', '%IMG_SRC%');
			}
			
			if (substr($widgetImgURL,0,4) != 'http') {
				$widgetImgURL = $PROTOCOL . DOMAIN .'/images/'. $widgetImgURL;
			}
			else {
				$widgetImgURL = str_replace('http://', $PROTOCOL, $widgetImgURL);
			}
		}
		
		$widgetLinkHref = '%LINK_HREF%';
		
		if (strpos($widgetLinkHref, 'http://') !== 0 && 
			strpos($widgetLinkHref, 'https://') !== 0 && 
			strpos($widgetLinkHref, 'mailto:') !== 0 && 
			trim($widgetLinkHref) != '') {
			$widgetLinkHref = 'http://' . $widgetLinkHref;
		}
		
		$widgetImgAlt = str_replace($SECURE_JADU_PATH . '/images/', '', '%IMG_SRC%');
		$widgetImgAlt = str_replace('http://' . DOMAIN . '/images/', '', $widgetImgAlt);
		$widgetImgAlt = str_replace('/', '', $widgetImgAlt);
		
		$widgetImgLinkText = '%LINK_TEXT%';
		
		$widgetImgLinkTextEncoded = encodeHtml($widgetImgLinkText);
		$widgetImgURLEncoded = encodeHtml($widgetImgURL);
		$widgetLinkHrefEncoded = encodeHtml($widgetLinkHref);
		
?>
<div class="widget_banner">
<?php
	if ($widgetLinkHrefEncoded != '' && $widgetImgLinkText != '') {
?>
	<a href="<?php print $widgetLinkHrefEncoded; ?>"><img src="<?php print $widgetImgURLEncoded; ?>" alt="<?php print encodeHtml(getImageProperty($widgetImgAlt, 'altText')); ?>" title="<?php print $widgetImgLinkTextEncoded; ?>" width="100%" /></a>
	<p><a href="<?php print $widgetLinkHrefEncoded; ?>"><?php print $widgetImgLinkTextEncoded; ?></a></p>
<?php
	}
	else {
		if ($widgetLinkHrefEncoded != '') {
?>
	<a href="<?php print $widgetLinkHrefEncoded; ?>"><img src="<?php print $widgetImgURLEncoded; ?>" alt="<?php print encodeHtml(getImageProperty($widgetImgAlt, 'altText')); ?>" width="100%" /></a>
<?php
		}
		else {
?>
	<img src="<?php print $widgetImgURLEncoded; ?>" alt="<?php print encodeHtml(getImageProperty($widgetImgAlt, 'altText')); ?>" width="100%" />
<?php
		}
	}
?>
</div>
<?php
	}
?>
	</div>