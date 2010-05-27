<?php
	session_start();
	
	include_once("JaduConstants.php");

	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$IPAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else {
		$IPAddress = $_SERVER['REMOTE_ADDR'];
	}
	
	$header = "From: $DEFAULT_EMAIL_ADDRESS";
	$message = "A potential cross site scripting attack was made on your website from the " . 
		"following IP address: $IPAddress.";
		
	if (!isset($_SESSION['mail']) || $_SESSION['mail'] != date('H')) {
		mail($DEFAULT_EMAIL_ADDRESS . ",security@jadu.co.uk", "Possible XSS Scripting Attack", 
			$message, $header);
	}
		
	$_SESSION['mail'] = date('H');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php print METADATA_GENERIC_COUNCIL_NAME; ?></title>
		<style type="text/css" media='screen' />
			body { font-size: 80%;}
			div { text-align: left; padding: 0.5em 2em 2em 5em;}
			body, h1, h2 { font-family:Verdana, Tahoma, Arial, Helvetica, Sans-Serif; }
			
			h1 { font-size: 2em; background: #900; color: #fff; padding: 10px 0; margin:0;}
			h1 span{ color:#fff; padding: 10px; background: #000 }
			h2 { margin:10px 0; font-size: 2em; color:#900; padding: 10px; }
			p { padding:0 10px; font-size: 1.4em; line-height: 1.7;}
			img { border-style:none; padding:0; margin:0;  text-align: center;}
			
			p strong { color: #900; }
			
			a:link { color: #009; font-weight: bold; }
			a:visited { color: #009; font-weight: bold; }
			a:hover { color: #009; font-weight: bold;  }
			a:active { color: #009; font-weight: bold; }
		</style> 
	</head>
	<body>
		<h1><span>Error:</span> Forbidden Action</h1>
		<h2>We're sorry, but your query looks like an attack on our website.</h2>
		<p>To protect our users, <strong>we can't process your request</strong>.</p>
		<p>Your <strong>IP address has been logged</strong>, forwarded to the system administrator and may subsequently be <strong>banned from accessing this site</strong>.</p>
		<p>If you believe this to be a mistake, please <a href="http://<?php print $DOMAIN;?>/site/scripts/contact.php">contact us</a></p>
		<p><a href="http://<?php print $DOMAIN;?>">Return to our site.</a></p>
	</body>
</html>