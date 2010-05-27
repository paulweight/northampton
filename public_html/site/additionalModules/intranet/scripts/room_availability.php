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

	$roomsToShow = 3;
	$officeStart = 8;
	$officeEnd = 18;
	$nightStart = 19;
	$nightEnd = 23;
	$morningStart = 0;
	$morningEnd = 7;

	if (isset($_POST['roomSearch'])) {

		if (!ereg("[0-9]{2}[-/\.][0-9]{2}[-/\.]20[0-9]{2}", $_POST['date']) || sizeof($_POST['areaList']) < 1) {
			header("Location: room_search.php?failedSearch=true&reason=insufficientDetails");
			exit;
		}

		else {
			$allRooms = array();

			$roomsInArea = array();
			$roomsWithFacilities = array();

			// get all the rooms for the selected areas
			foreach ($_POST['areaList'] as $areaID) {
				$roomsInArea = array_merge($roomsInArea, getAllRoomsInArea($areaID));
			}

			// get all the rooms with the selected facilities
			if (sizeof($_POST['facilityList']) > 0) {
				foreach ($_POST['facilityList'] as $facilityID) {
					$roomsWithFacilities = array_merge($roomsWithFacilities, getAllRoomsWithFacility($facilityID));
				}

				// check that the rooms with facilities is in the chosen areas 
				foreach ($roomsWithFacilities as $room) {

					foreach ($roomsInArea as $r) {

						if ($r->id == $room->id) {
							$allRooms[] = $room;
							break;
						}
					}
				}

				unset($room);
			}

			else {
				$allRooms = $roomsInArea;
			}

			if (sizeof($allRooms) < 1) {
				header("Location: room_search.php?failedSearch=true&reason=noRooms");
				exit;
			}
			
			// make the date into sometyhing we can use
			list ($day,$month,$year) = split('[./-]',$_POST['date']);
			$currentDate = mktime(0,0,0,$month,$day,$year);
			$previousDate = mktime(0,0,0,$month,$day - 1,$year);
			$nextDate = mktime(0,0,0,$month,$day + 1,$year);
		}
	}
	
	elseif (isset($_POST['checkRoom'])) {
		
		if (!ereg("[0-9]{2}[-/\.][0-9]{2}[-/\.]20[0-9]{2}", $_POST['date'])) {
			header("Location: room_search.php?failedCheck=true&reason=insufficientDetails");
			exit;
		}
	
		$allRooms = array();
		$allRooms[] = getRoom($_POST['roomID']);
		
		// make the date into sometyhing we can use
		list ($day,$month,$year) = split('[./-]',$_POST['date']);
		$currentDate = mktime(0,0,0,$month,$day,$year);
		$previousDate = mktime(0,0,0,$month,$day - 1,$year);
		$nextDate = mktime(0,0,0,$month,$day + 1,$year);
	}
	elseif (isset($_GET['date']) && isset($_GET['roomIDs'])) {
		
		$roomIDs = explode(',', $_GET['roomIDs']);
		$allRooms = array();
		foreach ($roomIDs as $id) {
			$allRooms[] = getRoom($id);
		}
		
		$currentDate = $_GET['date'];
		$previousDate = strtotime("-1 day", $currentDate);
		$nextDate = strtotime("+1 day", $currentDate);
	}
	else {
		header("Location: room_search.php");
		exit;
	}
	
	// create a string of room ids that can be used in a query string
	$roomIDstring = "";
	foreach ($allRooms as $room) {
		$roomIDstring .= "$room->id,";
	}
	
	$roomIDstring = rtrim($roomIDstring,',');
	
	$currentDaysBookings = getAllBookingsForRoomsOnDate(explode(',', $roomIDstring), date("Y-m-d",$currentDate));
	
	unset($room);
	
	$breadcrumb = 'roomAvailability';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Room Availability</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="home, homepage, index, root, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print $DOMAIN;?> is the online resource for <?php print METADATA_GENERIC_COUNCIL_NAME;?> - with council services online" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online - Tel: <?php print $address->telephone;?>" />
	<meta name="DC.description" lang="en" content="<?php print $DOMAIN;?> is the online resource for <?php print METADATA_GENERIC_COUNCIL_NAME;?> - with council services online" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

	<script type="text/javascript" language="javascript">
		
		var roomList = Array();
		var displayStart = 0;
		var roomsToShow = <?php print $roomsToShow; ?>;
		var officeStart = <?php print $officeStart; ?>;
		var officeEnd = <?php print $officeEnd; ?>;
		var morningStart = <?php print $morningStart; ?>;
		var morningEnd = <?php print $morningEnd; ?>;
		var nightStart = <?php print $nightStart; ?>;
		var nightEnd = <?php print $nightEnd; ?>;

		// add to room list
		function addToRoomList(id)
		{
			roomList.push(id);
		}

		function showNextRoom()
		{
			if (displayStart < roomList.length - roomsToShow) {
			
				var table = document.getElementById('bookingTable');
				var rows = table.getElementsByTagName('tr');
		
				displayStart++;
			
				for (var row = 0; row < rows.length; row++) {
				
					for (var i = 0; i < roomList.length; i++) {
						
						if (i >= displayStart && i < (displayStart + roomsToShow)) {
							table.rows[row].cells[i+1].style.display = "";
						}
						else {
							table.rows[row].cells[i+1].style.display = "none";
						}
					}
    			}
    			
    			if (displayStart >= (roomList.length - roomsToShow)) {
    				document.getElementById('nextRoom').style.display = "none";
    			}
    			document.getElementById('previousRoom').style.display = "block";
			}
		}

		function showPreviousRoom()
		{
			if (displayStart > 0) {
			
				var table = document.getElementById('bookingTable');
				var rows = table.getElementsByTagName('tr');
		
				displayStart--;
			
				for (var row = 0; row < rows.length; row++) {
				
					for (var i = 0; i < roomList.length; i++) {
						
						if (i >= displayStart && i < (displayStart + roomsToShow)) {
							table.rows[row].cells[i+1].style.display = "";
						}
						else {
							table.rows[row].cells[i+1].style.display = "none";
						}
					}
    			}
    			
    			if (displayStart == 0) {
    				document.getElementById('previousRoom').style.display = "none";
    			}
    			document.getElementById('nextRoom').style.display = "block";
			}
		}
		
		function changeTimeView(timeSpan)
		{
			var table = document.getElementById('bookingTable');
			var rows = table.getElementsByTagName('tr');
				
			if (timeSpan.value == 'office') {
			
				for (var row = 1; row < rows.length; row+=2) {
					if (row >= (officeStart * 2) && row < (officeEnd * 2) + 2) {
						rows[row].style.display = "";
						rows[row+1].style.display = "";
					}
					else {
						rows[row].style.display = "none";
						rows[row+1].style.display = "none";
					}				
				}
				
			}
			else if (timeSpan.value == 'morning') {
				for (var row = 1; row < rows.length; row+=2) {
					if (row >= morningStart && row < (morningEnd * 2) + 2) {
						rows[row].style.display = "";
						rows[row+1].style.display = "";
					}
					else {
						rows[row].style.display = "none";
						rows[row+1].style.display = "none";
					}				
				}
				
			}
			else if (timeSpan.value == 'evening') {
			
				for (var row = 1; row < rows.length; row+=2) {
					if (row >= (nightStart * 2) && row < (nightEnd * 2) + 2) {
						rows[row].style.display = "";
						rows[row+1].style.display = "";
					}
					else {
						rows[row].style.display = "none";
						rows[row+1].style.display = "none";
					}				
				}
			
			}
		}

