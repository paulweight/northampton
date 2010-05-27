<?php
	include_once("JaduStyles.php"); 
	include_once('JaduConstants.php');
	include_once('utilities/JaduStatus.php');   
	include_once("intranet/JaduIntranetRoomBookingAreas.php");
	include_once("intranet/JaduIntranetRoomBookingFacilities.php");
	include_once("intranet/JaduIntranetRoomBookingRooms.php");
	
	$facilities = getAllFacilities();
	$areas = getAllAreas();
	
	if (isset($_GET['areaID'])) {
		$rooms = getAllRoomsInArea($_GET['areaID']);
	}
	
	$breadcrumb = 'roomSearch';
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
	
		var currentAreas = Array();
		var currentFacilities = Array();
		
		// Area Stuff
		
		function addToCurrentAreas(id)
		{
			currentAreas.push(id);
		}
		
		// remove an area from the master list
		function removeFromCurrentAreas(id)
		{
			var size = currentAreas.length;
			
			for (var i = 0; i < size; i++) {
				
				if (currentAreas[i] == id) {
					currentAreas.splice(i,1);
					break;
				}
			}
		}
		
		// take the list of areas and create hidden inputs
		// so that the area ids can be posted
		function postAreas()
		{
			var size = currentAreas.length;
			
			var form = document.getElementById("roomForm");
	
			// add each facility id to the form		
			for (var i = 0; i < size; i++) {
			
				var hidden = document.createElement("input");
				hidden.setAttribute("type","hidden");
				hidden.setAttribute("name","areaList[]");
				hidden.setAttribute("value",currentAreas[i]);
				form.appendChild(hidden);
			}
		}
	
		// add a facility to the master list and create a new table row
		function addArea()
		{
			var areaID = document.getElementById("areaID").value;
			
			var size = currentAreas.length;
			
			var found = false;
			
			// see if the facility has already been added
			for (var i = 0; i < size; i++) {
				if (currentAreas[i] == areaID) {
					found = true;
					break;
				}
			}
			
			// if it has then display a message		
			if (found) {
				alert('You cannot add the same area twice');
			}
			
			// otherwise add it to the facility list and create a new table row
			else {
	
				addToCurrentAreas(areaID);
				
				var name = document.roomForm.areaID.options[document.roomForm.areaID.selectedIndex].innerHTML;
		
				var table = document.getElementById("areaTable");
				
				var noAreasMessage = document.getElementById("noAreasMessage");
				
				var row = table.insertRow(1);
				var td1 = document.createElement("td");
				var td2 = document.createElement("td");
				
				// for moz
		    	table.setAttribute("style","inline-block");
		    	// for ie
		    	table.style.display = "inline-block";
		    
				noAreasMessage.style.display = "none";
				
				row.id = "area_row_"+areaID;				

				var remove = document.createElement("input");
				//remove.type = "button";
				//remove.value = "Remove";
				//remove.addEventListener("onclick",removeArea(areaID), false);
				
				remove = "<a onclick=\"removeArea('"+areaID+"')\">Remove</a>";

				td2.innerHTML = remove;
		
				td1.innerHTML = name;
		
				row.appendChild(td1);
				row.appendChild(td2);
			}
		}
		
		function removeArea(id)
		{
			var size = currentAreas.length;
	
			removeFromCurrentAreas(id);

			// hide the table if there are no facilities in it
			if (currentAreas.length < 1) {
				var table = document.getElementById("areaTable");
				table.style.display = "none";
				
				var noAreasMessage = document.getElementById("noAreasMessage");
				noAreasMessage.style.display = "block";
			}
			
			var table = document.getElementById('areaTable');
								
			var numRows = table.rows.length;
			
			// loop through the table rows and delete the row once it's found
			for (var j = 0; j < numRows; j++) {
				if (table.rows[j].id == "area_row_"+id) {
					table.deleteRow(j);
					break;
				}
			}
		}

		// **** Facility Stuff

		function addToCurrentFacilities(id)
		{
			currentFacilities.push(id);
		}
		
		// remove an area from the master list
		function removeFromCurrentFacilities(id)
		{
			var size = currentFacilities.length;
			
			for (var i = 0; i < size; i++) {
				
				if (currentFacilities[i] == id) {
					currentFacilities.splice(i,1);
					break;
				}
			}
		}
		
		// take the list of facilities and create hidden inputs
		// so that the facility ids can be posted
		function postFacilities()
		{
			var size = currentFacilities.length;
			
			var form = document.getElementById("roomForm");
	
			// add each facility id to the form		
			for (var i = 0; i < size; i++) {
				
				var hidden = document.createElement("input");
				hidden.setAttribute("type","hidden");
				hidden.setAttribute("name","facilityList[]");
				hidden.setAttribute("value",currentFacilities[i]);
				form.appendChild(hidden);
			}
		}
	
		// add a facility to the master list and create a new table row
		function addFacility()
		{
			var facilityID = document.getElementById("facilityID").value;
			
			var size = currentFacilities.length;
			
			var found = false;
			
			// see if the facility has already been added
			for (var i = 0; i < size; i++) {
				if (currentFacilities[i] == facilityID) {
					found = true;
					break;
				}
			}
			
			// if it has then display a message		
			if (found) {
				alert('You cannot add the same facility twice');
			}
			
			// otherwise add it to the facility list and create a new table row
			else {
	
				addToCurrentFacilities(facilityID);
				
				var name = document.roomForm.facilityID.options[document.roomForm.facilityID.selectedIndex].innerHTML;
		
				var table = document.getElementById("facilityTable");
				
				var noFacilitiesMessage = document.getElementById("noFacilitiesMessage");
		
				var row = table.insertRow(1);
				var td1 = document.createElement("td");
				var td2 = document.createElement("td");
				
				// for moz
		    	table.setAttribute("style","inline-block");
		    	// for ie
		    	table.style.display = "inline-block";
		    	
				noFacilitiesMessage.style.display = "none";
				
				row.id = "facility_row_"+facilityID;
				
				td1.width = "80%";
				td2.width = "20%";
					
				row.style.backgroundColor = "#999999";

				//var remove = document.createElement("input");
				//remove.setAttribute("type","button");
				//remove.setAttribute("value","Remove");
				//remove.setAttribute("onclick","removeFacility('"+facilityID+"')");
				
				remove = "<a onclick=\"removeFacility('"+facilityID+"')\">Remove</a>";
		
				td2.innerHTML = remove;
		
				td1.appendChild (document.createTextNode(name));
		
				row.appendChild(td1);
				row.appendChild(td2);
			}
		}
		
		function removeFacility(id)
		{
			var size = currentFacilities.length;
	
			removeFromCurrentFacilities(id);

			// hide the table if there are no facilities in it
			if (currentFacilities.length < 1) {
				var table = document.getElementById("facilityTable");
				table.style.display = "none";
				
				var noFacilitiesMessage = document.getElementById("noFacilitiesMessage");
				noFacilitiesMessage.style.display = "block";
			}
			
			var table = document.getElementById('facilityTable');
								
			var numRows = table.rows.length;
			
			// loop through the table rows and delete the row once it's found
			for (var j = 0; j < numRows; j++) {
				if (table.rows[j].id == "facility_row_"+id) {
					table.deleteRow(j);
					break;
				}
			}
		}
		
		function showRoomSearch()
	    {
	        document.getElementById("roomSearchButton").style.display = "none";
	        document.getElementById("roomAvailabilityButton").style.display = "block";
	        document.getElementById("roomSearchArea").style.display = "block";
	        document.getElementById("roomAvailabilityArea").style.display = "none";
	    }
	    
	    function showCheckAvailability()
	    {
	        document.getElementById("roomAvailabilityArea").style.display = "block";
	        document.getElementById("roomSearchArea").style.display = "none";
	        document.getElementById("roomSearchButton").style.display = "block";
	        document.getElementById("roomAvailabilityButton").style.display = "none";
	    }
	    
	    function selectNav(arg)
		{
			choice = arg.selectedIndex;
			gotourl = arg.options[choice].value;
			location = gotourl;
		}
	
	</script>
	<script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/calendar.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
		
