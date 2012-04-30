<?php
	include_once("websections/JaduContact.php");

	$address = new Address();
?>

<div class="vcard">
	<h3>Contact us</h3>
    <li>Email: <a href="mailto:<?php print encodeHtml($address->email); ?>" class="email"><?php print encodeHtml($address->email); ?></a></li>
    <li>Telephone: <span class="tel"><?php print encodeHtml($address->telephone); ?></span></li>
    <li>Send <a href="<?php print getSiteRootURL() . buildFeedbackURL();?>">your feedback</a> or find <a href="<?php print getSiteRootURL() . buildContactURL();?>">key contact details</a>.</li>
</div>