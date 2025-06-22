<?php

// +-------------------------------------------------------+
// | b1gMail 6 Modul                                       |
// +-------------------------------------------------------+
// +-------------------------------------------------------+
// | $Author: Kufstein 
// | $Date: 2005/03/13 22:44:25 $
// | $Revision: 1.1 $
// +-------------------------------------------------------+


// Willkommens E-Mail - Erweiterung
// Sendet automatisch eine E-Mail an einen Neuregistrierten Benutzer


$MODULE_CALL = 'startmail';

class startmail extends b1gMailModul
{
	// Informationen zum Modul
	function startmail()
	{
		$reg = array();
		ereg('([0-9]*)\.([0-9]*)', '$Revision: 1.2 $', $reg);
		
		$this->titel		= 'Willkommens Mail 1.1';
		$this->autor		= 'Kufstein.ws';
		$this->web			= 'http://crew.kufstein.ws';
		$this->mail			= 'tec@support.kufstein.ws';
		$this->version		= $reg[0];
		$this->designedfor	= '6.2.0';
		$this->admin_pages	= true;
		$this->admin_page_title = "Willkommens Mail";
	}


	// Installieren
	function Install()
	{
		$sql = new SQLq("CREATE TABLE {pre}mod_wmail (
  		id int(11) NOT NULL auto_increment,
  		von text, 
  		vonm text,
  		betreff text,
		inhalt text,
  		PRIMARY KEY  (id)
		) TYPE=MyISAM;");
		$sql = new SQLq("INSERT INTO `{pre}mod_wmail` (`id`, `von`, `vonm`, `betreff`, `inhalt`) VALUES ('', NULL, NULL, NULL, NULL);");
		return(true);
 		
	}
	
	// Deinstallieren
	function Uninstall()
	{
		$sql = new SQLq("DROP TABLE {pre}mod_wmail;");
		return(true);
	}
	//Die Mailsendefunktion	
	function OnSignup($userid, $usermail)
	
	{
	$sql = new SQLq("SELECT * FROM {pre}users WHERE id = '" . $userid . "'");
	$row2 = $sql->FetchArray();
	$vorname = $row2['vorname'];
	$nachname = $row2['nachname'];
	$name = $vorname . " " . $nachname;

	$sql = new SQLq("SELECT * FROM {pre}mod_wmail WHERE id = '1'");
	$row = $sql->FetchArray();
	$betreff = $row['betreff'];
	$von = $row['von'];
	$vonm = $row['vonm'];
	$inhalt = $row['inhalt'];

	$sql->FreeClose();
	$msg = eregi_replace('%%user-name%%', $name, $inhalt);
	$msg = eregi_replace('%%user-mail%%', $usermail, $msg);
	
	$betreff = eregi_replace('%%user-name%%', $name, $betreff);
	$betreff = eregi_replace('%%user-mail%%', $usermail, $betreff);
	
	mail($usermail, $betreff, $msg, "From: \"$von\" <$vonm>\nReply-To: \"$von\" <$vonm>" );
		

	}

	//Administrationsfunktion
	function AdminHandler()	{

	IF ($_REQUEST['do'] == "save") {
	$sql = new SQLq("UPDATE {pre}mod_wmail SET betreff='".$_REQUEST['betreff']."',vonm='".$_REQUEST['vonm']."',von='".$_REQUEST['von']."',inhalt='".$_REQUEST['inhalt']."' WHERE id='1'");
			unset($_REQUEST['do']);		
	} else { }


	$sql = new SQLq("SELECT * FROM {pre}mod_wmail WHERE id = '1'");
	$row = $sql->FetchArray();
	$betreff = $row['betreff'];
	$von = $row['von'];
	$vonm = $row['vonm'];
	$inhalt = $row['inhalt'];

	$sql->FreeClose();
?>

  <div align="center">
 <form style="display:inline;" method="post" action="admin.php?action=modulepage&module=<?php echo($this->internal_name); ?>&do=save&PHPSESSID=<?php echo(session_id()); ?>">

 <table width="90%" cellspacing="1" bgcolor="#063781" height="106">
 	 <tr>
 	  <td height="19" colspan="2">
    
     
        
      &nbsp;&nbsp;<font color="#FFBE32"><b> 
      Automatische E-Mail beim Registrieren</b></font></td>
 	 </tr>

 	  <tr>
  		<td bgcolor="#EDEEF5" height="37" width="20%">Absendername der&nbsp;&nbsp;&nbsp; 
        E-Mail:</td>
  		<td bgcolor="#EDEEF5" height="37" width="80%">&nbsp;<input name="von" size="20" value="<?=$von;?>" tabindex="1" style="float: left"></td>
 	  </tr>
 	  <tr>
  		<td bgcolor="#EDEEF5" height="27" width="20%">&nbsp;Absenderadresse 
        der E-Mail</td>
  		<td bgcolor="#EDEEF5" height="27" width="80%">
        <input type="text" name="vonm" size="20" value="<?=$vonm;?>" tabindex="2"><tr>
  		<td bgcolor="#EDEEF5" height="11" width="20%">
        <p align="right">Betreff:</td>
  		<td bgcolor="#EDEEF5" height="11" width="80%">
        <input type="text" name="betreff" value="<?=$betreff;?>" size="20" tabindex="3"></tr>
     <tr>
  		<td bgcolor="#EDEEF5" height="13" width="20%">&nbsp;</td>
  		<td bgcolor="#EDEEF5" height="13" width="80%">&nbsp;</tr>
     <tr>
  		<td bgcolor="#EDEEF5" height="181" width="20%">
        <p align="right">Nachricht:</td>
  		<td bgcolor="#EDEEF5" height="181" width="80%">
        <textarea rows="13" name="inhalt" cols="67" tabindex="4"><?=$inhalt;?></textarea></tr>
     <tr>
  		<td bgcolor="#EDEEF5" height="11" width="20%">&nbsp;</td>
  		<td bgcolor="#EDEEF5" height="11" width="80%">Name = %%user-name%%<br>
        E-Mailadresse = %%user-mail%%</tr>
     <tr>
  		<td bgcolor="#EDEEF5" height="11" width="20%">&nbsp;</td>
  		<td bgcolor="#EDEEF5" height="11" width="80%">
        <input type="submit" value="Speichern" name="save"></tr> 
        </table>
    
</div>
</form>

<?




	}
}
?>
