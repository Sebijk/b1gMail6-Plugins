<?php
/*
 * Checkmail
 * Original Autor: haggi0505
 * (ph234@web.de)
 *
 * Optimized by Sebijk
 * http://www.sebijk.de
 */

$MODULE_CALL = 'modcheckmail';

class modcheckmail extends b1gMailModul
{	

	function modcheckmail()
	{
		global $s_loggedin;
		
		$this->titel			= 'CheckMail';
		$this->autor			= 'Sebijk, haggi0505';
		$this->web				= 'http://www.sebijk.de';
		$this->mail				= 'sebijk@web.de';
		$this->version			= '1.2';
		$this->designedfor		= '6.3.1';

		
		if($s_loggedin=='yes')
		{
			$this->user_pages = true;
			$this->user_page_array = array(0 => array('title' => 'CheckMail', 'link' => 'main.php?action=checkmail&amp;bmsession='.bmSession_ID().'" target="checkmail" onclick="void(window.open(\'main.php?action=checkmail&amp;bmsession='.bmSession_ID().'\',\'checkmail\',\'toolbar=no,width=190,height=280,resizable=yes,scrollbars=no\'));'));
		}	
	}
		
	function FileHandler(&$file, $action)
	{
		global $_REQUEST, $tpl, $lang_main, $db, $s_userrow, $s_usermail;
		
		if($file=="main.php" && $action=="checkmail")
		{
			// Mails auslesen
			$sql = $db->Query("SELECT COUNT(*) AS n FROM {pre}mails WHERE gelesen='no' AND user=?", $s_usermail);
			$notread = $sql->FetchArray();
			$notread = intval($notread['n']);
			$sql->FreeClose();
			unset($sql);
			$wtext = str_replace("%%mails%%", $notread, $lang_main['welcometext']);
			// Templatevariabeln zuweisen
			$tpl->assign('notread', $notread);
			$tpl->assign('zeitangabe', gmdate("d M Y, H:i:s", time()));
			$tpl->assign('in_refresh', $s_userrow['in_refresh']);
			$tpl->assign('willkommenstext', $wtext);
			$tpl->display('checkmailextern.tpl');
		}
	}
}

?>