<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("eConsultation/JaduComments.php");
	include_once("marketing/JaduUsers.php");
	include_once("eConsultation/JaduConsultations.php");
	
	if (isset($_GET['commentID']) && is_numeric($_GET['commentID']) && isset($_GET['consultationID']) && is_numeric($_GET['consultationID']) && $_GET['consultationID'] > 0) {
		$consultation = getConsultation($_GET['consultationID']);

		if ($consultation != -1) {
		    $comment = getComment($_GET['commentID']);
		    
		    if ($comment != -1) {
			    $commentUser = getUser($comment->userID);
			}
			else {
				header("Location: http://$DOMAIN/site/scripts/comment_viewer.php?consultationID=$consultation->id");
				exit;
			}
		}
		else {
			header("Location: http://$DOMAIN/site/scripts/consultation_open.php");
			exit;
		}
	}
	else {	
		header("Location: http://$DOMAIN/site/scripts/consultation_open.php");
		exit;
	}

	$breadcrumb = 'commentFull';	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - A Comment on <?php print $consultation->title; ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Consultation, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Comments for <?php print $consultation->title; ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Comments for <?php print $consultation->title; ?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Comments for <?php print $consultation->title; ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ####################################### -->
				
	<h2>Made by: <?php if ($comment->anonymous) print "Anonymous"; else print "$commentUser->salutation $commentUser->forename $commentUser->surname"; ?></h2>
	<p class="date">Posted on <?php print $comment->getFormattedDate("D d F Y (H:i)"); ?></p>
	<p><?php print nl2br($comment->comments); ?></p>

	<ul class="list">
		<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/comment_input.php?consultationID=<?php print $consultation->id; ?>">Make a comment</a></li>
		<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/comment_viewer.php?consultationID=<?php print $consultation->id; ?>">Back to comment list</a></li>
	</ul>
	
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
	
		
<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>