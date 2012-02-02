<?php
	include_once("websections/JaduContact.php");
	$address = new Address;
?>

<div class="contactBox">
	<h3>Contact the Council</h3>
	<ul>
		<li class="icoEmail">Email: <a href="mailto:<?php print $address->email;?>"><?php print $address->email;?></a></li>
		<li class="icoPhone">Telephone: <?php print $address->telephone;?></li>
		<li class="icoBubble">Use our <a href="/feedbackform">feedback form</a> or find <a href="/contactus">key contact details</a></li>
	</ul>
</div>