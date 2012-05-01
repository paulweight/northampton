<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> |<?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="email, fax, facsimilie, telephone, phone, call, contact, location, map, address, minicom, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is based at <?php print encodeHtml(METADATA_PUBLISHER); ?>. All key contacts are available here." />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Contact details" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is based at <?php print encodeHtml(METADATA_PUBLISHER); ?>. All key contacts are available here." />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
									
		<div class="lead_item vcard">
<?php 
		if (mb_strlen($address->telephone) > 0) { 
?>
			<p>Telephone <span class="tel"><?php print encodeHtml($address->telephone); ?></span></p>
<?php 
		}
		if (mb_strlen($address->email) > 0) { 
?>
			<p>Email <a href="mailto:<?php print encodeHtml($address->email); ?>" class="email"><?php print encodeHtml($address->email); ?></a></p>
<?php 
		}

		if (mb_strlen($address->fax) > 0) { 
?>
			<p class="tel"><span class="type">Fax</span> <?php print encodeHtml($address->fax); ?></p>
<?php 
		}
?>
			<p class="arrw">Visiting us? <a href="<?php print getSiteRootURL() . buildLocationURL();?>">Location map and travel details</a>.</p>
        </div>

<?php 
		if (mb_strlen($address->address) > 0) { 
?>

			<p><span class="org"><?php print encodeHtml(METADATA_GENERIC_NAME); ?></span>,<br /><span class="adr"><?php print nl2br(encodeHtml($address->address)); ?></span></p>
<?php 
		}
?>
   
<?php
	foreach ($contactsList as $contact) {
?>	              
		<div class="vcard">
<?php 
		if (mb_strlen($contact->title) > 0) { 
?>
			<h2><?php print encodeHtml($contact->title); ?></h2>
<?php 
		}

 		if (mb_strlen($contact->name) > 0) { 
?>
			<p class="fn"><?php print encodeHtml($contact->name); ?></p>
<?php 
		}

 		if (mb_strlen($contact->email) > 0) { 
?>
			<p><a href="mailto:<?php print encodeHtml($contact->email); ?>" class="email"><?php print encodeHtml($contact->email); ?></a></p>
<?php 
		}

 		if (mb_strlen($contact->phone) > 0) { 
?>
			<p class="tel"><?php print encodeHtml($contact->phone); ?></p>
<?php 
		}

 		if (mb_strlen($contact->address) > 0) { 
?>
			<p class="adr"><?php print encodeHtml($contact->address); ?></p>
<?php 
		}
?>
		</div>
<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>