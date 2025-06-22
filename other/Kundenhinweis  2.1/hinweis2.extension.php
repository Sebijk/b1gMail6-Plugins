<?php
$MODULE_CALL = 'Kundenhinweis2Ext';

class Kundenhinweis2Ext extends b1gMailModul
{function Kundenhinweis2Ext()
	{$this->titel		= 'Kundenhinweis2';
	$this->autor		= 'Turbo Internet Services (KREATIVE KOMMUNIKATION DIE VERBINDET)';
	$this->web		= 'http://www.Turbo-internet.eu/';
	$this->mail		= 'info@turbo-internet.eu';
	$this->version		= '2.1';
	$this->designedfor	= '6.31';
	$this->admin_pages	= 'true';
	$this->admin_page_title = 'Hinweisverwaltung';}

function Install()
		{$sql = new SQLq("ALTER TABLE {pre}users ADD hinweison varchar(255) NOT NULL default '0'");
		$sql = new SQLq("ALTER TABLE {pre}users ADD hinweistitel varchar(255) default NULL");
		$sql = new SQLq("ALTER TABLE {pre}users ADD hinweistext longtext default NULL");
  		return(true);}

function Uninstall()
		{$sql = new SQLq("ALTER TABLE {pre}users DROP hinweison");
		$sql = new SQLq("ALTER TABLE {pre}users DROP hinweistext");
		$sql = new SQLq("ALTER TABLE {pre}users DROP hinweistitel");
		return(true);}

function FileHandler($page, $action)
		{global $s_userid;
		global $tpl;
		if($page=='main.php' && ($action=='' || !isset($action)))
	
			{$sql = new SQLq("SELECT hinweison,hinweistext,hinweistitel FROM {pre}users WHERE id='$s_userid'");
           	        $row = $sql->FetchArray();
           	        $sql->FreeClose();
             	        $fehl = $row['hinweison'];
             	        if($fehl != "0")
				{$sql = new SQLq("SELECT email,hinweison,hinweistext,hinweistitel FROM {pre}users WHERE id='$s_userid'");
                		$row = $sql->FetchArray();
                  	        $email = $row['email'];
                  	        $hinweistext = $row['hinweistext'];
                  	        $hinweistitel = $row['hinweistitel'];
                 	        if($fehl!="1")
                		$sql = new SQLq("UPDATE {pre}users SET hinweison='0' WHERE id='$s_userid'");
	                        $tpl->assign('page', 'box.tpl');
	                        $tpl->assign('titel', $hinweistitel );
	                        $tpl->assign('text', $hinweistext );
	                        $tpl->display('index.tpl');
	                        exit();}
			}
		 }



function AdminHandler()	
	{if($_REQUEST['do'] == "save") 
		{$login = "0";
		if(isset($_REQUEST['login']))

		echo '<font color="#008000"><b>Einstellungen wurden gespeichert.</b></font>';}elseif($_REQUEST['do'] == "false") 

		{$id = $_POST['id'];
		$hinweison = $_POST['hinweison'];
		$hinweistitel = $_POST['hinweistitel'];
		$hinweistext = $_POST['hinweistext'];
		$sql = new SQLq("UPDATE {pre}users SET hinweison='" . $hinweison . "' WHERE id='" . $id . "'");
		$sql = new SQLq("UPDATE {pre}users SET hinweistitel='" . $hinweistitel . "' WHERE id='" . $id . "'");
		$sql = new SQLq("UPDATE {pre}users SET hinweistext='" . addslashes(nl2br($_REQUEST['hinweistext'])). "' WHERE id='" . $id . "'");
                   echo '<font color="#008000"><b>Die Hinweisoptionen wurden gespeichert!</b></font>';
		}


?>
<div align="center">
&nbsp;<form style="display:inline;" method="post" action="admin.php?action=modulepage&module=<?php echo($this->internal_name); ?>&do=false&PHPSESSID=<?php echo(session_id()); ?>">
<table width="90%" cellspacing="1" bgcolor="#999999" height="106">
<tr><td height="24" background="res/lauf.jpg" colspan="2">
&nbsp;&nbsp;<font color="#666666"><b>
Powerd by Turbo Internet Services (www.Turbo-Internet.eu) Anthony Steinmetz</b></font></td>
</tr><tr><td bgcolor="#efefef" height="11" width="20%"><p align="right">User:</td>
<td bgcolor="#f5f5f5" height="11" width="80%"><select size="1" name="id">
<?
$sql = new SQLq("SELECT * FROM {pre}users ");
while($typen=$sql->FetchArray())
		{echo '<option value="'.$typen['id'].'">';
    		echo $typen['id']." (".$typen['email'].")";
        	if($typen['datenfalsch']=="1")
   			 {echo " - Falsche Angaben";}echo '</option>';echo "";
		}

$sql->FreeClose;
?>
</select></tr><tr><td bgcolor="#efefef" height="11" width="20%">
<p align="right">Hinweisart:</td>
<td bgcolor="#f5f5f5" height="11" width="80%">
<select size="1" name="hinweison">
<option value="0" selected>AUS</option>
<option value="2">Einmaliger Hinweis</option>
<option value="1">Permanenter Hinweis</option>
</select></tr><tr><td bgcolor="#efefef" height="11" width="20%">
<p align="right">Betreff:</td>
<td bgcolor="#f5f5f5" height="11" width="80%">
<input type="text" name="hinweistitel" size="100">
</tr><tr><td bgcolor="#efefef" height="11" width="20%">
<p align="right">Text:</td>
<td bgcolor="#f5f5f5" height="11" width="80%">
<textarea rows="13" name="hinweistext" cols="67" tabindex="4"></textarea>
</tr><tr><td bgcolor="#efefef" height="11" width="20%">&nbsp;</td>
<td bgcolor="#f5f5f5" height="11" width="80%">
<input type="submit" value="Speichern" name="save"><? echo $did2; ?></tr>
</table><table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" height="1">
  <tr>
    <td width="100%" bgcolor="#FF0000" bordercolor="#FF0000" height="1" align="center">
    <font color="#FFFF00"><b><font size="5">Permanenter Hinweis:</font></b><font size="5">
    <br>
    </font>Kann nur durch Admin entfernt werden und der User kann auf den 
    Posteingang, Ordner, ...&nbsp; nicht zugreifen!</font></td>
  </tr>
  <tr>
    <td width="100%" bgcolor="#FFFF00" bordercolor="#FFFF00" height="1" align="center">
    <font size="5" color="#008000"><b>Einmaliger</b></font><font color="#008000"><b><font size="5"> 
    Hinweis:<br>
    </font></b>Der User bekommt den Hinweis nach dem einloggen ein mal 
    angezeigt, danach wird er gelöscht</font></td>
  </tr>
  <tr>
    <td width="100%" bgcolor="#008000" bordercolor="#008000" height="1" align="center">
    <font color="#FF0000"><b><font size="5">AUS:</font></b><font size="5"> <br>
    </font>Was aus bedeutet kann sich jeder denken :-)</font></td>
  </tr>
  <tr>
    <td width="100%" height="1" align="center"></td>
  </tr>
  <tr>
    <td width="100%" bgcolor="#111111" bordercolor="#111111" height="1" align="center">
    <font size="4" color="#FFFF00">Bei Fragen bitte auf dem </font>
    <font size="4"><a href="http://%20board.b1gmail.de">Board </a>
    <font color="#FFFF00">schauen&nbsp; <br>
    Das Modul wird kostenlos zur Verfügung gestellt! Anspruch auf Support gibt 
    es nicht! <br>
    Ansonsten wünscht </font><a href="http://www.turbo-internet.eu">Anthony 
    Steinmetz (Onlybest)</a></font><font size="4" color="#FFFF00"> noch viel 
    Spaß beim Einsatz :-)</font></td>
  </tr>
</table></form><br></div>
<?
	}
}
?>
