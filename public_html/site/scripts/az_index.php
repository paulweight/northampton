<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("egov/JaduEGovJoinedUpServices.php");


	if (strlen($_GET['startsWith']) > 1 || is_numeric($_GET['startsWith'])) {
		header("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}

	if (!isset($_GET['startsWith']) || !preg_match("/^[a-zA-Z]+$/", $_GET['startsWith'])) {// so a user can change to lower or upper if required
		$startsWith = 'A';
	}
	else {
		$startsWith = $_GET['startsWith'];
	}

	$startsWith = strtoupper($startsWith);

	$allServices = getAllServicesWithTitleAliases();
	$servicesList = getAllServicesWithTitleAliasesStartingWith ($allServices, $startsWith);
	$validLetters = getAllValidAlphabetLetters($allServices);

	$breadcrumb = 'azIndex';
	
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
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<!-- A-Z top list-->
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
		<div class="clear"></div>
	</div>
	<!-- End A-Z top list -->

	<div class="pop_az">
		<h2>Services that begin with <strong><?php print $startsWith;?></strong></h2>
			<!-- Returned list -->
<?php 
		if (sizeof($servicesList) > 0) {
?>
		<div id="top_services">
			<ol>
<?php 
			foreach ($servicesList as $service) {
				$service->title = htmlspecialchars($service->title);
?>
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/services_info.php?serviceID=<?php print $service->id;?>" ><?php print $service->title;?></a></li>
<?php
			}
?>
			</ol>
		</div>
<?php
	}
	else {
?>
		<h2>Sorry. There are no service entries for the letter <strong><?php print $startsWith;?></strong>.</h2>
<?php
		}
?>
	</div>
	<!-- END Returned list -->

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

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>