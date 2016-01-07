<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_mwmybooks_books=1
');
t3lib_extMgm::addPageTSConfig('

	# ***************************************************************************************
	# CONFIGURATION of RTE in table "tx_mwmybooks_books", field "description"
	# ***************************************************************************************
RTE.config.tx_mwmybooks_books.description {
  proc.exitHTMLparser_db=1
  proc.exitHTMLparser_db {
      
    allowTags = b, strong, i, em, u, ol, li, ul, a, h1, h2, h3, h4, h5, h6, pre
      
    tags.div.remap = P
  }
}
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_mwmybooks_category=1
');

  ## Extending TypoScript from static template uid=43 to set up userdefined tag:
t3lib_extMgm::addTypoScript($_EXTKEY,'editorcfg','
	tt_content.CSS_editor.ch.tx_mwmybooks_pi1 = < plugin.tx_mwmybooks_pi1.CSS_editor
',43);


t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_mwmybooks_pi1.php','_pi1','list_type',1);


t3lib_extMgm::addTypoScript($_EXTKEY,'setup','
	tt_content.shortcut.20.0.conf.tx_mwmybooks_books = < plugin.'.t3lib_extMgm::getCN($_EXTKEY).'_pi1
	tt_content.shortcut.20.0.conf.tx_mwmybooks_books.CMD = singleView
',43);


  ## Extending TypoScript from static template uid=43 to set up userdefined tag:
t3lib_extMgm::addTypoScript($_EXTKEY,'editorcfg','
	tt_content.CSS_editor.ch.tx_mwmybooks_pi2 = < plugin.tx_mwmybooks_pi2.CSS_editor
',43);


t3lib_extMgm::addPItoST43($_EXTKEY,'pi2/class.tx_mwmybooks_pi2.php','_pi2','list_type',1);


t3lib_extMgm::addTypoScript($_EXTKEY,'setup','
	tt_content.shortcut.20.0.conf.tx_mwmybooks_books = < plugin.'.t3lib_extMgm::getCN($_EXTKEY).'_pi2
	tt_content.shortcut.20.0.conf.tx_mwmybooks_books.CMD = singleView
',43);


  ## Extending TypoScript from static template uid=43 to set up userdefined tag:
t3lib_extMgm::addTypoScript($_EXTKEY,'editorcfg','
	tt_content.CSS_editor.ch.tx_mwmybooks_pi3 = < plugin.tx_mwmybooks_pi3.CSS_editor
',43);


t3lib_extMgm::addPItoST43($_EXTKEY,'pi3/class.tx_mwmybooks_pi3.php','_pi3','list_type',1);


t3lib_extMgm::addTypoScript($_EXTKEY,'setup','
	tt_content.shortcut.20.0.conf.tx_mwmybooks_category = < plugin.'.t3lib_extMgm::getCN($_EXTKEY).'_pi3
	tt_content.shortcut.20.0.conf.tx_mwmybooks_category.CMD = singleView
',43);
?>