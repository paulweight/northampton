<?php
	include_once('intranet/JaduIntranetPersonnel.php');

	$searchText = trim($_REQUEST['searchText']);

	if (!empty($searchText)) {
		$people = searchPersonnelByName($_REQUEST['searchText'], 'surname');

		if (sizeof($people) > 0) {
			foreach ($people as $person) {

?>
				<li>
					<a href="http://<?php print $DOMAIN;?>/site/scripts/personnel_info.php?personID=<?php print $person->id;?>" title="<?php print "$person->surname, $person->forename";?>"><?php print "$person->surname, $person->forename";?></a> 
				</li>
<?php
			}
		}
		else {
?>
			<li>No results for <?php print $searchText; ?></li>
<?php
		}
	}
	
	print '|' . $_REQUEST['seq'];
?>