<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Services Generator</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Accessibility, dda, disability discrimination act, disabled access, access keys, <?php print encodeHtml(METADATA_GENERIC_COUNCIL_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is committed to providing accessible web content and council services online for all" />
	
	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Accessibility features" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is committed to providing accessible web content and council services online for all" />
	
	<meta name="eGMS.subject.category" lang="en" scheme="IPSV" content="Local government;Government, politics and public administration" />
	<meta name="eGMS.subject.keyword" lang="en" scheme="LGCL" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include(HOME . "site/includes/opening.php"); ?>
<!-- ########################## -->

	<h2>Welcome to the services generator.</h2>
	<p class="first">Click the links below to generate lists of services ordered by PID or without PID.</p>
	
	<div class="content_box">
		<ul class="list">
			<li><a href="/site/IDEA/services_crawl.php?set=pid">Generate PID services list</a></li>
			<li><a href="/site/IDEA/services_crawl.php?set=nonpid">Generate non-PID services list</a></li>
		</ul>
	
<?php
	if (isset($_GET['set']) && ($_GET['set'] == 'pid' || $_GET['set'] == 'nonpid')) {
?>
		<form name="expform" method="post" enctype="multipart/form-data" action="http://<?php print DOMAIN; ?>/site/IDEA/services_crawl.php?set=<?php print encodeHtml($_GET['set']); ?>" class="basic_form">
			<p>Click the export spreadsheet button to download a CSV file of all the selected type of services.</p>
			<p class="center"><input type="submit" name="submit" value="Export spreadsheet" class="button" /></p>
		</form>
<?php
	}
?>
	</div>
	
	<div class="content_box">
<?php
	if (!empty($urls)) {
?>
		<ul class="list">
<?php
		foreach ($urls as $url) {
?>
			<li><a href="<?php print encodeHtml($url); ?>"><?php print encodeHtml($url); ?></a></li>
<?php
		}
?>
		</ul>
<?php
	}
?>
	</div>
		
<!-- ###################################### -->
	<?php include(HOME . "site/includes/closing.php"); ?>