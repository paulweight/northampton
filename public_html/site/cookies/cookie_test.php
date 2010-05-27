<?php
	$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
	$HTTP_HOST = $_SERVER['HTTP_HOST'];
	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
	
	$clogfile = "/tmp/cookielog$REMOTE_ADDR.txt"; // Use a unique logfile for each run.
	$ctestfile = basename($PHP_SELF);
	$clogcount = 5; // number of fields in cookielog
    
	function cookie_log( $tnum, $tresult ) {
		global $REMOTE_ADDR, $HTTP_USER_AGENT, $HTTP_HOST, $clogfile;
		$fd = fopen($clogfile, "a+");
		fwrite($fd,"$HTTP_HOST,$REMOTE_ADDR,\"$HTTP_USER_AGENT\",$tnum,$tresult\n");
		fclose($fd);
	}

	function clearcookies()
	{
		global $ctestfile;
		setcookie("testcookie1","",0,"/");
		setcookie("testcookie2","",0,"/");
		setcookie("testcookie3","",0,"/");
		setcookie("testcookie4","",0,"/");
		setcookie("testcookie5","",0,"/");
		setcookie("testcookie6","",0,"/");
		setcookie("testcookie7","",0,"/");
		setcookie("testcookie8","",0,"/");
		setcookie("testcookie9","",0,"/");
		setcookie("testcookie10","",0,"/");
		setcookie("testcookie11","",0,"/");
	}

	if(!isset($_GET['mode'])) {
		// Clear all cookies.  Do this at start, and again when we're finished.
		clearcookies();
		header("Location: $ctestfile?mode=1");
		exit;
	}

	if($_GET['mode'] == 1) {
		// Try setting all the cookies
		$time = mktime()+600;
		$date = gmstrftime("%A, %d-%b-%Y %H:%M:%S", (mktime()+6400));
		
		header("Set-Cookie: testcookie1=present;");
		header("Set-Cookie: testcookie2=present; expires=$date");
		header("Set-Cookie: testcookie3=present; expires=$date; path=/");
		header("Set-Cookie: testcookie11=present; path=/; domain=$HTTP_HOST; expires=".gmstrftime("%A, %d-%b-%Y %H:%M:%S GMT",time()+9600));
		setcookie("testcookie4", "present");
		setcookie("testcookie5", "present", (time()+6400));
		setcookie("testcookie6", "present", (time()+6400), "/");
		print "<meta http-equiv=\"Set-Cookie\" content=\"testcookie7=present\">\n";
		print "<meta http-equiv=\"Set-Cookie\" content=\"testcookie8=present; expires=$date\">\n";
		print "<meta http-equiv=\"Set-Cookie\" content=\"testcookie9=present; expires=$date; path=/\">\n";
		print "<script>document.cookie = 'testcookie10' + '=' + 'present';</script>\n";	
		
		sleep(1);
		print "<meta http-equiv=refresh content=\"0;URL=$ctestfile?mode=2\">\n";
		exit;
	}

	if($_GET['mode'] == 2) {
		// Check and log results
		if($_COOKIE ['testcookie1'] != "present")
		if($_COOKIE ['testcookie2'] != "present")
		if($_COOKIE ['testcookie3'] != "present")
		if($_COOKIE ['testcookie4'] != "present")
		if($_COOKIE ['testcookie5'] != "present")
		if($_COOKIE ['testcookie6'] != "present")
		if($_COOKIE ['testcookie7'] != "present")
		if($_COOKIE ['testcookie8'] != "present")
		if($_COOKIE ['testcookie9'] != "present")
		if($_COOKIE ['testcookie10'] != "present")
		if($_COOKIE ['testcookie11'] != "present") {
			cookie_log(0, "Cookies Disabled");    
			header("Location: $ctestfile?mode=3");    
			exit;
		}
		
		if($_COOKIE ['testcookie1'] == "present") { cookie_log(1,"OK"); }
		else   { cookie_log(1,"Fail"); }
		if($_COOKIE ['testcookie2'] == "present") { cookie_log(2, "OK"); }
		else    { cookie_log(2, "Fail"); }
		if($_COOKIE ['testcookie3'] == "present") { cookie_log(3, "OK"); }
		else    { cookie_log(3, "Fail"); }
		if($_COOKIE ['testcookie4'] == "present") { cookie_log(4, "OK"); }
		else    { cookie_log(4, "Fail"); }
		if($_COOKIE ['testcookie5'] == "present") { cookie_log(5, "OK"); }
		else    { cookie_log(5, "Fail"); }
		if($_COOKIE ['testcookie6'] == "present") { cookie_log(6, "OK"); }
		else    { cookie_log(6, "Fail"); }
		if($_COOKIE ['testcookie7'] == "present") { cookie_log(7, "OK"); }
		else    { cookie_log(7, "Fail"); }
		if($_COOKIE ['testcookie8'] == "present") { cookie_log(8, "OK"); }
		else    { cookie_log(8, "Fail"); }
		if($_COOKIE ['testcookie9'] == "present") { cookie_log(9, "OK"); }
		else    { cookie_log(9, "Fail"); }
		if($_COOKIE ['testcookie10'] == "present") { cookie_log(10, "OK"); }
		else    { cookie_log(10, "Fail"); }
		if($_COOKIE ['testcookie11'] == "present") { cookie_log(11, "OK"); }
		else    { cookie_log(11, "Fail"); }
		
		// Now clean up the cookies
		clearcookies();
		header("Location: $ctestfile?mode=3");
		exit;
	}

	include_once("JaduConstants.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME; ?> cookie test</title>
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

			table { border: 1px solid #ccc; padding:4px; margin: 0; width: 50%; }
		</style> 
</head>
<body>
	<div>
		<img src="logo.gif" alt="<?php print METADATA_GENERIC_COUNCIL_NAME; ?>" />	

<?php
	if (!isset($email) || !isset($submit) || ($email=="")) {
?>

		<h1>Browser Test Results</h1>
		<p><?=$HTTP_USER_AGENT;?></p>

		<h1>Cookie Test Results</h1>

		<table>
	<?php
			$cookie_results = "";
			$fp=@fopen($clogfile, "r");
			if (!$fp) {
				$cookie_results = "No results are currently available.  Please re-run the tests.<br>";
				print $cookie_results;
			}
			else {
				$lnum = 0;
				while ($line = fgetcsv ($fp, 1000, ",")) {
					print "<tr>";

					$lcount++;

					if (count($line) != $clogcount) {
						print "<td colspan='2'>".count($line). " Bad field count, line $lcount</td>";
						$cookie_results .= "Bad field count, line $lcount" . "\n";
						continue;
					}
					if ($line[1] == $REMOTE_ADDR && $line[2] == $HTTP_USER_AGENT) {
						print "<td width='50%'>Test " . $line[3] . "</td><td width='50%'> " . $line[4] . "</td>";
						$cookie_results .= "Test " . $line[3] . " : " . $line[4] . "\n";
					}

					print "</tr>";
				}
				fclose($fp);
				unlink($clogfile);  // use a unique logfile for each run.
			}
	?>	
		</table>

		<h1>Javascript Test Results</h1>

		<script LANGUAGE="JavaScript">
         	document.write("JavaScript <b>is<\/b> turned on.");
        </script> 
        <noscript>
        	JavaScript <b>is not</b> turned on.
        </noscript>


		<p>If you wish to re-run this test, please <a href="<?=$PHP_SELF?>">click here</a></p>

		<p>
		If you wish to email these results to technical support, please enter your email address and press 'Send'.</p>
		<form name="emailResults" action="" method="post">
			<input type="hidden" name="test_results" value="<?=$cookie_results?>">
			email address: <input name="email" type="text" size="30" value="">&nbsp;<input name="submit" type="submit" value="Send">
		</form>

		
	<?php
		}
		else if (isset($email) && isset($submit)) {
		
			if (($email!="") && ereg("^[^@ ]+@[^@ ]+\.[^@ \.]+$", $email, $rubbish)) {
		
				print "<br>Your browsers test results have been successfully sent to technical support. They will reply to you ASAP with any queries. <br><br>Thank you.<br><br>";
			
				$contents =	"A user requires cookie help on the following jadu website: $DOMAIN"."\n".
							"email: $email"."\n".
							"browser: $HTTP_USER_AGENT"."\n".
							"cookie test results:"."\n".
							$test_results."\n\n".
							"some instructions are available at: http://$DOMAIN/site/cookies/cookie_instructions.php";
			
				mail("support@jadu.co.uk", "Jadu Browser Test Results", $contents, "From: browser@$DOMAIN");
			}
			else {
				print "<br>The email address that you provided appears to be invalid. No action has been taken.<br>";
				print "<br>If you wish to re-run this test, please <a href='$PHP_SELF'>click here</a><br>";
			}
		}
		
	?>	
	
		<p>
		<a href='./error_page.php'>Return to error page</a>
		</p>	
		
	</div>
</body>
</html>