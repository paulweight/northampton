<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");

	if (isset($_SESSION['userID'])) {
		include_once("marketing/JaduUsers.php");
		
		$user = getUser($_SESSION['userID']);
		$userPostcode = $user->postcode;
	}

	$breadcrumb = 'myArea';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - In my area</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="about,in,your,area<?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - In my area postcode searches." />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - In my area" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - In my area postcode searches." />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
	<p class="first">Below are a collection of services that you may find useful in finding out what is happening in your area.</p>
			
	<div class="content_box">
		<h2>Up My Street</h2>
		<p><img src="http://<?php print $DOMAIN;?>/site/images/ums_logo.gif" alt="Up My Street logo" class="contentimage" /><a href="http://www.upmystreet.com/enter-location/l/?fpage=%2Ffindmynearest%2F">FindMyNearest...&trade;</a> is the quick and easy way to find a local business.</p>
		<p>Their easy-to-use pages let you search and compare detailed information about a specific postcode, city, town, district or region.</p>
		<p>Select a listing and UpMyStreet will list your nearest businesses and services. Everything from banks to vets, builders to surveyors, restaurants and much more.</p>
		<p>Simply choose the type of service you want, enter your <strong>full postcode</strong> and <strong>click "Go!"</strong></p>

		<form action="http://www.upmystreet.com/l/IV3.html" method="get" class="basic_form">
			<p>
				<label for="l1">Postcode</label>
				<input type="text" value="<?php print $userPostcode;?>" name="l1" id="l1" maxlength="20" class="field" /> <input type="submit" name="locSubmit" id="locSubmit" value="Go" class="button"/>
			</p>
		</form>
	</div>
	
	<div class="content_box">
		<h2>Land registry</h2>
		<p><img src="http://<?php print $DOMAIN;?>/site/images/lr_logo.gif" alt="Land Registry - Land Register Online" class="contentimage" />Download a plan for the house you&rsquo;re buying (only &pound;2).</p>
		<p>Find out what land's included, price paid etc.</p>
		<form action="http://www.landregisteronline.gov.uk/lro/servlet/MultipleResultsServlet" method="get" class="basic_form">
			<input type="hidden" name="titleNumber" value="" />
			<input type="hidden" name="flatNumber" value="" />
			<input type="hidden" name="external" value="true" />
			<input type="hidden" name="houseNumber" value="" />
			<input type="hidden" name="streetName" value="" />
			<input type="hidden" name="town" value="" />
			<p>
				<label for="postcode">Postcode</label> 
				<input id="postcode" type="text" name="postcode" value="<?php print $userPostcode;?>" maxlength="20" class="field" /> <input type="submit" value="Go" class="button" />
			</p>
		</form>
	</div>
	
	<div class="content_box">
		<h2>National statistics</h2>
		<p><img src="http://<?php print $DOMAIN;?>/site/images/ns_logo.gif" alt="National statistics" class="contentimage" />Interested or involved in neighbourhood regeneration and looking for statistics on topics such as employment or crime?</p>
		<p>Or you may want to know more about where you live or work.</p>
		<form action="http://neighbourhood.statistics.gov.uk/dissemination/NeighbourhoodProfileSearch.do" method="get" class="basic_form">
			<p>
				<label for="profileFocus">Postcode</label>
				<input type="text" value="<?php print $userPostcode;?>" name="profileSearch" id="profileFocus" maxlength="20" class="field" />
				<input type="submit" value="Go" class="button" />
			</p>
		</form>
	</div>
	
	<div class="content_box">
		<h2>Member of parliament</h2>
		<p><img src="http://<?php print $DOMAIN;?>/site/images/portcullis_black.gif" alt="Commons" class="contentimage" />Enter a complete postcode to find out which constituency you are in and who your Member of Parliament is.</p>
		<form action="http://www.locata.co.uk/cgi-bin/phpdriver" method="get" class="basic_form">
			<input type="hidden" name="MIval" value="hoc_search" />
			<p>
				<label for="postcodeTwo">Postcode</label> 
				<input id="postcodeTwo" type="text" name="postcode" value="<?php print $userPostcode;?>" maxlength="20" class="field" />
				<input type="submit" value="Go" class="button" />
			</p>
		</form>
	</div>
		
	<div class="content_box">
		<h2>Your Local Councillors</h2>
		<p>Information on Councillors, including contact details.</p>
		<form action="http://<?php print $DOMAIN;?>/site/scripts/whos_my_councillor.php" method="get" class="basic_form">
			<p>
				<label for="YourPostcode">Postcode</label>
				<input id="YourPostcode" type="text" name="postcode" value="<?php print $userPostcode;?>" maxlength="20" class="field" />
				<input type="submit" class="button" name="find" id="find" value="Go" />
			</p>
		</form>
	</div>
		
	<div class="content_box">
		<h2>BBC Weather</h2>
		<p><img src="http://<?php print $DOMAIN;?>/site/images/bbc_weather_logo.gif" alt="BBC Weather" class="contentimage" />Enter a town, city, country name, or a UK postcode.</p>
		<p>Postcode searches must include all letters and numbers of the first part of the postcode.</p>

		<form action="http://www.bbc.co.uk/cgi-perl/weather/search/new_search.pl" method="get" class="basic_form">
			<p>
				<label for="search_query">Postcode</label> 
				<input name="search_query" id="search_query" value="<?php print $userPostcode;?>" maxlength="20" class="field" />
				<input type="submit" value="Go" class="button" />
			</p>
		</form>
	</div>
		
	<div class="content_box">
		<h2>Other Useful sites</h2>
		<ul class="list">
			<li><a href="http://www.yell.com/">www.yell.com</a> - Lists of local services searchable by post code.</li>
			<li><a href="http://www.thomsonlocal.com/">www.thomsonlocal.com</a> - Enter your postcode and Thomson Local will sort your results by distance.</li>
			<li><a href="http://maps.google.co.uk/local">maps.google.co.uk/local</a> - A new service from the popular search engine. Search by postcode for local services.</li>
			<li><a href="http://www.nhs.uk/england/">www.nhs.uk/england</a> - This site allows you to search by postcode for doctors, dentists, opticians, pharmacists, hospitals, and social care.</li>
			<li><a href="http://www.ofsted.gov.uk/reports/">www.ofsted.gov.uk/reports</a> - This site does much more than inspect schools. It inspects nurseries, childminders, schools, colleges, teacher training providers, local authorities (LAs) and much more.</li>
			<li><a href="http://www.theaa.com/">www.theaa.com</a> - This site provides route information between 2 postcodes.</li>
			<li>If you do not know your postcode, then try using the <a href="http://pol.royalmail.com/PF.asp">Royal Mail's Postcode Finder</a></li>
			<li><a href="http://www.transportdirect.info">Transport Direct</a> offers information for door-to-door travel for both public transport and car journeys around Britain.</li>
		</ul>
	</div>
			
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>