<?php
$MODULE_CALL = 'modSpamfolderAdmin';

class modSpamfolderAdmin extends b1gMailModul
{
	var $myTemp=false;
	
	function modSpamfolderAdmin(){
		global $lang_main,$lang_admin;
		$this->titel		= 'Spam Verwaltung';
		$this->autor		= 'ASIKATEC';
		$this->web			= 'http://www.asikatec.de/';
		$this->mail			= 'service@asikatec.de';
		$this->version		= 'BETA 0.3';
		$this->designedfor	= '6.3.0';
		$this->admin_pages	= true;
		$this->admin_page_title = "Spam Administrator";
		$this->features = array('MySQLHandle');

		//Lang
		$lang_main['spamautoread']=" und automatisch als gelesen makieren";
		$lang_admin['cfg_spamadmin_usercount']='Benutzeranzahl';
		$lang_admin['cfg_spamadmin_spamfolder']='Spam Ordner';
		$lang_admin['cfg_spamadmin_spamfoldererror']='Spam Ordner Fehler';
		$lang_admin['cfg_spamadmin_spamautoread']='Autogelesen';
		$lang_admin['cfg_spamadmin_templatehack']='Auto Template Hack';
	}
	

	function Link($do=""){
		if ($do){
			$do='&do='.$do;
		}
		return('admin.php?action=modulepage'.$do.'&PHPSESSID=' . session_id() . '&module=' . $this->internal_name);
	}
	
	function SetSpamFolder($userid, $usermail){
		$sql = new SQLq("INSERT INTO {pre}folders(titel,user) VALUES('Spam','".addslashes($usermail)."')");
		$folder_id = $sql->GetID();
		$sql = new SQLq("UPDATE {pre}users SET spamaction='$folder_id' WHERE id='$userid'");
	}
	
	function Overview(){
		global $lang_admin;
		$sql_user=new SQLq("SELECT id,email,spamaction,spamautoread FROM `{pre}users`");
		$UserCount=0;
		$SpamFolder=0;
		$SpamFolderError=0;
		$SpamAutoRead=0;
		while($row=$sql_user->FetchArray()){
			$UserCount=$UserCount+1;
			$found=false;
			if ($row['spamaction']!=-1){
				$SpamFolder=$SpamFolder+1;
				$sql_spam=new SQLq("SELECT user FROM `{pre}folders` WHERE id='".$row['spamaction']."' and user='".$row['email']."'");
				while($row_spam=$sql_spam->FetchArray()){
					$found=true;
				}
				$sql_spam->FreeClose();
				if (!$found){
					$SpamFolderError=$SpamFolderError+1;
				}
			}
			if ($row['spamautoread']){
				$SpamAutoRead=$SpamAutoRead+1;
			}
		}
		if ($SpamFolderError){
			$SpamFolderError='<span style="color:#FF0000;">'.$SpamFolderError.'</span>';
		}
		$sql_user->FreeClose();		
		$box = new ConfigBox('Übersicht',"spamadmin");
		$box->AddField("custom", "spamadmin_usercount", $UserCount);
		$box->AddField("custom", "spamadmin_spamfolder", $SpamFolder);
		$box->AddField("custom", "spamadmin_spamfoldererror", $SpamFolderError);
		$box->AddField("custom", "spamadmin_spamautoread", $SpamAutoRead);
		$box->Out();
		?></div><?php
	}
	
	function user(){
		global $lang_admin;
		?>
		<form name="spam" style="display:inline;" method="post" action="<?php echo $this->Link('spam_standard_folder') ?>">
		<table width="90%" cellspacing="1" bgcolor="#999999">
		<tr>
		<td height="24" background="res/lauf.jpg" colspan="4">&nbsp;&nbsp;<font color="#666666"><b><?php echo $this->admin_page_title ?></b></font></td>
		</tr>
		<tr>
		<td bgcolor="#efefef" height="22" width="1%">&nbsp;<input onclick="negateChoice(spam);" type="checkbox" name="spam_folder_master" value=""></td>
		<td bgcolor="#efefef" height="22" width="1%">&nbsp;eMail</td>
		<td bgcolor="#efefef" height="22" width="40%">&nbsp;Ordner</td>
		<td bgcolor="#efefef" height="22" width="10%">&nbsp;AutoRead</td>
		</tr>
		<tr>
		<td bgcolor="#efefef" height="22" colspan="4">&nbsp;</td>
		</tr>
		<?php
		$sql_user=new SQLq("SELECT id,email,spamaction,spamautoread FROM `{pre}users`");
		while($row=$sql_user->FetchArray())
		{
			$spam_folder='';
			if ($row['spamaction']!=-1){
				$spam_folder='<a style="color:#FF0000;" href="javascript:document.getElementsByName(\'spam\')[0].action=\''.$this->Link('spam_rep_user').'\';document.spam.submit();">Error</a>';
				$sql_spam = new SQLq("SELECT titel FROM `{pre}folders` WHERE id='".$row['spamaction']."' and user='".$row['email']."'");
				while($row_spam = $sql_spam->FetchArray())
				{
					$spam_folder=$row_spam['titel'];
				}
				$sql_spam->FreeClose();
			}else{
				$spam_folder='';
			}
			?>
			<tr>
    	<td bgcolor="#f5f5f5" height="22" width="1%">&nbsp;
			<?php
			if (($row['spamaction']==-1)){
				echo '<input type="checkbox" name="spam'.$row['id'].'" value="true">';
			}
			echo '</td>';
			echo '<td bgcolor="#f5f5f5" height="22" width="1%">&nbsp;'.$row['email'].'</td>';
			echo '<td bgcolor="#f5f5f5" height="22" width="40%">&nbsp;'.$spam_folder.'</td>';
			echo '<td bgcolor="#f5f5f5" height="22" width="1%">&nbsp;';
			if (($row['spamautoread']!=0)){
				echo '•';
			}
			echo '</td>';
	    echo '</tr>';
		}	
		$sql_spam->FreeClose();
		
		?>
		</table>
		<br />
		<input type="submit" value="Standard&nbsp;Ordner&nbsp;Erzeugen">
		</form>
		</div>
		<br />
		<?php			
	}
	