<?php
	if (isset($_GET['failedSearch']) && $_GET['reason'] == 'insufficientDetails') {
?>
		<h2 class="warning">Please make sure you enter a date in the format dd-mm-yyyy and add at least one area</h2>	
<?php
	}
	elseif(isset($_GET['failedSearch']) && $_GET['reason'] == 'noRooms') {
?>
		<h2 class="warning">No Rooms were found for the criteria you provided</h2>	
<?php
	}
	if (isset($_GET['failedCheck']) && $_GET['reason'] == 'insufficientDetails') {
?>
		<h2 class="warning">Please make sure you enter a date in the format dd-mm-yyyy</h2>	
<?php
	}
	if (isset($_GET['cancelSuccess'])) {
?>
		<h2>Your booking was cancelled successfully</h2>	
<?php
	}
?>
		
		<p class="first">Find an available room to book from the below options.</p>
                
       <!-- <p class="center"><input id="roomSearchButton" type="button" class="button" value="Search for Suitable Room" onclick="showRoomSearch(); return false;" /></p>
        -->       
<?php 
	$imageCount = 1; 
?>

        <!-- Room Search 

		<div id="roomSearchArea" name="roomSearchArea" style="display: none;"> -->
		<form name="roomForm" id="roomForm" action="http://<?php print $DOMAIN; ?>/site/scripts/room_availability.php" method="post" class="basic_form">
		<fieldset>
			<legend>Search for Suitable Room</legend>                
			
						<p>
						<label for="date">Start Date (required)</label>
							<input name="date" id="date" type="text" size="10" value="<?php print date('d-m-Y'); ?>" class="field"> 
							<a href="#" onclick="cal1.popup(); return false;"><img src="http://<?php print $DOMAIN; ?>/site/images/cal.gif" alt="open calendar" /></a>
						   
						   <script language="JavaScript">
							<!-- // create calendar object(s) just after form tag closed
								 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
								 // note: you can have as many calendar objects as you need for your application
								var cal1 = new calendar1(document.forms['roomForm'].elements['date']);
								cal1.year_scroll = true;
								cal1.time_comp = false;
							//-->
							</script>
					   <span class="clear"></span>
					   </p>
						   
					   <p>
					   <label for="areaID">Areas (required)</label>
							
							<select id="areaID" size="1" >
							<option value="#">Select, then click Add</option>
						<?php
							foreach ($areas as $area) {
						?>
								
								<option value="<?php print $area->id; ?>"><?php print $area->title; ?></option>
						<?php
							}
						?>
							</select>
							<input type="button" class="button" value="Add" onclick="addArea(); return false;" />
						<span class="clear"></span>
						</p>
						
						<p class="center">	
							<table id="areaTable" style="display:none">
								<thead>
									<tr>
										<th width="80%"><b>Title</b></th>
										<th width="20%"><b>Remove</b></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							
							<span id="noAreasMessage" style="display:block;">There are currently no areas assigned to this search</span>
						</p>
		
					   <p>
					   <label for="facilityID">Required Facilities</label>
							<select id="facilityID" size="1" >
							<option value="#">Select, then click Add</option>
						<?php
							foreach ($facilities as $facility) {
						?>
							<option value="<?php print $facility->id; ?>"><?php print $facility->title; ?></option>
						<?php
							}
						?>
							</select>
							<input type="button" class="button" value="Add" onclick="addFacility(); return false;" />
						<span class="clear"></span>
						</p>
						 <p class="center">
							<table id="facilityTable" style="display:none">
								<thead>
									<tr>
										<th width="80%"><b>Title</b></th>
										<th width="20%"><b>Remove</b></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							
						<span id="noFacilitiesMessage" style="display:block;">There are currently no facilities assigned to this search</span>
						</p>
						
						<p class="center">
						<input type="submit" name="roomSearch" class="button" value="SEARCH For Rooms" onclick="postAreas(); postFacilities();" />
						</p>
														
		</fieldset>
		</form>
                	
                <!-- Check availability -->

		<form name="availabilityForm" id="availabilityForm" action="http://<?php print $DOMAIN; ?>/site/scripts/room_availability.php" method="post" class="basic_form">
			<fieldset>
				<legend>Or Check a Rooms Availability</legend>
					<p><label for="areaID2">Area (required)</label>
						<select id="areaID2" name="area" size="1" onchange="selectNav(this);">
							<option value="http://<?php print $DOMAIN; ?>/site/scripts/room_search.php?showCheckAvailability=true">Please Select</option>
