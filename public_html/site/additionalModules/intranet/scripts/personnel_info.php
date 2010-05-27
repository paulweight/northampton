<?php
	session_start();
	include_once('JaduConstants.php');
	include_once('utilities/JaduStatus.php');   
	include_once("JaduStyles.php"); 
	include_once("intranet/JaduIntranetPersonnel.php");
	include_once("intranet/JaduIntranetPersonnelDepartments.php");
	
	include_once("../includes/lib.php");

	if (isset($_GET['personID'])) {
		
		if ($_GET['personID']) {
			$person = getPerson($_GET['personID']);
		
			if ($person) {
				$department = getDepartment($person->departmentID);
			}
		} 
		else {
			header("Location: ./personnel.php?viewBy=name");
			exit;
		}
	}

    $breadcrumb = 'personnelInfo';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - People Directory</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="home, homepage, index, root, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print $DOMAIN;?> is the online resource for <?php print METADATA_GENERIC_COUNCIL_NAME;?> - with council services online" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online - Tel: <?php print $address->telephone;?>" />
	<meta name="DC.description" lang="en" content="<?php print $DOMAIN;?> is the online resource for <?php print METADATA_GENERIC_COUNCIL_NAME;?> - with council services online" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
	<div class="content_box">
<?php 
	if (trim($person->imageURL) != "") { 
?>
		<img src="http://<?php print $DOMAIN; ?>/images/<?php print $person->imageURL;?>" alt="<?php print "$person->forename $person->surname";?>" />
<?php 
	} 
	
	if (!empty($person->jobTitle)) { 
		print "<p><strong>Job title:</strong> $person->jobTitle</p>"; 
	} 

    if (!empty($person->departmentID)) {
?>
		<p><strong>Department:</strong> <?php print $department->title; ?></p>
<?php
	}
	else {
?>
		<p><strong>Department:</strong> Unknown</p>
<?php
	}
?>
		<?php if (!empty($person->telephone)) { print "<p><strong>Tel:</strong> $person->telephone</p>"; } ?>
		<?php if (!empty($person->extension)) { print "<p><strong>Ext:</strong> $person->extension</p>"; } ?>
		<?php if (!empty($person->mobile)) { print "<p><strong>Mobile:</strong> $person->mobile</p>"; } ?>
		<?php if (!empty($person->fax)) { print "<p><strong>Fax:</strong> $person->fax</p>"; } ?>
		<?php if (!empty($person->mobile)) { print "<p><strong>Mobile:</strong> $person->mobile</p>"; } ?>
		<?php if (!empty($person->alternateTelephone)) { print "<p><strong>Alternative Telephone:</strong> $person->alternateTelephone</p>"; } ?>
		<?php if (!empty($person->DDI)) { print "<p><strong>DDI:</strong> $person->DDI</p>"; } ?>
		<?php if (!empty($person->email)) { print "<p><strong>Email:</strong> <a href=\"mailto:$person->email\">$person->email</a></p>"; } ?>
		<?php if (!empty($person->address1)) { print "<p><strong>Address 1:</strong> $person->address1</p>"; } ?>
		<?php if (!empty($person->address2)) { print "<p><strong>Address 2:</strong> $person->address2</p>"; } ?>
		<?php if (!empty($person->team)) { print "<p><strong>Team:</strong> $person->team</p>"; } ?>
		<?php if (!empty($person->room)) { print "<p><strong>Room:</strong> $person->room</p>"; } ?>
		<?php if (!empty($person->building)) { print "<p><strong>Building:</strong> $person->building</p>"; } ?>
		<?php if (!empty($person->costCentre)) { print "<p><strong>Cost Centre:</strong> $person->costCentre</p>"; } ?>

		<div class="byEditor">  
			<?php print nl2br($person->content);?>
		</div>
	</div>

<?php 
	if ($department->id != -1) {
		$otherpersons = getAllPersonnelForDepartment($department->id);
		if (sizeof($otherpersons) > 1) { //  1 to discard above from being in list
			$splitArray = splitArray($otherpersons);			
?>
		<h2>Other people in <?php print $department->title;?></h2>
<?php
			print '<dl class="person_box">';	
			foreach ($splitArray['left'] as $otherperson) {
				if ($person->id != $otherperson->id) {
					$department = getdepartment($otherperson->departmentID);
?>
		<?php if (trim($otherperson->imageURL) != "") { ?><dd><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel_info.php?personID=<?php print $otherperson->id;?>"><img src="http://<?php print $DOMAIN; ?>/images/<?php print $otherperson->imageURL;?>" alt="<?php print $otherperson->forename . ' ' . $otherperson->surname;?>" class="cllrthumb" /></a></dd><?php } ?>
		<dt><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel_info.php?personID=<?php print $otherperson->id;?>"><strong><?php print "$otherperson->forename $otherperson->surname";?></strong></a></dt>
		<dd>Job title: <?php print $otherperson->jobTitle;?></dd>
		<dd>Email: <a href="mailto:<?php print $otherperson->email; ?>"><?php print $otherperson->email;?></a></dd>
		<dd>Extension: <?php print "$otherperson->extension"; ?></dd>
		<dd class="personBorder"><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel_info.php?personID=<?php print $otherperson->id;?>">View Details</a></p>
<?php
				}
			}
			print '</dl>';
			
			print '<dl class="person_box">';	
			foreach ($splitArray['right'] as $otherperson) {
				if ($person->id != $otherperson->id) {
					$department = getdepartment($otherperson->departmentID);
?>
		<?php if (trim($otherperson->imageURL) != "") { ?><dd><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel_info.php?personID=<?php print $otherperson->id;?>"><img src="http://<?php print $DOMAIN; ?>/images/<?php print $otherperson->imageURL;?>" alt="<?php print $otherperson->forename . ' ' . $otherperson->surname ;?>" class="cllrthumb" /></a></dd><?php } ?>
		<dt><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel_info.php?personID=<?php print $otherperson->id;?>"><strong><?php print "$otherperson->forename $otherperson->surname";?></strong></a></dt>
		<dd>Job title: <?php print $otherperson->jobTitle;?></dd>
		<dd>Email: <a href="mailto:<?php print $otherperson->email; ?>"><?php print $otherperson->email;?></a></dd>
		<dd>Extension: <?php print "$otherperson->extension"; ?></dd>
		<dd class="personBorder"><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel_info.php?personID=<?php print $otherperson->id;?>">View Details</a></p>						
<?php
				}
			}
			print '</dl>';
?>
	</div>
<?php
		}
	}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>