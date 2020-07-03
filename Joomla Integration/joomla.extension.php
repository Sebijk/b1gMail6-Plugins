<?php
/*
 * Copyright (c) 2007, Home of the Sebijk.de
 * http://www.sebijk.de
 */

$MODULE_CALL = 'modjoomla';

class modjoomla extends b1gMailModul
{	

	function modjoomla()
	{
		
		$this->titel			= 'Joomla Integration (Registrierung)';
		$this->autor			= 'Sebijk';
		$this->web				= 'http://www.sebijk.de';
		$this->mail				= 'sebijk@web.de';
		$this->version			= '1.0';
		$this->designedfor		= '6.3.1';	
	}
		
	
function OnSignup($userid, $usermail)

 {
  global $db, $vorname, $name, $fullmail, $_REQUEST;
  
  /** Sollte Joomla nicht in der gleichen Datenbank wie b1gMail installiert sein,
   so koennen Sie hier die Datenbank zu Joomla eingeben. **/
   
   $joomla_db = "";
  
 $joomla_benutzername = btrim($_REQUEST['reg_mail']);
 $joomla_emailadresse = btrim($fullmail);
 $joomla_kennwort = btrim($_REQUEST['reg_pass']);
 $joomla_kennwort = md5($joomla_kennwort);
 $joomla_id = intval($db->InsertId());
 $voller_name = $vorname." ".$name;
 
 // Noch in Entwicklung:
 $joomla_lastvisitdate = "";
 
 // Query ausfuehren
 $db->Query("INSERT INTO jos_users (id,name,username,email,password,usertype,block,sendEmail,gid,registerDate,lastvisitDate,activation,params) VALUES (?,?,?,?,?,'users','0','1','18',?,'0000-00-00 00:00:00','','')", $joomla_id, $voller_name, $joomla_emailadresse, $joomla_benutzername, $joomla_kennwort, $joomla_lastvisitdate);
 
 // Variabeln leeren
unset($joomla_db);
unset($joomla_emailadresse);
unset($joomla_kennwort);
unset($joomla_id);
unset($voller_name);
 } 
}

?>