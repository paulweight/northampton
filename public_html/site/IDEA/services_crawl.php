<?php
	include_once("utilities/JaduStatus.php");
	include_once("egov/JaduEGovJoinedUpServices.php");
	include_once("JaduStyles.php");
	
	$services = getAllServices();
	
	if (isset($_GET['set'])) {
		$urls = array();
		
		if ($_GET['set'] == 'pid') {
			// get all PID services and construct url if the id is a positive integer
			foreach ($services AS $service) {
				$PID = $service->PID_ID;
				if ($PID != -1) {
					$urls[$PID] = getSiteRootURL() . buildAZServicePIDURL($PID);
				}
			}
			if (sizeof($urls) > 0) {
				// sort in order of index, index being the PID
				ksort($urls);
			}
		}
		
		// if set to non-PID then get all services with a PID of -1
		else if ($_GET['set'] == 'nonpid') {
			//get all PID services and construct url if the id is a positive integer
			foreach ($services as $service) {
				$PID = $service->PID_ID;
				if ($PID == -1) {
					$urls[$service->id] = getSiteRootURL() . buildAZServiceURL($service->id);
				}
			}
			if (sizeof($urls) > 0) {
				// sort in order of index, index being the PID
				ksort($urls);
			}
		}
	}
	
	// if export has been clicked build headers to open the excel file, else do the rest of the page
	if (isset($_POST['submit'])) {
		$file = "Services:"."\n";
		foreach ($urls AS $url) {
			$file .= $url . "\n";
		}

		// build the header to open the page in an excel spreadsheet
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");               // Date in the past
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  // always modified
		header("Cache-Control: no-store, no-cache, must-revalidate");   // HTTP/1.1
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");                                     // HTTP/1.0 
		header("Content-type: text/comma-separated-values");
		header("Content-Disposition: attachment; filename=services_export.csv");
		header("Pragma: ");

		print $file;
		exit;
	} 
	
	$MAST_HEADING = 'A to Z';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>A to Z services list</span></li>';
	
	include("services_crawl.html.php");
		
?>