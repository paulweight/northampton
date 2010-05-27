<?php
	session_start();
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("intranet/JaduIntranetPersonnel.php");
	include_once("intranet/JaduIntranetPersonnelDepartments.php");
	include_once("intranet/JaduIntranetPersonnelJobTypes.php");	
	
	if (isset($_POST['submit'])) {

		$person = new Person();
		$person->forename = $_POST['forename'];
		$person->surname = $_POST['surname'];
		$person->departmentID = $_POST['departmentID'];
		$person->imageURL = '';
		$person->email = $_POST['email'];
		$person->telephone = '';
		$person->extension = $_POST['extension'];
		$person->fax = $_POST['fax'];
		$person->jobTitle = $_POST['jobTitle'];
		$person->jobType = $_POST['jobType'];
		$person->content = $_POST['content'];
		$person->mobile = $_POST['mobile'];
		$person->address1 = $_POST['address1'];
		$person->address2 = '';
		$person->division = $_POST['division'];
		$person->team = $_POST['team'];
		$person->costCentre = $_POST['costCentre'];
		$person->alternateTelephone = $_POST['alternateTelephone'];
		$person->DDI = $_POST['DDI'];
		$person->room = $_POST['room'];
		$person->building = $_POST['building'];
		$person->live = '0';

		$error_array = validatePerson($person);

		if (sizeof($error_array) < 1) {
			addPerson($person);		
			unset($person);
			unset($_POST);
			
			$EMAIL_HEADER = "From: Jadu\r\nContent-Type: text/plain; charset=iso-8859-1;\r\n";
			$EMAIL_MESSAGE = "A new person has been added to the personnel directory\r\n\r\nPlease login to the Jadu Control Centre to view the details.\r\n";
			mail($DEFAULT_EMAIL_ADDRESS, "Addition to personnel directory", $EMAIL_MESSAGE, $EMAIL_HEADER);
		}
	}

    $breadcrumb = 'personnelAdd';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Addition to people directory</title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="people directory, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="Addition to people directory at <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> people directory" />
	<meta name="DC.description" lang="en" content="Addition to people directory at <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
	
<?php
		if (isset($_REQUEST['submit']) && sizeof($error_array) < 1) {
?>
			<p class="first">Personnel details submitted.</p>
<?php	
		}
?>
		<p class="first">Use this form to submit an addition to the people directory to the intranet team.</p>
	
<?php
	if (sizeof($error_array) > 0) {
?>
		<h2 class="warning">Please check details highlighted <strong>!</strong> are entered correctly.</h2>
<?php
	}
?>
		<form action="http://<?php print $DOMAIN; ?>/site/scripts/personnel_add.php" method="post" enctype="x-www-form-encoded" class="basic_form">
			<fieldset>
				<legend>Personal</legend>
				<p>
					<label for="Forename"><?php if ($error_array['forename']) { ?><span class="warning"><strong>!</strong> <?php } ?>Firstname (required)<?php if ($error_array['forename']) { ?></span><?php } ?> </label>
					<input id="Forename" type="text" name="forename" class="field" value="<?php print $_POST['forename'];?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="Surname"><?php if ($error_array['surname']) { ?><span class="warning"><strong>!</strong> <?php } ?>Lastname (required)<?php if ($error_array['surname']) { ?></span><?php } ?> </label>
					<input id="Surname" type="text" name="surname" class="field" value="<?php print $_POST['surname'];?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="jobtitle"><?php if ($error_array['jobTitle']) { ?><span class="warning"><strong>!</strong> <?php } ?>Job title (required)<?php if ($error_array['jobtitle']) { ?></span><?php } ?> </label>
					<input id="jobtitle" type="text" name="jobTitle" class="field" value="<?php print $_POST['jobTitle'];?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="serviceunit"><?php if ($error_array['serviceunit']) { ?><span class="warning"><strong>!</strong> <?php } ?>Department (required)<?php if ($error_array['serviceunit']) { ?></span><?php } ?> </label>
					<select name="departmentID" id="serviceunit" >
						<option value="-1" <?php if (!isset($_POST['departmentID'])) { print 'selected="selected"'; } ?>>Please choose a department</option>