	function setup(){
		global $lang_admin;
		?>
		<form name="spam" style="display:inline;" method="post" action="<?php echo $this->Link('spamadmin_setup_save') ?>">
		<div align="center">
		<?php
		$box = new ConfigBox('Einstellungen',"spamadmin");
		$box->AddField("check", "spamadmin_templatehack");
		$box->Out();
		?>
		<input type="submit" value="<?php echo($lang_admin['saveprefs']); ?>">
		</form>
		<br />						
		</div>
		<?php
	}
		
	function AdminHandler()
	{
		global $lang_admin;
		?>
		<table width="100%" bgcolor="#999999" cellspacing="1" border="0">
		<tr>
			<td height="19" align="right" background="res/lauf.jpg">
				<a href="<?php echo $this->Link() ?>" style="color:#666666;">Übersicht</a>&nbsp;|&nbsp;
				<a href="<?php echo $this->Link('spamadmin_user') ?>" style="color:#666666;">Benutzer</a>&nbsp;|&nbsp;
				<a href="<?php echo $this->Link('spamadmin_setup') ?>" style="color:#666666;">Einstellungen</a>&nbsp;|&nbsp;
				<a href="<?php echo $this->Link('spamadmin_statistik') ?>" style="color:#666666;">Statistik</a>
			</td>
		</tr>
		</table><div align="center"><br/>
		<?php
		if(!isset($_REQUEST['do'])){
			$this->Overview();
		}
		if(isset($_REQUEST['do']) && ($_REQUEST['do']=='spamadmin_user')){
			$this->user();
		}
		if(isset($_REQUEST['do']) && ($_REQUEST['do']=='spamadmin_setup' or $_REQUEST['do']=='spamadmin_setup_save')){
			if ($_REQUEST['do']=='spamadmin_setup_save'){
				if ((isset($_REQUEST['chk_spamadmin_templatehack']))&&($_REQUEST['chk_spamadmin_templatehack']=='on')){
					$sql=new SQLq("UPDATE {pre}prefs SET spamadmin_templatehack='yes'");
				}else{
					$sql=new SQLq("UPDATE {pre}prefs SET spamadmin_templatehack='no'");
				}
				$this->Overview();
			}else{
				$this->setup();
			}
		}
		if(isset($_REQUEST['do']) && ($_REQUEST['do']=='spam_standard_folder')){
			$sql_user=new SQLq("SELECT id,email,spamaction FROM `{pre}users`");
			while($row=$sql_user->FetchArray())
			{
				if ($_REQUEST['spam'.$row['id']]=='true'){
					$this->SetSpamFolder($row['id'],$row['email']);
				}
			}
			$sql_user->FreeClose();
			$this->user();
		}
		if(isset($_REQUEST['do']) && ($_REQUEST['do']=='spam_rep_user')){
			$sql_user=new SQLq("SELECT id,email,spamaction FROM `{pre}users`");
			while($row=$sql_user->FetchArray())
			{
				if ($row['spamaction']!=-1){
					$found_folder=false;
					$sql_spam=new SQLq("SELECT titel FROM `{pre}folders` WHERE id='".$row['spamaction']."' and user='".$row['email']."'");
					while($row_spam=$sql_spam->FetchArray())
					{
						$found_folder=true;
					}
					$sql_spam->FreeClose();
					if ($found_folder!=true){
						$sql=new SQLq("UPDATE {pre}users SET spamaction='-1' WHERE id='".$row['id']."'");
					}
				}
			}
			$sql_user->FreeClose();
			$this->user();
		}

	}
	