<?php
	foreach ($allRooms as $room) {
?>
		addToRoomList('room_<?php print $room->id; ?>');
<?php
	}
	unset($room);
?>
	</script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

			<table width="100%">
				<tr>
					<td align="left" width="33%"><a href="http://<?php print $DOMAIN; ?>/site/scripts/room_availability.php?date=<?php print $previousDate; ?>&roomIDs=<?php print $roomIDstring; ?>">&lt;&lt;&nbsp;Previous day</a></td>
					<td align="center" width="33%"><b><?php print date("D d F Y",$currentDate); ?></b></td>
					<td align="right" width="33%"><a href="http://<?php print $DOMAIN; ?>/site/scripts/room_availability.php?date=<?php print $nextDate; ?>&roomIDs=<?php print $roomIDstring; ?>">Next day&nbsp;&gt;&gt;</a></td>
				</tr>
			</table>

<?php
		if (sizeof($allRooms) > $roomsToShow) {
?>
			<table width="100%">
				<tr>
					<td align="left" width="33%"><div id="previousRoom" style="display:none;"><a href="#" onclick="showPreviousRoom(); return false;">&lt;&lt;&nbsp;Cycle Back</a></div></td>
					<td align="center" width="33%"><b>Rooms</b></td>
					<td align="right" width="33%"><div id="nextRoom" style="display:<?php if (sizeof($allRooms) > $roomsToShow) print 'block'; else print 'none'; ?>;"><a href="#" onclick="showNextRoom(); return false;">Cycle Forward&nbsp;&gt;&gt;</a></div></td>
				</tr>
			</table>
			
<?php
		}
