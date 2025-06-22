<?php
$MODULE_CALL = 'modSpamassassin';
class modSpamassassin extends b1gMailModul
{
 function modSpamassassin()
 {
  $this->titel  = 'Auswertung der Spamassassin X-Header';
  $this->autor  = 'emailpoint.de';
  $this->web   = 'http://www.emailpoint.de/';
  $this->mail   = 'info@emailpoint.de';
  $this->version  = '1.0';
  $this->designedfor = '6.3.0';
 }

 function OnReceive2(&$mail, &$user, &$s)
 {
  $spamStatus = $mail->GetHeader('x-spam-status');
  if((substr(trim($spamStatus), 0, 3)) == 'Yes'){
  #mail("yourmail@example.org", "Es ist...", $spamStatus);
  return BM_IS_SPAM;
  } else {
  #mail("yourmail@example.org", "Seeeee...", $spamStatus);
  return BM_OK;
  }
 }

 function FileHandler($page, $action)
	{
		global $s_userid;
		global $tpl;
		if($page=='main.php' && $action=='bspam'){
		    if(isset($_REQUEST['isspam'])){
                $todo = "sudo /usr/bin/sa-learn --spam /path/to/b1gmail/data/" . DataFilename($_REQUEST['id'], 'msg');
                $process = exec($todo);
            }
        	if(!isset($_REQUEST['isspam'])){
		        $todo = "sudo /usr/bin/sa-learn --ham /path/to/b1gmail/data/" . DataFilename($_REQUEST['id'], 'msg');
                $process = exec($todo);
#mail("yourmail@example.org", "Spam?", $process);
			}
		}

		if($page=='main.php' && $_REQUEST['action2']=='markspam'){
		reset ($_REQUEST);
			while(list($key,$val) = each($_REQUEST)) {
				$thisid = substr($key, 4);
                if (substr($key, 0, 4) == "msg_") {
                    $direct = $direct . " /path/to/b1gmail/data/" . DataFilename(addslashes($thisid), 'msg');
                }
            }
        $todo = "sudo /usr/bin/sa-learn --spam" . $direct;
        $process = exec($todo);
		}

		if($page=='main.php' && $_REQUEST['action2']=='marknospam'){
		reset ($_REQUEST);
			while(list($key,$val) = each($_REQUEST)) {
				$thisid = substr($key, 4);
                if (substr($key, 0, 4) == "msg_") {
                    $direct = $direct . " /path/to/b1gmail/data/" . DataFilename(addslashes($thisid), 'msg');
                }
            }
        $todo = "sudo /usr/bin/sa-learn --ham" . $direct;
        $process = exec($todo);
		}
	}
}
