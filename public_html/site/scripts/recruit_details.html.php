<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="jobs, recruitment, application, job, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="Jobs currently available at <?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />

	<meta name="DC.title" lang="en" content="Jobs at <?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />
	<meta name="DC.description" lang="en" content="Jobs currently available at <?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($job->id < 1) {
?>
	<h2>Sorry this vacancy is no longer available</h2>
<?php
	}
	else {
?>
	<h2><?php print encodeHtml($job->title);?></h2>
	<ul>
		<li>This vacancy is: <?php print encodeHtml($job->getTypeString());?></li>
		<li>Salary: <?php print encodeHtml($job->salary); ?></li>
		<li>Location: <?php print encodeHtml($job->location); ?></li>
		<li>Closing Date: <?php print formatDateTime(FORMAT_DATE_FULL, $job->closingDate);?></li>
	</ul>
	<div class="byEditor article">
		<?php print processEditorContent($job->description); ?>
	</div>
	
<?php
		if (count($downloads) > 0) {
?>
	<h3>Important information</h3>
	<p>Please download and read the following information before proceeding with your application:</p>
<?php 
			foreach ($downloads as $download) {
?>
	<p><a href="<?php print 'http://' . DOMAIN . '/recruit_downloads/' . encodeHtml($download->filename); ?>"><?php print encodeHtml($download->title); ?></a></p>
<?php
			}
		}
	}
?>

	<p><?php print encodeHtml(METADATA_GENERIC_NAME); ?> is an equal opportunities employer.</p>
	
<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>