<?php
		$departments = getAllDepartments();
		foreach ($departments as $department) {
?>
					<option value="<?php print $department->id ?>" <?php if ($_POST['departmentID'] == $department->id) { print 'selected="selected"'; } ?>><?php print $department->title; ?></option>
<?php
	}
?>
				</select>
					<span class="clear"></span>
				</p>
				<p>
					<label for="division"><?php if ($error_array['division']) { ?><span class="warning"><strong>!</strong> <?php } ?>Division (required)<?php if ($error_array['division']) { ?></span><?php } ?> </label>
					<input id="division" type="text" name="division" class="field" value="<?php print $_POST['division'];?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="team"><?php if ($error_array['team']) { ?><span class="warning"><strong>!</strong> <?php } ?>Team (required)<?php if ($error_array['team']) { ?></span><?php } ?> </label>
					<input id="team" type="text" name="team" class="field" value="<?php print $_POST['team'];?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="Email">Email Address</label>
					<input id="Email" type="text" name="email" class="field" value="<?php print $_POST['email'];?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="costcentre">Cost centre</label>
					<input id="costcentre" type="text" name="costCentre" class="field" value="<?php print $_POST['costCentre'];?>" />
					<span class="clear"></span>
				</p>
			</fieldset>
			<fieldset>
				<legend>Numbers</legend>
				<p>
					<label for="Extension"><?php if ($error_array['Extension']) { ?><span class="warning"><strong>!</strong> <?php } ?>Extension (required)<?php if ($error_array['Extension']) { ?></span><?php } ?> </label>
					<input id="Extension" type="text" name="extension" class="field" value="<?php print $_POST['extension'];?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="Alternate">Alternate</label>
					<input id="Alternate" type="text" name="alternateTelephone" class="field" value="<?php print $_POST['alternateTelephone'];?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="Mobile">Work Mobile</label>
					<input id="Mobile" type="text" name="mobile" class="field" value="<?php print $_POST['mobile'];?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="Fax">Fax</label>
					<input id="Fax" type="text" name="fax" class="field" value="<?php print $_POST['fax'];?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="ddi"><?php if ($error_array['ddi']) { ?><span class="warning"><strong>!</strong> <?php } ?>DDI (required)<?php if ($error_array['ddi']) { ?></span><?php } ?> </label>
					<input id="ddi" type="text" name="DDI" class="field" value="<?php print $_POST['DDI'];?>" />
					<span class="clear"></span>
				</p>
			</fieldset>
			<fieldset>
				<legend>Location</legend>
				<p>
					<label for="room">Room</label>
					<input id="room" type="text" name="room" class="field" value="<?php print $_POST['room'];?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="building"><?php if ($error_array['building']) { ?><span class="warning"><strong>!</strong> <?php } ?>Building (required)<?php if ($error_array['building']) { ?></span><?php } ?> </label>
					<input id="building" type="text" name="building" class="field" value="<?php print $_POST['building'];?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="Address">Location</label>
					<input id="Address" type="text" name="address1" class="field" value="<?php print $_POST['address1'];?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="designation"><?php if ($error_array['designation']) { ?><span class="warning"><strong>!</strong> <?php } ?>Are you a ..? (required)<?php if ($error_array['designation']) { ?></span><?php } ?> </label>
					<select name="jobType" id="designation">
						<option value="-1" >Please choose a type</option>
<?php
		$jobTypes = getAllJobTypes();
		foreach ($jobTypes as $jobType) {
?>
						<option <?php if($jobType->id == $_POST['jobType']) { print 'selected="selected"'; } ?> value="<?php print $jobType->id ?>"><?php print $jobType->title ?></option>
<?php
		}
?>
					</select>
					<span class="clear"></span>
				</p>
				<p>
					<label for="comments">Additional information</label>
					<textarea id="comments" name="content" class="field" cols="2" rows="5"><?php print $_POST['content'];?></textarea>
					<span class="clear"></span>
				</p>
				<p class="center">
					<input type="submit" value="Submit details" name="submit" class="button"  />
				</p>
			</fieldset>
		</form>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>