<?php
							foreach ($areas as $area) {
								$selected = "";
								if ($area->id == $_GET['areaID']) {
									 $selected = 'selected="selected"';
								}
?>
							<option value="http://<?php print $DOMAIN; ?>/site/scripts/room_search.php?showCheckAvailability=true&areaID=<?php print $area->id; ?>" <?php print $selected; ?>><?php print $area->title; ?></option>
<?php
							}
?>
						</select>
					<span class="clear"></span>
					</p>    
<?php
					if (isset($_GET['areaID'])) {
?>
					<p><label for="roomID" >Room (required)</label>
						<select id="roomID" name="roomID" size="1" >
<?php
						foreach ($rooms as $room) {
?>
							<option value="<?php print $room->id; ?>" <?php print $selected; ?>><?php print $room->title; ?></option>
<?php
						}
?>
						</select>
					<span class="clear"></span>
					</p> 
					<p><label>Start Date (required)</label>
						<input type="text" name="date" size="10" value="" class="field" />
						<a href="#" onclick="cal2.popup(); return false;"><img src="http://<?php print $DOMAIN; ?>/site/images/cal.gif" alt="open calendar" /></a>
						<script language="JavaScript">
						<!-- // create calendar object(s) just after form tag closed
							 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
							 // note: you can have as many calendar objects as you need for your application
							var cal2 = new calendar1(document.forms['availabilityForm'].elements['date']);
							cal2.year_scroll = true;
							cal2.time_comp = false;
						//-->
						</script>
					<span class="clear"></span>
					</p>	
												
					<p class="center"><input type="submit" name="checkRoom" class="button" value="Check Availability"></p>
<?php
                   }
?>
			</fieldset>
		</form>                

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>