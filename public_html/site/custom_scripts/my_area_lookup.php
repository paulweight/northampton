<?php
	session_start();
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");

	$userPostcode = 'NN1 1DE';
	if (isset($_SESSION['userID'])) {
		include_once("marketing/JaduUsers.php");
		
		$user = getUser($_SESSION['userID']);
		$userPostcode = $user->postcode;
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - In my area</title>
	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY.$STYLESHEET;?>" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY;?>print.css" media="print" />
	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY;?>handheld.css" media="handheld" />
	<link rel="Shortcut Icon" type="image/x-icon" href="http://<?php print $DOMAIN;?>/site/favicon.ico" />
	<link rel="ToC" href="http://<?php print $DOMAIN;?>/site/scripts/site_map.php" />
	<!-- general metadata -->
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="content-language" content="en" />
	<meta name="generator" content="http://www.jadu.co.uk" />
	<meta name="robots" content="index,follow" />
	<meta name="revisit-after" content="2 days" />
	<meta name="Author-Template" content="Jadu CSS design" />
	<meta name="Publisher" content="<?php print METADATA_PUBLISHER;?>" />
	<meta name="Publisher-Email" content="<?php print METADATA_PUBLISHER_EMAIL;?>" />
	<meta name="Keywords" content="about,in,your,area<?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - In my area postcode searches." />
	<meta name="Coverage" content="Worldwide" />
	<!-- ICRA PICS label -->
	<meta http-equiv="pics-label" content='(pics-1.1 "http://www.icra.org/ratingsv02.html" comment "ICRAonline EN v2.0" l gen true for "http://www.kettering.gov.uk" r (nz 1 vz 1 lz 1 oz 1 cz 1) "http://www.rsac.org/ratingsv01.html" l gen true for "http://www.kettering.gov.uk" r (n 0 s 0 v 0 l 0))' />
	<!-- Dublin Core Metadata -->
	<meta name="DC.creator" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>" />
	<meta name="DC.date.created" lang="en" content="07-11-2005" />
	<meta name="DC.format" lang="en" content="text/html" />
	<meta name="DC.language" content="en" />
	<meta name="DC.publisher" lang="en" content="<?php print METADATA_PUBLISHER;?>" />
	<meta name="DC.rights.copyright" lang="en" content="<?php print METADATA_RIGHTS;?>" />
	<meta name="DC.coverage" lang="en" content="<?php print METADATA_COVERAGE;?>" />
	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - In my area" />
	<meta name="DC.identifier" content="http://<?php print $DOMAIN.$_SERVER['PHP_SELF'];?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - In my area postcode searches." />
	<!-- eGMS Metadata -->
	<meta name="eGMS.status" lang="en" content="<?php print METADATA_STATUS;?>" />
	<meta name="eGMS.subject.category" lang="en" scheme="GCL" content="Business and industry" />
	<meta name="eGMS.subject.category" lang="en" scheme="IPSV" content="Business and industry" />
	<meta name="eGMS.subject.keyword" lang="en" scheme="LGCL" content="Local businesses and markets" />
	<meta name="eGMS.accessibility" scheme="WCAG" content="<?php print METADATA_ACCESSIBILITY;?>" />
	<!-- to correct the unsightly Flash of Unstyled Content. -->
	<script type="text/javascript"></script>
</head>
<body>
    <!-- ########## CAUTION! MAIN STRUCTURE ########## -->
    <!-- ####################################### -->
    <div id="wrapper">
        <div id="pagewidth">
            <div id="pagemargin_inner">
                <!-- Left column -->
                <?php include("../includes/mast.php"); ?>
                <!-- END left column -->
                <!-- Left column -->
                <?php include("../includes/leftcolumn.php"); ?>
                <!-- END left column -->
                <div id="main">
                    <div id="content_inner">
                    <!-- ############ END! MAIN STRUCTURE ########### -->
                    <!-- ####################################### -->
                        
				<div class="genbox">
				<h1>In my area ...</h1>
				<p class="first">Below are a collection of services that you may find useful in finding out what is happening in your area.</p>
			</div>
			
			<div class="genbox">

				<p><img src="http://<?php print $DOMAIN;?>/site/images/ums_logo.gif" alt="Up My Street log" /><a href="http://www.upmystreet.com/enter-location/l/?fpage=%2Ffindmynearest%2F" title="FindMyNearest...">FindMyNearest...&trade;</a> is the quick and easy way to find a local business.</p>
				<p>Their easy-to-use pages let you search and compare detailed information about a specific postcode, city, town, district or region.</p>
				<p>Select a listing and UpMyStreet will list your nearest businesses and services. Everything from banks to vets, builders to surveyors, restaurants and much more.</p>
				<p>Simply choose the type of service you want, enter your <strong>full postcode</strong> and <strong>click "Go!"</strong></p>
	
				<form action="http://www.upmystreet.com/l/IV3.html" method="get">
					<label for="l1"><strong>Postcode: </strong> </label>
					<input type="text" value="<?php print $userPostcode;?>" name="l1" id="l1" size="10" maxlength="20" style="margin-left: 5px;" />
					<input type="submit" name="locSubmit" id="locSubmit" value="Go" class="button"/>
				</form>
			</div>
			
			<div class="genbox">
				<p><img src="http://<?php print $DOMAIN;?>/site/images/lr_logo.gif" alt="Land Registry - Land Register Online" />Download a plan for the house you&rsquo;re buying (only &pound;2).</p>
				<p>Find out what land's included, price paid etc.</p>
				<form action="http://www.landregisteronline.gov.uk/lro/servlet/MultipleResultsServlet" method="get">
					<label for="postcode"><strong>Postcode:</strong> </label> 
					<input type="hidden" name="titleNumber" value="" />
					<input type="hidden" name="flatNumber" value="" />
					<input type="hidden" name="external" value="true" />
					<input type="hidden" name="houseNumber" value="" />
					<input type="hidden" name="streetName" value="" />
					<input type="hidden" name="town" value="" />
					<input id="postcode" type="text" name="postcode" value="<?php print $userPostcode;?>" size="10" maxlength="20" style="margin-left: 5px;"  />
					<input type="submit" value="Go" class="button"/>
				</form>
			</div>
			
			<div class="genbox">
				<p><img src="http://<?php print $DOMAIN;?>/site/images/ns_logo.gif" alt="National statistics" />Interested or involved in neighbourhood regeneration and looking for statistics on topics such as employment or crime?</p>
				<p>Or you may want to know more about where you live or work.</p>
				<form class="form" action="http://neighbourhood.statistics.gov.uk/dissemination/NeighbourhoodProfileSearch.do" method="get">
					<label for="profileFocus"><strong>Postcode:</strong> </label>
					<input type="text" value="<?php print $userPostcode;?>" name="profileSearch" id="profileFocus" size="10" maxlength="20"  style="margin-left: 5px;" />
					<input type="submit" value="Go" class="button"/>
				</form>
			</div>
			
			<div class="genbox">
				<p><img  src="http://<?php print $DOMAIN;?>/site/images/portcullis_black.gif" alt="Commons" />Enter a complete postcode to find out which constituency you are in and who your Member of Parliament is.</p>
				<form class="form" action="http://www.locata.co.uk/cgi-bin/phpdriver" method="get">
					<input type="hidden" name="MIval" value="hoc_search" />
					<label for="postcodeTwo"><strong>Postcode:</strong> </label> 
					<input id="postcodeTwo" type="text" name="postcode" value="<?php print $userPostcode;?>" size="10" maxlength="20" style="margin-left: 5px;" />
					<input type="submit" value="Go" class="button" />
				</form>
			</div>
				
				
			<div class="genbox">
				<p><img src="http://<?php print $DOMAIN;?>/site/images/bbc_weather_logo.gif" alt="BBC Weather" />Enter a town, city, country name, or a UK postcode.</p>
				<p>Postcode searches must include all letters and numbers of the first part of the postcode.</p>

				<form class="form" action="http://www.bbc.co.uk/cgi-perl/weather/search/new_search.pl" method="get">
					<label for="search_query"><strong>Postcode:</strong> </label> 
					<input style="margin-left: 5px;" name="search_query" id="search_query" title="To search for a five day forecast please enter your location name or postcode and click GO" value="<?php print $userPostcode;?>" size="10" maxlength="20"/>
					<input type="submit" value="Go" class="button" />
				</form>
			</div>
				
			<div class="genbox">
			<p><img src="http://<?php print $DOMAIN;?>/site/images/TDLogo.gif" alt="Transport DIrect Logo" /><a href="http://www.transportdirect.info">Transport Direct</a> offers information for door-to-door travel for both public transport and car journeys around Britain.</p>  
			<p>The <a href="http://www.transportdirect.info/TransportDirect/en/Maps/JourneyPlannerLocationMap.aspx?cacheparam=0">maps area</a> of their site will also allow you to locate public buildings, bus or coach stops, taxi ranks, rail stations and airports in your area using your postcode.</p>
			</div>	
				
			<div class="genbox">
				<p><img src="http://<?php print $DOMAIN;?>/site/images/rm_logo.gif" alt="Royal Mail Logo" />If you do not know your postcode, then try using the <a href="http://pol.royalmail.com/PF.asp" title="Royal Mail's Postcode Finder">Royal Mail's Postcode Finder</a>.</p>
				<p>You will need to register on the royal mails website in order to use this service.</p>
			</div>
				
				<div class="genbox">
					<h2>Other Useful sites</h2>

					<p><a href="http://www.yell.com/">www.yell.com</a> - Lists of local services searchable by post code.</p>
					<p><a href="http://www.thomsonlocal.com/">www.thomsonlocal.com</a> - Enter your postcode and Thomson Local will sort your results by distance.</p>
					<p><a href="http://maps.google.co.uk/local">maps.google.co.uk/local</a> - A new service from the popular search engine. Search by postcode for local services.</p>
					<p><a href="http://www.nhs.uk/england/">www.nhs.uk/england</a> - This site allows you to search by postcode for doctors, dentists, opticians, pharmacists, hospitals, and social care.</p>
					<p><a href="http://www.ofsted.gov.uk/reports/">www.ofsted.gov.uk/reports</a> - This site does much more than inspect schools. It inspects nurseries, childminders, schools, colleges, teacher training providers, local authorities (LAs) and much more.</p>
					<p><a href="http://www.theaa.com/">www.theaa.com</a> - This site provides route information between 2 postcodes.</p>
				</div>

				<div class="mozhack"></div>
                        
                        
                    <!-- ########## CAUTION! MAIN STRUCTURE ########## -->
                    <!-- ####################################### -->
                    </div>
                </div>
                <!-- Footer -->
                <?php include("../includes/footer.php"); ?>
                <!-- END footer -->
            </div>
        </div>
    </div>
</body>
</html>