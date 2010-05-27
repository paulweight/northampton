<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduLocation.php");
	include_once("websections/JaduContact.php");

	$address = new Address;
	$contactsList = getAllContacts();
	
	$location = new Location();
	
	$jsAddress = str_replace("\r\n", " ", $address->address);
	
	$breadcrumb = 'location';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Location details | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s location map and directions" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> location details" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s location map and directions" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php print GOOGLE_MAPS_API_KEY; ?>" type="text/javascript"></script>
	<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=<?php print GOOGLE_MAPS_API_KEY; ?>" type="text/javascript"></script>
	
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->


	<div id="columnLeft">
	
		<div id="map" style="height:500px;width:450px;border:1px solid #ddd; margin: 10px auto;">
			<span style="color:#676767;font-size:11px;margin:10px;padding:4px;">Loading...</span>
		</div>
		
		<noscript>
			<strong>JavaScript must be enabled in order for you to use Google Maps.</strong> However, it seems JavaScript is either disabled or not supported by your browser. To view Google Maps, enable JavaScript by changing your browser options, and then try again.
		</noscript>
		
	<script type="text/javascript">
		 if (GBrowserIsCompatible()) {
	
		  var baseIcon = new GIcon();
				 baseIcon.iconSize=new GSize(38,38);
				 baseIcon.shadowSize=new GSize(40,28);
				 baseIcon.iconAnchor=new GPoint(16,32);
				 baseIcon.infoWindowAnchor=new GPoint(16,0);
				 
			var council = new GIcon(baseIcon, "http://<?php print $DOMAIN;?>/site/images/info.png", null, "http://<?php print $DOMAIN;?>/site/images/sign_shadow.png");
			var parking = new GIcon(baseIcon, "http://<?php print $DOMAIN;?>/site/images/info.png", null, "http://<?php print $DOMAIN;?>/site/images/sign_shadow.png");
		 
			function createMarker(point,html,icon) {
			  var marker = new GMarker(point,icon);
			  GEvent.addListener(marker, "click", function() {
				 marker.openInfoWindowHtml(html);
			  });
			  return marker;
			}
			
	// -------
			var map = new GMap2(document.getElementById("map"));
	
	
		  	map.addControl(new GLargeMapControl());
	
		  	map.setCenter(new GLatLng(52.237084,-0.894682), 15, G_NORMAL_MAP);
	
			// council office
			var point = new GLatLng(52.237084,-0.894682);
			var marker = createMarker(point,'<div class="mapBubble" style="width:300px;"><img alt="The Guildhall against a blue sky" src="http://<?php print $DOMAIN;?>/site/images/guildhall.jpg" /><p><strong><?php print METADATA_GENERIC_COUNCIL_NAME; ?></strong></p><p><?php print $jsAddress; ?></p><p>Telephone: <?php print $address->telephone;?></p><p><a href="mailto:<?php print $address->email;?>"><?php print $address->email;?></a><p></div>', council);
			map.addOverlay(marker);
	
			// car park
			var point = new GLatLng(52.576435,-1.544545);
			var marker = createMarker(point,'<div style="width:300px;"><p><strong>Short stay car park</strong></p></div>', parking);
			map.addOverlay(marker);
			
		 }
	
		 else {
			alert("Sorry, the Google Maps API is not compatible with this browser");
		 }
		 </script> 

	</div>
	
	<div id="columnRight">
	
		<p><a href="<?php print $location->alternativeURL; ?>">Text based directions.</a></p>
<?php
	if (strlen($address->address) > 0) {
?>
		<p class="first"><?php print METADATA_GENERIC_COUNCIL_NAME;?>,<br /><?php print nl2br($address->address);?></p>
<?php 
	}
?>
<?php 
	print nl2br($location->directions);
?>


	</div>

		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>