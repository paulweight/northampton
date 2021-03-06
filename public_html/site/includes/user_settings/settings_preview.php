<?php
	$backgroundColour = "inherit";
	$fontColour = "inherit";
	$linkColour = "inherit";
	$textSize = "inherit";
	$fontFamily = "inherit";
	$textSpacing = "inherit";

	if (isset($_POST["previewButton"])) {
		if (isset($_POST["colourScheme"])) {
			switch($_POST["colourScheme"]) {
				case "highcontrast":
					$backgroundColour = "#000";
					$fontColour = "#ff0";
					$linkColour = "#0ff";
					break;
				case "cream":
					$backgroundColour = "#fff9d2";
					$fontColour = "#010066";
					$linkColour = "#908000";
					break;
				case "blue":
					$backgroundColour = "#9fcfff";
					$fontColour = "#010066";
					$linkColour = "#ff0000";
					break;
				default:
					$backgroundColour = "#fff";
					$fontColour = "#444";
					$linkColour = "#AA1E48";
			}
		}
		
		if (isset($_POST["fontSize"])) {
			switch($_POST["fontSize"]) {
				case "small":
					$textSize = "12px";
					break;
				case "medium":
					$textSize ="14px";
					break;
				case "large":
					$textSize ="15px";
					break;
				case "larger":
					$textSize = "16px";
					break;
				case "largest":
					$textSize = "18px";
			}
		}
		
		if (isset($_POST["fontChoice"])) {
			switch($_POST["fontChoice"]) {
				case "times":
					$fontFamily = "times, 'times new roman', palatino, 'new century schoolbook', serif";
					break;
				case "comicsans":
					$fontFamily = "'comic sans', 'comic sans ms', cursive";
					break;
				case "courier":
					$fontFamily = "courier, 'courier new', monospace";
					break;
				case "ariel":
				default:
					$fontFamily = "arial, helvetica, 'gill sans', sans-serif";
			}
		}
		
		if (isset($_POST["letterSpacing"])) {
			switch($_POST["letterSpacing"]) {
				case "wide":
					$textSpacing = "0.1em";
					break;
				case "wider":
					$textSpacing = "0.2em";
					break;
				case "widest":
					$textSpacing = "0.3em";
					break;
				default:
					$textSpacing = "0";
			}
		}
		
		print '<div id="1"></div>';
	}
?>

<div id="preview_box" class="listed_item"
<?php
	if (isset($_POST["previewButton"])) {
?>
 style="padding: 6px; line-height: 1.4em; background: <?php print $backgroundColour; ?>; color: <?php print $fontColour; ?>; font-size: <?php print $textSize; ?>; font-family: <?php print $fontFamily; ?>; letter-spacing: <?php print $textSpacing; ?>;"
<?php
	}
?>
>
	<h2 style="background: <?php print $backgroundColour; ?>; color: <?php print $fontColour; ?>; font-family: <?php print $fontFamily; ?>; letter-spacing: <?php print $textSpacing; ?>;">Preview</h2>
	<img src="<?php print getStaticContentRootURL(); ?>/site/images/cute.gif" alt="" class="contentimage" />
	<p style="background: <?php print $backgroundColour; ?>; color: <?php print $fontColour; ?>; font-size: <?php print $textSize; ?>; font-family: <?php print $fontFamily; ?>; letter-spacing: <?php print $textSpacing; ?>;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam vestibulum velit eu nunc. <a style="background:<?php print $backgroundColour; ?>; color:<?php print $linkColour; ?>;" href="<?php print getSiteRootURL(); ?>">Nullam adipiscing</a> condimentum augue. </p>
	<p style="background: <?php print $backgroundColour; ?>; color: <?php print $fontColour; ?>; font-size: <?php print $textSize; ?>; font-family: <?php print $fontFamily; ?>; letter-spacing: <?php print $textSpacing; ?>;">Praesent tellus velit, ultricies sed, ornare eu, consectetuer sit amet, felis. Sed mollis vestibulum mauris. Nunc a tortor vitae nibh faucibus interdum.</p>
	<p style="background: <?php print $backgroundColour; ?>; color: <?php print $fontColour; ?>; font-size: <?php print $textSize; ?>; font-family: <?php print $fontFamily; ?>; letter-spacing: <?php print $textSpacing; ?>;">Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce vitae eros. Cras interdum.</p>	
	<p style="background: <?php print $backgroundColour; ?>; color: <?php print $fontColour; ?>; font-size: <?php print $textSize; ?>; font-family: <?php print $fontFamily; ?>; letter-spacing: <?php print $textSpacing; ?>;"><a style="background:<?php print $backgroundColour; ?>; color:<?php print $linkColour; ?>;" href="<?php print getSiteRootURL(); ?>">Vivamus quam nunc</a>, consequat quis, volutpat non, venenatis sed, nisi. In pede. Cras ut nulla. Etiam scelerisque, est at aliquet suscipit, augue ipsum euismod justo, vitae pellentesque nulla erat nec tellus. Sed a dui sit amet pede tempor semper. In hac habitasse platea dictumst.</p>
</div>


