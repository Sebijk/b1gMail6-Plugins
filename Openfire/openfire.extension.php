<?php
/*
 * Copyright (c) 2007 - 2008, Home of the Sebijk.com
 * http://www.sebijk.com
 */

$MODULE_CALL = 'modopenfire';

class modopenfire extends b1gMailModul
{	

	// Informationen zum Modul
	function modopenfire()
	{
		
		$this->titel			= 'Jabber Openfire-Integration';
		$this->autor			= 'Home of the Sebijk.com';
		$this->web				= 'http://www.sebijk.com';
		$this->mail				= 'sebijk@web.de';
		$this->version			= '1.3';
		$this->designedfor		= '6.3.1';
		$this->admin_pages		=  true;		$this->admin_page_title	= 'Openfire';
	}
	
	// Installation	function Install()	{		global $db;		$sql = $db->Query("CREATE TABLE `{pre}mod_openfire` (				  `secretkey` varchar(255) NOT NULL default '',				  `domain` varchar(255) NOT NULL default ''				) ENGINE=MyISAM;");
		$sql = new SQLq("INSERT INTO `{pre}mod_openfire` (`secretkey`, `domain`) VALUES ('', 'localhost');");		PutLog("Modul \"Openfire-Integration\" wurde erfolgreich installiert.", PRIO_NOTE, __FILE__, __LINE__);		return(true);	}	// Deinstallation	function Uninstall()	{		global $db;		$sql = $db->Query("DROP TABLE {pre}mod_openfire;");		PutLog("Modul \"Openfire-Integration\" wurde erfolgreich deinstalliert.", PRIO_NOTE, __FILE__, __LINE__);		return(true);	}
		
	
	// Jabber-Registrierungen zum Openfire Server senden
	function OnSignup($userid, $usermail)
	{
		global $vorname, $name, $fullmail, $_REQUEST;

		$benutzername = btrim($_REQUEST['reg_mail']);
		$jabber_kennwort = btrim($_REQUEST['reg_pass']);
		$voller_name = $vorname." ".$name;
 
		// Konfiguration
		$sql = new SQLq("SELECT * FROM {pre}mod_openfire");
		$row = $sql->FetchArray();
		$userservice_secretkey = $row['secretkey'];
		$jabber_domain = $row['domain'];
		
		$sendjabber_register = "https://".$jabber_domain.":9091/plugins/userService/userservice?type=add&secret=".$userservice_secretkey."&username=".rawurlencode($benutzername)."&password=".rawurlencode($jabber_kennwort)."&name=".rawurlencode($voller_name)."&email=".rawurlencode($fullmail);
		$http = new HTTPRequest($sendjabber_register);
		$receive_url = $http->DownloadToString();
 
	}
	
	function OnDeleteUser($id)
	{
		global $db;
		
		$sql = $db->Query("SELECT email FROM {pre}users WHERE id=?",$id);
		$jabber_row = $sql->FetchArray();
		$jabber_email = $jabber_row['email'];
		
		$sql = $db->Query("SELECT * FROM {pre}mod_openfire");
		$jabber_row = $sql->FetchArray();
		
		$benutzername = explode("@", $jabber_email);
		
		$sendjabber_delete = "https://".$jabber_row['domain'].":9091/plugins/userService/userservice?type=delete&secret=".$jabber_row['secretkey']."&username=".rawurlencode($benutzername[0]);
		$http = new HTTPRequest($sendjabber_delete);
		$result = $http->DownloadToString();
	}
	
	// Admin-Handler	function AdminHandler()	{		
		If (isset($_POST['save'])) {
			$sql = new SQLq("UPDATE {pre}mod_openfire SET domain='".$_POST['openfire_domain']."',secretkey='".$_POST['openfire_userservice_secretkey']."'");
			$erfolg = "<br /><b>Die Daten wurden erfolgreich gespeichert!</b><br />";	
			}
	
		$sql = new SQLq("SELECT * FROM {pre}mod_openfire");
		$row = $sql->FetchArray();
		$openfire_userserivce_secretkey = $row['secretkey'];
		$openfire_domain = $row['domain'];		?>
		<body style="margin: 0px; background-color: #FFFFFF">
		<center>
		
	<form style="display:inline;" method="post" name="save" id="save" action="admin.php?action=modulepage&module=<?php echo($this->internal_name); ?>&PHPSESSID=<?php echo(session_id()); ?>">	<?php echo $erfolg; ?>
<table width="90%" cellspacing="1" bgcolor="#999999"" height="106">
 	 <tr>
 	  <td height="19" colspan="2" background="res/lauf.jpg">
    
     
        
      &nbsp;&nbsp;<font color="#666666"><b> 
      Openfire-Integration</b></font></td>
 	 </tr>

 	  <tr>
  		<td bgcolor="#f5f5f5" height="27" width="20%">Openfire-Domain:</td>
  		<td bgcolor="#f5f5f5" height="27" width="80%">
  		<input type="text" name="openfire_domain" size="20" value="<?php echo $openfire_domain;?>" tabindex="1"></td>
 	  </tr>
 	  <tr>
  		<td bgcolor="#f5f5f5" height="27" width="20%">Secret Key vom User Service Plugin:</td>
  		<td bgcolor="#f5f5f5" height="27" width="80%">
        <input type="text" name="openfire_userservice_secretkey" size="20" value="<?php echo $openfire_userservice_secretkey;?>" tabindex="2"></td>
     <tr>
  		<td bgcolor="#f5f5f5" height="11" width="20%">&nbsp;</td>
  		<td bgcolor="#f5f5f5" height="11" width="80%">
        <input type="submit" value="Speichern" name="save"></tr> 
        </table>
    <p />b1gMail Openfire-Integration &copy; 2007 - 2008, <a href="http://www.sebijk.com" target="_blank">Home of the Sebijk.com</a>
</div>
</form>
</center></body><?php
	}
}
?>