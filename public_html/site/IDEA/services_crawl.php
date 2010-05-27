<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	

	$SERVICES_LIBRARY = "egov/JaduEGovJoinedUpServices.php";

	include_once($SERVICES_LIBRARY);
	
	$services = array();	
	$services = getAllServices();
		
	//if export has been clicked build headers to open the excel file, else do the rest of the page
	if (isset($Submit)) {
		if (!empty($_POST['address'])) {
			$file = "Services:"."\n";
			foreach ($_POST['address'] AS $value) {
				$file .= $value."\n";
			}

			//build the header to open the page in an excel spreadsheet			
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");               // Date in the past
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  // always modified
			header("Cache-Control: no-store, no-cache, must-revalidate");   // HTTP/1.1
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");                                     // HTTP/1.0 
			header("Content-type: text/comma-separated-values");
			header("Content-Disposition: attachment; filename=services_export.csv");
			header("Pragma: ");

			print $file;
		}
		else {
			print "<p style='color:red'>No services were generated to export, please click on one of the links below.</p>";
		}
		
	} 
	else {
		
		//if set on the query sting is set to pid, then get all pid services
		if (isset($set)) {
			if ($set=='pid') {
				//get all PID services and construct url if the id is a positive integer
				foreach ($services AS $service) {
					$PID = $service -> PID_ID;
					if ($PID != -1) {
						$urls[$PID] = 'http://'.$_SERVER['SERVER_NAME'].'/pid/'.$PID;
					}
				}
				if (sizeof($urls) > 0) {
					//sort in order of index, index being the PID
					ksort($urls);
				}
			}
			
		  //if set to non-PID then get all services with a PID of -1
			if ($set=='nonpid') {
				//get all PID services and construct url if the id is a positive integer
				foreach ($services as $service) {
					$PID = $service -> PID_ID;
					if ($PID == -1) {
						$urls[$service->id] = 'http://'.$_SERVER['SERVER_NAME'].'/site/scripts/services_info.php?serviceID='.$service->id;
					}
				}
				if (sizeof($urls) > 0) {
					//sort in order of index, index being the PID
					ksort($urls);
				}
			}
		}
		
	$breadcrumb = 'servicesCrawl';
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Services Generator</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Accessibility, dda, disability discrimination act, disabled access, access keys, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />
	
	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Accessibility features" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />
	
	<meta name="eGMS.subject.category" lang="en" scheme="IPSV" content="Local government;Government, politics and public administration" />
	<meta name="eGMS.subject.keyword" lang="en" scheme="LGCL" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include($HOME . "site/includes/opening.php"); ?>
<!-- ########################## -->

	<h2>Welcome to the services generator.</h2>
	<p class="first">Click the links below to generate lists of services ordered by PID or without PID.</p>
	

		<ul class="list">
			<li><a href="<?php print 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?set=pid' ?>">Generate PID services list</a></li>
			<li><a href="<?php print 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?set=nonpid' ?>">Generate non-PID services list</a></li>
		</ul>
	
		<?php
			if (isset($set) && ($set=='pid' || $set=='nonpid') && sizeof($urls) > 0) {
		?>
		<form name="expform" method="post" action="http://<?php print $DOMAIN; ?>/site/IDEA/services_crawl.php" class="basic_form">
				<p>Click the 'Export spreadsheet' button to download a CSV file of all the selected type of services.</p>
				<p class="center">
					<input type="submit" name="Submit" value="Export spreadsheet" class="button" />
				</p>
<?php
				//if urls is not empty then put them in the form
				if (!empty($urls)) {
					foreach ($urls AS $value) {
						print "<input type=\"hidden\" name=\"address[]\" value=\"".$value."\" />";
					}
				}
?>
		</form>
<?php
			}
?>

	
	<div class="content_box">
<?php
		if (!empty($urls)) {
			print "<ul class=\"list\">";
			foreach ($urls as $url) {
				print "<li><a href=\"".$url."\" title=\"".$url."\">".$url."</a></li>";
			}
			print '</ul>';
		}
?>
	</div>
		
<!-- ###################################### -->
	<?php include($HOME . "site/includes/closing.php"); ?>
<?php
	}
?>