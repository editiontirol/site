<?php

########################################################################
# Extension Manager/Repository config file for ext: "rtehtmlarea"
#
# Auto generated 06-04-2006 13:46
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'htmlArea RTE',
	'description' => 'Rich Text Editor based on the open source htmlArea editor.',
	'category' => 'be',
	'shy' => 0,
	'dependencies' => 'cms',
	'conflicts' => 'rte_conf',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod1,mod2,mod3,mod4,mod5,mod6',
	'state' => 'stable',
	'internal' => 0,
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author' => 'Stanislas Rolland',
	'author_email' => 'stanislas.rolland@fructifor.ca',
	'author_company' => 'Fructifor Inc.',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '1.3.7',
	'_md5_values_when_last_written' => 'a:222:{s:9:"ChangeLog";s:4:"241b";s:29:"class.tx_rtehtmlarea_base.php";s:4:"15ba";s:21:"ext_conf_template.txt";s:4:"5006";s:12:"ext_icon.gif";s:4:"2f41";s:17:"ext_localconf.php";s:4:"bc2b";s:14:"ext_tables.php";s:4:"3d3e";s:14:"ext_tables.sql";s:4:"cc72";s:13:"locallang.xml";s:4:"e79e";s:16:"locallang_db.xml";s:4:"a2a2";s:7:"tca.php";s:4:"3756";s:14:"doc/manual.sxw";s:4:"2037";s:32:"pi1/class.tx_rtehtmlarea_pi1.php";s:4:"9829";s:17:"pi1/locallang.xml";s:4:"2e58";s:13:"mod1/conf.php";s:4:"14fe";s:14:"mod1/popup.php";s:4:"3fd5";s:16:"mod2/acronym.php";s:4:"e6f9";s:14:"mod2/clear.gif";s:4:"cc11";s:13:"mod2/conf.php";s:4:"f442";s:18:"mod2/locallang.xml";s:4:"7dee";s:21:"mod3/browse_links.php";s:4:"524f";s:14:"mod3/clear.gif";s:4:"cc11";s:13:"mod3/conf.php";s:4:"2f07";s:18:"mod3/locallang.xml";s:4:"4393";s:14:"mod4/clear.gif";s:4:"cc11";s:13:"mod4/conf.php";s:4:"0c60";s:18:"mod4/locallang.xml";s:4:"8b55";s:21:"mod4/select_image.php";s:4:"bc2f";s:14:"mod5/clear.gif";s:4:"cc11";s:13:"mod5/conf.php";s:4:"b639";s:18:"mod5/locallang.xml";s:4:"7a78";s:13:"mod5/user.php";s:4:"4939";s:40:"mod6/class.tx_rtehtmlarea_parse_html.php";s:4:"26d1";s:13:"mod6/conf.php";s:4:"2b05";s:19:"mod6/parse_html.php";s:4:"5ded";s:29:"res/advanced/pageTSConfig.txt";s:4:"eb53";s:29:"res/advanced/userTSConfig.txt";s:4:"a72e";s:25:"res/demo/pageTSConfig.txt";s:4:"52d3";s:25:"res/demo/userTSConfig.txt";s:4:"54a0";s:26:"res/image/pageTSConfig.txt";s:4:"624e";s:25:"res/proc/pageTSConfig.txt";s:4:"6802";s:28:"res/typical/pageTSConfig.txt";s:4:"4a27";s:28:"res/typical/userTSConfig.txt";s:4:"6015";s:29:"htmlarea/HTMLAREA_LICENSE.txt";s:4:"a10f";s:31:"htmlarea/htmlarea-compressed.js";s:4:"c96e";s:37:"htmlarea/htmlarea-gecko-compressed.js";s:4:"a49a";s:26:"htmlarea/htmlarea-gecko.js";s:4:"918c";s:34:"htmlarea/htmlarea-ie-compressed.js";s:4:"b901";s:23:"htmlarea/htmlarea-ie.js";s:4:"c469";s:20:"htmlarea/htmlarea.js";s:4:"c22e";s:30:"htmlarea/locallang_dialogs.xml";s:4:"51f8";s:26:"htmlarea/locallang_msg.xml";s:4:"7b6f";s:31:"htmlarea/locallang_tooltips.xml";s:4:"ffff";s:31:"htmlarea/popupwin-compressed.js";s:4:"0b83";s:20:"htmlarea/popupwin.js";s:4:"9bd8";s:46:"htmlarea/plugins/Acronym/acronym-compressed.js";s:4:"1aa1";s:35:"htmlarea/plugins/Acronym/acronym.js";s:4:"507d";s:38:"htmlarea/plugins/Acronym/locallang.xml";s:4:"7221";s:57:"htmlarea/plugins/CharacterMap/character-map-compressed.js";s:4:"3a05";s:46:"htmlarea/plugins/CharacterMap/character-map.js";s:4:"7f00";s:43:"htmlarea/plugins/CharacterMap/locallang.xml";s:4:"8d28";s:58:"htmlarea/plugins/CharacterMap/popups/select_character.html";s:4:"2cd1";s:55:"htmlarea/plugins/ContextMenu/context-menu-compressed.js";s:4:"64ac";s:44:"htmlarea/plugins/ContextMenu/context-menu.js";s:4:"4ab0";s:42:"htmlarea/plugins/ContextMenu/locallang.xml";s:4:"59f5";s:52:"htmlarea/plugins/DynamicCSS/dynamiccss-compressed.js";s:4:"647f";s:42:"htmlarea/plugins/DynamicCSS/dynamiccss.css";s:4:"cbce";s:41:"htmlarea/plugins/DynamicCSS/dynamiccss.js";s:4:"ad38";s:41:"htmlarea/plugins/DynamicCSS/locallang.xml";s:4:"b6bf";s:52:"htmlarea/plugins/DynamicCSS/img/red_arrow_bullet.gif";s:4:"82d6";s:55:"htmlarea/plugins/FindReplace/find-replace-compressed.js";s:4:"6a25";s:44:"htmlarea/plugins/FindReplace/find-replace.js";s:4:"d5ac";s:41:"htmlarea/plugins/FindReplace/fr_engine.js";s:4:"3482";s:42:"htmlarea/plugins/FindReplace/locallang.xml";s:4:"f836";s:53:"htmlarea/plugins/FindReplace/popups/find_replace.html";s:4:"48ca";s:50:"htmlarea/plugins/InlineCSS/inlinecss-compressed.js";s:4:"39ed";s:39:"htmlarea/plugins/InlineCSS/inlinecss.js";s:4:"0d6a";s:40:"htmlarea/plugins/InlineCSS/locallang.xml";s:4:"60a1";s:57:"htmlarea/plugins/InsertSmiley/insert-smiley-compressed.js";s:4:"c300";s:46:"htmlarea/plugins/InsertSmiley/insert-smiley.js";s:4:"a667";s:43:"htmlarea/plugins/InsertSmiley/locallang.xml";s:4:"ed64";s:54:"htmlarea/plugins/InsertSmiley/popups/insertsmiley.html";s:4:"2a95";s:46:"htmlarea/plugins/InsertSmiley/smileys/0001.gif";s:4:"4aff";s:46:"htmlarea/plugins/InsertSmiley/smileys/0002.gif";s:4:"02c4";s:46:"htmlarea/plugins/InsertSmiley/smileys/0003.gif";s:4:"834f";s:46:"htmlarea/plugins/InsertSmiley/smileys/0004.gif";s:4:"fb6a";s:46:"htmlarea/plugins/InsertSmiley/smileys/0005.gif";s:4:"2a48";s:46:"htmlarea/plugins/InsertSmiley/smileys/0006.gif";s:4:"f970";s:46:"htmlarea/plugins/InsertSmiley/smileys/0007.gif";s:4:"97ee";s:46:"htmlarea/plugins/InsertSmiley/smileys/0008.gif";s:4:"10a6";s:46:"htmlarea/plugins/InsertSmiley/smileys/0009.gif";s:4:"1907";s:46:"htmlarea/plugins/InsertSmiley/smileys/0010.gif";s:4:"9ee6";s:46:"htmlarea/plugins/InsertSmiley/smileys/0011.gif";s:4:"ae73";s:46:"htmlarea/plugins/InsertSmiley/smileys/0012.gif";s:4:"f058";s:46:"htmlarea/plugins/InsertSmiley/smileys/0013.gif";s:4:"3ed8";s:46:"htmlarea/plugins/InsertSmiley/smileys/0014.gif";s:4:"a948";s:46:"htmlarea/plugins/InsertSmiley/smileys/0015.gif";s:4:"218d";s:46:"htmlarea/plugins/InsertSmiley/smileys/0016.gif";s:4:"3539";s:46:"htmlarea/plugins/InsertSmiley/smileys/0017.gif";s:4:"ee2e";s:46:"htmlarea/plugins/InsertSmiley/smileys/0018.gif";s:4:"8c66";s:46:"htmlarea/plugins/InsertSmiley/smileys/0019.gif";s:4:"ac36";s:46:"htmlarea/plugins/InsertSmiley/smileys/0020.gif";s:4:"71ef";s:39:"htmlarea/plugins/QuickTag/locallang.xml";s:4:"2f53";s:49:"htmlarea/plugins/QuickTag/quick-tag-compressed.js";s:4:"d8e3";s:38:"htmlarea/plugins/QuickTag/quick-tag.js";s:4:"d0a4";s:36:"htmlarea/plugins/QuickTag/tag-lib.js";s:4:"4b7d";s:46:"htmlarea/plugins/QuickTag/popups/quicktag.html";s:4:"297f";s:43:"htmlarea/plugins/RemoveFormat/locallang.xml";s:4:"aa85";s:57:"htmlarea/plugins/RemoveFormat/remove-format-compressed.js";s:4:"7282";s:46:"htmlarea/plugins/RemoveFormat/remove-format.js";s:4:"1703";s:54:"htmlarea/plugins/RemoveFormat/popups/removeformat.html";s:4:"6421";s:42:"htmlarea/plugins/SelectColor/locallang.xml";s:4:"9f9e";s:55:"htmlarea/plugins/SelectColor/select-color-compressed.js";s:4:"79a3";s:44:"htmlarea/plugins/SelectColor/select-color.js";s:4:"a3ac";s:43:"htmlarea/plugins/SpellChecker/locallang.xml";s:4:"20d8";s:51:"htmlarea/plugins/SpellChecker/spell-check-logic.php";s:4:"b044";s:51:"htmlarea/plugins/SpellChecker/spell-check-style.css";s:4:"82bd";s:49:"htmlarea/plugins/SpellChecker/spell-check-ui.html";s:4:"18b2";s:47:"htmlarea/plugins/SpellChecker/spell-check-ui.js";s:4:"0ca3";s:57:"htmlarea/plugins/SpellChecker/spell-checker-compressed.js";s:4:"223f";s:46:"htmlarea/plugins/SpellChecker/spell-checker.js";s:4:"816b";s:67:"htmlarea/plugins/SpellChecker/popups/spell-check-ui-iso-8859-1.html";s:4:"3927";s:56:"htmlarea/plugins/SpellChecker/popups/spell-check-ui.html";s:4:"d31e";s:44:"htmlarea/plugins/TYPO3Browsers/locallang.xml";s:4:"89b8";s:58:"htmlarea/plugins/TYPO3Browsers/typo3browsers-compressed.js";s:4:"afcd";s:47:"htmlarea/plugins/TYPO3Browsers/typo3browsers.js";s:4:"fbd4";s:47:"htmlarea/plugins/TYPO3Browsers/img/download.gif";s:4:"f6d9";s:52:"htmlarea/plugins/TYPO3Browsers/img/external_link.gif";s:4:"9e48";s:63:"htmlarea/plugins/TYPO3Browsers/img/external_link_new_window.gif";s:4:"6e8d";s:52:"htmlarea/plugins/TYPO3Browsers/img/internal_link.gif";s:4:"12b9";s:63:"htmlarea/plugins/TYPO3Browsers/img/internal_link_new_window.gif";s:4:"402a";s:43:"htmlarea/plugins/TYPO3Browsers/img/mail.gif";s:4:"d5a2";s:46:"htmlarea/plugins/TYPO3HtmlParser/locallang.xml";s:4:"e98e";s:63:"htmlarea/plugins/TYPO3HtmlParser/typo3html-parser-compressed.js";s:4:"e421";s:52:"htmlarea/plugins/TYPO3HtmlParser/typo3html-parser.js";s:4:"1432";s:46:"htmlarea/plugins/TableOperations/locallang.xml";s:4:"8377";s:63:"htmlarea/plugins/TableOperations/table-operations-compressed.js";s:4:"d835";s:52:"htmlarea/plugins/TableOperations/table-operations.js";s:4:"8ba3";s:43:"htmlarea/plugins/UserElements/locallang.xml";s:4:"33f9";s:57:"htmlarea/plugins/UserElements/user-elements-compressed.js";s:4:"dd4d";s:46:"htmlarea/plugins/UserElements/user-elements.js";s:4:"d394";s:26:"htmlarea/popups/about.html";s:4:"936e";s:26:"htmlarea/popups/blank.html";s:4:"e697";s:32:"htmlarea/popups/editor_help.html";s:4:"398a";s:33:"htmlarea/popups/insert_image.html";s:4:"9719";s:33:"htmlarea/popups/insert_table.html";s:4:"8111";s:25:"htmlarea/popups/link.html";s:4:"72e2";s:24:"htmlarea/popups/popup.js";s:4:"cc37";s:33:"htmlarea/popups/select_color.html";s:4:"d387";s:50:"htmlarea/skins/default/htmlarea-edited-content.css";s:4:"6bd1";s:35:"htmlarea/skins/default/htmlarea.css";s:4:"e082";s:42:"htmlarea/skins/default/images/ed_about.gif";s:4:"2763";s:49:"htmlarea/skins/default/images/ed_align_center.gif";s:4:"419a";s:50:"htmlarea/skins/default/images/ed_align_justify.gif";s:4:"9c31";s:47:"htmlarea/skins/default/images/ed_align_left.gif";s:4:"9c22";s:48:"htmlarea/skins/default/images/ed_align_right.gif";s:4:"9386";s:42:"htmlarea/skins/default/images/ed_blank.gif";s:4:"0208";s:45:"htmlarea/skins/default/images/ed_color_bg.gif";s:4:"c6e2";s:45:"htmlarea/skins/default/images/ed_color_fg.gif";s:4:"5d7f";s:41:"htmlarea/skins/default/images/ed_copy.gif";s:4:"4f55";s:43:"htmlarea/skins/default/images/ed_custom.gif";s:4:"e7b2";s:40:"htmlarea/skins/default/images/ed_cut.gif";s:4:"1b00";s:43:"htmlarea/skins/default/images/ed_delete.gif";s:4:"926b";s:48:"htmlarea/skins/default/images/ed_format_bold.gif";s:4:"f4f6";s:50:"htmlarea/skins/default/images/ed_format_italic.gif";s:4:"a800";s:50:"htmlarea/skins/default/images/ed_format_strike.gif";s:4:"3aa0";s:47:"htmlarea/skins/default/images/ed_format_sub.gif";s:4:"a840";s:47:"htmlarea/skins/default/images/ed_format_sup.gif";s:4:"cad7";s:53:"htmlarea/skins/default/images/ed_format_underline.gif";s:4:"505a";s:41:"htmlarea/skins/default/images/ed_help.gif";s:4:"e7fc";s:39:"htmlarea/skins/default/images/ed_hr.gif";s:4:"ff70";s:41:"htmlarea/skins/default/images/ed_html.gif";s:4:"fa6e";s:42:"htmlarea/skins/default/images/ed_image.gif";s:4:"f91c";s:48:"htmlarea/skins/default/images/ed_indent_less.gif";s:4:"8503";s:48:"htmlarea/skins/default/images/ed_indent_more.gif";s:4:"3835";s:50:"htmlarea/skins/default/images/ed_left_to_right.gif";s:4:"a0f9";s:41:"htmlarea/skins/default/images/ed_link.gif";s:4:"44fe";s:48:"htmlarea/skins/default/images/ed_list_bullet.gif";s:4:"236b";s:45:"htmlarea/skins/default/images/ed_list_num.gif";s:4:"48d3";s:42:"htmlarea/skins/default/images/ed_paste.gif";s:4:"fbd2";s:41:"htmlarea/skins/default/images/ed_redo.gif";s:4:"e9e8";s:50:"htmlarea/skins/default/images/ed_right_to_left.gif";s:4:"5149";s:41:"htmlarea/skins/default/images/ed_save.gif";s:4:"07ad";s:47:"htmlarea/skins/default/images/ed_splitblock.gif";s:4:"503e";s:45:"htmlarea/skins/default/images/ed_splitcel.gif";s:4:"2c04";s:41:"htmlarea/skins/default/images/ed_undo.gif";s:4:"b9ba";s:43:"htmlarea/skins/default/images/ed_unlink.gif";s:4:"a416";s:53:"htmlarea/skins/default/images/fullscreen_maximize.gif";s:4:"2118";s:53:"htmlarea/skins/default/images/fullscreen_minimize.gif";s:4:"91d6";s:46:"htmlarea/skins/default/images/insert_table.gif";s:4:"bf88";s:52:"htmlarea/skins/default/images/Acronym/ed_acronym.gif";s:4:"a2c5";s:57:"htmlarea/skins/default/images/CharacterMap/ed_charmap.gif";s:4:"5aa6";s:53:"htmlarea/skins/default/images/FindReplace/ed_find.gif";s:4:"d01c";s:56:"htmlarea/skins/default/images/InsertSmiley/ed_smiley.gif";s:4:"810e";s:54:"htmlarea/skins/default/images/QuickTag/ed_quicktag.gif";s:4:"b783";s:55:"htmlarea/skins/default/images/RemoveFormat/ed_clean.gif";s:4:"c936";s:58:"htmlarea/skins/default/images/SelectColor/CO-forecolor.gif";s:4:"5d7f";s:60:"htmlarea/skins/default/images/SelectColor/CO-hilitecolor.gif";s:4:"c6e2";s:58:"htmlarea/skins/default/images/SpellChecker/spell-check.gif";s:4:"15cf";s:56:"htmlarea/skins/default/images/TYPO3Browsers/ed_image.gif";s:4:"f91c";s:55:"htmlarea/skins/default/images/TYPO3Browsers/ed_link.gif";s:4:"9a55";s:61:"htmlarea/skins/default/images/TableOperations/cell-delete.gif";s:4:"031c";s:67:"htmlarea/skins/default/images/TableOperations/cell-insert-after.gif";s:4:"4d36";s:68:"htmlarea/skins/default/images/TableOperations/cell-insert-before.gif";s:4:"9ead";s:60:"htmlarea/skins/default/images/TableOperations/cell-merge.gif";s:4:"a2d2";s:59:"htmlarea/skins/default/images/TableOperations/cell-prop.gif";s:4:"bf67";s:60:"htmlarea/skins/default/images/TableOperations/cell-split.gif";s:4:"d87c";s:60:"htmlarea/skins/default/images/TableOperations/col-delete.gif";s:4:"b0f6";s:66:"htmlarea/skins/default/images/TableOperations/col-insert-after.gif";s:4:"f5f7";s:67:"htmlarea/skins/default/images/TableOperations/col-insert-before.gif";s:4:"5711";s:59:"htmlarea/skins/default/images/TableOperations/col-split.gif";s:4:"eacc";s:62:"htmlarea/skins/default/images/TableOperations/insert_table.gif";s:4:"c1db";s:60:"htmlarea/skins/default/images/TableOperations/row-delete.gif";s:4:"7cdb";s:66:"htmlarea/skins/default/images/TableOperations/row-insert-above.gif";s:4:"d034";s:66:"htmlarea/skins/default/images/TableOperations/row-insert-under.gif";s:4:"59f9";s:58:"htmlarea/skins/default/images/TableOperations/row-prop.gif";s:4:"b11e";s:59:"htmlarea/skins/default/images/TableOperations/row-split.gif";s:4:"a712";s:60:"htmlarea/skins/default/images/TableOperations/table-prop.gif";s:4:"2a21";s:64:"htmlarea/skins/default/images/TableOperations/toggle-borders.gif";s:4:"ae22";s:54:"htmlarea/skins/default/images/UserElements/ed_user.gif";s:4:"a294";s:59:"htmlarea/skins/default/images/TYPO3ViewHelp/module_help.gif";s:4:"a500";s:32:"pi2/class.tx_rtehtmlarea_pi2.php";s:4:"361f";s:17:"pi2/locallang.xml";s:4:"a0a7";}',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'php' => '4.1.0-',
			'typo3' => '4.0.0-',
		),
		'conflicts' => array(
			'rte_conf' => '',
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
);

?>