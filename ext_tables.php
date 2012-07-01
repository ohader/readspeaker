<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/', 'ReadSpeaker');

$tempColumns = array(
	'tx_readspeaker_disable' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:readspeaker/locallang_db.xml:pages.tx_readspeaker_disable.label',
		'config' => array(
			'type' => 'check',
			'items' => array(
				array('LLL:EXT:readspeaker/locallang_db.xml:pages.tx_readspeaker_disable.checkbox', 1),
			)
		)
	),
);

t3lib_div::loadTCA('pages');
t3lib_extMgm::addTCAcolumns('pages', $tempColumns, TRUE);
Tx_Readspeaker_Common::addToAllPalettesContainingField('pages', 'hidden', 'tx_readspeaker_disable', 'after:hidden');


$tempColumns = array(
	'tx_readspeaker_disable' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:readspeaker/locallang_db.xml:tt_content.tx_readspeaker_disable.label',
		'config' => array(
			'type' => 'check',
			'items' => array(
				array('LLL:EXT:readspeaker/locallang_db.xml:tt_content.tx_readspeaker_disable.checkbox', 1),
			)
		)
	),
);

t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content', $tempColumns, TRUE);
Tx_Readspeaker_Common::addToAllPalettesContainingField('tt_content', 'hidden', 'tx_readspeaker_disable', 'after:hidden');
?>