<?php
/***************************************************************
*  Copyright notice
*
*  (c) 1999-2005 Kasper Skaarhoj (kasperYYYY@typo3.com)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Page navigation tree for the Web module
 *
 * $Id: alt_db_navframe.php 1483 2006-05-20 16:49:46Z baschny $
 * Revised for TYPO3 3.6 2/2003 by Kasper Skaarhoj
 * XHTML compliant
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   78: class localPageTree extends t3lib_browseTree
 *   88:     function localPageTree()
 *   99:     function wrapIcon($icon,&$row)
 *  142:     function wrapStop($str,$row)
 *  158:     function wrapTitle($title,$row,$bank=0)
 *
 *
 *  192: class SC_alt_db_navframe
 *  210:     function init()
 *  313:     function main()
 *  387:     function printContent()
 *
 *              SECTION: Temporary DB mounts
 *  415:     function initializeTemporaryDBmount()
 *  449:     function settingTemporaryMountPoint($pageId)
 *
 * TOTAL FUNCTIONS: 9
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */


$BACK_PATH='';
require('init.php');
require('template.php');
require_once(PATH_t3lib.'class.t3lib_browsetree.php');



/**
 * Extension class for the t3lib_browsetree class, specially made for browsing pages in the Web module
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage core
 * @see class t3lib_browseTree
 */
class localPageTree extends t3lib_browseTree {

	var $ext_showPageId;
	var $ext_IconMode;

	/**
	 * Calls init functions
	 *
	 * @return	void
	 */
	function localPageTree() {
		$this->init();
	}

	/**
	 * Wrapping icon in browse tree
	 *
	 * @param	string		Icon IMG code
	 * @param	array		Data row for element.
	 * @return	string		Page icon
	 */
	function wrapIcon($icon,&$row)	{
			// If the record is locked, present a warning sign.
		if ($lockInfo=t3lib_BEfunc::isRecordLocked('pages',$row['uid']))	{
			$aOnClick = 'alert('.$GLOBALS['LANG']->JScharCode($lockInfo['msg']).');return false;';
			$lockIcon='<a href="#" onclick="'.htmlspecialchars($aOnClick).'">'.
				'<img'.t3lib_iconWorks::skinImg('','gfx/recordlock_warning3.gif','width="17" height="12"').' title="'.htmlspecialchars($lockInfo['msg']).'" alt="" />'.
				'</a>';
		} else $lockIcon = '';

			// Add title attribute to input icon tag
		$thePageIcon = $this->addTagAttributes($icon, $this->titleAttrib.'="'.$this->getTitleAttrib($row).'"');

			// Wrap icon in click-menu link.
		if (!$this->ext_IconMode)	{
			$thePageIcon = $GLOBALS['TBE_TEMPLATE']->wrapClickMenuOnIcon($thePageIcon,'pages',$row['uid'],0,'&bank='.$this->bank);
		} elseif (!strcmp($this->ext_IconMode,'titlelink'))	{
			$aOnClick = 'return jumpTo(\''.$this->getJumpToParam($row).'\',this,\''.$this->treeName.'\');';
			$thePageIcon='<a href="#" onclick="'.htmlspecialchars($aOnClick).'">'.$thePageIcon.'</a>';
		}

			// Wrap icon in a drag/drop span.
		$spanOnDrag = htmlspecialchars('return dragElement("'.$row['uid'].'")');
		$spanOnDrop = htmlspecialchars('return dropElement("'.$row['uid'].'")');
		$dragDropIcon = '<span id="dragIconID_'.$row['uid'].'" ondragstart="'.$spanOnDrag.'" onmousedown="'.$spanOnDrag.'" onmouseup="'.$spanOnDrop.'">'.$thePageIcon.'</span>';

			// Add Page ID:
		if ($this->ext_showPageId)	{
			$pageIdStr = '['.$row['uid'].']&nbsp;';
		} else {
			$pageIdStr = '';
		}

		return $dragDropIcon.$lockIcon.$pageIdStr;
	}

