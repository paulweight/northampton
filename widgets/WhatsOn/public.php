<?php
	include_once("websections/JaduEvents.php");
	
	//	Get the Event (Pick of week)
	$pickOfWeek = getPickOfWeek();
	
	$oldPickSummary = $pickOfWeek->summary;
	// get the first 200 chars of the pick of the week summary, splitting at 
	// a word boundary
	if (mb_strlen($pickOfWeek->summary) > 200) {
		$pickOfWeek->summary = mb_substr($pickOfWeek->summary, 0, 200);
		$pos = mb_strrpos($pickOfWeek->summary, " ");
		if ($pos !== false) {
			$pickOfWeek->summary = mb_substr($pickOfWeek->summary, 0, $pos - 3);
			$pickOfWeek->summary .= "...";
		}
	}
?>

<div class="WhatsOnWidget">
	<h2>What's on</h2>
	<h3><a href="<?php print getSiteRootURL() . buildEventsURL(-1,'',$pickOfWeek->id); ?>"><?php print encodeHtml($pickOfWeek->title);?></a></h3>
	<p><?php print encodeHtml($pickOfWeek->summary);?></p>
	<p><a href="<?php print getSiteRootURL() . buildEventsURL(); ?>">More events.</a></p>
</div>