<?php

// +-------------------------------------------------------+
// | b1gMail 6 Modul                                       |
// +-------------------------------------------------------+
// +-------------------------------------------------------+
// | $Author: emailpoint
// | $Date: 2005/04/04 17:12:54 $
// | $Revision: 0.9 $
// +-------------------------------------------------------+
$MODULE_CALL = 'datenhinweis';

class datenhinweis extends b1gMailModul
{
	// Informationen zum Modul
	function datenhinweis()
	{
		global $s_loggedin;
		$this->titel		= 'Datenhinweis';
		$this->autor		= 'emailpoint.de';
		$this->web			= 'http://www.emailpoint.de';
		$this->mail			= 'info@unc4you.de';
		$this->version		= '0.9.6';
		$this->designedfor	= '6.2.0';
		$this->admin_pages	= true;
		$this->admin_page_title = "Datenhinweis";
	}

	// Installation
	function Install()
	{
        $sql = new SQLq("ALTER TABLE {pre}users ADD datenfalsch tinyint(4)default NULL");
        $sql = new SQLq("ALTER TABLE {pre}users ADD gesehen text");
        $sql = new SQLq("CREATE TABLE {pre}mod_falsch (
        id int(11) NOT NULL auto_increment,
        abzug text,
        titel text,
        inhalt text,
        login int(11),
        PRIMARY KEY  (id)
          ) TYPE=MyISAM");
        $sql = new SQLq("INSERT INTO `{pre}mod_falsch` (`id`,  `abzug`, `titel`, `inhalt`) VALUES ('1', '3600', 'Ihre Daten sind fehlerhaft', 'Wir haben festgestellt das Ihre Benutzerangaben falsch sind. Klicken Sie links im Menü bitte auf <i>Einstellungen</i> und dort auf <i>Allgemein</i>. Korrigieren Sie dort bitte Ihre persönliche Angaben (Vorname, Nachname, Strasse etc.).
Wir werden Ihre Angaben in wenigen Tagen erneut kontrolieren. Sollten Ihre Daten dann immer noch falsch sein, löschen wir Ihren Account. (Beachten Sie unsere <a href=\"index.php?action=agb\" target=\"_new\">AGB</a>)<br><a href=\"javascript:document.location.reload();\">Weiter zu Ihren Mails</a>')");
        return(true);
    }

	// Deinstallation
	function Uninstall()
	{
		$sql = new SQLq("ALTER TABLE {pre}users DROP datenfalsch");
		$sql = new SQLq("ALTER TABLE {pre}users DROP gesehen");
		$sql = new SQLq("DROP TABLE {pre}mod_falsch");
		return(true);
	}


	// Hauptfunktion2
	function FileHandler($page, $action)
	{
		global $s_userid;
		global $tpl;
		if($page=='main.php' && ($action=='' || !isset($action)))
		{
			$t=time();
			$sql = new SQLq("SELECT datenfalsch FROM {pre}users WHERE id='$s_userid'");
			$row = $sql->FetchArray();
			$daten = $row['datenfalsch'];
			$sql = new SQLq("SELECT * FROM {pre}users WHERE id='$s_userid'");
			$row = $sql->FetchArray();
			$gesehen = $row['gesehen'];
			$sql = new SQLq("SELECT * FROM {pre}mod_falsch WHERE id = '1'");
			$row = $sql->FetchArray();
			$sql->FreeClose();
			$titel = $row['titel'];
			$inhalt = $row['inhalt'];
			$abzug = $row['abzug'];
			if($t-$abzug >= $gesehen)
			{
				if($daten == 1)
				{
					$sql = new SQLq("UPDATE {pre}users SET gesehen='$t' WHERE id='$s_userid'");
					$tpl->assign('page', 'box.tpl');
					$tpl->assign('titel', $titel);
					$tpl->assign('text', $inhalt);
					$tpl->display('index.tpl');

					exit();
				}
		}
		}
	}
		//Administrationsfunktion
	function AdminHandler()	{

	if($_REQUEST['do'] == "save") {
		$login = "0";
		if(isset($_REQUEST['login']))
			$login = "1";
	$sql = new SQLq("UPDATE {pre}mod_falsch SET abzug='".$_REQUEST['abzug']."', titel='".$_REQUEST['titel']."',inhalt='".$_REQUEST['inhalt']."',login='".$login."' WHERE id='1'");
	echo '<font color="#008000"><b>Einstellungen wurden gespeichert.</b></font>';
	} elseif($_REQUEST['do'] == "false") {
	  	$id = $_POST['id'];
		$status = $_POST['status'];
		$sql = new SQLq("UPDATE {pre}users SET datenfalsch='" . $status . "' WHERE id='" . $id . "'");
		$inhalt = $_POST['mail'];
			if($inhalt != ""){
				$sql = new SQLq("SELECT email FROM {pre}users WHERE id='".$id."'");
				$row = $sql->FetchArray();
				$email = $row['email'];
				$sql = new SQLq("SELECT passmail_abs FROM {pre}prefs WHERE id='1'");
				$row = $sql->FetchArray();
				$abs = $row['passmail_abs'];
                $betreff = "Mitteilung vom Postmaster";
                mail($email, $betreff, $inhalt, "From: Postmaster <".$abs.">");
                }
		echo '<font color="#008000"><b>Einstellungen wurden gespeichert.</b></font>';
	}

	$sql = new SQLq("SELECT * FROM {pre}mod_falsch WHERE id = '1'");
	$row = $sql->FetchArray();
	$titel = $row['titel'];
	$inhalt = $row['inhalt'];
	$abzug = $row['abzug'];
	$login = $row['login'];

	$sql->FreeClose();
?>
<div align="center">
<br>
 <form style="display:inline;" method="post" action="admin.php?action=modulepage&module=<?php echo($this->internal_name); ?>&do=save&PHPSESSID=<?php echo(session_id()); ?>">
 <table width="90%" cellspacing="1" bgcolor="#999999" height="106">
 	 <tr>
 	  <td height="24" background="res/lauf.jpg" colspan="2">
      &nbsp;&nbsp;<font color="#666666"><b>
      Hinweis bei Falschangaben</b></font></td>
 	 </tr>
     <tr>
  		<td bgcolor="#efefef" height="11" width="20%">
        <p align="right">Titel der Box:</td>
  		<td bgcolor="#f5f5f5" height="11" width="80%">
        <input type="text" name="titel" value="<?=$titel;?>" size="20" tabindex="3"></tr>
     <tr>
  		<td bgcolor="#efefef" height="11" width="20%">
        <p align="right">Hinweis alle <b>X</b> Sekunden anzeigen:</td>
  		<td bgcolor="#f5f5f5" height="11" width="80%">
        <input type="text" name="abzug" value="<?=$abzug;?>" size="20" tabindex="3"></tr>
     <tr>
  		<td bgcolor="#efefef" height="13" width="20%">&nbsp;</td>
  		<td bgcolor="#f5f5f5" height="13" width="80%">&nbsp;</tr>
     <tr>
  		<td bgcolor="#efefef" height="181" width="20%">
        <p align="right">Text:</td>
  		<td bgcolor="#f5f5f5" height="181" width="80%">
        <textarea rows="13" name="inhalt" cols="67" tabindex="4"><?=$inhalt;?></textarea><br> Achtung! Sollte einen Link in dieser Form enthalten:<br><textarea rows="1" cols="67" tabindex="4"><a href="javascript:document.location.reload();">Weiter</a></textarea></tr>
     <tr>
  		<td bgcolor="#efefef" height="11" width="20%">&nbsp;</td>
  		<td bgcolor="#f5f5f5" height="11" width="80%">
        <input type="submit" value="Speichern" name="save"></tr>
        </table>
	</form>
<br><br>
 <form style="display:inline;" method="post" action="admin.php?action=modulepage&module=<?php echo($this->internal_name); ?>&do=false&PHPSESSID=<?php echo(session_id()); ?>">
 <table width="90%" cellspacing="1" bgcolor="#999999" height="106">
 	 <tr>
 	  <td height="24" background="res/lauf.jpg" colspan="2">
      &nbsp;&nbsp;<font color="#666666"><b>
      User (de)markieren</b></font></td>
 	 </tr>
     <tr>
  		<td bgcolor="#efefef" height="11" width="20%">
        <p align="right">User ID:</td>
  		<td bgcolor="#f5f5f5" height="11" width="80%"><select size="1" name="id">
<?
$sql = new SQLq("SELECT * FROM {pre}users ORDER BY `datenfalsch` DESC");
while($typen=$sql->FetchArray())
{
    echo '<option value="'.$typen['id'].'">';
    echo $typen['id']." (".$typen['email'].")";
        if($typen['datenfalsch']=="1")
    {
        echo " - Falsche Angaben";
    }
    echo '</option>';
    echo "
    ";
}
$sql->FreeClose;
?>
</select>
</tr>
     <tr>
  		<td bgcolor="#efefef" height="11" width="20%">
        <p align="right">Status:</td>
  		<td bgcolor="#f5f5f5" height="11" width="80%">
        <select size="1" name="status">
    	<option value="1" selected>Daten sind falsch</option>
    	<option value="0">Daten sind richtig</option>
  		</select>
     </tr>
     <tr>
  		<td bgcolor="#efefef" height="11" width="20%">
        <p align="right">Mail schicken? (leer lassen wenn nicht):</td>
  		<td bgcolor="#f5f5f5" height="11" width="80%">
        <textarea rows="13" name="mail" cols="67" tabindex="4"></textarea>
     </tr>
     <tr>
  		<td bgcolor="#efefef" height="11" width="20%">&nbsp;</td>
  		<td bgcolor="#f5f5f5" height="11" width="80%">
        <input type="submit" value="Speichern" name="save"><? echo $did2; ?></tr>
        </table>
</form><br>
<?
$url_to_grab = 'http://www.emailpoint.de/mods/datenmod.php';
$start = '<version>';
$end = '</version>';

// Grab source code from a file or web site
if(!($myFile=@fopen($url_to_grab,"r")))
{
echo "The updateserver is down for maintenance.";
}

while(!feof($myFile))
{
// Read each line and add to $version
$version.=fgets($myFile,255);
}
fclose($myFile);

$start_position=strpos($version, $start);
$end_position=strpos($version, $end)+strlen($end);
$length=$end_position-$start_position;
$version=substr($version, $start_position, $length);
$version=ereg_replace('<version>', '', $version);
$version=ereg_replace('</version>', '', $version);
	if($version != "0.9.6")
		echo '<b>Ihre Version ist evt. nicht aktuell. Die aktuellste Version finden Sie <a href="http://www.emailpoint.de/mods/daten.extension.zip">hier</a>.</b>';
?>
</div>
<?
	}
}
?>