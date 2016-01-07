<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007 Mathias Willburger <mathias@willburger.cc>
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
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Booklist' for the 'mw_mybooks' extension.
 *
 * @author	Mathias Willburger <mathias@willburger.cc>
 * @package	TYPO3
 * @subpackage	tx_mwmybooks
 */
class tx_mwmybooks_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_mwmybooks_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_mwmybooks_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'mw_mybooks';	// The extension key.
	var $pi_checkCHash = true;


	function pi_list_makelist_lokal($res,$tableParams='')	{
			// Make list table header:
		$tRows=array();
		$this->internal['currentRow']='';
		$tRows[] = $this->pi_list_header();

			// Make list table rows
		$c=0;
		while($this->internal['currentRow'] = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
			$tRows[] = $this->pi_list_row($c);
			$c++;
		}

		$out = '

		<!--
			Record list:
		-->
		<div'.$this->pi_classParam('listrow').'>
			<'.trim('table '.$tableParams).' width=100% style="font-size:11pt">
				'.implode('',$tRows).'
			</table>
		</div>';

		return $out;
	}

	
	/**
	 * Main method of your PlugIn
	 *
	 * @param	string		$content: The content of the PlugIn
	 * @param	array		$conf: The PlugIn Configuration
	 * @return	The content that should be displayed on the website
	 */
	function main($content,$conf)	{
		switch((string)$conf['CMD'])	{
			case 'singleView':
				list($t) = explode(':',$this->cObj->currentRecord);
				$this->internal['currentTable']=$t;
				$this->internal['currentRow']=$this->cObj->data;
				return $this->pi_wrapInBaseClass($this->singleView($content,$conf));
			break;
			default:
				if (strstr($this->cObj->currentRecord,'tt_content'))	{
					$conf['pidList'] = $this->cObj->data['pages'];
					$conf['recursive'] = $this->cObj->data['recursive'];
				}
				return $this->pi_wrapInBaseClass($this->listView($content,$conf));
			break;
		}
	}
	
	/**
	 * Shows a list of database entries
	 *
	 * @param	string		$content: content of the PlugIn
	 * @param	array		$conf: PlugIn Configuration
	 * @return	HTML list of table entries
	 */
	function listView($content,$conf)	{
		$this->conf=$conf;		// Setting the TypoScript passed to this function in $this->conf
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();		// Loading the LOCAL_LANG values
		
		$lConf = $this->conf['listView.'];	// Local settings for the listView function
	
		if ($this->piVars['showUid'])	{	// If a single element should be displayed:
			$this->internal['currentTable'] = 'tx_mwmybooks_books';
			$this->internal['currentRow'] = $this->pi_getRecord('tx_mwmybooks_books',$this->piVars['showUid']);
	
			$content = $this->singleView($content,$conf);
			return $content;
		} else {
			$items=array(
				'1'=> $this->pi_getLL('list_mode_1','Mode 1'),
				'2'=> $this->pi_getLL('list_mode_2','Mode 2'),
				'3'=> $this->pi_getLL('list_mode_3','Mode 3'),
			);
			if (!isset($this->piVars['pointer']))	$this->piVars['pointer']=0;
			if (!isset($this->piVars['mode']))	$this->piVars['mode']=1;
	
				// Initializing the query parameters:
			list($this->internal['orderBy'],$this->internal['descFlag']) = explode(':',$this->piVars['sort']);
			$this->internal['results_at_a_time']=t3lib_div::intInRange($lConf['results_at_a_time'],0,1000,20);		// Number of results to show in a listing.
			$this->internal['maxPages']=t3lib_div::intInRange($lConf['maxPages'],0,1000,2);;		// The maximum number of "pages" in the browse-box: "Page 1", "Page 2", etc.
			$this->internal['searchFieldList']='title,author,isbn,price,shortdescription,description';
			$this->internal['orderByList']='title,author';
	
				// Get number of records:
			$res = $this->pi_exec_query('tx_mwmybooks_books',1);
			list($this->internal['res_count']) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
	
				// Make listing query, pass query to SQL database:
				
			
			$allPidsList = $this->pi_getPidList($GLOBALS[TSFE]->tmpl->rootId,4);
			$addWhere = 'OR pid IN ('.$allPidsList.')';
			// gibt die Eintr„ge in zuf„lliger Reihenfolge aus
			
			// where klausel um nur bücher von martin reiter anzuzeigen
			if ($this->cObj->data['tx_mwmybooks_martinreiter'] == 1)
			{
				$martinreiter =" and martinreiter=1";
			}
			
			if ($this->cObj->data['tx_mwmybooks_category'] != 0)
			{
				$res = $this->pi_exec_query('tx_mwmybooks_books',0,$addWhere." and category=".$this->cObj->data['tx_mwmybooks_category'].$martinreiter,'','','','');
			} else {
				$res = $this->pi_exec_query('tx_mwmybooks_books',0,$addWhere.$martinreiter,'','','','');	
				
			}
			$this->internal['currentTable'] = 'tx_mwmybooks_books';
	
				// Put the whole list together:
			$fullTable='';	// Clear var;
		#	$fullTable.=t3lib_div::view_array($this->piVars);	// DEBUG: Output the content of $this->piVars for debug purposes. REMEMBER to comment out the IP-lock in the debug() function in t3lib/config_default.php if nothing happens when you un-comment this line!
	
				// Adds the mode selector.
		#	$fullTable.=$this->pi_list_modeSelector($items);

					// Adds the search box:
			$fullTable.=$this->pi_list_searchBox();

				// Adds the whole list table
			$fullTable.=$this->pi_list_makelist_lokal($res);
		
				// Adds the result browser:
			$fullTable.=$this->pi_list_browseresults();
	
				// Returns the content from the plugin.
			return $fullTable;
		}
	}
	/**
	 * Display a single item from the database
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	HTML of a single database entry
	 */
	function singleView($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		
		$image = "uploads/tx_mwmybooks/".$this->getFieldContent('cover');
	
			// This sets the title of the page for use in indexed search results:
		if ($this->internal['currentRow']['title'])	$GLOBALS['TSFE']->indexedDocTitle=$this->internal['currentRow']['title'];
	
	
		if ($this->getFieldContent('available') == 0)
		{
			$available ='<font style="color:red; font-size:12px;">NICHT MEHR LIEFERBAR - VERGRIFFEN</font>';
		}
		
		
		$content='<div'.$this->pi_classParam('singleView').'>
			<br/>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="250" rowspan="11"><div align="center"><img src="'.$image.'"/></center></td>
					<td><H2>'.$this->getFieldContent('title').'</H2>
						('.$this->getFieldContent('author').')
			</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>'.$this->getFieldContent('shortdescription').'</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><b>ISBN:</b> '.$this->getFieldContent('isbn').'</td>
				</tr>
				<tr>
					<td><b>Preis:</b> EURO '.$this->getFieldContent('price').'</td>
				</tr>
				<tr>
					<td>'.$available.'</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><b>Beschreibung:</b> '.$this->getFieldContent('description').'</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><b>Zus&auml;tzliche Information:</b> '.$this->getFieldContent('link').'</td>
				</tr>
			</table>
		<p>'.$this->pi_list_linkSingle($this->pi_getLL('back','Back'),0).'</p></div>'.
		$this->pi_getEditPanel();
	
		return $content;
	}
	/**
	 * Returns a single table row for list view
	 *
	 * @param	integer		$c: Counter for odd / even behavior
	 * @return	A HTML table row
	 */
	function pi_list_row($c)	{
		$editPanel = $this->pi_getEditPanel();
		
		$image = "uploads/tx_mwmybooks/".$this->getFieldContent('cover');
		$imagelink = $this->pi_list_linkSingle("",$this->internal['currentRow']['uid'],"",$this->internal['currentRow']['uid'],1);
		

		if ($editPanel)	$editPanel='<TD>'.$editPanel.'</TD>';
		return '<tr'.($c%2 ? $this->pi_classParam('listrow-odd') : '').'>
				<td valign="middle" width=70px><p><a href="'.$imagelink.'"><img src="'.$image.'" width="50px"/></a></p></td>
				<td valign="middle"><p>'.$this->getFieldContent('title').'</p></td>
				<td valign="middle"><p>'.$this->getFieldContent('author').'</p></td>
				<td valign="middle"><p>'.$this->getFieldContent('price').'</p></td>
			</tr>';
	}
	/**
	 * Returns a table row with column names of the table
	 *
	 * @return	A HTML table row
	 */
	function pi_list_header()	{
		return '<tr'.$this->pi_classParam('listrow-header').'>
		<!--
				<td><p>'.$this->getFieldHeader_sortLink('cover').'</p></td>
				<td><p>'.$this->getFieldHeader_sortLink('Titel').'</p></td>
				<td nowrap><p>'.$this->getFieldHeader('author').'</p></td>
				<td nowrap><p>'.$this->getFieldHeader('isbn').'</p></td>
				<td nowrap><p>'.$this->getFieldHeader('price').'</p></td>
				-->
			</tr>';
	}
	/**
	 * Returns the content of a given field
	 *
	 * @param	string		$fN: name of table field
	 * @return	Value of the field
	 */
	function getFieldContent($fN)	{
		switch($fN) {
			case 'uid':
				return $this->pi_list_linkSingle($this->internal['currentRow'][$fN],$this->internal['currentRow']['uid'],1);	// The "1" means that the display of single items is CACHED! Set to zero to disable caching.
			break;
			case 'title':
				return $this->pi_list_linkSingle($this->internal['currentRow'][$fN],$this->internal['currentRow']['uid'],1);	// The "1" means that the display of single items is CACHED! Set to zero to disable caching.
			break;

			default:
				return $this->internal['currentRow'][$fN];
			break;
		}
	}
	/**
	 * Returns the label for a fieldname from local language array
	 *
	 * @param	[type]		$fN: ...
	 * @return	[type]		...
	 */
	function getFieldHeader($fN)	{
		switch($fN) {
			case "title":
				return $this->pi_getLL('listFieldHeader_title','<em>title</em>');
			break;
			default:
				return $this->pi_getLL('listFieldHeader_'.$fN,'['.$fN.']');
			break;
		}
	}
	
	/**
	 * Returns a sorting link for a column header
	 *
	 * @param	string		$fN: Fieldname
	 * @return	The fieldlabel wrapped in link that contains sorting vars
	 */
	function getFieldHeader_sortLink($fN)	{
		return $this->pi_linkTP_keepPIvars($this->getFieldHeader($fN),array('sort'=>$fN.':'.($this->internal['descFlag']?0:1)));
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mw_mybooks/pi1/class.tx_mwmybooks_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mw_mybooks/pi1/class.tx_mwmybooks_pi1.php']);
}

?>