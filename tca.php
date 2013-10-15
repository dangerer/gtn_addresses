<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_gtnaddresses_data'] = array (
	'ctrl' => $TCA['tx_gtnaddresses_data']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,nachname,vorname,titel,geschlecht,jahrgang,praxis_plz,praxis_ort,praxis_adresse,aus_weiterbildung,html_format,praxis2_plz,praxis2_ort,praxis2_adresse,iaus,freiekassenplaetze_abkw,freiekassenplaetze2,email,telefon,images,web_adresse,vid,nid,fe_uid,edited_from,bezeichnung,promote,sticky,revision_titel,beschreibung,teaser,dateien,signature,usersdata,cat'
	),
	'feInterface' => $TCA['tx_gtnaddresses_data']['feInterface'],
	'columns' => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'nachname' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.nachname',		
			'config' => array (
				'type' => 'input',	
				'size' => '48',	
				'eval' => 'required,trim',
			)
		),
		'vorname' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.vorname',		
			'config' => array (
				'type' => 'input',	
				'size' => '48',	
				'eval' => 'required',
			)
		),
		'titel' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.titel',		
			'config' => array (
				'type' => 'input',	
				'size' => '48',	
				'eval' => 'trim',
			)
		),
		'geschlecht' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.geschlecht',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.geschlecht.I.0', '1'),
					array('LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.geschlecht.I.1', '2'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'jahrgang' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.jahrgang',		
			'config' => array (
				'type' => 'input',	
				'size' => '5',	
				'max' => '4',	
				'eval' => 'trim',
			)
		),
		'praxis_plz' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.praxis_plz',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'praxis_ort' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.praxis_ort',		
			'config' => array (
				'type' => 'input',	
				'size' => '48',	
				'eval' => 'trim',
			)
		),
		'praxis_adresse' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.praxis_adresse',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'aus_weiterbildung' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.aus_weiterbildung',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'html_format' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.html_format',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.html_format.I.0', '1'),
					array('LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.html_format.I.1', '2'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'praxis2_plz' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.praxis2_plz',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'praxis2_ort' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.praxis2_ort',		
			'config' => array (
				'type' => 'input',	
				'size' => '48',	
				'eval' => 'trim',
			)
		),
		'praxis2_adresse' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.praxis2_adresse',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'iaus' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.iaus',		
			'config' => array (
				'type' => 'check',
			)
		),
		'freiekassenplaetze_abkw' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.freiekassenplaetze_abkw',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'freiekassenplaetze2' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.freiekassenplaetze2',		
			'config' => array (
				'type' => 'check',
			)
		),
		'email' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.email',		
			'config' => array (
				'type' => 'input',	
				'size' => '48',	
				'eval' => 'trim',
			)
		),
		'telefon' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.telefon',		
			'config' => array (
				'type' => 'input',	
				'size' => '48',	
				'eval' => 'trim',
			)
		),
		'images' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.images',		
			'config' => array (
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => 'gif,png,jpeg,jpg',	
				'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],	
				'uploadfolder' => 'uploads/tx_gtnaddresses',
				'size' => 3,	
				'minitems' => 0,
				'maxitems' => 3,
			)
		),
		'web_adresse' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.web_adresse',		
			'config' => array (
				'type'     => 'input',
				'size'     => '15',
				'max'      => '255',
				'checkbox' => '',
				'eval'     => 'trim',
				'wizards'  => array(
					'_PADDING' => 2,
					'link'     => array(
						'type'         => 'popup',
						'title'        => 'Link',
						'icon'         => 'link_popup.gif',
						'script'       => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'vid' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.vid',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'nid' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.nid',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'fe_uid' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.fe_uid',		
			'config' => array (
				'type' => 'select',	
				'foreign_table' => 'fe_users',	
				'foreign_table_where' => 'ORDER BY fe_users.uid',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'edited_from' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.edited_from',		
			'config' => array (
				'type' => 'select',	
				'foreign_table' => 'fe_users',	
				'foreign_table_where' => 'ORDER BY fe_users.uid',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'bezeichnung' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.bezeichnung',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'promote' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.promote',		
			'config' => array (
				'type' => 'check',
			)
		),
		'sticky' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.sticky',		
			'config' => array (
				'type' => 'check',
			)
		),
		'revision_titel' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.revision_titel',		
			'config' => array (
				'type' => 'input',	
				'size' => '48',	
				'eval' => 'trim',
			)
		),
		'beschreibung' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.beschreibung',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'teaser' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.teaser',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'dateien' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.dateien',		
			'config' => array (
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => 'gif,png,jpeg,jpg',	
				'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],	
				'uploadfolder' => 'uploads/tx_gtnaddresses',
				'size' => 3,	
				'minitems' => 0,
				'maxitems' => 3,
			)
		),
		'signature' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.signature',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'usersdata' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.usersdata',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'cat' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_data.cat',		
			'config' => array (
				'type' => 'select',	
				'foreign_table' => 'tx_gtnaddresses_cat',	
				'foreign_table_where' => 'ORDER BY tx_gtnaddresses_cat.uid',	
				'size' => 20,	
				'minitems' => 0,
				'maxitems' => 100,	
				"MM" => "tx_gtnaddresses_data_cat_mm",
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'hidden;;1;;1-1-1, nachname, vorname, titel, geschlecht, jahrgang, praxis_plz, praxis_ort, praxis_adresse, aus_weiterbildung, html_format, praxis2_plz, praxis2_ort, praxis2_adresse, iaus, freiekassenplaetze_abkw, freiekassenplaetze2, email, telefon, images, web_adresse, vid, nid, fe_uid, edited_from, bezeichnung, promote, sticky, revision_titel, beschreibung, teaser, dateien, signature, usersdata, cat')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);



$TCA['tx_gtnaddresses_cat'] = array (
	'ctrl' => $TCA['tx_gtnaddresses_cat']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,title,description,weight,cattype'
	),
	'feInterface' => $TCA['tx_gtnaddresses_cat']['feInterface'],
	'columns' => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'title' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_cat.title',		
			'config' => array (
				'type' => 'input',	
				'size' => '48',	
				'eval' => 'trim',
			)
		),
		'description' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_cat.description',		
			'config' => array (
				'type' => 'input',	
				'size' => '48',	
				'eval' => 'trim',
			)
		),
		'weight' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_cat.weight',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'cattype' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_cat.cattype',		
			'config' => array (
				'type' => 'select',	
				'items' => array (
					array('',0),
				),
				'foreign_table' => 'tx_gtnaddresses_cattypes',	
				'foreign_table_where' => 'ORDER BY tx_gtnaddresses_cattypes.uid',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'hidden;;1;;1-1-1, title;;;;2-2-2, description;;;;3-3-3, weight, cattype')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);



$TCA['tx_gtnaddresses_cattypes'] = array (
	'ctrl' => $TCA['tx_gtnaddresses_cattypes']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'title,description,multiple'
	),
	'feInterface' => $TCA['tx_gtnaddresses_cattypes']['feInterface'],
	'columns' => array (
		'title' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_cattypes.title',		
			'config' => array (
				'type' => 'input',	
				'size' => '48',	
				'eval' => 'trim',
			)
		),
		'description' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_cattypes.description',		
			'config' => array (
				'type' => 'input',	
				'size' => '48',	
				'eval' => 'trim',
			)
		),
		'multiple' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gtn_addresses/locallang_db.xml:tx_gtnaddresses_cattypes.multiple',		
			'config' => array (
				'type' => 'check',
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'title;;;;2-2-2, description;;;;3-3-3, multiple')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>