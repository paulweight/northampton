<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="opinions, poll, results, previous, past, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Voting polls - quick research on local issues" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Past Opinion Poll Results" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Voting polls - quick research on local issues" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php if(!isset($_REQUEST['pollID']) && !is_numeric($_REQUEST['pollID'])){ ?>
	<p>Opinion polls and their final results.</p>

<?php
	$archivedPolls = getArchivedPolls();

	if (sizeof($archivedPolls) > 0) {
?>
	<ul class="list icons generic">
<?php
		foreach($archivedPolls as $poll) {
?>
		<li>
			<strong><a href="<?php print getSiteRootURL();?>/site/scripts/poll_past_results.php?pollID=<?php print $poll->id; ?>"><?php print encodeHtml($poll->question); ?></a></strong> - Concluded on <?php print formatDateTime(FORMAT_DATE_FULL, $poll->getLastVoteDate()); ?> with <?php print $poll->getTotalVotes(); ?> votes.
		</li>
<?php
		}
?>
	</ul>
<?php
	}
?>

<?php } else { ?>

<?php
	if (isset($_REQUEST['pollID']) && is_numeric($_REQUEST['pollID']) && $currentPoll->id != $_REQUEST['pollID']) {
?>

	<h2 class="warning">Sorry, this poll is not available</h2>
	
<?php
	}
	else if ($currentPoll->id != NULL){
?>	

	<h2><?php print encodeHtml($currentPoll->question); ?></h2>
	<p>Total Votes: <strong><?php print $currentPoll->getTotalVotes(); ?></strong></p>
	
<?php
	if(sizeof ($currentPoll->answers) > 0) {
?>
	<ul class="list icons generic">
<?php
		$i = 1;
		foreach($currentPoll->answers as $answer) {
			list($count, $percentage) = $currentPoll->getCountPercentageArray($i);
?>	
		<li>
			<?php print encodeHtml($answer); ?> : <?php print (int) $count; ?> vote<?php if ($count != 1) { print 's'; } ?> : <?php print (int) $percentage; ?>%	
<?php
			if ($count > 0) {
?>
			<img src="<?php print getStaticContentRootURL(); ?>/site/images/poll_bar.png" width="<?php print round($percentage * 0.85); ?>&#37;" alt="Poll bar at <?php print (int) $percentage; ?>&#37;" height="12" />
<?php
			}
?>
		</li>
<?php
			$i++;
		}
		print '</ul>';
		}
		
	}
	else {
		print '<h2>There is no current opinion poll</h2>';
	}
?>

<?php
	$archivedPolls = getArchivedPolls();
	if(sizeof($archivedPolls) > 0) {
?>
	<p class="clear"><a href="<?php print getSiteRootURL() . buildPastPollResultsURL() ;?>">View past polls</a></p>
<?php
	}
?>
	<p class="note">Please note: All poll results are indicative and may not reflect public opinion.</p>

<?php } ?>
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>