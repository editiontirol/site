<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003 Carlos Chiari <ccho@dimension-e.net>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
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
/**
* Module 'W4x Backup' for the 'w4x_backup' extension.

*
* @author	Carlos Chiari <ccho@dimension-e.net>
*/

// DEFAULT initialization of a module [BEGIN]
unset($MCONF);
include ("conf.php");
include ($BACK_PATH."init.php");
include ($BACK_PATH."template.php");
include ("locallang.php");
require_once(PATH_t3lib."class.t3lib_scbase.php");
require_once(PATH_t3lib."class.t3lib_tsstyleconfig.php");
require_once(PATH_t3lib.'class.t3lib_install.php');

$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
// DEFAULT initialization of a module [END]
class tx_w4xbackup_module1 extends t3lib_SCbase {

	var $pageinfo;
	var $trapError = '2>&1';
	var $whoami; //script owner name
	var $needs; //flag. indicates configuration needed.
	var $permCheck;
	var $addTarList;


	function init()	{

		global $AB,$BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

		parent::init();

	}



	/**
	
	* Adds items to the ->MOD_MENU array. Used for the function menu selector.
	
	*/

	function menuConfig()	{

		global $LANG;

		$this->MOD_MENU = Array (

		"function" => Array (
		"1" => $LANG->getLL("function1"),
		"2" => $LANG->getLL("function2"),
		"3" => $LANG->getLL("function3"),
		"4" => $LANG->getLL("function4"),
		"5" => $LANG->getLL("function5")
		)
		);

		parent::menuConfig();

	}



	/**
	
	* Main function of the module. Write the content to $this->content
	
	*/

	function main()	{

		global $AB,$BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

		// Access check!
		// The page will show only if there is a valid page and if this page may be viewed by the user
		

		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);

		$access = is_array($this->pageinfo) ? 1 : 0;

		if (($this->id && $access) || ($BE_USER->user["admin"] && !$this->id)  || ($BE_USER->user["uid"] && !$this->id))	{

			// Verify if we need to configurate the extension
			$w4xConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['w4x_backup']);
			$w4xConf = $w4xConf['w4x_backup.'];

			$tKey = (TYPO3_OS == 'WIN')? '7zip_path' : 'tar_path';

			if(!$w4xConf[$tKey] && !t3lib_div::_POST('submit')){
				$this->needs = 1;
				$this->MOD_SETTINGS["function"] = 4;

			}


			// Draw the header.

			$this->doc = t3lib_div::makeInstance("bigDoc");

			$this->doc->backPath = $BACK_PATH;

			$this->doc->form='<form action="" method="POST">';



			// JavaScript

			$this->doc->JScode = '
				<script language="javascript">
					script_ended = 0;
					function jumpToUrl(URL)	{
						document.location = URL;
					}
				</script>
			';

			$this->doc->postCode='
				<script language="javascript">
					script_ended = 1;
					if (top.theMenu) top.theMenu.recentuid = ".intval($this->id).";
				</script>
			';



			$headerSection = $this->doc->getHeader("pages",$this->pageinfo,$this->pageinfo["_thePath"])."<br>".$LANG->php3Lang["labels"]["path"].": ".t3lib_div::fixed_lgd_pre($this->pageinfo["_thePath"],50);



			$this->content.=$this->doc->startPage($LANG->getLL("title"));

			$this->content.=$this->doc->header($LANG->getLL("title"));

			$this->content.=$this->doc->spacer(5);

