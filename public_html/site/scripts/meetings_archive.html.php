<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="committee, meetings, minutes, agendas, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s directory of Agendas, Reports and Minutes" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Agendas, Reports and Minutes" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s directory of Agendas, Reports and Minutes" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<p>To view a full archive of meetings, select a committee heading.</p>
	
<?php
	if (count($activeHeaders) > 0) {
?>
		<h2>Active</h2>
<?php
	if(count($activeHeaders) > 0) {
		print '<ul class="list">';
		foreach ($activeHeaders as $left) {
?>	
			<li><a href="<?php print getSiteRootURL() . buildMeetingsURL( -1, 'committee', $left->id); ?>"><?php print encodeHtml($left->title); ?></a></li>
<?php
		}
		print '</ul>';
	}
	}
?>
	
<?php
	if (count($archivedHeaders) > 0) {
?>
		<h2>Archived</h2>
<?php
	if(count($archivedHeaders) > 0) {
		print '<ul class="list">';
		foreach ($archivedHeaders as $left) {
?>	
			<li><a href="<?php print getSiteRootURL() . buildMeetingsURL( -1, 'committee', $left->id); ?>"><?php print encodeHtml($left->title); ?></a></li>
<?php
		}
		print '</ul>';
	}
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>