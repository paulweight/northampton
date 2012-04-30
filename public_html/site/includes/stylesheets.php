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
    
            case 'small':
                $url = 'user/small.css';
                break;

            case 'medium':
                $url = 'user/medium.css';
                break;
                
            case 'large':
                $url = 'user/large.css';
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
<link rel="ToC" href="<?php print getSiteRootURL() . buildSiteMapURL(); ?>" />
<?php
	if (isset($homepage) && $homepage !== null) {
?>			
<link rel="stylesheet" type="text/css" href="<?php print getURLToWidgetStylesFileForSeason($STYLESHEET); ?>" media="screen" />
<?php			
	}
?>


<base href="<?php print getCurrentProtocolSiteRootURL(); ?>/" />
<script type="text/javascript" src="<?php print getStaticContentRootURL() . '/site/javascript/swfobject.js'; ?>"></script>

<!--
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js"></script>
-->
<?php
	popJavascript();
	popCSS();
?>
