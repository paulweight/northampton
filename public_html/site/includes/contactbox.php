<?php
	include_once("websections/JaduContact.php");

	$address = new Address();
?>

<div class="contact">
	<h4>Contact us</h4>
	<ul>
		<li>Email: <a href="mailto:<?php print encodeHtml($address->email); ?>" class="email"><?php print encodeHtml($address->email); ?></a></li>
		<li>Telephone: <span class="tel"><?php print encodeHtml($address->telephone); ?></span></li>
	</ul>
</div>