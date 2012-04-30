<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php 
	$docDir = '';
	if ((TEXT_DIRECTION == 'rtl') || (defined('LANG_XFORMS_TEXT_DIRECTION') && LANG_XFORMS_TEXT_DIRECTION == 'rtl')) $docDir = ' dir="rtl"'; 
	
	if(@isset($language) && $language != '') { $docLang = $language; } else { $docLang = 'en'; }
?>
<html<?php print $docDir; ?> xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $docLang; ?>" lang="<?php print $docLang; ?>">
