<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php"); 
	
	include_once("websections/JaduPolls.php");
	
	if (isset($_SESSION['userID'])) {
		$user = getUser($_SESSION['userID']);
	}
	if (isset($_REQUEST['pollID']) && is_numeric($_REQUEST['pollID'])) {
		$currentPoll = getPoll($_REQUEST['pollID']);
		if (isset($_REQUEST['answer']) and $_SESSION["voted$currentPoll->id"] == null) {
			$currentPoll->addAnswer($_REQUEST['answer']);
			$_SESSION["voted$currentPoll->id"] = 1;
		}
	}
	else {
		$currentPoll = getCurrentPoll();
	}

	if ($currentPoll == null) {
		header("Location: ../index.php");
		exit;
	}
	
	$breadcrumb = 'pollResults';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Opinion poll results | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="opinions, poll, results, previous, past, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Opinion polls - <?php print $currentPoll->question;?>" />

	<meta name="DC.title" lang="en" content="<?php print $currentPoll->question;?> - <?php print METADATA_GENERIC_COUNCIL_NAME;?> Opinion Poll Results" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Opinion polls - <?php print $currentPoll->question;?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
					
<?php
	if (isset($_REQUEST['pollID']) && $currentPoll->id != $_REQUEST['pollID']) {
?>
	<h2 class="warning">Sorry, this poll is not available</h2>
<?php
	}
	else {
?>	
	<h2><abbr title="Question">Q</abbr>: <?php print $currentPoll->question;?></h2>
	<p class="first">Total Votes: <strong><?php print $currentPoll->getTotalVotes(); ?></strong></p>		
<?php
		$i = 1;
		foreach($currentPoll->answers as $answer) {
			list($count, $percentage) = $currentPoll->getCountPercentageArray($i);
?>			
	
		<div class="cate_info">
		<h3><?php print $answer;?>  <?php print $percentage; ?>&#37;</h3>
		<div class="poll_box">
<?php
		if ($count > 0) {
?>
		<img src="http://<?php print $DOMAIN; ?>/site/images/poll_bar.png" width="<?php print round($percentage * 0.85); ?>&#37;" alt="Poll bar at <?php print round($percentage * 0.85); ?>&#37;" height="22" />
<?php
		}
?>
		</div>
		<p class="note"><?php print $count; ?> total votes</p>
		</div>
	
<?php
			$i++;
		}
	}
?>
	
	<p class="first"><a href="http://<?php print $DOMAIN;?>/site/scripts/poll_past_results.php">View past polls</a>.</p>
	<p class="note">Please note: All poll results are indicative and may not reflect public opinion or that of the council.</p>


	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>