<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduCache.php");
	include_once("egov/JaduEGovJoinedUpServices.php");
	include_once("lookingGlass/JaduLookingGlass.php");
	include_once("utilities/JaduFriendlyURLs.php");

	$cloudStatsThreshold = 0;
	$topServicesToShow = 20;

	$allServices = array();
	
	$allServices = getAllServicesWithTitleAliases();
	$validLetters = getAllValidAlphabetLetters($allServices);

	if (sizeof($allServices) > 0) {

		$lg = new JaduLookingGlass();
	    $lg->getRequestStatsForRange(date("Y-m-d", strtotime('-1 Day')), date("Y-m-d", time()), '/site/scripts/services_info.php?serviceID=%');

		$i = 0;
		foreach ($allServices as $service) {

			if (isset($lg->requestsReport->requests['/site/scripts/services_info.php?serviceID=' . $service->id]['requests'])) {
				$requests = $lg->requestsReport->requests['/site/scripts/services_info.php?serviceID=' . $service->id]['requests'];
			}
			else {
				$requests = 0;
			}

			if ($requests > $cloudStatsThreshold) {
				$tags[$service->id] = $requests;
			}

			$topServices[$service->id] = $requests;
			$i++;
			
			if ($i > $topServicesToShow) {
				break;
			}
		}

		if (sizeof($topServices) > 0) {
			arsort($topServices);
		}

		// change these font sizes if you will
		$max_size = 250; // max font size in %
		$min_size = 100; // min font size in %

		// get the largest and smallest array values
		if (sizeof($tags) > 0) {
			$max_qty = max(array_values($tags));
			$min_qty = min(array_values($tags));
		}

		// find the range of values
		$range = $max_qty - $min_qty;
		if (0 == $range) {
		    $range = 1;
		}

		$step = ($max_size - $min_size) / $range;
	}
	
	$view = 'list';
	if(isset($_GET['view'])) {
		$view = $_GET['view'];
	}

	$breadcrumb = 'azHome';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Council services | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="services, a-z, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Full A to Z listing alphabetically details of all services in your area" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Glossary" />
	<meta name="DC.identifier" content="http://<?php print $DOMAIN.$_SERVER['PHP_SELF'];?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

	<script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/prototype.js"></script>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php print GOOGLE_MAPS_API_KEY; ?>" type="text/javascript"></script>
	<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=<?php print GOOGLE_MAPS_API_KEY; ?>" type="text/javascript"></script>
	<script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/services.js"></script>
	<script type="text/javascript">

		function changeStatsView(view)
		{
			if (view == 'list') {
				Element.show('top_services');
				Element.hide('tag_cloud');
			}
			else {
				Element.hide('top_services');
				Element.show('tag_cloud');
			}
		}
	
	</script>
	</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
				
	<div id="az_index">
		<ul>
			<li><?php if ($validLetters['A']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=A">a</a><?php } else { ?><span>a</span><?php } ?></li>
			<li><?php if ($validLetters['B']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=B">b</a><?php } else { ?><span>b</span><?php } ?></li>
			<li><?php if ($validLetters['C']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=C">c</a><?php } else { ?><span>c</span><?php } ?></li>
			<li><?php if ($validLetters['D']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=D">d</a><?php } else { ?><span>d</span><?php } ?></li>
			<li><?php if ($validLetters['E']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=E">e</a><?php } else { ?><span>e</span><?php } ?></li>
			<li><?php if ($validLetters['F']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=F">f</a><?php } else { ?><span>f</span><?php } ?></li>
			<li><?php if ($validLetters['G']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=G">g</a><?php } else { ?><span>g</span><?php } ?></li>
			<li><?php if ($validLetters['H']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=H">h</a><?php } else { ?><span>h</span><?php } ?></li>
			<li><?php if ($validLetters['I']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=I">i</a><?php } else { ?><span>I</span><?php } ?></li>
			<li><?php if ($validLetters['J']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=J">j</a><?php } else { ?><span>J</span><?php } ?></li>
			<li><?php if ($validLetters['K']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=K">k</a><?php } else { ?><span>k</span><?php } ?></li>
			<li><?php if ($validLetters['L']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=L">l</a><?php } else { ?><span>l</span><?php } ?></li>
			<li><?php if ($validLetters['M']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=M">m</a><?php } else { ?><span>m</span><?php } ?></li>
			<li><?php if ($validLetters['N']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=N">n</a><?php } else { ?><span>n</span><?php } ?></li>
			<li><?php if ($validLetters['O']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=O">o</a><?php } else { ?><span>o</span><?php } ?></li>
			<li><?php if ($validLetters['P']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=P">p</a><?php } else { ?><span>p</span><?php } ?></li>
			<li><?php if ($validLetters['Q']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=Q">q</a><?php } else { ?><span>q</span><?php } ?></li>
			<li><?php if ($validLetters['R']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=R">r</a><?php } else { ?><span>r</span><?php } ?></li>
			<li><?php if ($validLetters['S']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=S">s</a><?php } else { ?><span>s</span><?php } ?></li>
			<li><?php if ($validLetters['T']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=T">t</a><?php } else { ?><span>t</span><?php } ?></li>
			<li><?php if ($validLetters['U']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=U">u</a><?php } else { ?><span>u</span><?php } ?></li>
			<li><?php if ($validLetters['V']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=V">v</a><?php } else { ?><span>v</span><?php } ?></li>
			<li><?php if ($validLetters['W']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=W">w</a><?php } else { ?><span>w</span><?php } ?></li>
			<li><?php if ($validLetters['X']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=X">x</a><?php } else { ?><span>x</span><?php } ?></li>
			<li><?php if ($validLetters['Y']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=Y">y</a><?php } else { ?><span>y</span><?php } ?></li>
			<li><?php if ($validLetters['Z']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=Z">z</a><?php } else { ?><span>z</span><?php } ?></li>
		</ul>
	</div>

	<div class="pop_az">
		<h2>Most popular services</h2>
<?php
			if (is_array($tags) && sizeof($tags) > 0) {
?>
		<p>View as a <a href="-" onclick="changeStatsView('list'); return false;">List</a> or as a <a href="-" onclick="changeStatsView('cloud'); return false;">Cloud</a>.</p>
<?php
			}
?>
		<!-- Top Services List -->
		<div id="top_services" style="display:block;">
			<ol>
<?php
	if (sizeof($topServices) > 0) {
		$count = 0;
		foreach ($topServices as $id => $requests) {
			$service = getService($id);
?>
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/services_info.php?serviceID=<?php print $id;?>"><?php print $service->title . "</a> <span>(" . $requests . ")</span>";?></li>
<?php
			if ($count++ > $topServicesToShow) {
				break;
			}
		}
	}
?>
			</ol>
		</div>

		<!-- Services tag cloud -->
		<div id="tag_cloud" style="display:none;">
	<?php
		if (is_array($tags)) {
			foreach ($tags as $key => $value) {
	
				$size = $min_size + (($value - $min_qty) * $step);

				$service = getService($key);
	?>
		<a href="http://<?php print $DOMAIN; ?>/site/scripts/services_info.php?serviceID=<?php print $service->id; ?>" style="font-size:<?php print $size; ?>%" ><?php print $service->title; ?></a>
	<?php
			}
		}
	?>
		</div>
		<!-- End services tag cloud -->
	</div>

	<!-- Live Search -->
	<div class="search_az">
		<div>
			<h3>Find a service</h3>
			<p id="az_live_find">
				<label for="searchText">Begin to type and select from the appearing choices below.</label>
				<input type="text" name="searchText" id="searchText" class="field" value="" />
				<img id="loading" style="display:none;" alt="-" src="http://<?php print $DOMAIN;?>/site/images/loading.gif" />
			</p>
			<div id="search_results"></div>
		</div>
	</div>
	<!-- End live search -->
	
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
