<?php 
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduContact.php");

	$address = new Address;
	$contactsList = getAllContacts();
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Contact us';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Contact us</span></li>';

	include("contact.html.php");
?>