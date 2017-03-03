<?php 
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("websections/JaduEvents.php");
	include_once("JaduMetadata.php");
	include_once('JaduUpload.php');

	$title = "";
	$startDate = "";
	$interval = "";
	$endDate = "";
	$startTime = "";
	$endTime = "";
	$location = "";
	$cost = "";
	$summary = "";
	$description = "";

	$error_array = array();

	if (isset($_POST['submit'])) {

		$event = new Event();
		$event->title = $_POST['title'];
		$event->startTime = $_POST['startTime'];
		$event->endTime = $_POST['endTime'];
		$event->startDate = $_POST['startDate'];
		$event->interval = $_POST['interval'];
		$event->endDate = $_POST['endDate'];
		$event->location = $_POST['location'];
		$event->cost = $_POST['cost'];
		$event->summary = $_POST['summary'];
		$event->description = $_POST['description'];

		if($_POST['interval'] == '1day') {
			$event->endDate = $event->startDate;
		}

		$eventLocationMapper = new Jadu_Page_Event_DataMapper_Location(
			Jadu_Service_Container::getInstance()->getSiteDB(),
			Jadu_Service_Container::getInstance()->getCacheManage()
		);
		if($location = $eventLocationMapper->getById(intval($_POST['location_id']))) {
			$event->location = $location;
		}

		$error_array = validateEventDetails($event->title, $event->startDate, $event->endDate,
			$event->startTime, $event->endTime,$event->location, $event->cost, $event->summary,
			$event->description, true, true);

		if ($_POST['auth'] == 'fail' || $_POST['auth'] != md5(DOMAIN . date('Y'))) {
			$error_array['auth'] = true;
		}

		// deal with image uploads
		$filesToUpload = array();
		if (count($_FILES) > 0) {
			foreach ($_FILES as $file) {
				if (!empty($file['name'])) {
					$allowedExtensions = array("jpg", "jpeg", "gif", "png");

					// get the extension of the file - anything after the last .
					$extension = mb_strtolower(mb_substr($file['name'], mb_strrpos($file['name'], '.', 0) + 1));
					// check that the mime type is image
					// and that the extension is in the allowed list
					if (mb_substr($file['type'], 0, 5) != 'image' || !in_array($extension, $allowedExtensions)) {
						$error_array['image'] = true;
						break;
					}

					// check the filenames don't exist
					$filename = cleanFilename ($file['name']);
					$filename = checkFilenameClash($filename, $HOME . 'images/');

					// upload the file
					$filesToUpload[$file['tmp_name']] = $HOME . 'images/' . $filename;
					$filenameAlt = substr($filename, 0 , (strpos($file['name'], '.'))? strpos($filename, '.') : strlen($filename));

					// prepend the image to the description
					$event->description = "<img src=\"/images/$filename\" alt=\"$filenameAlt\" />" . $event->description;
				}
			}
		}

		if (sizeof($error_array) == 0) {

			foreach ($filesToUpload as $from => $to) {
				uploadFile ($from, $to);
			}

			$event->cost = (strpos($event->cost, '£') === false) ? '£'. $event->cost : $event->cost;

			$event->id = newEvent($event->title, $event->startDate, $event->endDate, $event->startTime,
				$event->endTime, $event->interval, $event->location, $event->cost,
				$event->summary, $event->description, '', 0, 0, -1);

			$metadata = new JaduMetadata();
			$metadata->creator = 'Public';
			$metadata->publisher = METADATA_PUBLISHER;
			$metadata->rights = METADATA_RIGHTS;
			$metadata->coverage = METADATA_COVERAGE;
			$metadata->status = METADATA_STATUS;
			$metadata->created = mktime(0,0,0);
			
			newMetadata(EVENTS_METADATA_TABLE, $event->id, $metadata->creator, $metadata->contributor,
				$metadata->publisher, $metadata->rights, $metadata->source, $metadata->status, $metadata->coverage,
				$metadata->created,$metadata->modified, $metadata->valid, $metadata->expired, $metadata->format,
				$metadata->language, $metadata->subject, $metadata->description);

			$headerEmail = DEFAULT_EMAIL_ADDRESS;

			$HEADER = "From: $headerEmail\r\nReply-to: $headerEmail\r\nContent-Type: text/plain; charset=UTF-8;\r\nContent-Transfer-Encoding: 8bit\r\n";

			$SUBJECT = $DOMAIN. " New Event has been added.";
			$MESSAGE = "A new event has been added from the " . DOMAIN . " website.\n\n";

			if (!empty($event->title)) {
				$MESSAGE .= "Title: " . $event->title . "\n";
			}
			if (!empty($event->startDate)) {
				$MESSAGE .= "Start Date: " . $event->startDate. "\n";
			}
			if (!empty($event->interval)) {
				$MESSAGE .= "Interval: " . $event->interval;
				if (!empty($event->endDate)) {
					$MESSAGE .= " until " . $event->endDate;
				}
				$MESSAGE .= "\n";
			}
			if (!empty($event->startTime)) {
				$MESSAGE .= "Start Time: " . $event->startTime. "\n";
			}
			if (!empty($event->endTime)) {
				$MESSAGE .= "End Time: " . $event->endTime. "\n";
			}
			if (!empty($event->location)) {
				$MESSAGE .= "Location: " . $event->location. "\n";
			}
			if (!empty($event->cost)) {
				$MESSAGE .= "Cost: " . $event->cost . "\n";
			}
			if (!empty($event->summary)) {
				$MESSAGE .= "Summary: " . nl2br($event->summary) . "\n";
			}
			if (!empty($event->description)) {
				$MESSAGE .= "Description: " . nl2br($event->description) . "\n";
			}

			mail(DEFAULT_EMAIL_ADDRESS, $SUBJECT, $MESSAGE, $HEADER);
			header('Location: ' . buildEventThanksURL());
			exit;
		}
	}

	if (isset($_POST['submit'])) {
		$title = isset($_POST['title']) ? $_POST['title'] : '';
		$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : '';
		$interval = isset($_POST['interval']) ? $_POST['interval'] : '';
		$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : '';
		$startTime = isset($_POST['startTime']) ? $_POST['startTime'] : '';
		$endTime = isset($_POST['endTime']) ? $_POST['endTime'] : '';
		$location = isset($_POST['location']) ? $_POST['location'] : '';
		$cost = isset($_POST['cost']) ? $_POST['cost'] : '';
		$summary = isset($_POST['summary']) ? $_POST['summary'] : '';
		$description = isset($_POST['description']) ? $_POST['description'] : '';
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Suggest an event';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildEventsURL() .'">Events</a></li><li><span>Suggest an event</span></li>';

	include("event_new.html.php");
