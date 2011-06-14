<?php
	//session_start();
	include_once("egov/JaduEGovJoinedUpServices.php");
	include_once("websections/JaduContact.php");
	require_once('JaduConstants.php');
	
	$allServices = getAllServicesWithTitleAliases();
	$validLetters = getAllValidAlphabetLetters($allServices);
	
	$mastAddress = new Address;
    
    $pageUrl = $DOMAIN.$_SERVER['PHP_SELF'];
    if ($_SERVER['QUERY_STRING'] != '') {
    	$pageUrl .= '?' . htmlspecialchars($_SERVER['QUERY_STRING']);
    }
    
?>
<div id="mobile_name"><?php print METADATA_GENERIC_COUNCIL_NAME; ?></div>
<div id="mast">
	<ul id="skip">
		<li><a href="http://<?php print $pageUrl ;?>#content" rel="nofollow">Jump to content</a></li>
		<li><a href="http://<?php print $pageUrl ;?>#column_nav" rel="nofollow">Jump to navigation</a></li>
	</ul>
	
<?php 
	if (basename($_SERVER['PHP_SELF']) != 'index.php') {
?>
	<div class="pseudoH1">
		<a href="http://<?php print $DOMAIN;?>/site/index.php"><span><?php print METADATA_GENERIC_COUNCIL_NAME;?></span></a>
	</div>			
<?php
	}
	else {
?>
	<h1><span><?php print METADATA_GENERIC_COUNCIL_NAME;?></span></h1>
<?php
	}
?>

	<ul id="mastNav">
		<li class="contactDetails"><a href="http://<?php print $DOMAIN;?>/site/scripts/documents_info.php?documentID=657&amp;pageNumber=1">Contact Us <span>Telephone: <?php print $mastAddress->telephone;?></span></a></li>
		<li class="easyRead"><a href="http://<?php print $DOMAIN;?>/site/scripts/user_settings.php">Easy to read? <span>Change text size and colours</span></a></li>
		<li class="languages"><a title="Help with languages and translation" href="http://<?php print $DOMAIN;?>/languages"><span>Languages</span></a></li>
	</ul>
</div>
<br class="clear" />
<div id="mainNav">

	<form action="http://<?php print $DOMAIN;?>/site/scripts/google_results.php" method="get" name="search">
		<label for="SearchSite">Search this site</label>
		<input type="text" size="22" maxlength="40" class="field" name="q" id="SearchSite" value="<?php if (!isset($_GET['searchQuery']) || $_GET['searchQuery'] == '') { print 'Looking for something?'; } else { print $_GET['searchQuery']; }?>" onclick="if (this.value == 'Looking for something?') { this.value = ''; } return false;" />
		<input type="submit" value="Search" class="button" />
		<input type="hidden" name="ie" value="" />
		<input type="hidden" name="site" value="<?php print $GOOGLE_COLLECTION; ?>" />
		<input type="hidden" name="output" value="xml" />
		<input type="hidden" name="client" value="<?php print $GOOGLE_COLLECTION; ?>" />
		<input type="hidden" name="lr" value="" /> 
		<input type="hidden" name="oe" value="" />
		<input type="hidden" name="filter" value="0" />
	</form>

	<ul id="mainNavTop">
		
	<?php if (isset($_SESSION['userID'])) { ?>
		<li><em>Hello,

	<?php 
	
	if ($user->salutation != "" && $user->surname != "") 
		print $user->salutation .  " ";
	
	if ($user->forename != "") 
		print $user->forename . " ";
		
	if  ($user->surname != " ")
		print $user->surname; 
	
	if ($user->forename == "" && $user->surname=="") 
		print $user->email;
		
	?></em></li><li><a href="http://<?php print $DOMAIN;?>/site/index.php">Home</a></li><li><a href="http://<?php print $DOMAIN;?>/site/scripts/user_home.php">Your account</a></li><li><a href="http://<?php print $DOMAIN;?>/site/index.php?logout=true">Sign out</a></li>
	
			
	<?php } else { ?>
	
		<li><a href="http://<?php print $DOMAIN;?>/site/index.php">Home</a></li><li><a href="http://<?php print $DOMAIN;?>/site/scripts/home_info.php?homepageID=172">Your Account</a></li>
	<?php } ?>
	</ul>
	
	<ul id="mainNavAZ">
		<li class="azFirst">
			<a class="hidden" href="<?php print $baseURL . $_SERVER['PHP_SELF']."?".htmlspecialchars($_SERVER['QUERY_STRING']);?>#widget0" rel="nofollow">Jump over A to Z links</a>
			<a href="http://<?php print $DOMAIN;?>/site/scripts/az_home.php">Services A to Z</a>
		</li>
		<li><?php if ($validLetters['A']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=A">a</a><?php } else { ?><span>a</span><?php } ?></li>
		<li><?php if ($validLetters['B']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=B">b</a><?php } else { ?><span>b</span><?php } ?></li>
		<li><?php if ($validLetters['C']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=C">c</a><?php } else { ?><span>c</span><?php } ?></li>
		<li><?php if ($validLetters['D']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=D">d</a><?php } else { ?><span>d</span><?php } ?></li>
		<li><?php if ($validLetters['E']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=E">e</a><?php } else { ?><span>e</span><?php } ?></li>
		<li><?php if ($validLetters['F']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=F">f</a><?php } else { ?><span>f</span><?php } ?></li>
		<li><?php if ($validLetters['G']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=G">g</a><?php } else { ?><span>g</span><?php } ?></li>
		<li><?php if ($validLetters['H']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=H">h</a><?php } else { ?><span>h</span><?php } ?></li>
		<li><?php if ($validLetters['I']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=I">i</a><?php } else { ?><span>i</span><?php } ?></li>
		<li><?php if ($validLetters['J']) { ?><a href="http://<?php print $DOMAIN;?>/site/scripts/az_index.php?startsWith=J">j</a><?php } else { ?><span>j</span><?php } ?></li>
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
<br class="clear" />

<?php
	if ($liveUpdate->id != '' && $liveUpdate->id != -1) {
?>
	<div class="announcement">
		<div>
			<h2><?php print $liveUpdate->title; ?></h2>
			<p><?php print $liveUpdate->content; ?></p>
		<?php
				if ($liveUpdate->url != '') {
		?>
			<p><a href="<?php print $liveUpdate->url; ?>" title="<?php print $liveUpdate->title; ?>"><?php print $liveUpdate->linkText; ?></a>.</p>
<?php
		}
		print '</div></div>';
	}
?>
