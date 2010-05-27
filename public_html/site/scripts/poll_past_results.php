<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("websections/JaduPolls.php");

	$breadcrumb = 'pollList';	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Past opinion polls | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="opinions, poll, results, previous, past, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Voting polls - quick research on local issues" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Past Opinion Poll Results" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Voting polls - quick research on local issues" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<p class="first">Opinion polls and their final results.</p>

<?php
	$archivedPolls = getArchivedPolls();
	foreach($archivedPolls as $poll) {
?>
			
	<div class="cate_info">
		<h2><abbr title="Question">Q:</abbr> <a href="http://<?php print $DOMAIN; ?>/site/scripts/poll_results.php?pollID=<?php print $poll->id;?>"><?php print $poll->question; ?></a></h2>
		<p class="date">Concluded on <?php print date('F jS, Y', $poll->getLastVoteDate()); ?> with <strong><?php print $poll->getTotalVotes(); ?> votes</strong>.</p>
	</div>
<?php
	}
?>
		
	<p class="note">Please note: All poll results are indicative and may not reflect public opinion or that of the Society.</p>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>