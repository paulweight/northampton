<?php 
	header("content-type: text/css");
	include_once("JaduStyles.php");

	if ($_COOKIE["userColourscheme"] == "highcontrast") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/highcontrast.css);
<?php 
	}
	elseif ($_COOKIE["userColourscheme"] == "simple") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/simple.css);
<?php 
	}
	elseif ($_COOKIE["userColourscheme"] == "newsprint") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/newsprint.css);
<?php 
	}
	elseif ($_COOKIE["userColourscheme"] == "slate") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/slate.css);
<?php 
	}
	elseif ($_COOKIE["userColourscheme"] == "reflect") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/reflect.css);	
<?php 
	}
	elseif ($_COOKIE["userColourscheme"] == "reversed") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/reversed.css);	
<?php 
	}
	elseif ($_COOKIE["userColourscheme"] == "soft1") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/soft1.css);
<?php 
	}
	elseif ($_COOKIE["userColourscheme"] == "soft2") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/soft2.css);
<?php 
	}
	elseif ($_COOKIE["userColourscheme"] == "blue1") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/blue1.css);
<?php 
	}
	elseif ($_COOKIE["userColourscheme"] == "blue2") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/blue2.css);	
<?php 
	}
	elseif ($_COOKIE["userColourscheme"] == "blue3") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/blue3.css);
<?php 
	}
	if ($_COOKIE["userFontsize"] == "small") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/small.css);
<?php 
	}
	elseif ($_COOKIE["userFontsize"] == "medium") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/medium.css);
<?php 
	}
	elseif ($_COOKIE["userFontsize"] == "large") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/large.css);	
<?php 
	}
	elseif ($_COOKIE["userFontsize"] == "larger") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/larger.css);
<?php 
	}
	elseif ($_COOKIE["userFontsize"] == "largest") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/largest.css);
<?php 
	}
	if ($_COOKIE["userFontchoice"] == "times") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/times.css);
<?php
}
	elseif ($_COOKIE["userFontchoice"] == "comicsans") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/comicsans.css);
<?php
}
	elseif ($_COOKIE["userFontchoice"] == "courier") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/courier.css);
<?php
}
	elseif ($_COOKIE["userFontchoice"] == "ariel") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/ariel.css);
<?php 
	}
	if ($_COOKIE["userLetterspacing"] == "small") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/small1.css);
<?php 
	}
	elseif ($_COOKIE["userLetterspacing"] == "medium") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/medium1.css);
<?php 
	}
	elseif ($_COOKIE["userLetterspacing"] == "wide") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/wide.css);	
<?php 
	}
	elseif ($_COOKIE["userLetterspacing"] == "wider") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/wider.css);
<?php 
	}
	elseif ($_COOKIE["userLetterspacing"] == "widest") {
?>
	@import url(<?php print $STYLES_DIRECTORY;?>user/widest.css);
<?php
}
?>