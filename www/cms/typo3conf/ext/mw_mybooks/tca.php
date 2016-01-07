<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA["tx_mwmybooks_books"] = array (
	"ctrl" => $TCA["tx_mwmybooks_books"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "hidden,starttime,endtime,title,author,isbn,price,shortdescription,description,cover,link,category,martinreiter,available"
	),
	"feInterface" => $TCA["tx_mwmybooks_books"]["feInterface"],
	"columns" => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array (
					'upper' => mktime(0, 0, 0, 12, 31, 2020),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_books.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"author" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_books.author",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"isbn" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_books.isbn",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"price" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_books.price",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"max" => "10",
			)
		),
		"shortdescription" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_books.shortdescription",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"description" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_books.description",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"cover" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_books.cover",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 500,	
				"uploadfolder" => "uploads/tx_mwmybooks",
				"show_thumbs" => 1,	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"link" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_books.link",		
			"config" => Array (
				"type"     => "input",
				"size"     => "15",
				"max"      => "255",
				"checkbox" => "",
				"eval"     => "trim",
				"wizards"  => array(
					"_PADDING" => 2,
					"link"     => array(
						"type"         => "popup",
						"title"        => "Link",
						"icon"         => "link_popup.gif",
						"script"       => "browse_links.php?mode=wizard",
						"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
					)
				)
			)
		),
		"category" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_books.category",		
			"config" => Array (
				"type" => "select",	
				"foreign_table" => "tx_mwmybooks_category",	
				"foreign_table_where" => "ORDER BY tx_mwmybooks_category.uid",	
				"size" => 6,	
				"minitems" => 0,
				"maxitems" => 1,	
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
							"table"=>"tx_mwmybooks_category",
							"pid" => "###CURRENT_PID###",
							"setValue" => "prepend"
						),
						"script" => "wizard_add.php",
					),
					"edit" => Array(
						"type" => "popup",
						"title" => "Edit",
						"script" => "wizard_edit.php",
						"popup_onlyOpenIfSelected" => 1,
						"icon" => "edit2.gif",
						"JSopenParams" => "height=350,width=580,status=0,menubar=0,scrollbars=1",
					),
				),
			)
		),
		"martinreiter" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_books.martinreiter",		
			"config" => Array (
				"type" => "check",
			)
		),
		"available" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_books.available",		
			"config" => Array (
				"type" => "check",
				"default" => 1,
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "hidden;;1;;1-1-1, title;;;;2-2-2, author;;;;3-3-3, isbn, price, shortdescription, description;;;richtext[cut|copy|paste|bold|italic|underline|orderedlist|unorderedlist|link]:rte_transform[mode=ts_css|imgpath=uploads/tx_mwmybooks/rte/], cover, link, category, martinreiter, available")
	),
	"palettes" => array (
		"1" => array("showitem" => "starttime, endtime")
	)
);



$TCA["tx_mwmybooks_category"] = array (
	"ctrl" => $TCA["tx_mwmybooks_category"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "hidden,name,description"
	),
	"feInterface" => $TCA["tx_mwmybooks_category"]["feInterface"],
	"columns" => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"name" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_category.name",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"description" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:mw_mybooks/locallang_db.xml:tx_mwmybooks_category.description",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "hidden;;1;;1-1-1, name, description;;;richtext[cut|copy|paste|bold|italic|underline|orderedlist|unorderedlist|link]:rte_transform[mode=ts_css|imgpath=uploads/tx_mwmybooks/rte/]")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);
?>