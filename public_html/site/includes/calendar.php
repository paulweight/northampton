<?php
	include_once("websections/JaduEvents.php");
	include_once("egov/JaduEGovMeetingMinutes.php");
	
	if (isset($_GET['month']) && $_GET['month'] > 0 && isset($_GET['year']) && $_GET['year'] > 1970) {
		$month = (int) $_GET['month'];
		$year = (int) $_GET['year'];
	}
	else {
		$month = (int) date('m');
		$year = (int) date('Y');
	}
	
	$timestamp = mktime(0, 0, 0, $month, 1, $year);
	$daysInMonth = date('t', $timestamp);
	$day = 1 - date('w', $timestamp);
	
	$dayLabels = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	if (CALENDAR_FIRST_DAY > 0 && CALENDAR_FIRST_DAY < 7) {
		$dayLabels = array_merge(array_slice($dayLabels, CALENDAR_FIRST_DAY), array_slice($dayLabels, 0, CALENDAR_FIRST_DAY));
		$day -= 7 - CALENDAR_FIRST_DAY;
		if ($day <= -6) {
			$day += 7;
		}
	}
?>
<div id="calendarcontainer">
<table id="calendar" cellspacing="0" cellpadding="0" summary="This month&#39;s events calendar">
	<caption>
	<!-- Select the month by clicking forward and back -->
<?php
	if ($year > date('Y') - 2) {
		$previousYear = $year;
		$previousMonth = $month - 1;
		if ($previousMonth < 1) {
			$previousMonth = 12;
			$previousYear--;
		}
?>
		<a href="<?php print getSiteRootURL() . buildEventsURL(-1, '', -1, '', '', -1, $previousYear, $previousMonth); ?>" class="nav">&laquo;</a>
<?php
	}
?>	

	<?php print strftime('%B %Y', $timestamp); ?>
	
	<!-- Select the month by clicking forward and back -->
<?php
	if ($year < date('Y') + 2) {
		$nextYear = $year;
		$nextMonth = $month + 1;
		if ($nextMonth > 12) {
			$nextMonth = 1;
			$nextYear++;
		}	
?>	
		<a href="<?php print getSiteRootURL() . buildEventsURL(-1, '', -1, '', '', -1, $nextYear, $nextMonth) ?>" class="nav">&raquo;</a>
<?php
	}
?>
	</caption>
	<tr>
<?php
	foreach ($dayLabels as $dayLabel) {
?>
		<th scope="col" abbr="<?php print encodeHtml($dayLabel); ?>"><span><?php print encodeHtml(substr($dayLabel, 0, 1)); ?></span></th>
<?php
	}
?>
	</tr>
	<tr>
<?php
	$index = 1;
	while (true) {
		$class = '';
		$timestamp = mktime(0, 0, 0, $month, $day, $year);
		$date = date('d-m-Y', $timestamp);
		
		if ($timestamp == mktime(0, 0, 0)) {
			$class = 'today';
		}
?>
		<td<?php if ($class != '') print ' class="' . $class . '"'; ?>>
<?php
		if ($day > 0 && $day <= $daysInMonth) {
			if (sizeof(getEventsForDate(date('d-m-Y', $timestamp))) > 0 || sizeof(getMeetingsInDateRangeAsEvents($date, $date)) > 0) {
?>
			<a href="<?php print buildEventsURL(-1, '', -1, $date, $date, -1, $year, $month) ?>"><?php print strftime('%d', $timestamp); ?></a>
<?php
			}
			else {
				print strftime('%d', $timestamp);
			}
		}
?>
		</td>
<?php
		if ($index % 7 === 0) {
			if ($day > $daysInMonth) {
				break;
			}
?>
		</tr><tr>
<?php
		}

		$index++;
		$day++;
	}
?>
	</tr>
</table>
</div>