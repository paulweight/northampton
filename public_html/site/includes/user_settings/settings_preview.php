<?php
	$backgroundColour = "inherit";
	$fontColour = "inherit";
	$linkColour = "inherit";
	$textSize = "inherit";
	$fontFamily = "inherit";	
	$textSpacing = "inherit";

	if (isset($_POST["previewButton"])) {
		if (isset($_POST["colourScheme"])) {
			if ($_POST["colourScheme"] == "highcontrast") {
				$backgroundColour = "#000";
				$fontColour = "#ff0";
				$linkColour = "#0ff";
			}
			elseif ($_POST["colourScheme"] == "simple") {
				$backgroundColour = "#fff";
				$fontColour = "#010066";
				$linkColour = "#b22222";
			}
			elseif ($_POST["colourScheme"] == "newsprint") {
				$backgroundColour = "#fff";
				$fontColour = "#000";
				$linkColour = "#000080";
			}			
			elseif ($_POST["colourScheme"] == "slate") {
				$backgroundColour = "#6F6F6F";
				$fontColour = "#fff";
				$linkColour = "#ff0";
			}
			elseif ($_POST["colourScheme"] == "reflect") {
				$backgroundColour = "#CECFCE";
				$fontColour = "#000";
				$linkColour = "#303030";
			}		
			elseif ($_POST["colourScheme"] == "reversed") {
				$backgroundColour = "#112233";
				$fontColour = "#fff";
				$linkColour = "#FC3";
			}		
			elseif ($_POST["colourScheme"] == "soft1") {
				$backgroundColour = "#FFF9D2";
				$fontColour = "#010066";
				$linkColour = "#908000";
			}		
			elseif ($_POST["colourScheme"] == "soft2") {
				$backgroundColour = "#FFC";
				$fontColour = "#000";
				$linkColour = "#00F";
			}	
			elseif ($_POST["colourScheme"] == "blue1") {
				$backgroundColour = "#9FCFFF";
				$fontColour = "#010066";
				$linkColour = "#00F";
			}	
			elseif ($_POST["colourScheme"] == "blue2") {
				$backgroundColour = "#9FCFFF";
				$fontColour = "#00f";
				$linkColour = "#fff";
			}
			elseif ($_POST["colourScheme"] == "blue3") {
				$backgroundColour = "#9FCFFF";
				$fontColour = "#010066";
				$linkColour = "#f00";
			}	
			elseif ($_POST["colourScheme"] == "") {
				$backgroundColour = "#fff";
				$fontColour = "#333";
				$linkColour = "#f90";
			}
		}
	
		if (isset($_POST["fontSize"])) {	
			if ($_POST["fontSize"] == "small") {
				$textSize = "13px";
			}
			elseif ($_POST["fontSize"] == "medium") {
				$textSize = "15px";
			}		
			elseif ($_POST["fontSize"] == "large") {
				$textSize = "16px";
			}
			elseif ($_POST["fontSize"] == "larger") {
				$textSize = "17px";
			}	
			elseif ($_POST["fontSize"] == "largest") {
				$textSize = "18px";
			}	
			elseif ($_POST["fontSize"] == "") {
				$textSize = "12px";
			}			
		}
	
		if (isset($_POST["fontChoice"])) {	
			if ($_POST["fontChoice"] == "times") {
				$fontFamily = "times, 'times new roman', palatino, 'new century schoolbook', serif";
			}
			elseif ($_POST["fontChoice"] == "comicsans") {
				$fontFamily = "'comic sans', 'comic sans ms', cursive";
			}	
			elseif ($_POST["fontChoice"] == "courier") {
				$fontFamily = "courier, 'courier new', monospace";
			}			
			elseif ($_POST["fontChoice"] == "ariel") {
				$fontFamily = "arial, helvetica, 'gill sans', sans-serif";
			}		
			elseif ($_POST["fontChoice"] == "") {
				$fontFamily = "Verdana, Tahoma, Arial, Helvetica, Sans-Serif";
			}				
		}
		
		if (isset($_POST["letterSpacing"])) {	
			if ($_POST["letterSpacing"] == "small") {
				$textSpacing = "0.1em";
			}
			elseif ($_POST["letterSpacing"] == "medium") {
				$textSpacing = "0.2em";
			}	
			elseif ($_POST["letterSpacing"] == "wide") {
				$textSpacing = "0.3em";
			}	
			elseif ($_POST["letterSpacing"] == "") {
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
style=" line-height:1.4em; background:<?php print $backgroundColour; ?>; color: <?php print $fontColour; ?>; font-size: <?php print $textSize; ?>; font-family: <?php print $fontFamily; ?>; letter-spacing: <?php print $textSpacing; ?>; "
<?php
	}
?>
>
	<h2 style="color: <?php print $fontColour; ?>;">Preview</h2>	
	<img src="<?php print getStaticContentRootURL(); ?>/site/images/cute.gif" alt="" class="contentimage" />	
	<p style="color: <?php print $fontColour; ?>;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam vestibulum velit eu nunc. <a style="color:<?php print $linkColour; ?>;" href="<?php print getSiteRootURL(); ?>">Nullam adipiscing</a> condimentum augue. </p>
	<p style="color: <?php print $fontColour; ?>;">Praesent tellus velit, ultricies sed, ornare eu, consectetuer sit amet, felis. Sed mollis vestibulum mauris. Nunc a tortor vitae nibh faucibus interdum.</p>
	<p style="color: <?php print $fontColour; ?>;">Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce vitae eros. Cras interdum.</p>	
	<p style="color: <?php print $fontColour; ?>;"><a style="color:<?php print $linkColour; ?>;" href="<?php print getSiteRootURL(); ?>">Vivamus quam nunc</a>, consequat quis, volutpat non, venenatis sed, nisi. In pede. Cras ut nulla. Etiam scelerisque, est at aliquet suscipit, augue ipsum euismod justo, vitae pellentesque nulla erat nec tellus. Sed a dui sit amet pede tempor semper. In hac habitasse platea dictumst.</p>
</div>


