<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::allowTableOnStandardPages('tx_mwmybooks_books');

$TCA["tx_mwmybooks_books"] = array (
	"ctrl" => array (
		'title'     => 'LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_books',		
		'label'     => 'title',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => 'sorting',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',	
			'starttime' => 'starttime',	
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_mwmybooks_books.gif',
	),
	"feInterface" => array (
		"fe_admin_fieldList" => "hidden, starttime, endtime, title, author, isbn, price, shortdescription, description, cover, link, category, martinreiter, available",
	)
);


t3lib_extMgm::allowTableOnStandardPages('tx_mwmybooks_category');

$TCA["tx_mwmybooks_category"] = array (
	"ctrl" => array (
		'title'     => 'LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_category',		
		'label'     => 'name',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' => 'name',	
		'sortby' => 'sorting',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_mwmybooks_category.gif',
	),
	"feInterface" => array (
		"fe_admin_fieldList" => "hidden, name, description",
	)
);

$tempColumns = Array (
	"tx_mwmybooks_category" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tt_content.tx_mwmybooks_category",		
		"config" => Array (
			"type" => "select",	
			"items" => Array (
				Array("",0),
			),
			"foreign_table" => "tx_mwmybooks_category",	
			"foreign_table_where" => "ORDER BY tx_mwmybooks_category.uid",	
			"size" => 6,	
			"minitems" => 0,
			"maxitems" => 1,
		)
	),
	"tx_mwmybooks_martinreiter" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tt_content.tx_mwmybooks_martinreiter",		
		"config" => Array (
			"type" => "check",
		)
	),
);


t3lib_div::loadTCA("tt_content");
t3lib_extMgm::addTCAcolumns("tt_content",$tempColumns,1);


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='tx_mwmybooks_category;;;;1-1-1, tx_mwmybooks_martinreiter';


t3lib_extMgm::addPlugin(array('LLL:EXT:mw_mybooks/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');


t3lib_extMgm::addStaticFile($_EXTKEY,'pi1/static/','Booklist');


if (TYPO3_MODE=="BE")	$TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_mwmybooks_pi1_wizicon"] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_mwmybooks_pi1_wizicon.php';


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi2']='layout,select_key';


t3lib_extMgm::addPlugin(array('LLL:EXT:mw_mybooks/locallang_db.xml:tt_content.list_type_pi2', $_EXTKEY.'_pi2'),'list_type');


t3lib_extMgm::addStaticFile($_EXTKEY,'pi2/static/','Random Books');


if (TYPO3_MODE=="BE")	$TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_mwmybooks_pi2_wizicon"] = t3lib_extMgm::extPath($_EXTKEY).'pi2/class.tx_mwmybooks_pi2_wizicon.php';


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi3']='layout,select_key';


t3lib_extMgm::addPlugin(array('LLL:EXT:mw_mybooks/locallang_db.xml:tt_content.list_type_pi3', $_EXTKEY.'_pi3'),'list_type');


t3lib_extMgm::addStaticFile($_EXTKEY,'pi3/static/','Category List');


if (TYPO3_MODE=="BE")	$TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_mwmybooks_pi3_wizicon"] = t3lib_extMgm::extPath($_EXTKEY).'pi3/class.tx_mwmybooks_pi3_wizicon.php';
?>