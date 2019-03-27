<?php
if ( function_exists( 'wfLoadSkin' ) ) {
	wfLoadSkin( 'Michigan' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$GLOBALS['wgMessagesDirs']['Michigan'] = __DIR__ . '/i18n';
	/* wfWarn(
		'Deprecated PHP entry point used for Metrolook skin. Please use wfLoadSkin instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	); */
	return true;
} else {
	die( 'Your MediaWiki version is too old. The Michigan skin requires MediaWiki 1.25+' );
}
