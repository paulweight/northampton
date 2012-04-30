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
	if (count($jobs) == 0) {
?>

	<h2>There are currently no vacancies available</h2>
	
<?php
	}
	else {
?>

	<p>We currently have the following vacancies:</p>
	
<?php
		foreach ($categories as $category) {
			if (isset($jobs[$category->id]) && count($jobs[$category->id]) > 0) {
?>

	<h2><?php print encodeHtml($category->title); ?></h2>
	<ul>
<?php
			foreach ($jobs[$category->id] as $job) {
?>
		<li><a href="<?php print getSiteRootURL() . buildJobsURL($job->id); ?>"><?php print encodeHtml($job->title); ?></a> - Closing date <?php print formatDateTime(FORMAT_DATE_LONG, $job->closingDate); ?></li>
<?php
			}
?>
	</ul>

<?php
			}
		}
	}
?>
	
	<p><?php print encodeHtml(METADATA_GENERIC_NAME); ?> is an equal opportunities employer.</p>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>