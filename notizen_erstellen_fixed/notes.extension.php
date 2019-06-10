<?

$MODULE_CALL = 'modNotes';

class modNotes extends b1gMailModul
{	

	function modNotes()
	{
		global $s_loggedin;
		
		$this->titel			= 'Notizen';
		$this->autor			= 'radada, Sebijk';
		$this->web				= '';
		$this->mail				= 'hcs.ts@gmx.de';
		$this->version			= '1.0';
		$this->designedfor		= '6.3.1';

		
		if($s_loggedin=='yes')
		{
			$this->user_pages = true;
			$this->user_page_array = array(0 => array('title' => 'Notizen', 'link' => 'main.php?action=notes&bmsession='.bmSession_ID()));
		}	
	}
		
	function FileHandler(&$file, $action)
	{
		global $_REQUEST;
		global $s_userid;
		global $tpl;
		global $FCKeditorBasePath;
		
		if($file=="main.php" && $action=="notes")
		{
		
			if($_REQUEST['do']=="del" && is_numeric($_REQUEST['id']))
			{
				$sql = new SQLq("DELETE FROM {pre}notes WHERE id='".$_REQUEST['id']."' AND userid='".$s_userid."'");
				$sql->FreeClose();
				unset($_REQUEST['do']);
			}
			
			if($_REQUEST['do']=="new")
			{
				$FCKeditorBasePath = "./editor/";
				$fck = new FCKEditor();
				$fck->Value = '';
				$editor=$fck->ReturnFCKeditor("text", "100%", "400px");
				
				$tpl->assign('page', 'notizen_erstellen.tpl');
				$tpl->assign('editor',$editor);
				$tpl->display('index.tpl');
			}
			
			if($_REQUEST['do']=="savenew")
			{
				if(trim($_REQUEST['betreff'])=="")
				{
					$_REQUEST['betreff']="-";
				}
				$sql = new SQLq("INSERT INTO {pre}notes(userid,zeit,betreff,text) VALUES('".$s_userid."','".time()."','".str_replace(array("'","<",">"),array("\\'","&lt;","&gt;"),$_REQUEST['betreff'])."','".str_replace("'","\\'",$_REQUEST['text'])."')");
				$sql->FreeClose();
				unset($_REQUEST['do']);
			}
			
			if($_REQUEST['do']=="edit")
			{
				if(is_numeric($_REQUEST['id']))
				{
					$sql = new SQLq("SELECT * FROM {pre}notes WHERE id='".$_REQUEST['id']."' AND userid='".$s_userid."'");
					if($sql->RowCount()>0)
					{
						$row=$sql->FetchArray();
						$FCKeditorBasePath = "./editor/";
						$fck = new FCKEditor();
						$fck->Value = htmlentities($row['text']);
						$editor=$fck->ReturnFCKeditor("text", "100%", "400px");
						
						$tpl->assign('page', 'notizen_aendern.tpl');
						$tpl->assign('note',array("editor"=>$editor,"betreff"=>$row['betreff'],"id"=>$row['id']));
						$tpl->display('index.tpl');
					}
					else
					{
						unset($_REQUEST['do']);
					}
					$sql->FreeClose();
				}
				else
				{
					unset($_REQUEST['do']);
				}
			}

			if($_REQUEST['do']=="saveedit")
			{
				if(trim($_REQUEST['betreff'])=="")
				{
					$_REQUEST['betreff']="-";
				}
				$notiz_text = $_REQUEST['text'];
				$notiz_text = str_replace("'","\\'",$notiz_text);
				$notiz_text = htmlspecialchars($notiz_text);
				$sql = new SQLq("UPDATE {pre}notes SET betreff='".str_replace(array("'","<",">"),array("\\'","&lt;","&gt;"),$_REQUEST['betreff'])."',text='".$notiz_text."' WHERE id='".str_replace("'","\\'",$_REQUEST['id'])."' AND userid='".$s_userid."'");
				$sql->FreeClose();
				$_REQUEST['do']="show";
			}

			if($_REQUEST['do']=="show")
			{
				if(is_numeric($_REQUEST['id']))
				{
					$sql = new SQLq("SELECT * FROM {pre}notes WHERE userid='".$s_userid."' AND id='".$_REQUEST['id']."'");
					if($sql->RowCount()>0)
					{
						$row=$sql->FetchArray();
						$note=array("id"=>$row['id'],"betreff"=>$row['betreff'],"uhrzeit"=>date("H:i:s",$row['zeit']),"datum"=>date("d.m.Y",$row['zeit']),"text"=>htmlspecialchars($row['text']));
						$tpl->assign('page', 'notizen_anzeigen.tpl');
						$tpl->assign('note',$note);
						$tpl->display('index.tpl');
					}
					else
					{
						unset($_REQUEST['do']);
					}
					$sql->FreeClose();
				}
				else
				{
					unset($_REQUEST['do']);
				}
			}

			if(!isset($_REQUEST['do']))
			{
				$sql = new SQLq("SELECT * FROM {pre}notes WHERE userid='".$s_userid."' ORDER BY id DESC");
				$notes=array();
				while($row=$sql->FetchArray())
				{
					$notes[]=array("id"=>$row['id'],"betreff"=>$row['betreff'],"uhrzeit"=>date("H:i:s",$row['zeit']),"datum"=>date("d.m.Y",$row['zeit']));						
				}
				$sql->FreeClose;
				$tpl->assign('page', 'notizen.tpl');
				$tpl->assign('notes',$notes);
				$tpl->display('index.tpl');
			}
		}
	}
	
	function Install()
	{
		$sql = new SQLq("CREATE TABLE `{pre}notes` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11),
  `zeit` int(11),
  `betreff` varchar(255),
  `text` text,
  PRIMARY KEY  (`id`)
);");
		$sql->FreeClose();
	}

	function Uninstall()
	{
		$sql = new SQLq("DROP TABLE `{pre}notes`");
		$sql->FreeClose();
	}
}

?>