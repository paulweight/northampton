<?php 
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduContact.php");

	$address = new Address;
	$contactsList = getAllContacts();
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Thank you for your event suggestion';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildNewEventURL() .'">Suggest an event</a></li><li><span>Thank you for your event suggestion</span></li>';
	
	include("event_thanks.html.php");
?>