<?php
	include_once("JaduConstants.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME; ?> cookie instructions</title>
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

			table { border: 1px solid #e5e5e5; padding:4px; margin: 0; width: 50%; }
			hr { border: 3px solid #e5e5e5; padding:0; margin: 10px; width: 90%; }
		</style> 
</head>
<body>
	<div>
		<div id="top"></div>
		<img src="logo.gif" alt="<?php print METADATA_GENERIC_COUNCIL_NAME; ?>" />	

	<h1>How to determine your browser's version:</h1>
	<p>If you are not sure which version of your browser you are using, from the Help menu, select "About Internet Explorer" or "About Netscape".</p>
	<p>
	If you selected About Internet Explorer, a pop-up window will open. The version is listed just below the Microsoft Internet Explorer logo. If the number starts with 5, or 6 follow the instructions below for Microsoft Internet Explorer, otherwise you should look into updating your installation.</p>
	<p>
	If you selected About Netscape, a new window will open. The version is listed at the top of the text in this window. If the number starts with 7, follow the instructions below for Netscape 7.</p>
	<hr>
	<h2>How to enable Cookies</h2>

	<ol>
		<li><a href="#win_ie6">Windows - Internet Explorer 6</a></li>
		<li><a href="#win_ie5x">Windows - Internet Explorer 5.x</a></li>
		<li><a href="#win_net7">Win2000 / XP / Mac OS 9.2 - Netscape 7.0 / Mozilla</a></li>
		<li><a href="#mac_ie">Mac OS X - Internet Explorer</a></li>
		<li><a href="#mac9.2_ie">Mac OS 9.2 - Internet Explorer 5.1</a></li>
	</ol>

	<hr>

	<a name="win_ie6"><h2>Windows Internet Explorer 6</h2></a>
	<ol>
		<li>Start your windows machine and load Internet Explorer.</li>
		<li>Using the Menu system, go to "Tools"->"Internet Options", and a popup window should appear.</li>
		<img class="help" src="cookie_images/ie6_menu.jpg" alt="IE6 internet options menu selection" />
		<li>Under the "General" tab, In the middle you should see a bordered area called "Temporary Internet Files":
			<ul>
				<li>Click on the "Settings" button and ensure that the top choice is selected ("Every visit to this page)", and the the slider for "amount of diskspace to use" is set to be greater than 0, Then pres "OK".</li>
				<li>You should now be back in the "General section", click on "Delete Files", check the "Delete all offline content" box, press "OK".</li>
				<li>You should now be back in the "General section", click on "Delete Cookies", press "OK".</li>
			</ul>
			</li>
			<img class="help" src="cookie_images/ie6_io.jpg" alt="IE6 internet options" />
			<img class="help" src="cookie_images/ie6_settings.jpg" alt="IE6 settings"  />
		<li>Further down the "General" tab, you should see a further bordered area called "History". Press "Clear history", the Press "Yes" when asked if you are sure.</li>
		<li>Now select the "Security" tab at the top of this popup window.
			<ul>
				<li>Select the Internet icon (the globe).</li>
				<li>Press "Default Level" button to set slider to medium.</li>
				<li>At this point, you may receive a warning from Internet Explorer that you are about to make changes to your security settings. Click "Yes".</li>
				<li>Click "OK".</li>
			</ul>
			</li>
			<img class="help" src="cookie_images/ie6_security.jpg" alt="IE6 security options" />
				
		<li>Click on the Privacy tab
			<ul>
				<li>Click and drag the security slider-bar down until you are at "Medium".</li>
			</ul>
			</li>
			<img class="help" src="cookie_images/ie6_privacy.jpg" alt="IE6 privacy options" />

		<li>Go To "General" Tab
			<ul>
				<li>Click on "Settings" button in middle "Temporary internet Files" section.</li>
				<li>Press "View Files".</li>
				<li>On Menu of window that appears go to "Tools" if can see this, or "View" if cannot see "Tools" THEN "Folder Options".</li>
				<li>Click on "View" tab in new window.</li>
				<li>You should then see a little folder called "Hidden files and folders" which is slightly indented, Ensure that below this "Do not show hidden files and folders" is selected.</li>
				<li>Apply this.</li>
			</ul>
			</li>
				<img class="help" src="cookie_images/ie6_folder_options.jpg" alt="IE6 folders" />
				<img class="help" src="cookie_images/ie6_folders_view.jpg" alt="IE6 folders"  />
			</p>
		<li>Click on "Apply" and "Ok" to confirm the settings changes and close the window.</li>
		<li>Close Internet Explorer.</li>
		<li>Restart your machine.</li>
	</ol>
	<p>
	<a href="#top">Back to the top</a>
	</p>
	<hr>

	<a name="win_ie5x"><h2>Windows - Internet Explorer 5.x</h2>
	<ol>
		<li>Select Tools > Internet Options.</li>
		<li>Click the Security tab.</li>
		<li>Verify that the Internet web content zone is highlighted.</li>
		<li>Click the Customize Level button at the bottom of the Internet Options dialog box.</li>	
		<img class="help" src="cookie_images/ie5x_security.gif" alt="IE5.x cookies" />
		<li>Scroll down to the Cookies section.</li>
		<li>Verify that Allow per-session cookies is set to "Enable".</li>
		<li>Verify that Allow cookies that are stored on your computer is set to "Enable".</li>
		<li>Click OK to close the Security Settings dialog box, and then click OK to close the Internet Options dialog box.</li>
		<img class="help" src="cookie_images/ie5x_cookies.gif" alt="IE5.x cookies" />		
	</ol>

	<p><a href="#top">Back to the top</a></p>
	<hr>
	
	<a name="win_net7"><h2>Win2000 / XP / Mac OS 9.2 - Netscape 7.0 / Mozilla</h2></a>
	<ol>
		<li>From the main browser menu: Edit > Preferences</li>
		<li>From the left window with options, click the arrow next to Privacy and Security.</li>
		<li>Select Cookies.</li>
		<li>Check either "Enable cookies for the originating web site only" or "Enable all cookies."</li>
		<li>Click "OK."</li>
		<li>Close and restart Netscape.</li>
		<img class="help" src="cookie_images/ns_prefs.jpg" alt="Netscape" />
	</ol>
	<p><a href="#top">Back to the top</a></p>
	<hr>

	<a name="mac_ie"><h2>Mac OS X - Internet Explorer</h2></a>
	<ol>
		<li>From the main browser menu: Explorer > Preferences</li>
		<li>From the left window with options under Web Browser , select "Web Content."</li>
		<li>Check "Enable scripting" under Active Content.</li>
		<li>Under Receiving Files in the left window, select "Cookies."</li>
		<li>Under the pull down menu "When receiving cookies:" make sure that "Never Ask" is selected.</li>
		<li>Click "OK."</li>
		<li>Close and restart IE.</li>
		<img class="help" src="cookie_images/macie.jpg" alt="Mac IE" />
	</ol>

	<p><a href="#top">Back to the top</a></p>
	<hr>
	
	<a name="mac9.2_ie"><h2>Mac OS 9.2 - Internet Explorer 5.1</h2></a>
	<ol>

		<li>From the browsers main menu: Edit > Preferences</li>
		<li>From the left window with options under Web Browser , select "Web Content."</li>
		<li>Check "Enable scripting" under Active Content.</li>
		<li>Under Receiving Files in the left window, select Cookies.</li>
		<li>Under the pull down menu When receiving cookies: make sure that "Never Accept" is NOT SELECTED.</li>
		<li>Click "OK."</li>
		<li>Close and restart IE.</li>
	</ol>

	<p><a href="#top">Back to the top</a></p>

	<hr>
		
	<p>
	<a href='./error_page.php'>Return to error page</a>
	</p>

</body>
</html>