	function OnCreateTemplate(&$tpl)
	{
		global $s_userrow;
		global $bm_prefs;
		if ($bm_prefs['spamadmin_templatehack']=='yes'){
			//Spam Folder Icon Hack
			//$spam_folder='';
			//if ($row['spamaction']!=-1){
			//	$sql_spam = new SQLq("SELECT titel FROM `{pre}folders` WHERE id='".$s_userrow['spamaction']."' and user='".$s_userrow['email']."'");
			//	while($row_spam = $sql_spam->FetchArray())
			//	{
			//		$spam_folder=$row_spam['titel'];
			//	}
			//	$sql_spam->FreeClose();
			//}else{
			//	$spam_folder='';
			//}
			//$tpl->assign('spam_folder_name',$spam_folder);
			
			if ($_REQUEST['action']=='common'){
				$tpl->register_outputfilter("spam_common");
			}
			if ($_REQUEST['action']=='save_common'){
				if (isset($_REQUEST['spamautoread'])){
					$sql=new SQLq("UPDATE {pre}users SET spamautoread='".$_REQUEST['spamautoread']."' WHERE id='".$s_userrow['id']."'");
					$sql->FreeClose();
				}
			}
		}
	}
	
	function Install()
	{
		$sql=new SQLq('ALTER IGNORE TABLE `{pre}users` ADD `spamautoread` int(1) DEFAULT 0 AFTER `spamaction`');
		$sql->FreeClose();
		$sql=new SQLq("ALTER IGNORE TABLE `{pre}prefs` ADD `spamadmin_templatehack` enum('yes','no') DEFAULT 'yes'");
		$sql->FreeClose();
		PutLog("Modul SPAM-ADMIN angelegt",PRIO_NOTE,__FILE__,__LINE__);
	}
	
	function Uninstall()
	{
		//wenn Eintrag gelöscht werden soll beim deaktiveren dann bitte // entfernen
		//$sql=new SQLq('ALTER TABLE {pre}users ADD DROP spamautoread');
		//$sql->FreeClose();
		//$sql=new SQLq('ALTER TABLE {pre}prefs ADD DROP spamadmin_templatehack');
		//$sql->FreeClose();
		PutLog("Modul SPAM-ADMIN deaktiviert",PRIO_NOTE,__FILE__,__LINE__);
	}
	
	function AfterPutMail($id,$mail){
		$sql=new SQLq("SELECT user,is_spam FROM {pre}mails WHERE id='".$id."'");
		while($row=$sql->FetchArray())
		{
			$useremail=$row['user'];
			$mailIsSpam=$row['is_spam'];
		}
		$sql->FreeClose();
		if ((isset($useremail))&&(isset($mailIsSpam))&&($mailIsSpam)){
			$sql=new SQLq("SELECT spamautoread FROM {pre}users WHERE email='".$useremail."'");
			while($row=$sql->FetchArray())
			{
				$spamautoread=$row['spamautoread'];
			}
			$sql->FreeClose();
			if ($spamautoread!=0){
				$sql=new SQLq("UPDATE {pre}mails SET gelesen='yes' WHERE id='".$id."'");
				PutLog("Modul SPAM-ADMIN 4 read",PRIO_NOTE,__FILE__,__LINE__);
				$sql->FreeClose();
			}
		}
	}
	
	function AfterFunctions(){
		global $s_userrow;
			if ($s_userrow['spamautoread']==1 && $s_userrow['spamaction']!=-1 && $s_userrow['id']){
			$sql_folders=new SQLq("SELECT user FROM {pre}folders WHERE id='".$s_userrow['spamaction']."'");//Sicherheitsabfrage das Folder auch zum User gehört
			$isok=false;
			while($row_folders=$sql_folders->FetchArray()){
				if ($row_folders['user']==$s_userrow['email']){
					$isok=true;					
				}
			}
			$sql_folders->FreeClose();
			if ($isok){
				$sql=new SQLq("UPDATE {pre}mails SET gelesen='yes' WHERE user='".$s_userrow['email']."' and folder='".$s_userrow['spamaction']."' and trashed='no'");
				$sql->FreeClose();
			}else{putLog("SPAM Error: ".$s_userrow['email']." SPAM-Ordner gehört nicht zum Benutzer!",PRIO_WARNING,__FILE__,__LINE__);}
		}
	}

}

	function spam_common($tpl_source,&$smarty){
		global $folderlist,$lang_main,$s_userrow;
		$sql=new SQLq("SELECT spamautoread FROM {pre}users WHERE id='".$s_userrow['id']."'");
		while($row=$sql->FetchArray())
		{
			$spamautoread=$row['spamautoread'];
		}
		$sql->FreeClose();
		$extra='</select>&nbsp;'.$lang_main['spamautoread'].'&nbsp;<select name="spamautoread"><option value="0" ';
		if ($spamautoread==0){
			$extra.='selected="selected"';
		}
		$extra.='>Nein</option><option value="1" ';
		if ($spamautoread!=0){
			$extra.='selected="selected"';
		}
		$extra.='>Ja</option>';
		$tpl_source=str_replace($folderlist,$folderlist.$extra,$tpl_source);
		return $tpl_source;
	}	
	
?>