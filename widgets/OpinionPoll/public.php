<?php
	include_once("websections/JaduPolls.php");
	
	$currentPoll = getCurrentPoll();
	if ($_SESSION["voted$currentPoll->id"] != null)
		$noVote = true;
?>

<?php
			if ($currentPoll != null) {
?>
				<div class="opinionPollWidget">
					<h2>Opinion Poll</h2>
					<p><?php print $currentPoll->question;?></p>
				<?php if ($noVote != true) { ?>
					<form name="pollForm" id="pollForm" action="http://<?php print $DOMAIN;?>/site/scripts/poll_results.php" method="post">
						<input type="hidden" name="pollID" value="<?php print $currentPoll->id;?>" />
<?php
					$i = 1;
					foreach($currentPoll->answers as $answer) {
?>
						<p><label for="pollAnswer<?php print $i; ?>"><input type="radio" id="pollAnswer<?php print $i; ?>" name="answer" value="<?php print $i;?>" /> <?php print $answer;?></label>
						<div class="clear"></div></p>
<?php
						$i++;
					}
?>
						<p><input type="submit" value="Vote now" class="button" /></p>
					</form>
<?php
				}
?>
					<p>Total Votes: <?php print $currentPoll->getTotalVotes(); ?> | <a href="http://<?php print $DOMAIN;?>/site/scripts/poll_results.php">Results</a> | <a href="http://<?php print $DOMAIN;?>/site/scripts/poll_past_results.php">Past Polls</a></p>
				</div>
<?php
			}
?>