			$this->content.=$this->doc->section("",$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,"SET[function]",$this->MOD_SETTINGS["function"],$this->MOD_MENU["function"])));

			$this->content.=$this->doc->divider(5);



			// Render content:

			$beginTime = time();
			
			$this->moduleContent();

			$endTime = time();
			
			$totalTime = ($endTime-$beginTime)?$endTime-$beginTime:'0';

			$this->content.= $this->doc->section("",'<br><br>'.$LANG->getLL('time_elapsed').': '.$totalTime);
			
			// ShortCut

			if ($BE_USER->mayMakeShortcut())	{

				$this->content.=$this->doc->spacer(20).$this->doc->section("",$this->doc->makeShortcutIcon("id",implode(",",array_keys($this->MOD_MENU)),$this->MCONF["name"]));

			}



			$this->content.=$this->doc->spacer(10);

		} else {

			// If no access or if ID == zero



			$this->doc = t3lib_div::makeInstance("mediumDoc");

			$this->doc->backPath = $BACK_PATH;



			$this->content.=$this->doc->startPage($LANG->getLL("title"));

			$this->content.=$this->doc->header($LANG->getLL("title"));

			$this->content.=$this->doc->spacer(5);

			$this->content.=$this->doc->spacer(10);

		}

	}

	function printContent()	{

		global $SOBE;



		$this->content.=$this->doc->middle();

		$this->content.=$this->doc->endPage();

		echo $this->content;

	}






	/**
	 * function moduleContent
	 * 
	 * @result  mixed Results. Gives back the selected operation's result.
	 *
	 * This functions is in charge of the flow's logic of the extension
	 *
	 */
	function moduleContent()	{

		global $LANG, $_POST, $TYPO3_CONF_VARS,$BE_USER;

		$content = "";

		$w4xConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['w4x_backup']);

		$w4xConf = $w4xConf['w4x_backup.'];

		$this->mysqldump_path = $w4xConf['mysqldump_path'];
		$this->mysql_path = $w4xConf['mysql_path'];
		$this->backup_path = $w4xConf['backup_path'];
		$this->tar_path = $w4xConf['tar_path'];
		$this->zip_path = $w4xConf['7zip_path'];
		$this->date_format = $w4xConf['date_format'];
		$this->max_number = $w4xConf['max_number'];
		$this->max_days = $w4xConf['max_days'];
		$this->exclude = $w4xConf['excluded'];
		
		$this->permCheck = $w4xConf['permCheck'];
		$this->addTarList = $w4xConf['addTarList'];

		// t3lib_div::debug($w4xConf);
		
		$tempPath = PATH_site.$this->backup_path;
		
		exec('whoami',$this->whoami);
		$this->whoami = implode('',$this->whoami);

		$filesList = '';

		switch((string)$this->MOD_SETTINGS["function"])	{

			case 1:  //BACKUP
			
			$tempFiles = array_values(t3lib_div::getFilesInDir($tempPath,'gz', 0, mtime));


			//List files
			for ($i=count($tempFiles)-1; $i >= 0;$i--) {
				$file = $tempFiles[$i];

				if (($this->max_days > 0) && (time()-filemtime($tempPath.'/'.$file) > ($this->max_days)*3600*24)) {
					unlink($tempPath.'/'.$file);
					continue;
				}
				if (($this->max_number > 0) && ($i < count($tempFiles)-($this->max_number))) {
					unlink($tempPath.'/'.$file);
					continue;
				}

				$filesList .= '<div align="center">'.$this->getFileDate($tempPath.'/'.$file).' : <a href="';

				$filesList .= TYPO3TEMP_PATH.$this->backup_path.'/'.$file;

				$filesList .='" target="_blank">'.$LANG->getLL("downloadFile").' ';

				$filesList .= $file.'</a> '.$this->bytesToHumanReadableUsage(filesize($tempPath.'/'.$file)).'</div><br>';
			}

			if ($filesList) $filesList = '<div align="left"><b>'.$LANG->getLL('avBckps').'</b></div>'.$filesList.'<br><br><div align="left"><b>'.$LANG->getLL("f1s1_title").':</b></div>';

			// Make backup
			if(@$_POST["f1s1_backupnow"]!="") {
				$content  = $this->makeBackup();
			} else {
				$content = sprintf($LANG->getLL('mkbkp_message'),
									$this->whoami,
									$this->whoami);
				$content .= '<br><br><br><HR>';
			}

			$content .= $filesList;
			
			$proceed = ($this->permCheck)? '<input name="f1s1_nocheck" type="checkbox" id="f1s1_nocheck" value="1" onClick="javascript: if (this.checked) alert(\''.$LANG->getLL('mkbkp_warning').'\');"> '.$LANG->getLL('mkbkp_warning1') : '<input name="f1s1_nocheck" type="hidden" id="f1s1_nocheck" value="1"><em><font color="#666666">'.$LANG->getLL('mkbkp_warning1').'</font></em>';

			// Page
			$this->content.=$this->doc->section($LANG->getLL("f1s1_title"),$content.'<form action="" method="post"><input name="f1s1_wholetypo3conf" type="checkbox" id="f1s1_wholetypo3conf" value="1"> '.$LANG->getLL('wholetypo3conf').'<br><br>'.$proceed.'<div align="center"><input type="submit" name="f1s1_backupnow" value="'.$LANG->getLL("submit_backupnow").'"><br><br></div></form>',0,1);

			break;

			case 2:  // RESTORE
			//List files
			$tempFiles = array_values(t3lib_div::getFilesInDir($tempPath,'gz', 0, mtime));

					
			for ($i=count($tempFiles)-1; $i >= 0;$i--) {
				$file = $tempFiles[$i];
				if (($this->max_days > 0) && (time()-filemtime($tempPath.'/'.$file) > ($this->max_days)*3600*24)) {
					unlink($tempPath.'/'.$file);
					continue;
				}
				if (($this->max_number > 0) && ($i < count($tempFiles)-($this->max_number))) {
					unlink($tempPath.'/'.$file);
					continue;
				}
				$filesList .= '<div align="center"><form action="" method="POST" name="restoreFile'.$i.'" id="restoreFile'.$i.'"><input name="flsl_restore" type="hidden" value="1"><input name="file" type="hidden" value="'.$file.'">'.$this->getFileDate($tempPath.'/'.$file).' : <a href="#" onClick="document.forms['. "'restoreFile$i'" .'].submit();">'.$LANG->getLL("restoreFile").' '.$file.'</a> '.$this->bytesToHumanReadableUsage(filesize($tempPath.'/'.$file)).'</form></div><br>';
			}

			if ($filesList == '') {

				$result = $LANG->getLL('noFile');

			} else {

				$result = "<form action='' method='POST'><input name='any' type='hidden' value='0'></form>".$filesList;

			}

			// Restore file...

			$restoredMessage = '';

			$problem = '';

			if ($_POST['flsl_restore']!='') { // a form has been submited

					$rFile = $_POST['file'];
		
					if ($rFile != '') { // There is a file selected. We proceed
		
					// First step: file verification
					if(!@$_POST['fls1_restore-verify'] && $this->permCheck) { // no verification yet AND we need to verify
		
										$verifyForm = '<div align="center">
										<form action="" method="POST" name="restoreFileVerify" id="restoreFileVerify">
										<table align="center" width="80%">
										<tr><td><h4><span  style="color: navy;">'.$LANG->getLL("restoreForm1").'</span>&nbsp;'.$LANG->getLL("restoreForm2").'</h4></td></tr>
										<tr><td><b>'.$LANG->getLL("restoreForm3").'</b>&nbsp;<font color=teal>'.$rFile.'</font> <em>('.$this->bytesToHumanReadableUsage(filesize($tempPath.'/'.$rFile)).')</em></td></tr>
										<tr><td><b>'.$LANG->getLL("restoreForm4").'</b>&nbsp;'.$this->getFileDate($tempPath.'/'.$rFile).'</td></tr>
										<tr><td><table width="70%" align="center">
												<tr><td align="left">
													<input name="file" type="hidden" value="'.$rFile.'">
													<input name="flsl_restore" type="hidden" value="1">
													<br><br><br><input name="fls1_restore-verify" type="checkbox" id="fls1_restore-verify" value="1">
													<font color=red><b>'.$LANG->getLL("restoreForm5").'</b></font>&nbsp;&nbsp; '.$LANG->getLL("restoreForm6").'<br><br><input name="f1s1_nocheck" type="checkbox" id="f1s1_nocheck" value="1" onClick="javascript: if (this.checked) alert(\''.$LANG->getLL("restoreForm7").'\');"> '.$LANG->getLL("restoreForm8").'<br><br>
												</td></tr>
												<tr><td align="center">
													<input name="Submit" type="submit" id="Submit" value="'.$LANG->getLL("restoreForm9").'">
												</td></tr>
										</table></td></tr>
										</table>
										</form></div>';

										$result = $verifyForm.'<br><br><br>'.$filesList;


			// Second step: confirm RESTORE order

			} elseif((@$_POST['fls1_restore-verify'] || !$this->permCheck) && !@$_POST['fls1_restore-confirmation']) { //(verified or do not check) but not confirmed

					if ($this->permCheck) $problem = $this->checkPermissions($tempPath.'/'.$rFile);

					if($problem && !$_POST['f1s1_nocheck']) {
	
						$result = '<b><font color=purple>'.$LANG->getLL("restoreForm10").'</font></b><br><br>'.$problem;
						$result .= '<center><a href="javascript: window.history.go(-1);">'.$LANG->getLL("restoreForm11").'</a></center>';

					} else {
	
										$confirmForm = '<div align="center">
										<form action="" method="POST" name="restoreFileConfirmation" id="restoreFileConfirmation">
										<table align="center" width="80%">';
										
										if($this->permCheck) {
											$confirmForm .= '<tr><td><h4><span  style="color: navy;">'.$LANG->getLL("restoreForm1").'</span> <span style="color:red;">'.$LANG->getLL("restoreForm12").'</span></h4></td></tr>';
										} else {
											
											$confirmForm .= '<tr><td><h4><em><font color="#666666"><b>'.$LANG->getLL("restoreForm1").'</b> '.$LANG->getLL("restoreForm13").'</font></em></h4></td></tr>';
										}
										
										$confirmForm .= '
										<tr><td><h4><span  style="color: navy;">'.$LANG->getLL("restoreForm14").'</span>&nbsp;'.$LANG->getLL("restoreForm15").'</h4></td></tr>
										<tr><td><b>'.$LANG->getLL("restoreForm16").'</b>&nbsp;<font color=teal>'.$rFile.'</font> <em>('.$this->bytesToHumanReadableUsage(filesize($tempPath.'/'.$rFile)).')</em></td></tr>
										<tr><td><b>'.$LANG->getLL("restoreForm4").'</b>&nbsp;'.$this->getFileDate($tempPath.'/'.$rFile).'</td></tr>
										<tr><td>&nbsp;</td></tr>
										<tr><td>'.$problem.'</td></tr>
										<tr><td><table width="70%" align="center">
												<tr><td align="left">
													<input name="flsl_restore" type="hidden" value="1">
													<input name="fls1_restore-verify" type="hidden" value="1">
													<input name="f1s1_nocheck" type="hidden" value="'.$_POST['f1s1_nocheck'].'">
													<input name="file" type="hidden" value="'.$rFile.'">
													<br><br><br><input name="fls1_restore-confirmation" type="checkbox" id="fls1_restore-confirmation" value="1">
													<font color=red><b>Yes</b></font>&nbsp;&nbsp; '.$LANG->getLL("restoreForm17").'<br><br>
												</td></tr>
												<tr><td align="center">
													<input name="Submit" type="submit" id="fls1_restore-confirmation" value="'.$LANG->getLL("restoreForm9").'">
												</td></tr>
										</table></td></tr>
										</table>
										</form></div>';

										$result = $confirmForm.'<br><br><br>'.$filesList;
					}

			// Third step: execute
				} else {


				$newBackupBeforeRestore = $this->makeBackup(1);

				$restoredMessage = $this->restoreFile($rFile);

				$result = $restoredMessage.'<br><br><br><div align="center">'.$LANG->getLL("restoreForm18").' <a href="'.TYPO3TEMP_PATH.$this->backup_path.'/'.$newBackupBeforeRestore.'" target="_blank"><span style="text-decoration:underline;">'.$LANG->getLL('here').'</span></a></div>';
					}


				}
			} else {
			
				$result = $LANG->getLL("restoreForm19").'<br><br>'.$result;
					
			}

			$this->content.=$this->doc->section($LANG->getLL("flsl_title3"),$result,0,1);


			break;



			case 3: // CLEAR

			$deletedMessage = '';

			// Check if need to delete..
			if ($_POST['flsl_clear']!='') {


				$deletedMessage = $this->deleteFiles($_POST['file']);


			}

			// dummy form: the first form on the document can't be submited by the onClick javascript, so we insert this dummy form first.
			$filesList = "<form action='' method='POST'><input name='any' type='hidden' value='0'></form>";

			$tempFiles = array_values(t3lib_div::getFilesInDir($tempPath,'gz', 0, mtime));


			//Make forms
			for ($i=count($tempFiles)-1; $i >= 0;$i--) {
				$file = $tempFiles[$i];
				if (($this->max_days > 0) && (time()-filemtime($tempPath.'/'.$file) > ($this->max_days)*3600*24)) {
					unlink($tempPath.'/'.$file);
					continue;
				}
				if (($this->max_number > 0) && ($i < count($tempFiles)-($this->max_number))) {
					unlink($tempPath.'/'.$file);
					continue;
				}
				$filesList .= '<div align="center"><form action="" method="POST" name="delFile'.$i.'" id="delFile'.$i.'"><input name="flsl_clear" type="hidden" value="1"><input name="file" type="hidden" value="'.$file.'">'.$this->getFileDate($tempPath.'/'.$file).' : <a href="#" onClick="document.forms['. "'delFile$i'" .'].submit();">'.$LANG->getLL("clearFile").$file.'</a> '.$this->bytesToHumanReadableUsage(filesize($tempPath.'/'.$file)).'</form></div><br>';
			}

			$form .= '<form action="" method="POST" name="clear"><div align="center">
            <input type="submit" name="flsl_clear" value="'.$LANG->getLL('clearAll').'" ></div>
			</form>';

			// Confirmed deletion message...

			$filesList = $deletedMessage.$filesList;

			$this->content.=$this->doc->section($LANG->getLL("f1s1_title2"),$filesList.$form,0,1);


			break;

			case 4:  // Settings

			if($BE_USER->user["admin"]){  // only available for admins. this is a security meassure in case the tool is tweaked to be available on "users" module

			$this->content.=$this->doc->spacer(10);

			//$this->content.=$this->doc->section($LANG->getLL('flsl_title4'),$LANG->getLL('confMsg'),0,1);

			$content .=$LANG->getLL('confMsg');

			$content .= $this->tsStyleConfigForm('w4x_backup','w4x_backup',1);

			} else {

				$content .= '<b><font color=red>'.$LANG->getLL('confMsg_2').'</font></b>';
			}

			$this->content.=$this->doc->section($LANG->getLL("flsl_title4"),$content,0,1);

			break;

			case 5:

			$content = $this->getAbout();

			$this->content.= $this->doc->section($LANG->getLL("flsl_title5"),$content,0,1);

			break;

		}

	}

	// This function does the backup

	function makeBackup($fromRestore = 0) {
		global $LANG;

		$tempPath = PATH_site.$this->backup_path;

		$fR = ($fromRestore)?'securityBackup-':'';

		// Host name on backup file's name: asked feature
		$f1_temp = $fR.$_SERVER['HTTP_HOST'].'-'.date("Y-m-d",time()).'-'.substr(MD5(microtime()),0,20);

		// Dump database.  Original command: $createCommand = $this->mysqldump_path.' --opt -u '.TYPO3_db_username.' --password='.TYPO3_db_password.' '.TYPO3_db.' > '.$tempPath.'/'.$f1_temp.'.sql '.$this->trapError;
		//  ' > ' is substituted for ' -r ' for Peter Russ sugestion. Problem arise if safe_mode is enabled with ' > '

		// Now we include the host: asked feature
		$createCommand = $this->mysqldump_path.' --opt -h '.TYPO3_db_host.' -u '.TYPO3_db_username.' -p'.TYPO3_db_password.' -r '.$tempPath.'/'.$f1_temp.'.sql '.TYPO3_db.' '.$this->trapError;

		exec($createCommand,$error);

		$Serror = array('BACKUP: mysqldump error',$error);

		// Make TAR archive
		if (TYPO3_OS == 'WIN') {
			chdir(PATH_site);

			// SQL file
			$createCommand = $this->zip_path.' a -ttar '.$this->backup_path.'/'.$f1_temp.'.tar '.$this->backup_path.'/'.$f1_temp.'.sql';
			exec($createCommand);

			// typo3conf
			$createCommand = $this->zip_path.' a -ttar '.$this->backup_path.'/'.$f1_temp.'.tar ';
			if(@$_POST['f1s1_wholetypo3conf'] == '' && !$fromRestore) {
				$createCommand .= 'typo3conf/localconf.php';
			} else {
				$createCommand .= 'typo3conf/* -r';
			}
			exec($createCommand);

			// fileadmin
			$createCommand = $this->zip_path.' a -ttar -r '.$this->backup_path.'/'.$f1_temp.'.tar ';
			$createCommand .= 'fileadmin/*';
			exec($createCommand);

			// uploads
			$createCommand = $this->zip_path.' a -ttar -r '.$this->backup_path.'/'.$f1_temp.'.tar ';
			$createCommand .= 'uploads/*';
			exec($createCommand);

			// GZIP
			$createCommand = $this->zip_path.' a -tgzip '.$tempPath.'/'.$f1_temp.'.tar.gz '.$tempPath.'/'.$f1_temp.'.tar';
			exec($createCommand);
			unlink($tempPath.'/'.$f1_temp.'.tar');


		} else {	// UNIX/LINUX

		//make exclude clause. Does anyone know if this is possible with 7-zip?
		if($this->exclude > '') {
			$typeArray = explode(',',$this->exclude);
			$excludeClause = '';

			foreach ($typeArray as $type) {
				$excludeClause .= ' --exclude="*.'.$type.'" ';
			}

			$content .='<b><font color=red>'.$LANG->getLL('e_files').':</font></b><br>'.$LANG->getLL('e_files2').' ('.$this->exclude.') '.$LANG->getLL('e_files3').'.<br><br>';

		}

		// tar the sql dump
		$createCommand = $this->tar_path.' -zcf '.$tempPath.'/'.$f1_temp.'.tar.gz '.$tempPath.'/'.$f1_temp.'.sql ';

		// decide if tar the whole typo3conf or just localconf.php. If it comes from Restore, we tar the whole thing
		if(@$_POST['f1s1_wholetypo3conf'] == '' && !$fromRestore) {
			$createCommand .= PATH_site.'typo3conf/localconf.php';
		} else {
			$createCommand .= PATH_site.'typo3conf/';
		}

		// tar fileadmin & uploads
		$createCommand .= ' '.PATH_site.'fileadmin '.PATH_site.'uploads'.$excludeClause;

			exec($createCommand.' '.$this->trapError,$error);

		}

		// merge errors
		$Serror = array_merge($Serror,array('BACKUP: tar error',$error));

		unlink($tempPath.'/'.$f1_temp.'.sql');
		
		
		
		// if we are coming from a Restore command, we return with the file name
		if($fromRestore)  { 

			return $f1_temp.'.tar.gz'; 

		}
		
		// Else, we proceed with the permissions check.

		if($this->permCheck) $problems = $this->checkPermissions($tempPath.'/'.$f1_temp.'.tar.gz ',$Serror);
		


		// Prepare return:
		if($problems && !@$_POST['f1s1_nocheck']) {

			unlink($tempPath.'/'.$f1_temp.'.tar.gz'); // we unlink the failed tar
			return '<b><font color=purple>'.$LANG->getLL('restoreForm10').'</font></b><br><br>'.$problems;


		}   else {

			$content .= '<div align=center><font color="red"><b>'.$LANG->getLL('backupCreated').'</b></font><br><br> ';
			$content .= '<a href="'.TYPO3TEMP_PATH.$this->backup_path.'/'.$f1_temp.'.tar.gz" target="_blank"><span style="text-decoration:underline;">'.$LANG->getLL('here').'</span></a></div><br><br>';


			if($Serror || $problems) { //if error creating the tar or problems with permissions...
			$content.= $problems;
			}


			return $content;

		}

	}

	// This function does the file deleting.
	function deleteFiles ($file='') {
		global $LANG;

		$tempPath = PATH_site.$this->backup_path;

		if ($file != ''){
			//$exec = 'rm -f '.$tempPath.$file;
			unlink($tempPath.'/'.$file);

			$result = "<div align=center><font color=red>".$LANG->getLL('executed').": ".$exec."<br>".$file." ".$LANG->getLL('clearedOk')."</font></div><br>";

		} else {

			//$exec = 'rm -f '.$tempPath.'*.gz';
			$tempFiles = array_values(t3lib_div::getFilesInDir($tempPath,'gz'));
			for ($i=count($tempFiles)-1; $i >= 0;$i--) {
				$file = $tempFiles[$i];
				unlink($tempPath.'/'.$file);
			}

			$result = "<div align=center><font color=red>".$LANG->getLL('executed').": ".$exec."<br>".$LANG->getLL('clearedAllOk')."</font></div><br>";

		}


		return $result;
	}





	function restoreFile($file) {
		global $LANG;

		$tempPath = PATH_site.$this->backup_path;



		if (TYPO3_OS == 'WIN') {
			$Command = $this->zip_path.' x -aoa '.$tempPath.'/'.$file.' -o'.$tempPath;
			exec($Command);
			$Command = $this->zip_path.' x -aoa '.$tempPath.'/'.substr($file, 0, strlen($file)-3).' -o'.PATH_site;
			exec($Command);
			unlink($tempPath.'/'.substr($file, 0, strlen($file)-3));
		} else {

			$Command = $this->tar_path." -zxf ".$tempPath.'/'.$file." --same-owner --same-permissions --overwrite --directory=/ ";
			exec($Command.$this->trapError,$error);
		}

		$Serror = array('RESTORE: untar error',$error);

		$Command = $this->mysql_path." ".TYPO3_db." < ".$tempPath.'/'.substr($file,0,-7).".sql -h".TYPO3_db_host."
 -u".TYPO3_db_username." -p".TYPO3_db_password.' ';


		exec($Command.$this->trapError,$error);

		$Serror =array_merge($Serror,array('RESTORE: mysql error',$error));



		//seems unlink fails to remove the file here...
		if (TYPO3_OS == 'WIN') {
			if (@unlink($tempPath.'/'.substr($file,0,-7).".sql ")==false) $result .= '<br><div align=center>File '.$tempPath.'/'.substr($file,0,-7).'.sql '.$LANG->getLL('errorLog4').'.</div><br>' ;
		} else {

			unlink($tempPath.'/'.substr($file,0,-7).".sql");
		}

		$result = "<div align=center><font color=red>".$LANG->getLL('executed').":<br>".$file;

		$result.= " ".$LANG->getLL('restoreOk')."</font></div>"; // <br>$echoC<br><br>";

		if($Serror) {
			$Serror = t3lib_div::view_array($Serror);

			// Was there any tar error?
			t3lib_div::writeFile(PATH_site.'typo3temp/w4x_backup_errorlog.html','<font face=verdana size=1>The RESTORE operation throwed these errors:<br>'.$Serror.'</font>');

			$result.= '<hr><br><br><b><font color=red>'.$LANG->getLL('errorLog1').'</font></b><br><br>'.$LANG->getLL('errorLog2').':<a href="/typo3temp/w4x_backup_errorlog.html" target=_blank>'.$LANG->getLL('errorLog3').'</a><br><br><br><hr>';

		}

		return $result;



	}



	function checkPermissions($file,$prependErrors='') { //pass file with path included.
	global $LANG;

	// We need to verify we have the permissions to do the operation.
	// Otherwise, we will end with a flawed restore.
	// Only for Unix...

	if(TYPO3_OS != 'WIN') {  // We do verifications on UNIX.

	// List files verbosely... this can be long.. we need to enable/disable this feature
	$Command = $this->tar_path." -ztvf ".$file;
	exec($Command,$msg);
	// t3lib_div::debug($error);
	$count  = count($msg);

	$owner= array();
	$ingroup = array();
	$problem = array();

	for ($i = 0; $i < $count; $i++) {
		$tmp = explode($this->whoami.'/',$msg[$i]);
		//t3lib_div::debug($tmp);
		if (count($tmp)==2 && eregi('^..w',$tmp[0])) {
			// if (count($tmp)==2) {
			$owner[$i] = 1;
		}
		$tmp = explode('/'.$this->whoami,$msg[$i]);
		if(count($tmp)==2 && eregi('^.....w',$tmp[0])) {
			// if(count($tmp)==2) {
			$ingroup[$i] = 1;
		}
		if(!$ingroup[$i] && !$owner[$i]) {
			$problem[$i]=1;
		}


	}
	$problematicFiles = array();
	foreach ($problem as $item => $value) {  // $item holds the array key of the msg2 array item where whoami is no owner and no member of group
	$problematicFiles[] = $msg[$item];
	}


	// t3lib_div::debug(array($problem,$problematicFiles));


	if($problematicFiles[0]>'') { // we test the first element to see if we are going to have problems.

	if($prependErrors) {

		$previousErrorMsg = '<br><br>The "exec" commands throwed these errors:<br>'.t3lib_div::view_array($prependErrors).'<br><br>';

	}

	
	$fileContent = '<font size=1 face=verdana>The server is running as <b>'.$this->whoami.'</b>'.$previousErrorMsg.'<br>The permissions check reported that these files will cause problem with the Restore operation due to ownership and/or permissions problem. Please fix them before proceeding.<br><br>Problematic files:<br>'.t3lib_div::view_array($problematicFiles);
	
	$fileContent .= ($this->addTarList)? '<br><br>This is the files list on the tar:<br>'.t3lib_div::view_array($msg):' ';
	
	$fileContent .= '</font>';
	
	
	t3lib_div::writeFile(PATH_site.'typo3temp/w4x_backup_errorlog.html',$fileContent);


	$result = '<br><br><b><font color=red>'.$LANG->getLL('errorLog1').'</font></b><br><br>'.$LANG->getLL('errorLog2').':<a href="/typo3temp/w4x_backup_errorlog.html" target=_blank>'.$LANG->getLL('errorLog3').'</a><br><br><br><hr>';

	} else {

		$result = false;

	}
	}

	return $result;

	}





	// This function returns the "About" message
	function getAbout() {

		$content = <<<EOF
<div align=center><B>Version:</b>&nbsp;0.9.0<br>
<div><font size='1' face='arial'>This is an extended version of original W4 Backup extension, by Ramon Bucher, with minor fixes.</div><br>
<div align=left>Differences:<br>
<b>0.9.0</b><br>
<li>Permissions verification: The system will check for server's permissions to do the backup and the restore with the files. You can modify this behavior at the SETTINGS tool, in the Backend module menu.
<li>Security Backup: Before RESTORE is committed, a security backup will be generated. This will have the phrase "securityBackup" appended to its name.
<li>Database Host is included in the mysql and mysqldump commands. Now the system should be able to do remote database backup. (Thx. Hanno Bolte).
<li>Host Name included in the Backup File. (Thx Stefano Cecere).
<li>Time elapsed for operations calculated and shown.
<li>typo3temp/w4x file created. Will be the default directory for backup files for easy cleaning at the typo3temp tool at install panel. You can change it at the configuration tool.
<li>Mysqldump command modified to allow usage under safe_mode. (Thx Peter Russ).
<BR>
<BR>
<BR><b>0.8.0</b><br>
<li>Enabled for Windows, with the use of 7-ZIP software, thanks to <b>Norman Seibert</b> contribution.
<li>Excluded types: Now you can set a list of excluded files that will NOT be backed up.
<li>Automatic backup file download (on popup window) disabled.
<br>
<br>
<br>
<b>Previous</b><br>
<li>Operation logging enabled. If any report is given back from backup, typical an error, restore or cleanup operations, a link to the report will be shown to the user.
<li>Relative path to backup file, instead of fixed path
<li>Improved backup file download method<br>
&nbsp;&nbsp;&nbsp;&nbsp;(javascript 'open' instead of php 'echo')
<li>Previous backup files listing for download
<li>Optional download link if automatic method fails<li>Deleting of typo3temp/*.sql files implemented
<li>Deleting of selected typo3temp/*.tar.gz files allowed
<li>Deleting al typo3temp/*.tar.gz allowed.
<li>Allows full typo3conf/ directory backup or just typo3conf/localconf.php.
<li>Restore function added (v.0.4).
<li>File size now shown on lists.<br>
<br>
</div>
<div align=center><font size=1>Extension by: <br><a href='http://www.dimension-e.net' target='_blank'>Dimension-e</a></font></div></div>
EOF;

		return $content;


	}


	function bytesToHumanReadableUsage($bytes, $precision = 2, $names = '') {
		if (!is_numeric($bytes) || $bytes < 0) {
			return false;
		}

		for ($level = 0; $bytes >= 1024; $level++) {
			$bytes /= 1024;
		}

		switch ($level)
		{
			case 0:
			$suffix = (isset($names[0])) ? $names[0] : 'Bytes';
			break;
			case 1:
			$suffix = (isset($names[1])) ? $names[1] : 'KB';
			break;
			case 2:
			$suffix = (isset($names[2])) ? $names[2] : 'MB';
			break;
			case 3:
			$suffix = (isset($names[3])) ? $names[3] : 'GB';
			break;
			case 4:
			$suffix = (isset($names[4])) ? $names[4] : 'TB';
			break;
			default:
			$suffix = (isset($names[$level])) ? $names[$level] : '';
			break;
		}
		if (empty($suffix)) {
			trigger_error('Unable to find suffix for case ' . $level);
			return false;
		}
		return round($bytes, $precision) . ' ' . $suffix;
	}

	function getFileDate($file) {
		if ($file != '') {
			$tstamp = filemtime($file);
			return (date($this->date_format, $tstamp));
		}
	}


	/*
	* These functions were taken/modified from extensionmanager em/index.php script
	* written by Kasper
	*/


	function tsStyleConfigForm($extKey,$extInfo,$output=0,$script='',$addFields='')	{

		global $TYPO3_CONF_VARS, $LANG;

		// Initialize:
		$absPath = t3lib_extMgm::extPath($extKey);
		$relPath = t3lib_extMgm::extRelPath($extInfo);

		// Look for template file for form:
		if (@is_file($absPath.'ext_conf_template.txt'))	{

			// Load tsStyleConfig class and parse configuration template:
			$tsStyleConfig = t3lib_div::makeInstance('t3lib_tsStyleConfig');
			$theConstants = $tsStyleConfig->ext_initTSstyleConfig(
			t3lib_div::getUrl($absPath.'ext_conf_template.txt'),
			$relPath,
			$absPath,
			$GLOBALS['BACK_PATH']
			);

			// Load the list of resources.
			$tsStyleConfig->ext_loadResources($absPath.'res/');

			// Load current value:
			$arr = unserialize($TYPO3_CONF_VARS['EXT']['extConf'][$extKey]);
			$arr = is_array($arr) ? $arr : array();

			// Warn if no configuration

			$warning = '';

			if($this->needs){

				$warning = '<b><font face=verdana size=1 color=red>'.$LANG->getLL('confMsg_3').'</font></b><br>'.$LANG->getLL('confMsg_4').'<br><br>';

			}


			// Call processing function for constants config and data before write and form rendering:

			// If saving operation is done:
			if (t3lib_div::_POST('submit'))	{
				$tsStyleConfig->ext_procesInput(t3lib_div::_POST(),array(),$theConstants,array());
				$arr = $tsStyleConfig->ext_mergeIncomingWithExisting($arr);
				$form = '<b><font face=verdana size=1 color=red>'.$this->writeTsStyleConfig($extKey,$arr).'</font></b><br><br>';
			}

			// Setting value array
			$tsStyleConfig->ext_setValueArray($theConstants,$arr);

			// Getting session data:

			$MOD_MENU = array();
			$MOD_MENU['constant_editor_cat'] = $tsStyleConfig->ext_getCategoriesForModMenu();
			$MOD_SETTINGS = t3lib_BEfunc::getModuleData($MOD_MENU, t3lib_div::_GP('SET'), 'xMod_test');

			// Resetting the menu (stop)
			if (count($MOD_MENU)>1)	{
				$menu = 'Category: '.t3lib_BEfunc::getFuncMenu(0,'SET[constant_editor_cat]',$MOD_SETTINGS['constant_editor_cat'],$MOD_MENU['constant_editor_cat'],'','&CMD[showExt]='.$extKey);
				$this->content.=$this->doc->section('','<span class="nobr">'.$menu.'</span>');
				$this->content.=$this->doc->spacer(10);
			}

			// Category and constant editor config:
			$form .= '
				<table border="0" cellpadding="0" cellspacing="0" width="600">
					<tr>
						<td>'.$tsStyleConfig->ext_getForm($MOD_SETTINGS['constant_editor_cat'],$theConstants,$script,$addFields).'</td>
					</tr>
				</table>';
			if ($output)	{
				return $warning.$form;
			} else {
				$this->content.=$this->doc->section('','</form>'.$warning.$form.'<form>');
			}
		}
	}



	function writeTsStyleConfig($extKey,$arr)	{
		global $LANG;

		// Instance of install tool
		$instObj = new t3lib_install;
		$instObj->allowUpdateLocalConf =1;
		$instObj->updateIdentity = 'Tasks Manager Config Update';

		// Get lines from localconf file
		$lines = $instObj->writeToLocalconf_control();
		$instObj->setValueInLocalconfFile($lines, '$TYPO3_CONF_VARS[\'EXT\'][\'extConf\'][\''.$extKey.'\']', serialize($arr));	// This will be saved only if there are no linebreaks in it !
		$instObj->writeToLocalconf_control($lines);

		$this->removeCacheFiles();

		return $LANG->getLL('confMsg_5');


	}



	function removeCacheFiles()	{
		$cacheFiles = t3lib_extMgm::currentCacheFiles();
		$out = 0;
		if (is_array($cacheFiles))	{
			reset($cacheFiles);
			while(list(,$cfile) = each($cacheFiles))	{
				@unlink($cfile);
				clearstatcache();
				$out++;
			}
		}
		return $out;
	}

}

// This line needs to have single quotes ', not double quotes " to prevent error reporting from the extension manager!

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/w4x_backup/mod1/index.php'])	{

	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/w4x_backup/mod1/index.php']);

}

// Make instance:
$SOBE = t3lib_div::makeInstance("tx_w4xbackup_module1");
$SOBE->init();
// Include files?
reset($SOBE->include_once);
while(list(,$INC_FILE)=each($SOBE->include_once))	{include_once($INC_FILE);}
$SOBE->main();
$SOBE->printContent();
?>