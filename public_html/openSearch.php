<?php
	require_once('JaduConstants.php');
	
	if (defined('SCRIPTING_LANGUAGE') && SCRIPTING_LANGUAGE == DOT_NET) {
		$fileExtension = 'aspx';
	}
	else {
		$fileExtension = 'php';
	}
	
	print '<?xml version="1.0"?>';
?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
	<ShortName><?php print METADATA_GENERIC_COUNCIL_NAME; ?></ShortName>
	<Description><?php print METADATA_GENERIC_COUNCIL_NAME; ?></Description>
	<Image height="16" width="16" type="image/x-icon">http://<?php print DOMAIN; ?>/favicon.ico</Image>
	<Url type="text/html" method="get" template="http://<?php print DOMAIN; ?>/site/scripts/google_results.<?php print $fileExtension; ?>?q={searchTerms}" />
</OpenSearchDescription>