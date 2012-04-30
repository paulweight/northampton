<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="event,<?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is based at <?php print encodeHtml(METADATA_PUBLISHER); ?>. Thank you for your submission." />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> event submission" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is based at <?php print encodeHtml(METADATA_PUBLISHER); ?>. Thank you for your submission." />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
						
	<h2>Thank you for your event submission</h2>
	<p class="first">Your event now has to be approved by the webmaster before you are able to view the event on this site.</p>
			
	<ul>
<?php 
		if (mb_strlen($address->telephone) > 0) { 
?>
		<li>Telephone: <?php print encodeHtml($address->telephone); ?></li>
<?php 
		}
		if (mb_strlen($address->email) > 0) { 
?>
		<li>Email: <a href="mailto:<?php print encodeHtml($address->email); ?>"><?php print encodeHtml($address->email); ?></a></li>
<?php 
		}

		if (mb_strlen($address->fax) > 0) { 
?>
		<li>Fax: <?php print encodeHtml($address->fax); ?></li>
<?php 
		}
?>
		<li><a href="<?php print getSiteRootURL() . buildLocationURL();?>">Location map and travel details</a>.</li>

<?php 
		if (mb_strlen($address->address) > 0) { 
?>

		<li><?php print encodeHtml(METADATA_GENERIC_NAME); ?>, 	<?php print nl2br(encodeHtml($address->address)); ?></li>
<?php 
		}
?>
	</ul>
  
<?php
	foreach ($contactsList as $contact) {
?>	              
	<ul>
<?php 
		if (mb_strlen($contact->title) > 0) { 
?>
		<li><?php print encodeHtml($contact->title); ?></li>
<?php 
		}

 		if (mb_strlen($contact->name) > 0) { 
?>
		<li><?php print encodeHtml($contact->name); ?></li>
<?php 
		}

 		if (mb_strlen($contact->email) > 0) { 
?>
		<li><a href="mailto:<?php print encodeHtml($contact->email); ?>"><?php print encodeHtml($contact->email); ?></a></li>
<?php 
		}

 		if (mb_strlen($contact->phone) > 0) { 
?>
		<li><span class="tel"><?php print encodeHtml($contact->phone); ?></li>
<?php 
		}

 		if (mb_strlen($contact->address) > 0) { 
?>
		<li><?php print nl2br(encodeHtml($contact->address));?></li>
<?php 
		}
?>
	</ul>
<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>