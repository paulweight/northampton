<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduLocation.php");
	include_once("websections/JaduContact.php");

	$address = new Address;
	$contactsList = getAllContacts();
	
	$location = new Location();
	
	$jsAddress = str_replace("\r\n", " ", $address->address);
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Location';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildContactURL() .'" >Contact us</a></li><li><span>Location</span></li>';
	
	include("location.html.php");
?>