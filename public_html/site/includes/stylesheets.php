<?php
	require_once('websections/JaduHomepageWidgetStyles.php');
	require_once('JaduStyles.php');
	
	$stylesRoot = getStaticContentRootURL() . '/site/styles/';
?>
<link rel="stylesheet" type="text/css" href="<?php print $stylesRoot . encodeHtml($STYLESHEET);?>" />
<?php
	if (isset($_COOKIE['userColourscheme']) && !empty($_COOKIE['userColourscheme'])) {
		$url = '';
		switch ($_COOKIE['userColourscheme']) {
			case 'highcontrast':
				$url = 'user/highcontrast.css';
				break;
			case 'cream':
				$url = 'user/cream.css';
				break;
			case 'blue':
				$url = 'user/blue.css';
				break;
		}
		if (isset($url)) {
			print '<link rel="stylesheet" type="text/css" href="' . $stylesRoot . $url . '" media="screen" />';
		}
	}
	
	if (isset($_COOKIE['userFontsize']) && !empty($_COOKIE['userFontsize'])) {
		$url = '';
		switch ($_COOKIE['userFontsize']) {
			case 'large':
				$url = 'user/large.css';
				break;
			case 'larger':
				$url = 'user/larger.css';
				break;
			case 'largest':
				$url = 'user/largest.css';
				break;
		}
		if (isset($url)) {
			print '<link rel="stylesheet" type="text/css" href="' . $stylesRoot . $url . '" media="screen" />';
		}
	}
	
	if (isset($_COOKIE['userFontchoice']) && !empty($_COOKIE['userFontchoice'])) {
		$url = '';
		switch ($_COOKIE['userFontchoice']) {
			case 'comicsans':
				$url = 'user/comicsans.css';
				break;
			case 'courier':
				$url = 'user/courier.css';
				break;
			case 'ariel':
				$url = 'user/ariel.css';
				break;
			case 'times':
				$url = 'user/times.css';
				break;
		}
		if (isset($url)) {
			print '<link rel="stylesheet" type="text/css" href="' . $stylesRoot . $url . '" media="screen" />';
		}
	}
	
	if (isset($_COOKIE['userLetterspacing']) && !empty($_COOKIE['userLetterspacing'])) {
		$url = '';
		switch ($_COOKIE['userLetterspacing']) {
			case 'wide':
				$url = 'user/wide.css';
				break;
			case 'wider':
				$url = 'user/wider.css';
				break;
			case 'widest':
				$url = 'user/widest.css';
				break;
		}
		if (isset($url)) {
			print '<link rel="stylesheet" type="text/css" href="' . $stylesRoot . $url . '" media="screen" />';
		}
	}
?>

<link rel="Shortcut Icon" type="image/x-icon" href="<?php print getStaticContentRootURL(); ?>/site/favicon.ico" />
<link rel="apple-touch-icon" href="<?php print getStaticContentRootURL(); ?>/site/apple-touch-icon.png" />
<link rel="ToC" href="<?php print getSiteRootURL() . buildSiteMapURL(); ?>" />
<?php
	if (isset($homepage) && $homepage !== null) {
?>
<link rel="stylesheet" type="text/css" href="<?php print getURLToWidgetStylesFileForSeason($STYLESHEET); ?>" media="screen" />
<?php
	}
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
	<!--
	"undefined"==typeof jQuery&&document.write(unescape("%3Cscript type='text/javascript' src='<?php print getStaticContentRootURL() . '/site/javascript/jquery.min.js'; ?>'%3E%3C/script%3E"));
	//-->
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#main-nav-dropdown").hide();
		$(".show_hide").show();
		$(".show_hide").click(function(event){
			if($(window).width() > 759) {
				if(event.preventDefault) {
					event.preventDefault();
				}
				else {
					event.returnValue = false;
				}
				$("#main-nav-dropdown").slideToggle();
			}
		});
		$(".expand").show();
		$(".expand").click(function(event){
			if(event.preventDefault) {
				event.preventDefault();
			}
			else {
				event.returnValue = false;
			}
			$(".tasks").slideToggle();
			$(this).toggleClass('down');
		});
<?php
	if (!isset($indexPage) || !$indexPage) {
?>
		$(".tasks").hide();
<?php
	}
	else {
?>
		$(".tasks").show();

<?php
	}
?>
	});
</script>
<?php
	popJavascript();
	popCSS();
?>