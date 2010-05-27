<?php
	include_once("JaduStyles.php");
	include_once('JaduConstants.php');
	include_once('utilities/JaduStatus.php');   
	include_once("marketing/JaduUsers.php");
	include_once("intranet/JaduIntranetRoomBookingAreas.php");
	include_once("intranet/JaduIntranetRoomBookingFacilities.php");
	include_once("intranet/JaduIntranetRoomBookingRoomFacilities.php");
	include_once("intranet/JaduIntranetRoomBookingRooms.php");
	include_once("intranet/JaduIntranetRoomBooking.php");
	include_once("intranet/JaduIntranetRoomBookingContacts.php");
	include_once("egov/JaduEGovMeetingMinutes.php");
	
	if (!isset($_GET['bookingID']) && !isset($_SESSION['userID'])) {
		$referer = "http://" . $DOMAIN . $_SERVER['PHP_SELF'] . '?' . htmlspecialchars($_SERVER['QUERY_STRING']);
		
		$referer = urlencode($referer);
		
		header("Location: ../index.php?sign_in=true&referer=$referer");
		exit;
	}

	$startTime = 0;
	$endTime = 23;

	if (isset($_POST['submit']) || isset($_POST['updateBooking'])) {

		$booking = new Booking();
		
		if (isset($_POST['updateBooking'])) {
			$booking = getBooking($_POST['bookingID']);
			$_GET['bookingID'] = $_POST['bookingID'];
		}

		$booking->roomID = $_GET['roomID'];

		$booking->date = $_POST['startDate'];
		
		$booking->startTime = $_POST['startTime'].":00";

		$booking->endTime = $_POST['endTime'].":00";

		$booking->interval = $_POST['interval'];

		$booking->notes = $_POST['notes'];

		$booking->bookedByAdmin = $_SESSION['userID'];

		$booking->duration = $_POST['duration'];

		$booking->meetingID = $_POST['meetingID'];

		$errors = validateBooking($booking);

		if (isset($_POST['updateBooking']) && sizeof(getAllBookingsInGroup($booking->bookingGroup)) > 1) {
			$tmpBookings = getAllBookingsInGroup($booking->bookingGroup);
			$lastBooking = array_pop($tmpBookings);
			if ($lastBooking->date >= mktime(0,0,0,date('m'), date('d'), date('Y')) && $errors['oldDate'] == true) {
				unset($errors['oldDate']);
			}
		}
		//elseif (isset($_POST['updateBooking']) && sizeof(getAllBookingsInGroup($booking->bookingGroup) == 1) {
		//	if ($booking->date >= mktime(0,0,0,date('m'), date('d'), date('Y')) && $errors['oldDate'] == true) {
		//		unset($errors['oldDate']);
		//	}
		//}

		$bookingSuccess = false;

		if (sizeof($errors) < 1) {

			$bookingOverlaps = checkBookingTimes($booking);
			
			if (sizeof($bookingOverlaps) < 1) {

				if (isset($_POST['updateBooking'])) {
					deleteBooking($booking->id);
				}

				list ($day,$month,$year) = split('[./-]',$_POST['startDate']);

				$booking->date = "$year$month$day";

				$booking->bookingGroup = getNextBookingGroupNumber();

				if ($_POST['interval'] == "justThisDay") {
          			$booking->date = $item->displayUntilDate = mktime(23, 59, 59, substr($booking->date, 4, 2), substr($booking->date, 6, 2), substr($booking->date, 0, 4));
					$_GET['bookingID'] = addBooking($booking);
					$bookingSuccess = true;
				}
				else {
					for ($i = 0; $i < $_POST['duration']; $i++) {

						if ($_POST['interval'] == 'daily') {
							$booking->date = mktime(0,0,0,$month,$day++,$year);
						}
						if ($_POST['interval'] == 'weekly') {
							$booking->date = mktime(0,0,0,$month,$day,$year);
							$day += 7;
						}

						//$booking->date = date("Y-m-d", $booking->date);
						$_GET['bookingID'] = addBooking($booking);
						$bookingSuccess = true;
					}
				}

				$bookedBy = getUser($booking->bookedByAdmin);

				list ($tmpDay,$tmpMonth,$tmpYear) = split("[-/]", $_POST['startDate']);
				$booking->date = mktime(0,0,0,$tmpDay,$tmpMonth,$tmpYear);
			}
		}
		
		if (!$errors['date'] && !$errors['oldDate'] && !$bookingSuccess && sizeof($bookingOverlaps) < 1) {
			list ($tmpDay,$tmpMonth,$tmpYear) = split("[-/]", $booking->date);
			$booking->date = mktime(0,0,0,$tmpDay,$tmpMonth,$tmpYear);
		}
		
		// if the booking was successful then email the people assigned to room alerts
		if ($bookingSuccess) {
			$contacts = getAllRoomBookingContacts();
			
			if ($contacts) {
				
				$room = getRoom($_GET['roomID']);
				
				$header = "From: $DEFAULT_EMAIL_ADDRESS\r\n";
				
				$subject = "New Room Booking Made On Intranet";
	
				$message  = "A room has been booked with the following details:\r\n\r\n";
				$message .= "Room: $room->title\r\n";
				$message .= "Date: " . date("d m Y",mktime(0,0,0,$month,$day,$year)) . "\r\n";
				$message .= "Time: " . $booking->startTime . " - " . $booking->endTime ."\r\n";
				if ($booking->interval == 'daily') {
					$message .= "Duration: ". $booking->interval ." for ". $booking->duration  ." days \r\n"; 
				}
				if ($booking->interval == 'weekly') {
					$message .= "Duration: ". $booking->interval ." for ". $booking->duration  ." weeks \r\n"; 
				}
				$message .= "Booked by: $bookedBy->forename $bookedBy->surname\r\n";
				if (!empty($notes)) {
					$message .= "Notes: $booking->notes";
				}
				
				foreach($contacts as $contact) {
					//mail($contact->email, $subject, $message, $header);
				}
			}
		}
	}

	if (isset($_GET['startDate']) && isset($_GET['roomID'])) {

		$room = getRoom($_GET['roomID']);

		$area = getArea($room->areaID);

		if (!isset($_POST['submit'])) {

			$booking->startTime = $_GET['time'];

			$booking->endTime = $_GET['time'];

			$booking->date = $_GET['startDate'];
		}
	}

	if (isset($_GET['bookingID'])) {

		$bookingsInGroup = array();

		$booking = getBooking($_GET['bookingID']);

		$bookingsInGroup = getAllBookingsInGroup($booking->bookingGroup);

		$booking = $bookingsInGroup[0];

		$_GET['roomID'] = $booking->roomID;
		$_GET['startDate'] = $booking->date;

		$room = getRoom($booking->roomID);

		$area = getArea($room->areaID);

		$bookedBy = getUser($booking->bookedByAdmin);

		if ((isset($_POST['submit']) || isset($_POST['updateBooking'])) && ($errors['date'] || $errors['oldDate'])) {
			$booking->date = $_POST['startDate'];
		}		
	}

	if (isset($_POST['cancel'])) {

		deleteBooking($_POST['bookingID']);

		header("Location: room_search.php?cancelSuccess=true");
		exit;
	}

	$disableInput = false;
	if (isset($bookingID) && $_SESSION['userID'] != $booking->bookedByAdmin) {
		$disableInput = true;
	}
	
	$meetingHeaders = getAllMeetingMinutesHeaders();
	
	$breadcrumb = 'roomBooking';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>	
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Room Booking</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="home, homepage, index, root, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print $DOMAIN;?> is the online resource for <?php print METADATA_GENERIC_COUNCIL_NAME;?> - with council services online" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online - Tel: <?php print $address->telephone;?>" />
	<meta name="DC.description" lang="en" content="<?php print $DOMAIN;?> is the online resource for <?php print METADATA_GENERIC_COUNCIL_NAME;?> - with council services online" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script language="javascript" type="text/javascript">
	
		function showDuration(interval)
		{
			if (interval.value == 'daily') {
				
				document.getElementById('durationArea').style.display = 'block';
				document.getElementById('durationType').innerHTML = 'days';
				
			}
			else if (interval.value == 'justThisDay') {
				
				document.getElementById('durationArea').style.display = 'none';
				
			}
			else if (interval.value == 'weekly') {
				
				document.getElementById('durationArea').style.display = 'block';
				document.getElementById('durationType').innerHTML = 'weeks';				
			}
		}

		function showIntervalAndDuration(interval, duration)
		{
			if (interval == 'daily') {
				
				document.getElementById('durationArea').style.display = 'block';
				document.getElementById('durationType').innerHTML = 'days';
				document.getElementById('duration').value = duration;				
				
			}
			else if (interval == 'justThisDay') {
				
				document.getElementById('durationArea').style.display = 'none';
				
			}
			else if (interval == 'weekly') {

				document.getElementById('durationArea').style.display = 'block';
				document.getElementById('durationType').innerHTML = 'weeks';
				document.getElementById('duration').value = duration;
			}
		}

		function confirmCancellation()
		{
			return confirm("Are you sure you want to cancel this booking?");
		}

		// **** meeting code ****
		
		function Meeting () {

			// add some properties to our Person
			this.id = -1 ;
			this.date = -1;
		}

		var headerIDs = Array();

