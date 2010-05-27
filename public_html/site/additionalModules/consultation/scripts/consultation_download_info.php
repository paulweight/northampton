<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("egov/JaduCL.php");

	include_once("eConsultation/JaduConsultations.php");
	include_once("eConsultation/JaduConsultationMappings.php");
	include_once("eConsultation/JaduConsultationDownloads.php");

	if (isset($_SESSION['userID']))
		$user = getUser($_SESSION['userID']);

	if (isset($_GET['downloadID']) && is_numeric($_GET['downloadID'])) {
		$downloadID = $_GET['downloadID'];
		$download = getConsultationDownload($downloadID);
		$allFiles = getAllConsultationDownloadFilesForConsultationDownload($downloadID);

		$consultationID = $download->consultationID;
		$consultation = getConsultation($consultationID, true, true);
	}
	else {
		header("Location: http://$DOMAIN/site/scripts/consultation_open.php");
		exit;
	}
	
	$breadcrumb = 'consultationDownload';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - <?php print $download->title; ?> Downloads <?php print "$consultation->title"; ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Consultation, download, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - <?php print $download->title; ?> Downloads <?php print "$consultation->title"; ?>" />

	<?php printMetadata(CONSULTATION_DOWNLOADS_METADATA_TABLE, CONSULTATION_DOWNLOADS_CATEGORIES_TABLE, $downloadID, $download->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ####################################### -->

	<h2><?php print htmlentities($download->title);?></h2> 

	<p>
		<?php print nl2br($download->description); ?>
	</p>
<?php	
	if (sizeof($allFiles) > 0) {
		foreach ($allFiles as $file) {
			$extension = $file->getFilenameExtension();
			$filename = CONSULTATIONS_PUBLIC_FOLDER.$consultation->folderName. '/' .$file->filename;
?>
	<div class="download_box">
		<ul>
			<li><a href="<?php print $filename;?>"><?php print $file->title;?></a></li>
			<li><img src="http://<?php print $DOMAIN; ?>/site/images/file_type_icons/<?php print $extension;?>.gif" alt="<?php print $extension;?>" />&nbsp;(<?php print $extension;?>)</li>
			<li>Size: <?php print $file->getHumanReadableSize();?></li>
			<li>Estimated download time: <?php print $file->getConsultationDownloadTime56k();?></li>
		</ul>
	</div>
<?php
		}
	}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>