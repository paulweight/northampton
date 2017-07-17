<?php
	include_once("websections/JaduEvents.php");

	// if a month and year have been provided we'll add these onto the get text
	$dateRequest = "";

	// see if a specific month has been requested
	if (isset($_GET['month']) && isset($_GET['year'])){
		$currentMonth = $_GET['month'];
		$currentYear = $_GET['year'];
		if (strlen($currentMonth) == 1) {
			$currentMonth = "0" . $currentMonth;
		}
		$dateRequest = "&month=$currentMonth&year=$currentYear";
	}

	// otherwise use this month
	else{
		$currentMonth = date("m");
		$currentYear = date("Y");
	}

	// work out what the next month will be
	$nextMonth = $currentMonth + 1;
	$nextYear = $currentYear;
	if ($nextMonth > 12){
		$nextMonth = 1;
		$nextYear = $currentYear + 1;
	}

	// work out what the previous month is
	$previousMonth = $currentMonth - 1;
	$previousYear = $currentYear;
	if ($previousMonth < 1) {
		$previousMonth = 12;
		$previousYear = $currentYear - 1;
	}

	$days = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
	$monthAsStr = date("M", mktime(0, 0, 0, $currentMonth, 1, $currentYear));
	$monthAsName = date("F", mktime(0, 0, 0, $currentMonth, 1, $currentYear));

	// get the first day, e.g. "mon", "tue" and the ladt date, e.g. 30, 31
	$firstDayOfMonth = date("D", mktime(0, 0, 0, $currentMonth, 1, $currentYear));
	$lastDateOfMonth = date("d", mktime(0, 0, 0, $nextMonth, 1-1, $nextYear));

	// work out how many weeks are in the month
	if (($firstDayOfMonth == "Mon") && ($monthAsStr == "Feb") && $lastDateOfMonth == 28) {
		$numWeeksInMonth = 4;
	}
	elseif ((($firstDayOfMonth == "Sat") || ($firstDayOfMonth == "Sun")) && $lastDateOfMonth == 31) {
		$numWeeksInMonth = 6;
	}
	elseif (($firstDayOfMonth == "Sun") && $lastDateOfMonth >= 30) {
		$numWeeksInMonth = 6;
	}
	else {
		$numWeeksInMonth = 5;
	}

	// function to see whether there is an event for the given day
	function eventIsOnDate($d) {
		global $eventsForMonth;
		foreach ($eventsForMonth as $event) {
			list ($day, $month, $year) = split ('[/.-]', $event->nextDate);
			if (($d == $day) && ($event->live == 1)) {
				return true;
			}
		}
		return false;
	}

?>
<div class="eventCalendarWidget">
<h2><?php print $monthAsName;?></h2>
<table summary="This month's events calendar" class="calendar">
	<tr>
<?php
	foreach ($days as &$day) {
		print "		<th scope=\"col\" abbr=\"$day\">".substr($day, 0, 1)."</th>\n";
	}
?>
	</tr>
	
<?php
	$nextDay = 1;
	$started = false;

	// for each week start a new row
	for ($i = 0; $i < $numWeeksInMonth; $i++){
		print "<tr>\n";
		// for each day start a new column
		foreach ($days as &$day){
			if ($started){
				if (strlen($nextDay) == 1) {
					$nextDay = "0" . $nextDay;
				}
				if ($nextDay <= $lastDateOfMonth) {
					("$nextDay-$currentMonth-$currentYear" == date("d-m-Y")) ? 	print '<td class="today">' : print '<td>';
					if (sizeof(getEventsForDate("$nextDay-$currentMonth-$currentYear")) > 0) {
						$thisDate = $nextDay . "-" . $currentMonth . "-" . $currentYear;
						print "<a href=\"" . getSiteRootURL() ."/site/scripts/events_info.php?startDate=$thisDate&amp;endDate=$thisDate$dateRequest\">" . $nextDay . "</a>";
					}
					else {
						print $nextDay;
					}
					print "</td>\n";
					$nextDay++;
				}
				else {
					print "<td></td>";
				}
			}
			else {
				if (substr($day,0,3) == $firstDayOfMonth){
					("$nextDay-$currentMonth-$currentYear" == date("d-m-Y")) ? 	print '<td class="today">' : print '<td>';
					if (strlen($nextDay) == 1)
						$nextDay = "0" . $nextDay;
					if (sizeof(getEventsForDate("$nextDay-$currentMonth-$currentYear")) > 0) {
						$thisDate = $nextDay . "-" . $currentMonth . "-" . $currentYear;
						print "<a href=\"" . getSiteRootURL() ."/site/scripts/events_info.phpstartDate=$thisDate&amp;endDate=$thisDate$dateRequest\">" . $nextDay . "</a>";
					}
					else
						print $nextDay;
					print "</td>\n";
					$started = true;
					$nextDay++;
				}
				else {
					print "<td></td>\n";
				}
			}
		}
		print "</tr>\n";
	}
?>
</table>

</div>
