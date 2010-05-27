<?php
	include_once("JaduConstants.php");
	include_once("websections/JaduContact.php");

	$address = new Address;
	$contactsList = getAllContacts();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME; ?> temporarily offline - Tel: <?php print $address->telephone;?></title>
		<style type="text/css" media='screen' />
		body { font-size: 75%; }
		p { padding: 1em 0 1em 0; }
		div { text-align: left; padding: 0.5em 2em 2em 5em;}
		body, h1, h2 { font-family:Verdana, Tahoma, Arial, Helvetica, Sans-Serif; }
		
		h1 { padding:0; font-size: 1.3em; color:#339; padding: 8px 0; }
        h2 { padding:0; font-size: 1.1em; color:#333; padding: 8px 0; }
        img { border-style:none; padding:0; margin:0;  text-align: center;}
		
		a:link { font-weight: bold; text-decoration:none; color:#339; background:transparent; }
        a:visited { font-weight: bold; text-decoration: none; color:#669; background: transparent;}
        a:hover { font-weight: bold; text-decoration: underline; color:#339; background: transparent;}
        a:active { font-weight: bold; text-decoration: underline; color: #339; background: transparent;}
		</style> 
</head>

<body>
	<div>
	   <img src="cookies/logo.gif" alt="<?php print METADATA_GENERIC_COUNCIL_NAME; ?>" />
		<h1>This site is temporarily offline for scheduled routine maintenance.</h1>
		<h2>We apologise for any inconvenience, please call back soon.</h2>
		<p>If you need to get in touch, please telephone <?php print $address->telephone;?> or email <a href="mailto:<?php print $address->email;?>"><?php print $address->email;?></a>.</p>
		<p> The following facilities are available:</p>
		<ul>
			<li><a href="https://homechoice.northampton.gov.uk/scripts/cgiip.exe/WService%3DOELIVE/ibsxmlpr.p?docid=nbchome" title="homechoice">HomeChoice</a></li>
			<li><a href="https://secure.northampton.gov.uk/payments/newpay.asp" title="Online Payments">Online Payments</a></li>
			<li><a href="http://planning.northamptonboroughcouncil.com:8099/PlanApp/jsp/searchPlan.jsp" title="Planning Search">Planning Search</a></li>
			<li><a href="http://jobs.northampton.gov.uk/" title="Jobs">Jobs</a></li>
			<li><a href="http://www.northamptonboroughcouncil.com/councillors/ieDocHome.asp?bcr=1" title="Minutes and Meetings">Minutes and Meetings</a></li>
			<li><a href="https://myaccounts.northampton.gov.uk/" title="E-asy bill">E-asy bill</a></li>
		</ul>
	</div>
</body>
</html>
