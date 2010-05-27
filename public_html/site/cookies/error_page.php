<?php
	session_start();
	include_once("JaduConstants.php");
	
/*	if (isset($TestCookie)) {
		$string = "http://".$DOMAIN."/site/index.php";
    	header("Location: $string");
    	exit;
    } else {
    	setcookie ("TestCookie",'Test',(time()+60));
    }
 */  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php print METADATA_GENERIC_COUNCIL_NAME; ?></title>
		<style type="text/css" media='screen' />
			body { font-size: 85%; padding: 40px;}
			p { padding: 1em 0; }
			div { text-align: left; padding: 0.5em 2em 2em 5em;}
			body, h1, h2 { font-family:'Lucida grande', 'Lucida Sans Unicode', 'lucida sans', Verdana, Helvetica, Arial, sans-serif; }
		
			h1 { padding:0; font-size: 1.3em; color:#333; padding: 8px 0; }
			h2 { padding:0; font-size: 1.1em; color:#333; padding: 8px 0; }
			img { border-style:none; padding:0; margin:0;  text-align: center;}
		
			a:link { color: #900; text-decoration:none; font-weight:normal; background: url(./styles/css_img/underline.gif) repeat-x left bottom; padding: 0 0 1px 0;}
			a:visited {color: #c33; text-decoration:none; font-weight:normal; background: url(./css_img/underline.gif) repeat-x left bottom; padding: 0 0 1px 0;}
			a:hover { color: #000; text-decoration:none; font-weight:normal; border-bottom: 1px solid #333; padding: 0; background: none;}
			a:active { color: #900; text-decoration:none; font-weight:normal; border-bottom: 1px solid #c33; padding: 0; background: none;}
		</style> 
	</head>
	<body>
		<div>
		  <img src="logo.gif" alt="<?php print METADATA_GENERIC_COUNCIL_NAME; ?>" />					
			<h1>You have arrived at this page for one of FOUR reasons:</h1>
				<ul>
					<li>We have detected that your browser has cookies turned off
					<li>Your cookie has expired
					<li>Your session has timed out
					<li>You are not authorised to view this page
				</ul>
			<p>Please activate cookies in your browsers preferences before continuing.</p>
			<p>To test your browsers setup and query this with Jadu Technical Support, please <a href="./cookie_test.php">click here</a></p>
			<p>To view a tutorial and instructions on how to enable cookies, please <a href="./cookie_instructions.php">click here</a></p>
			<p>To return to the site, please <a href="../index.php">click here</a></p>
		</div>
	</body>
</html>