?>
			
			<p align="center">
				<select size="1" onclick="changeTimeView(this); return false;">
					<option value="office" selected="selected">Office Hours</option>
					<option value="morning">Early Morning</option>
					<option value="evening">Evening and Night</option>
				</select>
			</p>
			
			<table id="bookingTable">

				<tr>
					<th width="10%" align="center">Time</th>
<?php
				$roomCount = 0;
				foreach ($allRooms as $room) {
?>
					<th id="room_<?php print $room->id; ?>" style="<?php if ($roomCount >= $roomsToShow) print "display:none;"; ?>" align="center" width="<?php if (sizeof($allRooms) < 4) print (100 / sizeof($allRooms)); else print '22.5%'; ?>"><?php print $room->title; ?></th>
<?php
					$roomCount++;
				}
?>
				</tr>

<?php
				for ($i = $morningStart; $i <= $nightEnd; $i += 1) {
?>
					<tr id="<?php print $i; ?>" style="<?php if ($i < $officeStart || $i > $officeEnd) print "display:none;"; ?>">
						<td><?php print str_pad($i.":00", 5, "0", STR_PAD_LEFT); ?></td>
<?php
					$roomCount = 0;
					foreach ($allRooms as $room) {
						$booking = isRoomBookedFromBookingList ($room->id, str_pad($i.":00", 5, "0", STR_PAD_LEFT), $currentDaysBookings);
						if ($booking != -1) {
							$user = getUser($booking->bookedByAdmin);
?>
							<td id="room_<?php print $room->id; ?>" style="<?php if ($roomCount >= $roomsToShow) print "display:none;"; ?>"><a href="http://<?php print $DOMAIN; ?>/site/scripts/room_booking.php?bookingID=<?php print $booking->id; ?>&date=<?php print $currentDate; ?>&roomIDs=<?php print $roomIDstring; ?>"><?php if ($user->forename != null && $user->surname != null) print "$user->forename $user->surname"; else print $user->email; ?></a></td>
<?php
						}
						else {
?>
							<td id="room_<?php print $room->id; ?>" style="<?php if ($roomCount >= $roomsToShow) print "display:none;"; ?>"><a href="http://<?php print $DOMAIN; ?>/site/scripts/room_booking.php?roomID=<?php print $room->id; ?>&startDate=<?php print $currentDate; ?>&time=<?php print str_pad($i.":00", 5, "0", STR_PAD_LEFT); ?>&date=<?php print $currentDate; ?>&roomIDs=<?php print $roomIDstring; ?>"><img src="http://<?php print $DOMAIN; ?>/site/images/pen.jpg"></a></td>
<?php
						}
						$roomCount++;
					}
?>
					</tr>

					<tr id="<?php print $i + 0.5; ?>" style="<?php if ($i < $officeStart || $i > $officeEnd) print "display:none;"; ?>">
						<td><?php print str_pad($i.":30", 5, "0", STR_PAD_LEFT); ?></td>
<?php
					$roomCount = 0;
					foreach ($allRooms as $room) {
						$booking = isRoomBookedFromBookingList ($room->id, str_pad($i.":30", 5, "0", STR_PAD_LEFT), $currentDaysBookings);
						if ($booking != -1) {
							$user = getUser($booking->bookedByAdmin);
?>
							<td id="room_<?php print $room->id; ?>" style="<?php if ($roomCount >= $roomsToShow) print "display:none;"; ?>"><a href="http://<?php print $DOMAIN; ?>/site/scripts/room_booking.php?bookingID=<?php print $booking->id; ?>&currentDate=<?php print $nextDate; ?>&roomIDs=<?php print $roomIDstring; ?>"><?php if ($user->forename != null && $user->surname != null) print "$user->forename $user->surname"; else print $user->email; ?></a></td>
<?php
						}
						else {
?>
							<td id="room_<?php print $room->id; ?>" style="<?php if ($roomCount >= $roomsToShow) print "display:none;"; ?>"><a href="http://<?php print $DOMAIN; ?>/site/scripts/room_booking.php?roomID=<?php print $room->id; ?>&startDate=<?php print $currentDate; ?>&time=<?php print str_pad($i.":30", 5, "0", STR_PAD_LEFT); ?>&date=<?php print $currentDate; ?>&roomIDs=<?php print $roomIDstring; ?>"><img src="http://<?php print $DOMAIN; ?>/site/images/pen.jpg"></a></td>
<?php
						}
						$roomCount++;
					}
?>
					</tr>
<?php
				}
?>
			</table>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>