	/**
	 * Adds a red "+" to the input string, $str, if the field "php_tree_stop" in the $row (pages) is set
	 *
	 * @param	string		Input string, like a page title for the tree
	 * @param	array		record row with "php_tree_stop" field
	 * @return	string		Modified string
	 * @access private
	 */
	function wrapStop($str,$row)	{
		if ($row['php_tree_stop'])	{
			$str.='<a href="'.htmlspecialchars(t3lib_div::linkThisScript(array('setTempDBmount' => $row['uid']))).'" class="typo3-red">+</a> ';
		}
		return $str;
	}

	/**
	 * Wrapping $title in a-tags.
	 *
	 * @param	string		Title string
	 * @param	string		Item record
	 * @param	integer		Bank pointer (which mount point number)
	 * @return	string
	 * @access private
	 */
	function wrapTitle($title,$row,$bank=0)	{
		$aOnClick = 'return jumpTo(\''.$this->getJumpToParam($row).'\',this,\''.$this->domIdPrefix.$this->getId($row).'\','.$bank.');';
		$CSM = '';
		if ($GLOBALS['TYPO3_CONF_VARS']['BE']['useOnContextMenuHandler'])	{
			$CSM = ' oncontextmenu="'.htmlspecialchars($GLOBALS['TBE_TEMPLATE']->wrapClickMenuOnIcon('','pages',$row['uid'],0,'&bank='.$this->bank,'',TRUE)).'"';
		}
		$thePageTitle='<a href="#" onclick="'.htmlspecialchars($aOnClick).'"'.$CSM.'>'.$title.'</a>';

			// Wrap title in a drag/drop span.
		$spanOnDrag = htmlspecialchars('return dragElement("'.$row['uid'].'")');
		$spanOnDrop = htmlspecialchars('return dropElement("'.$row['uid'].'")');
		$dragDropTitle = '<span id="dragTitleID_'.$row['uid'].'" ondragstart="'.$spanOnDrag.'" onmousedown="'.$spanOnDrag.'" onmouseup="'.$spanOnDrop.'">'.$thePageTitle.'</span>';
		return $dragDropTitle;
	}
}












/**
 * Main script class for the page tree navigation frame
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage core
 */
class SC_alt_db_navframe {

		// Internal:
	var $content;
	var $pagetree;
	var $doc;
	var $active_tempMountPoint = 0;		// Temporary mount point (record), if any

		// Internal, static: GPvar:
	var $currentSubScript;
	var $cMR;
	var $setTempDBmount;			// If not '' (blank) then it will clear (0) or set (>0) Temporary DB mount.

