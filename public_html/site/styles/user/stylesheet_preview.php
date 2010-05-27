	include_once("websections/JaduHomepages.php");	
	$allIndependantHomepages = getAllHomepagesIndependant();
?>
	<!--[if lte IE 6]>
		<link rel="stylesheet" type="text/css" href="http://<?php print DOMAIN ?>/site/styles/generic/ie_special.css" media="screen" />
	<![endif]-->
	<!--[if IE 7]>
		<link rel="stylesheet" type="text/css" href="http://<?php print DOMAIN ?>/site/styles/generic/ie_seven.css" media="screen" />
	<![endif]-->	
	<link rel="stylesheet" type="text/css" href="http://<?php print DOMAIN ?>/site/styles/generic/homepages.css" media="screen" />	
	<link rel="stylesheet" type="text/css" href="http://<?php print DOMAIN ?>/site/styles/generic/homepageElements.php?homepageID=<?php print $allIndependantHomepages[0]->id; ?>" media="screen" />