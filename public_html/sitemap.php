<?php
    include_once('JaduConstants.php');
	include_once('utilities/JaduGoogleSitemap.php');
	header("Content-Type: text/xml; charset=UTF-8");
	$urlset = new URLSet();
	$urlset->generateCompleteURLSet();
	$urlset->exportAsXML('', true);
?>