	/**
	 * Initialiation of the class
	 *
	 * @return	void
	 */
	function init()	{
		global $BE_USER,$BACK_PATH;

			// Setting GPvars:
		$this->currentSubScript = t3lib_div::_GP('currentSubScript');
		$this->cMR = t3lib_div::_GP('cMR');
		$this->setTempDBmount = t3lib_div::_GP('setTempDBmount');

			// Create page tree object:
		$this->pagetree = t3lib_div::makeInstance('localPageTree');
		$this->pagetree->ext_IconMode = $BE_USER->getTSConfigVal('options.pageTree.disableIconLinkToContextmenu');
		$this->pagetree->ext_showPageId = $BE_USER->getTSConfigVal('options.pageTree.showPageIdWithTitle');
		$this->pagetree->thisScript = 'alt_db_navframe.php';
		$this->pagetree->addField('alias');
		$this->pagetree->addField('shortcut');
		$this->pagetree->addField('shortcut_mode');
		$this->pagetree->addField('mount_pid');
		$this->pagetree->addField('mount_pid_ol');
		$this->pagetree->addField('nav_hide');
		$this->pagetree->addField('url');

#		$this->settingTemporaryMountPoint(11);
			// Temporary DB mounts:
		$this->initializeTemporaryDBmount();

			// Setting highlight mode:
		$this->doHighlight = !$BE_USER->getTSConfigVal('options.pageTree.disableTitleHighlight') && $BE_USER->workspace===0;

			// Create template object:
		$this->doc = t3lib_div::makeInstance('template');
		$this->doc->docType='xhtml_trans';

			// Setting backPath
		$this->doc->backPath = $BACK_PATH;

			// Setting JavaScript for menu.
		$this->doc->JScode=$this->doc->wrapScriptTags(
	($this->currentSubScript?'top.currentSubScript=unescape("'.rawurlencode($this->currentSubScript).'");':'').'

		// Function, loading the list frame from navigation tree:
	function jumpTo(id,linkObj,highLightID,bank)	{	//
		var theUrl = top.TS.PATH_typo3+top.currentSubScript+"?id="+id;
		top.fsMod.currentBank = bank;

		if (top.condensedMode)	{
			top.content.location.href=theUrl;
		} else {
			parent.list_frame.location.href=theUrl;
		}

		'.($this->doHighlight?'hilight_row("web",highLightID+"_"+bank);':'').'

		'.(!$GLOBALS['CLIENT']['FORMSTYLE'] ? '' : 'if (linkObj) {linkObj.blur();}').'
		return false;
	}

		// Call this function, refresh_nav(), from another script in the backend if you want to refresh the navigation frame (eg. after having changed a page title or moved pages etc.)
		// See t3lib_BEfunc::getSetUpdateSignal()
	function refresh_nav()	{	//
		window.setTimeout("_refresh_nav();",0);
	}
	function _refresh_nav()	{	//
		window.location.href="'.$this->pagetree->thisScript.'?unique='.time().'";
	}

		// Highlighting rows in the page tree:
	function hilight_row(frameSetModule,highLightID) {	//

			// Remove old:
		theObj = document.getElementById(top.fsMod.navFrameHighlightedID[frameSetModule]);
		if (theObj)	{
			theObj.className = "";
		}

			// Set new:
		top.fsMod.navFrameHighlightedID[frameSetModule] = highLightID;
		theObj = document.getElementById(highLightID);
		if (theObj)	{
			theObj.className = "navFrameHL";
		}
	}

	'.($this->cMR?"jumpTo(top.fsMod.recentIds['web'],'');":'').';
		');

			// Click menu code is added:
		$CMparts=$this->doc->getContextMenuCode();
		$this->doc->bodyTagAdditions = $CMparts[1];
		$this->doc->JScode.= $CMparts[0];
		$this->doc->postCode.= $CMparts[2];

			// Drag and Drop code is added:
		$DDparts=$this->doc->getDragDropCode('pages');
		// ignore the $DDparts[1] for now
		$this->doc->JScode.= $DDparts[0];
		$this->doc->postCode.= $DDparts[2];
	}

	/**
	 * Main function, rendering the browsable page tree
	 *
	 * @return	void
	 */
	function main()	{
		global $LANG,$CLIENT;

			// Produce browse-tree:
		$tree = $this->pagetree->getBrowsableTree();

			// Start page:
		$this->content = '';
		$this->content.= $this->doc->startPage('Page tree');

			// Outputting workspace info
		if ($GLOBALS['BE_USER']->workspace!==0 || $GLOBALS['BE_USER']->getTSConfigVal('options.pageTree.onlineWorkspaceInfo'))	{
			switch($GLOBALS['BE_USER']->workspace)	{
				case 0:
					$wsTitle = '&nbsp;'.$this->doc->icons(2).'['.$LANG->sL('LLL:EXT:lang/locallang_misc.xml:shortcut_onlineWS',1).']';
				break;
				case -1:
					$wsTitle = '['.$LANG->sL('LLL:EXT:lang/locallang_misc.xml:shortcut_offlineWS',1).']';
				break;
				default:
					$wsTitle = '['.$GLOBALS['BE_USER']->workspace.'] '.htmlspecialchars($GLOBALS['BE_USER']->workspaceRec['title']);
				break;
			}

			$this->content.= '
				<div class="bgColor4 workspace-info">'.
					'<a href="'.htmlspecialchars('mod/user/ws/index.php').'" target="content">'.
					'<img'.t3lib_iconWorks::skinImg('','gfx/i/sys_workspace.png','width="18" height="16"').' align="top" alt="" />'.
					'</a>'.$wsTitle.'
				</div>
			';
		}

			// Outputting Temporary DB mount notice:
		if ($this->active_tempMountPoint)	{
			$this->content.= '
				<div class="bgColor4 c-notice">
					<img'.t3lib_iconWorks::skinImg('','gfx/icon_note.gif','width="18" height="16"').' align="top" alt="" />'.
					'<a href="'.htmlspecialchars(t3lib_div::linkThisScript(array('setTempDBmount' => 0))).'">'.
					$LANG->sl('LLL:EXT:lang/locallang_core.php:labels.temporaryDBmount',1).
					'</a><br/>
					'.$LANG->sl('LLL:EXT:lang/locallang_core.php:labels.path',1).': <span title="'.htmlspecialchars($this->active_tempMountPoint['_thePathFull']).'">'.htmlspecialchars(t3lib_div::fixed_lgd_cs($this->active_tempMountPoint['_thePath'],-50)).'</span>
				</div>
			';
		}

			// Outputting page tree:
		$this->content.= $tree;

			// Outputting refresh-link
		$refreshUrl = t3lib_div::getIndpEnv('REQUEST_URI');
		$this->content.= '
			<p class="c-refresh">
				<a href="'.htmlspecialchars($refreshUrl).'">'.
				'<img'.t3lib_iconWorks::skinImg('','gfx/refresh_n.gif','width="14" height="14"').' title="'.$LANG->sL('LLL:EXT:lang/locallang_core.php:labels.refresh',1).'" alt="" />'.
				'</a><a href="'.htmlspecialchars($refreshUrl).'">'.
				$LANG->sL('LLL:EXT:lang/locallang_core.php:labels.refresh',1).'</a>
			</p>
			<br />';

			// CSH icon:
		$this->content.= t3lib_BEfunc::cshItem('xMOD_csh_corebe', 'pagetree', $GLOBALS['BACK_PATH']);

			// Adding highlight - JavaScript
		if ($this->doHighlight)	$this->content .=$this->doc->wrapScriptTags('
			hilight_row("",top.fsMod.navFrameHighlightedID["web"]);
		');
	}

	/**
	 * Outputting the accumulated content to screen
	 *
	 * @return	void
	 */
	function printContent()	{
		$this->content.= $this->doc->endPage();
		$this->content = $this->doc->insertStylesAndJS($this->content);
		echo $this->content;
	}












	/**********************************
	 *
	 * Temporary DB mounts
	 *
	 **********************************/

	/**
	 * Getting temporary DB mount
	 *
	 * @return	void
	 */
	function initializeTemporaryDBmount(){
		global $BE_USER;

			// Set/Cancel Temporary DB Mount:
		if (strlen($this->setTempDBmount))	{
			$set = t3lib_div::intInRange($this->setTempDBmount,0);
			if ($set>0 && $BE_USER->isInWebMount($set))	{	// Setting...:
				$this->settingTemporaryMountPoint($set);
			} else {	// Clear:
				$this->settingTemporaryMountPoint(0);
			}
		}

			// Getting temporary mount point ID:
		$temporaryMountPoint = intval($BE_USER->getSessionData('pageTree_temporaryMountPoint'));

			// If mount point ID existed and is within users real mount points, then set it temporarily:
		if ($temporaryMountPoint > 0 && $BE_USER->isInWebMount($temporaryMountPoint))	{
			if ($this->active_tempMountPoint = t3lib_BEfunc::readPageAccess($temporaryMountPoint, $BE_USER->getPagePermsClause(1))) {
				$this->pagetree->MOUNTS = array($temporaryMountPoint);
			}
			else {
				// Clear temporary mount point as we have no access to it any longer
				$this->settingTemporaryMountPoint(0);
			}
		}
	}

	/**
	 * Setting temporary page id as DB mount
	 *
	 * @param	integer		The page id to set as DB mount
	 * @return	void
	 */
	function settingTemporaryMountPoint($pageId)	{
		global $BE_USER;

			// Setting temporary mount point ID:
		$BE_USER->setAndSaveSessionData('pageTree_temporaryMountPoint',intval($pageId));
	}
}

// Include extension?
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/alt_db_navframe.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/alt_db_navframe.php']);
}












// Make instance:
$SOBE = t3lib_div::makeInstance('SC_alt_db_navframe');
$SOBE->init();
$SOBE->main();
$SOBE->printContent();

?>