<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduContact.php");

	$address = new Address;
	$contactsList = getAllContacts();
	
	$breadcrumb = "contactPage";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Contact details | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="email, fax, facsimilie, telephone, phone, call, contact, location, map, address, minicom, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is based at <?php print METADATA_PUBLISHER;?>. All key contacts are available here." />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Contact details" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is based at <?php print METADATA_PUBLISHER;?>. All key contacts are available here." />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
        		
		<p class="first">Contact <?php print METADATA_GENERIC_COUNCIL_NAME;?> directly using the <a href="/feedbackform">feedback form</a> or alternatively, you can contact us using the details below.</p>
	
	<div class="contactPage">
		<ul>
<?php 
		if (strlen($address->telephone) > 0) { 
?>
		<li class="icoPhone">Telephone: <?php print $address->telephone;?></li>
<?php 
		}

		if (strlen($address->email) > 0) { 
?>
		<li class="icoEmail">Email: <a href="mailto:<?php print $address->email;?>"><?php print $address->email;?></a></li>
<?php 
		}

		if (strlen($address->fax) > 0) {
?>
		<li class="icoFax">Fax:<?php print $address->fax;?></li>
<?php 
		} 
?>
		<li class="icoGlass">Visiting us? <a href="http://<?php print $DOMAIN;?>/site/scripts/location.php">Location map and travel details</a>.</li>

<?php 
		if (strlen($address->address) > 0) { 
?>
		<li class="icoAddress"><?php print nl2br($address->address);?></li>
<?php 
		} 
?>

		</ul>
	</div>
	

	<div class="display_box">
	<ul>
<?php
		foreach ($contactsList as $contact) {
?>	              
		
<?php 
		if (strlen($contact->name) > 0) { 
?>
		<li><h2><?php print htmlspecialchars($contact->name); ?></h2></li>
<?php 
		} 

		if (strlen($contact->title) > 0) { 
?>
		<li><h3><?php print htmlspecialchars($contact->title); ?></h3></li>
<?php 
		}

		if (strlen($contact->email) > 0) { 
?>
		<li  class="icoEmail">Email: <a href="mailto:<?php print $contact->email;?>"><?php print $contact->email;?></a></li>
<?php 
		} 

		if (strlen($contact->phone) > 0) { 
?>
		<li class="icoPhone">Telephone: <?php print $contact->phone;?></li>
<?php 
		}

		if (strlen($contact->address) > 0) {
?>
		<li class="icoAddress"><?php print $contact->address;?></li>
<?php 
		}
?>
		
<?php
		}
?>
	</ul>
	</div>
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>