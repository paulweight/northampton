<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("eConsultation/JaduComments.php");
	include_once("marketing/JaduUsers.php");
	include_once("eConsultation/JaduConsultations.php");
	
	define("MAX_COMMENT_SIZE", 250);
	
	if (isset($_GET['consultationID']) && is_numeric($_GET['consultationID']) && $_GET['consultationID'] > 0) {
		$consultation = getConsultation($_GET['consultationID'], true, true);

		if ($consultation != -1) {
			$topic = $consultation->title;		    
			$comments = getCommentsByConsultation($consultation->id);		    
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

	$breadcrumb = 'commentInput';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Comments for <?php print $consultation->title; ?></title>
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

<?php
	if (isset($_GET['commentsReceived'])) {
?>
		<h2>Thank you for your comments.</h2>
<?php
	}
?>
	
		<p><span class="comment">
<?php
	if (!isset($_SESSION['userID'])) {
?>
		Log in to make a comment
<?php
	} 
	else {
?>
		<a href="http://<?php print $DOMAIN;?>/site/scripts/comment_input.php?consultationID=<?php print $consultation->id;?>" >Make a comment</a>
<?php
	}
?>
		</span></p>

<?php
	if (sizeof($comments) > 0){
		foreach ($comments as $comment) {
			$commentUser = getUser($comment->userID);
?>
				<h2>Made by: <?php if ($comment->anonymous) print "Anonymous"; else print "$commentUser->salutation $commentUser->forename $commentUser->surname"; ?></h2>
				<p class="date"><?php print $comment->getFormattedDate("D d F Y (H:i)"); ?></p>
				<p><?php print nl2br(substr($comment->comments,0,MAX_COMMENT_SIZE)); if (strlen($comment->comments) > MAX_COMMENT_SIZE) print " ..."; ?></p>
<?php
			if (strlen($comment->comments) > MAX_COMMENT_SIZE) {
?>
				<p><a href="http://<?php print $DOMAIN; ?>/site/scripts/comment_full.php?commentID=<?php print $comment->id; ?>&amp;consultationID=<?php print $consultation->id;?>">Read comment in full</a></p>
<?php
			}
		}
	}
	else {
?>
		<p>There are no comments currently available for this consultation.</p>
<?php
	}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>