<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:gtn_addresses/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');


t3lib_extMgm::allowTableOnStandardPages('tx_gtnaddresses_data');

$TCA['tx_gtnaddresses_data'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data',		
		'label'     => 'uid',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'moduser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_gtnaddresses_data.gif',
	),
);

$TCA['tx_gtnaddresses_cat'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_cat',		
		'label'     => 'title',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'moduser_id' => 'cruser_id',
		'sortby' => 'sorting',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_gtnaddresses_cat.gif',
	),
);

$TCA['tx_gtnaddresses_cattypes'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_cattypes',		
		'label'     => 'uid',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'moduser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_gtnaddresses_cattypes.gif',
	),
);
?>