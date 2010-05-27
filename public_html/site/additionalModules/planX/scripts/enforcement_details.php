<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("planXLive/JaduPlanXEnforcementNotices.php");
	include_once("planXLive/JaduPlanXEnforcementDownloads.php");
	include_once("planXLive/JaduPlanXConfiguration.php");

	$constants = getAllPlanXConfigurationValues();

	if (isset($_GET['noticeRef'])) {

		$params = array("noticeRef" => $_GET['noticeRef']);

		$notices = searchEnforcementNotices($params);

		if (sizeof($notices) == 1) {
			$notice = $notices[0];
		}
		elseif (sizeof($notices) > 1) {
			header("Location: enforcement_list.php?noticeRef=" . $_GET['noticeRef']);
			exit();
		}
		else {
			header("Location: enforcement.php?noResults=true");
			exit();
	    }
	}
	elseif (isset($_GET['id'])) {
		$notice = getEnforcementNotice($_GET['id'], 'id');
	}
	else {
		header("Location: enforcement.php?noResults=true");
	    exit();
	}

	// if the user has chosen to accept the download agreemnt then set a 
	// cookie that lasts for 7 days allowing files to be downloaded
	if (isset($_POST['submitDownloadAgreement']) && $_POST['agree'] == 'true') {
		setcookie('planx_download', 'true', time() + 60 * 60 * 24);
		$_COOKIE['planx_download'] = 'true';
	}

	$enforcementDownloads = getDownloadsForEnforcementNotice($notice->reference);
	
	$breadcrumb = 'enforcementDetails';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Enforcement Notice Search</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="planning, enforcement, notices, applications, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Enforcement Register Search" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Enforcement Register Search" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Enforcement Register Search" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

			<table summary="Enforcement Complaint Details">
				<thead>
					<tr>
						<th>Field Name</th>
						<th>Details</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td>Location</td>
						<td><?php print $notice->getFormattedValueForField('address') . ', ' . $notice->getFormattedValueForField('town') . ', ' . $notice->getFormattedValueForField('postcode'); ?></td>
					</tr>
<?php
				if (!empty($notice->ward)) {
?>
					<tr>
						<td>Ward</td>
						<td><?php print $notice->getFormattedValueForField('ward'); ?></td>
					</tr>
<?php
				}
				if (!empty($notice->parish)) {
?>
					<tr>
						<td>Parish</td>
						<td><?php print $notice->getFormattedValueForField('parish'); ?></td>
					</tr>
<?php
				}
				if (!empty($notice->noticeType)) {
?>			
					<tr>
						<td>Notice Type</td>
						<td><?php print $notice->getFormattedValueForField('noticeType'); ?></td>
					</tr>
<?php
				}
				if (!empty($notice->dateIssued)) {
?>				
					<tr>
						<td>Date Issued</td>
						<td><?php print $notice->getFormattedValueForField('dateIssued'); ?></td>
					</tr>
<?php
				}
				if (!empty($notice->dateServed)) {
?>
					<tr>
						<td>Date Served</td>
						<td><?php print $notice->getFormattedValueForField('dateServed'); ?></td>
					</tr>
<?php
				}
				if (!empty($notice->complianceRequiredBy)) {
?>
					<tr>
						<td>Compliance required by</td>
						<td><?php print $notice->getFormattedValueForField('complianceRequiredBy'); ?></td>
					</tr>
<?php
				}
?>
				</tbody>
			</table>

<?php
		if ($notice->appealStartDate > 0) {
?>
			<h2>Appeal Details</h2>
			<table summary="Enforcement Appeal Details">
				<thead>
					<tr>
						<th>Field Name</th>
						<th>Details</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td>Appeal start date</td>
						<td><?php print $notice->getFormattedValueForField('appealStartDate'); ?></td>
					</tr>
<?php
				if (!empty($notice->inspectorateRefNum)) {
?>
					<tr>
						<td>Insectorate's Ref. No.</td>
						<td><?php print $notice->getFormattedValueForField('inspectorateRefNum'); ?></td>
					</tr>
<?php
				}
				if (!empty($notice->appealDecision)) {
?>
					<tr>
						<td>Appeal Decision</td>
						<td><?php print $notice->getFormattedValueForField('appealDecision'); ?></td>
					</tr>
<?php
				}
				if (!empty($notice->appealDecisionDate)) {
?>
					<tr>
						<td>Appeal Decision Date</td>
						<td><?php print $notice->getFormattedValueForField('appealDecisionDate'); ?></td>
					</tr>
<?php
				}
				if (!empty($notice->groundsOfAppeal)) {
?>
					<tr>
						<td>Grounds of appeal</td>
						<td><?php print $notice->getFormattedValueForField('groundsOfAppeal'); ?></td>
					</tr>
<?php
				}
?>
				</tbody>
			</table>

<?php
		}
?>


<?php
		if (sizeof($enforcementDownloads) > 0 && isset($_COOKIE['planx_download'])) {
?>
			<h3>Downloads For This Notice</h3>

			<ul class="list">
<?php
			foreach ($enforcementDownloads as $enforcementDownload) {
?>
				<li><a href="<?php print "http://" . $DOMAIN . "/planx_downloads/$enforcementDownload->filename"; ?>"><?php print $enforcementDownload->title; ?></a></li>
<?php
			}
?>
			</ul>
<?php
		}

		if (!isset($_COOKIE['planx_download']) && sizeof($enforcementDownloads) > 0) {
?>
			<h3>Downloads For This Notice</h3>
			<p>Documents in PDF format can be read using <a href="http://www.adobe.com/products/acrobat/readstep2.html">Adobe Acrobat Reader.</a></p>
			<p><strong>Copyright Notice:</strong>Plans, drawings and material submitted to the Council are protected by the Copyright Acts (Section 47, 1988 Act). You may only use material which is downloaded and/or printed for personal/private use, for example to compare current applications with previous schemes, or to check whether developments have been completed in accordance with approved plans. You cannot use them, for example, to build from, or to copy from to use as part of your own planning application, unless you have the architect's permission.</p>

			<form action="http://<?php print $DOMAIN; ?>/site/scripts/enforcement_details.php?id=<?php print $notice->id; ?>" method="post">
				<p>
					<label for="agreement">I Agree</label>
					<input type="checkbox" id="agreement" name="agree" value="true" /> 
				</p>
				<p class="center">
					<input type="submit" name="submitDownloadAgreement" value="Submit" class="button" />
				</p>
			</form>
<?php
		}
?>
	        <p>
				<strong>Last updated:</strong> <?php print date("d M Y", $constants['lastEnforcementImportTimestamp']->value); ?>
			</p>

    <!--  contact box  -->
    <?php include("../includes/contactbox.php"); ?>
    <!--  END contact box  -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>