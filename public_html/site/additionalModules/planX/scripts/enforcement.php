<?php 
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("planXLive/JaduPlanXEnforcementNotices.php");
	
	$wardLookupTableID = 10;
	$parishLookupTableID = 11;
	
	$wards = getAllRecordsInLookupTable($wardLookupTableID);
	$parishes = getAllRecordsInLookupTable($parishLookupTableID);
	$noticeTypesTableID = 7;
	$noticeTypes = getAllRecordsInLookupTable($noticeTypesTableID);

	$breadcrumb = 'enforcement';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?>, enforcement notice search</title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="planning, plans, applications, planning, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Planning Application Search" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Planning Application Search" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Planning Application Search" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (isset($_GET['noResults'])) {
?>	
		<h2 class="warning">No notices were found for the search criteria</h2>
<?php
	}
?>
		<p class="first">Search for <strong>enforcement notices</strong> decided by <?php print METADATA_GENERIC_COUNCIL_NAME;?> using the following:</p>

		<h2>Notice number</h2>
		<p>Please type in your enforcement notice number and press Go.</p>
		<form action="http://<?php print $DOMAIN; ?>/site/scripts/enforcement_details.php" method="get" class="basic_form">
			<input type="hidden" name="thisWeek" value="0" />
			<p>
				<label for="appNumber">Notice N&ordm;</label> 
				<input class="field" id="appNumber" type="text" size="22" name="noticeRef" value=""  />
			</p>
			<p class="center">
				<input class="button" type="submit"  name="submit" value="Go" />
			</p>				
		</form>

		<h2>Location search</h2>
		<p>Type a location and press Go.</p>	
		<form action="http://<?php print $DOMAIN; ?>/site/scripts/enforcement_list.php" method="get" class="basic_form">
			<input type="hidden" name="thisWeek" value="0" />
			<p>
				<label for="location">Location Keywords</label>
				<input class="field" id="location" type="text" name="location" size="22" />
			</p>
			<p class="center">
				<input class="button" type="submit" name="submitLocation" value="Go" />
			</p>
		</form>

		<h2>Ward Search</h2>
		<form action="http://<?php print $DOMAIN; ?>/site/scripts/enforcement_list.php" method="get" class="basic_form">
			<p>
				<label for="ward">Ward</label>
				<select class="select" id="ward" name="ward">
					<option value="-1" selected="selected">Please select</option>
<?php
				foreach ($wards as $ward) {
?>
					<option value="<?php print $ward->reference; ?>"><?php print $ward->value; ?></option>
<?php
				}
?>
				</select>
			</p>
			<p class="center">
				<input class="button" type="submit"  name="submitWard" value="Go" />
			</p>
		</form>

		<h2>Parish Search</h2>
		<form action="http://<?php print $DOMAIN; ?>/site/scripts/enforcement_list.php" method="get" class="basic_form">
			<p>
				<label for="parish">Parish</label>
				<select class="select" id="parish" name="parish">
					<option value="-1" selected="selected">Please select</option>
<?php
				foreach ($parishes as $parish) {
?>
					<option value="<?php print $parish->reference; ?>"><?php print $parish->value; ?></option>
<?php
				}
?>
				</select>
			</p>
			<p class="center">
				<input class="button" type="submit"  name="submitParish" value="Go" />
			</p>
		</form>

		<h2>Notice Type</h2>
		<form action="http://<?php print $DOMAIN; ?>/site/scripts/enforcement_list.php" method="get" class="basic_form">
			<p>
				<label for="noticeType">Notice Type</label>
				<select class="select" id="noticeType" name="noticeType">
					<option value="-1" selected="selected">Please select</option>
<?php
				foreach ($noticeTypes as $noticeType) {
?>
					<option value="<?php print $noticeType->reference; ?>"><?php print $noticeType->value; ?></option>
<?php
				}
?>
				</select>
			</p>
			<p class="center">
				<input class="button" type="submit"  name="submitNoticeType" value="Go" />
			</p>
		</form>

		<h2>Appeal Date Search</h2>
		<form action="http://<?php print $DOMAIN; ?>/site/scripts/enforcement_list.php" method="get" class="basic_form">
			<input type="hidden" name="thisWeek" value="0" />
			<p>
				<label for="AppealfromDay">Date Between</label>
				<select class="select" id="AppealfromDay" name="fromDay">
					<option value="-1" selected="selected">Day</option>
<?php
				for ($i = 1; $i < 32; $i++) {
?>
					<option value="<?php print $i; ?>"><?php print $i; ?></option>
<?php
				}
?>
				</select>

				<label for="AppealfromMonth" class="hidden">Month</label>
				<select class="select" id="AppealfromMonth" name="fromMonth">
					<option value="-1" selected="selected">Month</option>
<?php
				for ($i = 1; $i < 13; $i++) {
					$month = date("M", mktime(0,0,0,$i+1,0,0));
?>
					<option value="<?php print $i; ?>"><?php print $month; ?></option>
<?php
				}
?>
				</select> 

				<label for="AppealfromYear" class="hidden">Year</label>
				<select class="select" id="AppealfromYear" name="fromYear">
					<option value="-1" selected="selected">Year</option>
<?php
				for ($i = 1965; $i < date("Y")+1; $i++) {
?>
					<option value="<?php print $i; ?>"><?php print $i; ?></option>
<?php
				}
?>
				</select>
			</p>
			<p>
				<label for="AppealtoDay">and</label>
				<select class="select" id="AppealtoDay" name="toDay">
					<option value="-1"  selected="selected">Day</option>
<?php
				for ($i = 1; $i < 32; $i++) {
?>
					<option value="<?php print $i; ?>" ><?php print $i; ?></option>
<?php
				}
?>
				</select>

				<label for="AppealtoMonth" class="hidden">Month</label>
				<select class="select" id="AppealtoMonth" name="toMonth">
					<option value="-1"  selected="selected">Month</option>
<?php
				for ($i = 1; $i < 13; $i++) {
					$month = date("M", mktime(0,0,0,$i+1,0,0));
?>
					<option value="<?php print $i; ?>" ><?php print $month; ?></option>
<?php
				}
?>
				</select>

				<label for="AppealtoYear" class="hidden">Year</label>
				<select class="select" id="AppealtoYear" name="toYear" >
					<option value="-1"  selected="selected">Year</option>
<?php
				for ($i = 1965; $i < date("Y")+1; $i++) {
?>
					<option value="<?php print $i; ?>" ><?php print $i; ?></option>
<?php
				}
?>
				</select>
			</p>
			<p class="center">
				<input class="button" type="submit"  name="submitAppealDate" value="Go" />
			</p>
		</form>
		<!-- END search -->

		<!--  contact box  -->
		<?php include("../includes/contactbox.php"); ?>
		<!--  END contact box  -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>