<?php
	foreach ($meetingHeaders as $header) {
		$meetings = getAllMeetingMinutesForHeader ($header->id, true);
?>
		var meetings = Array();
<?php
		foreach ($meetings as $meeting) {
?>
			var meeting = new Meeting();
			meeting.id = '<?php print $meeting->id; ?>';
			meeting.date = '<?php print $meeting->getMeetingMinutesDateFormatted("d M Y"); ?>';
			meetings.push(meeting);
<?php
		}
?>
		headerIDs[<?php print $header->id; ?>] = meetings;
<?php
	}
?>

		function showMeetingsForHeader(headerSelect)
		{
			var meetingSelect = document.getElementById('meetingID');
			
			if (meetingSelect.options.length > 0) {
				for (var i = (meetingSelect.options.length - 1); i >= 0; i--) {
					meetingSelect.options[i] = null; 
				} 
				meetingSelect.selectedIndex = -1;
			}
			
			if (headerSelect.value > -1) {
				var meetings = headerIDs[headerSelect.value];
				
				for (var i = 0; i < meetings.length; i++) {
					meetingSelect.options[i] = new Option(meetings[i].date, meetings[i].id, false, false);
				}
				
				document.getElementById("meetingRow").setAttribute("style","display:inline-block");
				document.getElementById("meetingRow").style.display = "inline-block";
			}
			else {
				meetingSelect.options[0] = new Option("", "-1", false, false);
				meetingSelect.selectedIndex = 0;

				document.getElementById("meetingRow").setAttribute("style","display:none");
				document.getElementById("meetingRow").style.display = "none";
			}
		}

	</script>
	
	<script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/calendar.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (!isset($_GET['bookingID']) && !isset($_SESSION['userID'])) {
?>
	<h2 class="warning">You must sign in to make a booking.</h2>
<?php
	}
	else {
?>		
			<table>
				<tr>
					<td><strong>Booked by</strong></td>
					<td><?php print "$bookedBy->forename $bookedBy->surname"; ?></td>
				</tr>
				<tr>
					<td><strong>Room</strong></td>
					<td><?php print $room->title; ?></td>
				</tr>
				<tr>
					<td><strong>Area</strong></td>
					<td><?php print $area->title; ?></td>
				</tr>
			</table>
			
<?php
			if (sizeof($errors) > 0) {
				print '<h2 class="warning">Your booking could not be made for the following reasons</h2>';
				if ($errors['date']) {
					print "<p class=\"warning\">You must enter a start date in the format dd-mm-yyyy</p>";
				}
				if ($errors['oldDate']) {
					print "<p class=\"warning\">The date you entered is in the past</p>";
				}
				if ($errors['time']) {
					print "<p class=\"warning\">You cannot enter an end time that is before or equal to the start time</p>";
				}
				if ($errors['duration']) {
					print "<p class=\"warning\">You must enter a duration when you select an interval of daily or weekly</p>";
				}
			}
			if (sizeof($bookingOverlaps) > 0) {
				print '<h2 class="warning">Your booking could not be made as it would overlap with bookings on the following dates</h2>';
				
				foreach ($bookingOverlaps as $overlapDate) {
					
					list ($tmpYear, $tmpMonth, $tmpDay) = split("[/-]", $overlapDate);
					$tmpDate = mktime(0,0,0,$tmpMonth, $tmpDay, $tmpYear);
					print "<p class=\"warning\">" . date("D d M Y</li>", $tmpDate) . "</p>";
				}
			}
			if ($bookingSuccess && !isset($_POST['updateBooking'])) {
				print '<h2>Your booking has been made</h2>';
			}
			elseif (isset($_POST['updateBooking']) && $bookingSuccess) {
				print '<h2>Your booking has been updated</h2>';
			}
		?>

			<form name="bookingForm" id="bookingForm" class="basic_form" action="http://<?php print $DOMAIN; ?>/site/scripts/room_booking.php?roomID=<?php print $_GET['roomID']; ?>&startDate=<?php print $_GET['startDate']; ?>&date=<?php print $_GET['date']; ?>&roomIDs=<?php print $_GET['roomIDs']; ?>" method="post">
				<fieldset>	
					<input type="hidden" value="-1"  name="meetingHeaderID" />

<?php
			if (isset($_GET['bookingID'])) {
?>
				<input type="hidden" name="bookingID" value="<?php print $_GET['bookingID']; ?>" />
<?php
			}
?>
					<p>
						<label for="startDate">Start Date <em>(required)</em></label>
<?php
					if ($disableInput) {
?>
					<input type="text" disabled="disabled" class="field disabled" name="startDate" value="<?php print date("d-m-Y", $booking->date); ?>" />
<?php
					}
					else {
?>
							<input type="text" class="field" name="startDate" value="<?php if (($errors['date'] || $errors['oldDate']) || sizeof($bookingOverlaps) > 0) print $booking->date; else print date("d-m-Y", $booking->date); ?>"> <a href="#" onclick="cal1.popup(); return false;"><img src="http://<?php print $DOMAIN; ?>/site/images/cal.gif" /></a>
							<script language="JavaScript">
							<!-- // create calendar object(s) just after form tag closed
								 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
								 // note: you can have as many calendar objects as you need for your application
								var cal1 = new calendar1(document.forms['bookingForm'].elements['startDate']);
								cal1.year_scroll = true;
								cal1.time_comp = false;
							//-->
							</script>
<?php
					}
?>
					<span class="clear"></span>
					</p>
					<p>
						<label for="interval">Duration <em>(required)</em></label>
<?php
					if ($disableInput) {
						switch ($booking->interval) {
							case "justThisDay":
								print "Just this day";
								break;
							case "daily":
								print $booking->duration . "Days";
								break;
							case "weekly":
								print $booking->duration . "Weeks";
								break;
						}
					}
					else {
?>
							<select name="interval" size="1" onchange="showDuration(this);">
								<option value="justThisDay" <?php if ($booking->interval == 'justThisDay') print 'selected="selected"'; ?>>Just This Day</option>
								<option value="daily" <?php if ($booking->interval == 'daily') print 'selected="selected"'; ?>>Daily</option>
								<option value="weekly" <?php if ($booking->interval == 'weekly') print 'selected="selected"'; ?>>Weekly</option>
							</select>
						<span class="clear"></span>
					</p>
						
					<div style="display:none;" id="durationArea">
						<p class="center" >
								For <input type="text" size="2" id="duration" name="duration" value="" /> <span id="durationType">Weeks</span>
						</p>
					</div>

							<script language="javascript">

								showIntervalAndDuration('<?php print $booking->interval; ?>','<?php if ($errors['duration'] || sizeof($bookingOverlaps) > 0) print $booking->duration; else print sizeof($bookingsInGroup); ?>');

							</script>
<?php
					}
?>

					<p>
						<label for="startTime">Start Time <em>(required)</em></label>
<?php
					if ($disableInput) {
?>
					<input type="text" disabled="disabled" class="field disabled" name="startTime" value="<?php print substr($booking->startTime,0,5); ?>" />
<?php
					}
					else {
?>
							<select name="startTime" size="1">
<?php
							$indexUsed = -1;
							for ($i = $startTime; $i <= $endTime; $i += 1) {
								$selected = "";
								if (str_pad($i.":00", 5, "0", STR_PAD_LEFT) == substr($booking->startTime,0,5)) {
									$selected = 'selected="selected"';
									$indexUsed = $i;
								}
?>
								<option value="<?php print str_pad($i.":00", 5, "0", STR_PAD_LEFT); ?>" <?php print $selected; ?>><?php print str_pad($i.":00", 5, "0", STR_PAD_LEFT); ?></option>
<?php
								$selected = "";
								if (str_pad($i.":30", 5, "0", STR_PAD_LEFT) == substr($booking->startTime,0,5)) {
									$selected = 'selected="selected"';
									$indexUsed = $i + 0.5;
								}
?>
								<option value="<?php print str_pad($i.":30", 5, "0", STR_PAD_LEFT); ?>" <?php print $selected; ?>><?php print str_pad($i.":30", 5, "0", STR_PAD_LEFT); ?></option>
<?php
							}
?>
							</select>
<?php
					}
?>
					<span class="clear"></span>
					</p>
					<p>
						<label for="endTime">End Time <em>(required)</em></label>
<?php
					if ($disableInput) {
?>					
					<input type="text" disabled="disabled" class="field disabled" name="endTime" value="<?php print substr($booking->endTime,0,5); ?>" />
						
<?php
					}
					else {
?>
							<select name="endTime" size="1">
<?php
							for ($i = $startTime; $i <= $endTime; $i += 1) {
								$selected = "";
								if ((!isset($_GET['bookingID']) && !isset($_POST['submit']) && $i == $indexUsed + 3) || 
									(str_pad($i.":00", 5, "0", STR_PAD_LEFT) == substr($booking->endTime,0,5))) {
									$selected = 'selected="selected"';
								}
?>
								<option value="<?php print str_pad($i.":00", 5, "0", STR_PAD_LEFT); ?>" <?php print $selected; ?>><?php print str_pad($i.":00", 5, "0", STR_PAD_LEFT); ?></option>
<?php
								$selected = "";
								if ((!isset($_GET['bookingID']) && !isset($_POST['submit']) && $i == $indexUsed + 2.5) || 
									(str_pad($i.":30", 5, "0", STR_PAD_LEFT) == substr($booking->endTime,0,5))) {
									$selected = 'selected="selected"';
								}
?>
								<option value="<?php print str_pad($i.":30", 5, "0", STR_PAD_LEFT); ?>" <?php print $selected; ?>><?php print str_pad($i.":30", 5, "0", STR_PAD_LEFT); ?></option>
<?php
							}
?>
							</select>
<?php
					}
?>
					<span class="clear"></span>
					</p>
					<p>
						<label for="notes">Notes</label>
						<textarea id="notes" name="notes" class="field" cols="2" rows="5"><?php print $booking->notes;?></textarea>
						<span class="clear"></span>
					</p>
												
					<p class="center">
<?php
				if ((isset($_GET['bookingID']) || $bookingSuccess) && isset($_SESSION['userID']) && $_SESSION['userID'] == $booking->bookedByAdmin) {
?>
						<input type="submit" name="updateBooking" class="button" value="Update Booking">
						<input type="submit" class="button" name="cancel" value="Cancel Booking" onclick="return confirmCancellation();" />
<?php
				}
				elseif (!isset($_GET['bookingID']) && !$bookingSuccess) {
?>
						<input type="submit" name="submit" class="button" value="Make Booking">
<?php
				}
	}
?>
					</p>
				</fieldset>
			</form>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
