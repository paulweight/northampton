<?php
	include_once("websections/JaduPressReleases.php");
	include_once("JaduConstants.php");

    $MAX_PRESS_RELEASE_ITEMS = 20;
	$PressReleasesList = getAllPressReleasesByDateLimited($MAX_PRESS_RELEASE_ITEMS, true, true);

	function morehtmlentities($string) {
	
		$transTable = get_html_translation_table(HTML_ENTITIES);
		$transTable[chr(142)] = '&eacute;';
		$transTable[chr(145)] = '\'';
		$transTable[chr(146)] = '\'';
		$transTable[chr(147)] = '&quot;';
		$transTable[chr(148)] = '&quot;';
		$string = strtr($string, $transTable);

		$string = str_replace(array("&pound;"), array("&#163;"), $string);
		$string = str_replace(array("&eacute;"), array("&#233;"), $string);

		return $string;
	}

	$charset = "ISO-8859-1";
	$mime = "application/xml";
	header("Content-Type: $mime;charset=$charset");	
?>
<?php print "<" . "?" . "xml version=\"1.0\" encoding=\"ISO-8859-1\" " . "?" . ">"; ?>

<rdf:RDF
 xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
 xmlns="http://purl.org/rss/1.0/"
 xmlns:dc="http://purl.org/dc/elements/1.1/"
>

<channel rdf:about="http://<?php print $DOMAIN;?>/site/scripts/pressRss.php">
<title><?php print $DOMAIN;?>::Latest Press releases</title>
<link>http://<?php print $DOMAIN;?></link>
<description><?php print $DOMAIN;?></description>
<dc:language>en</dc:language>
<items>
	<rdf:Seq>
<?php
	foreach ($PressReleasesList as $PressReleases) {
?>
		<rdf:li rdf:resource="http://<?php print $DOMAIN;?>/site/scripts/press_article.php?pressReleaseID=<?php print $PressReleases->id;?>" />
<?php
	}
?>
	</rdf:Seq>
</items>
</channel>

<?php
	foreach ($PressReleasesList as $PressReleases) {
?>
<item rdf:about="http://<?php print $DOMAIN;?>/site/scripts/press_article.php?pressReleaseID=<?php print $PressReleases->id;?>">
	<title><?php print morehtmlentities($PressReleases->title);?></title>
	<link>http://<?php print $DOMAIN;?>/site/scripts/press_article.php?pressReleaseID=<?php print $PressReleases->id;?></link>
	<description><?php print morehtmlentities(nl2br($PressReleases->summary));?></description>
	<dc:date><?php print $PressReleases->getPressReleasesDateISO8601();?></dc:date>
</item>
<?php
	}
?>

</rdf:RDF>