<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduCache.php");
	include_once("egov/JaduEGovJoinedUpServices.php");
	include_once("lookingGlass/JaduLookingGlass.php");
	include_once("utilities/JaduFriendlyURLs.php");

	$cloudStatsThreshold = 0;
	$topServicesToShow = 20;
	
	$services = array();
	$allServices = getAllServicesWithTitleAliases(true, true);
	$validLetters = getAllValidAlphabetLetters($allServices);
	$topServices = array();
	$tags = array();

	if (sizeof($allServices) > 0) {

		$lg = new LookingGlass();
		$services_pattern = READABLE_URLS_ENABLED?'/a_to_z/service/%':'/site/scripts/services_info.php?serviceID=%';
        $lg->getRequestStatsForRange(date("Y-m-d", strtotime('-1 Day')), date("Y-m-d", time()), $services_pattern);

		foreach ($allServices as $service) {
			$services[$service->id] = $service;
			
			if (isset($lg->requestsReport->requests[buildAZServiceURL($service->id, true, $service->title)]['requests'])) {
				$requests = $lg->requestsReport->requests[buildAZServiceURL($service->id, true, $service->title)]['requests'];
			}
			else {
				$requests = 0;
			}

			if ($requests > $cloudStatsThreshold) {
				$tags[$service->id] = $requests;
			}

			$topServices[$service->id] = $requests;
		}
		
		unset($allServices);

		if (sizeof($topServices) > 0) {
			arsort($topServices);
		}

		// change these font sizes if you will
		$max_size = 250; // max font size in %
		$min_size = 100; // min font size in %
		
		$range = 1;
		// get the largest and smallest array values
		if (sizeof($tags) > 0) {
			$max_qty = max(array_values($tags));
			$min_qty = min(array_values($tags));
			
			// find the range of values
			$range = $max_qty - $min_qty;
			if (0 == $range) {
			    $range = 1;
			}
		}

		$step = ($max_size - $min_size) / $range;
	}
	
	$view = 'list';
	if(isset($_GET['view'])) {
		$view = $_GET['view'];
	}

	$breadcrumb = 'azHome';
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Council services';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Council services</span></li>';

	include("az_home.html.php");	
?>