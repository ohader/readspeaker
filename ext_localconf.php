<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// Hook to pre-process resources and add ReadSpeaker scripts
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess']['readspeaker'] =
	'EXT:readspeaker/Classes/Hooks/PageRendererHook.php:Tx_Readspeaker_Hooks_PageRendererHook->preProcess';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all']['readspeaker'] =
	'EXT:readspeaker/Classes/Hooks/PageRendererHook.php:Tx_Readspeaker_Hooks_TypoScriptFrontendControllerHook->postProcessContent';
?>