<?php

########################################################################
# Extension Manager/Repository config file for ext: "w4x_backup"
#
# Auto generated 14-02-2007 11:31
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Full Backup',
	'description' => 'Full Backup: backup your database, fileadmin, ulploads and typo3conf directories. For Linux and Windows. (Windows require 7-zip installed).',
	'category' => 'module',
	'shy' => 0,
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod1',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => 'typo3temp/w4x',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => 'L',
	'author' => 'Carlos A. Chiari O.',
	'author_email' => 'ccho@dimension-e.net',
	'author_company' => 'Dimension-E',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.9.1',
	'constraints' => array(
		'depends' => array(
			'php' => '3.0.0-',
			'typo3' => '3.5.0-',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);

?>