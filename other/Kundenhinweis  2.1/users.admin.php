<?php
// +-------------------------------------------------------+
// | b1gMail 6                                             |
// +-------------------------------------------------------+
// | b1gMail ist keine Freeware (seit Version 6), es darf  |
// | nicht unerlaubt verfielfältigt werden!                |
// +-------------------------------------------------------+
// | Copyright (c) 2002-2004 by B1G PHP Scripts            |
// +-------------------------------------------------------+
// | $Author: patrick $
// | $Date: 2006/01/12 15:52:09 $
// | $Revision: 1.17 $
// | $Source: /cvs/b1gmail6/script/admin/users.admin.php,v $
// +-------------------------------------------------------+
// | $Download_Date: Fri, 13 Oct 2006 14:10:31 +0200 $
// | $License: SL298708 $
// +-------------------------------------------------------+

if(isset($_REQUEST['directcall']) || $directcall != "no") {
	@include("../lib/functions.inc.php");
	ErrorPage("Direkter Aufruf", "Der direkte Aufruf von Dateien im Format xyz.admin.php ist nicht möglich. Bitte nutzen Sie den Login vom Admin-Bereich!");
	exit;
}

if(!isset($_REQUEST['do'])) {
	$_REQUEST['do'] = "main";
}

if($_REQUEST['do'] != "inactive") {
	$reqdo = "b_".$_REQUEST['do'];
	$reqdo2 = "b_".$_REQUEST['do']."end";
	$$reqdo = "<b>";
	$$reqdo2 = "</b>";
	?>
	<table width="100%" bgcolor="#999999" cellspacing="1" border="0">
		<tr>
			<td height="19" align="right" background="res/lauf.jpg">
				<a href="admin.php?action=users&do=main&PHPSESSID=<?php echo(session_id()); ?>" style="color:#666666;"><?php echo($b_main.$lang_admin['overview'].$b_mainend); ?></a> | 
				<a href="admin.php?action=users&do=lockedusers&PHPSESSID=<?php echo(session_id()); ?>" style="color:#666666;"><?php echo($b_lockedusers.$lang_admin['lockedusers'].$b_lockedusersend); ?></a> | 
				<a href="admin.php?action=users&do=yeslockedusers&PHPSESSID=<?php echo(session_id()); ?>" style="color:#666666;"><?php echo($b_yeslockedusers.$lang_admin['yeslockedusers'].$b_yeslockedusersend); ?></a> | 
				<a href="admin.php?action=users&do=todel&PHPSESSID=<?php echo(session_id()); ?>" style="color:#666666;"><?php echo($b_todel.$lang_admin['todel'].$b_todelend); ?></a> | 
				<a href="admin.php?action=users&do=search&PHPSESSID=<?php echo(session_id()); ?>" style="color:#666666;"><?php echo($b_search.$lang_admin['search'].$b_searchend); ?></a> | 
				<a href="admin.php?action=users&do=lock&PHPSESSID=<?php echo(session_id()); ?>" style="color:#666666;"><?php echo($b_lock.$lang_admin['lock'].$b_lockend); ?></a> | 
				<a href="admin.php?action=users&do=create&PHPSESSID=<?php echo(session_id()); ?>" style="color:#666666;"><?php echo($b_create.$lang_admin['erstellen'].$b_create); ?></a>&nbsp;&nbsp;
			</td>
		</tr>
	</table>
	<?php
}
	
if($_REQUEST['do'] == "main" || $_REQUEST['do'] == "inactive" || $_REQUEST['do'] == "yeslockedusers" || $_REQUEST['do'] == "lockedusers" || $_REQUEST['do'] == "todel") {
	if(!isset($_REQUEST['step']) || $_REQUEST['step'] == "main") {
			
		if(!isset($_REQUEST['number'])) {
			$number = 50;
		} else {
			$number = $_REQUEST['number'];
		}
		if(isset($_REQUEST['orderby']) && $_REQUEST['orderby'] != "") {
			$orderby = $_REQUEST['orderby'];
			$_SESSION['orderby'] = $orderby;
			$asc_desc = $_REQUEST['asc_desc'];
			$_SESSION['asc_desc'] = $asc_desc;
		} else {
			if(isset($_SESSION['orderby'])) {
				$orderby = $_SESSION['orderby'];
				$asc_desc = $_SESSION['asc_desc'];
			} else {
				$orderby = "email";
				$asc_desc = "ASC";
			}
		}
	 
		$where_tmp = "";
			 
		if(isset($_REQUEST['search']) && $_REQUEST['search'] == "yes") {
			// '*' durch '%' ersetzen, da '*' geläufiger
			$_REQUEST['value'] = str_replace("*", "%", $_REQUEST['value']);
		
			if($_REQUEST['search_email']) {
				$where_tmp .= "email LIKE '%".$_REQUEST['value']."%'";
			}
			if($_REQUEST['search_vorname']) {
				if($where_tmp != "") { $where_tmp .= " OR "; }
				$where_tmp .= "vorname LIKE '%".$_REQUEST['value']."%'";
			}
			if($_REQUEST['search_nachname']) {
				if($where_tmp != "") { $where_tmp .= " OR "; }
				$where_tmp .= "nachname LIKE '%".$_REQUEST['value']."%'";
			}
			if($_REQUEST['search_hnr']) {
				if($where_tmp != "") { $where_tmp .= " OR "; }
				$where_tmp .= "hnr LIKE '%".$_REQUEST['value']."%'";
			}
			if($_REQUEST['search_plz']) {
				if($where_tmp != "") { $where_tmp .= " OR "; }
				$where_tmp .= "plz LIKE '%".$_REQUEST['value']."%'";
			}				
			if($_REQUEST['search_strasse']) {
				if($where_tmp != "") { $where_tmp .= " OR "; }
				$where_tmp .= "strasse LIKE '%".$_REQUEST['value']."%'";
			}
			if($_REQUEST['search_ort']) {
				if($where_tmp != "") { $where_tmp .= " OR "; }
				$where_tmp .= "ort LIKE '%".$_REQUEST['value']."%'";
			}
			if($_REQUEST['search_land']) {
				if($where_tmp != "") { $where_tmp .= " OR "; }
				$where_tmp .= "land LIKE '%".$_REQUEST['value']."%'";
			}
			if($_REQUEST['search_tel']) {
				if($where_tmp != "") { $where_tmp .= " OR "; }
				$where_tmp .= "tel LIKE '%".$_REQUEST['value']."%'";
			}
			if($_REQUEST['search_fax']) {
				if($where_tmp != "") { $where_tmp .= " OR "; }
				$where_tmp .= "fax LIKE '%".$_REQUEST['value']."%'";
			}				
			if($_REQUEST['search_altmail']) {
				if($where_tmp != "") { $where_tmp .= " OR "; }
				$where_tmp .= "altmail LIKE '%".$_REQUEST['value']."%'";
			}
			if($_REQUEST['search_gruppe']) {
				$sql_tmp = new SQLq("SELECT id FROM {pre}gruppen WHERE titel LIKE '%".$_REQUEST['value']."%'");
					if($sql_tmp->RowCount()) {
						while($row = $sql_tmp->FetchArray()) {
							if($where_tmp != "") { $where_tmp .= " OR "; }
							$where_tmp .= "gruppe = '".$row['id']."'";
						}
					}
				$sql_tmp->FreeClose();
			}
			if($_REQUEST['search_passwort']) {
				if($where_tmp != "") { $where_tmp .= " OR "; }
				$where_tmp .= "passwort = '".md5($_REQUEST['value'])."'";
			}
			if($_REQUEST['search_cellphone']) {
				if($where_tmp != "") { $where_tmp .= " OR "; }
				$where_tmp .= "mail2sms_nummer LIKE '%".$_REQUEST['value']."%'";
			}
			if($_REQUEST['search_ip']) {
				if($where_tmp != "") { $where_tmp .= " OR "; }
				$where_tmp .= "ip LIKE '%".$_REQUEST['value']."%'";
			}
			if($_REQUEST['search_forward']) {
				if($where_tmp != "") { $where_tmp .= " OR "; }
				$where_tmp .= "forward_to LIKE '%".$_REQUEST['value']."%'";
			}																																																																										
		}									
			
		if($_REQUEST['do'] == "lockedusers") {
			$_SESSION['returnpage'] = $_SERVER['REQUEST_URI'];
			if($where_tmp != "") { $where_tmp .= " AND "; }
			$where_tmp .= "gesperrt = 'locked'";
		} elseif($_REQUEST['do'] == "yeslockedusers") {
			if($where_tmp != "") { $where_tmp .= " AND "; }
			$where_tmp .= "gesperrt = 'yes'";
		} elseif($_REQUEST['do'] == "todel") {
			if($where_tmp != "") { $where_tmp .= " AND "; }
			$where_tmp .= "gesperrt = 'delete'";
		} elseif($_REQUEST['do'] == "main") {
			if($where_tmp != "") { $where_tmp .= " AND "; }
			$where_tmp .= "gesperrt = 'no'";
		}			
									
		if($where_tmp != "") {
			$where = "WHERE ".$where_tmp." ";
		} else {
			$where = "";
		}
		
		if(isset($_REQUEST['where']) && $_REQUEST['where'] != "") {
			$where = urldecode($_REQUEST['where']);
			// Hier müssen die %-Zeichen für die URL-Übertragung ersetzt werden
			$where = str_replace(" PROZENT ", "%", $where);
		}
		
		$query = "SELECT * FROM {pre}users ".$where."ORDER BY ".$orderby." ".$asc_desc;
		
		if($_REQUEST['do'] == "inactive") {
			if(isset($_REQUEST['tage'])) {
				$_SESSION['tage'] = $_REQUEST['tage'];
				$tage = $_REQUEST['tage'];			
			} else {
				$tage = $_SESSION['tage'];
			}
			$sekunden = $tage * 86400;
			$timestamp = mktime(23, 59, 59, date("m"), date("d"), date("Y")) - $sekunden;
			
			$orderby = "lastlogin";
			$asc_desc = "DESC";
	
			
			$query = "SELECT id,email,vorname,nachname,lastlogin FROM {pre}users WHERE ((lastlogin <= " . $timestamp . ") AND (lastlogin > 0)) AND last_pop3 <= " . $timestamp . " AND last_imap <= " . $timestamp . " ORDER BY ".$orderby." ".$asc_desc;
		}
		
		// Hier müssen die %-Zeichen für die URL-Übertragung ersetzt werden
		$where = str_replace("%", " PROZENT ", $where);
	
		/* Das Such-Query wird ausgeführt */
		$sql = new SQLq($query);
			$count = $sql->RowCount();
		$sql->FreeClose();
		
		/* Bei einer Suche wurden keine Ergebnisse gefunden */
		if(isset($_REQUEST['search']) && $_REQUEST['search'] == "yes" && $where == "" ) {
			exit("<br>&nbsp; <b>".$lang_admin['nosearchresult']."</b>");
		} elseif($count == 0) {
			exit("<br>&nbsp; <b>".$lang_admin['nousers']."</b>");
		}
		
		$pages = ceil($count / $number);
		
		if(!isset($_REQUEST['page']) or $_REQUEST['page'] == "") {
			$page = 1;
		} else {
			if($pages < $_REQUEST['page']) {
				$page = $pages;
			} else {
				$page = $_REQUEST['page'];
			}
		}
		$start = $page * $number - $number;
		
		/* Seite zu der nach einigen Aktionen zurügekehrt wird -> diese Seite */
		if($count > 2) {
			$_SESSION['returnpage'] = $_SERVER['REQUEST_URI'];
		} else {
			$_SESSION['returnpage'] = "admin.php?action=users&do=main&PHPSESSID=".session_id();
		}
		$_SESSION['returnpage2'] = "admin.php?action=users".(isset($number) ? "&number=".$number : "")."&page=$page".($where != "" ? "&where=".urlencode($where) : "")."&PHPSESSID=".session_id();					
	
	?>
		<br>
		<center>	
		<table width="90%" bgcolor="#999999" cellspacing="1" border="0">
			<tr>
				<td height="24" background="res/lauf.jpg">&nbsp;&nbsp;<font color="#666666"><b><?php if($_REQUEST['do'] == "main") { echo($lang_admin['benutzer']); } else { echo($lang_admin[$_REQUEST['do']]); } ?></b></td>
			</tr>
			<tr>
				<td bgcolor="#ffffff">
					<table cellspacing="1" border="0" width="100%">
						<tr>
							<td bgcolor="#ffffff" colspan="4" align="justify"><p align="justify">
								<table width="100%" cellspacing="1">
								 <tr>
									 <td bgcolor="#EFEFEF" height="22" align="center"><input type="checkbox" name="checker" onClick="negateChoice(usersform);" border="0" class="checkbox"<?php echo($_REQUEST['do'] == "inactive" ? "checked" : ""); ?>></td>
									 <td bgcolor="#EFEFEF" height="22" width="25"> &nbsp;<b><?php if($_REQUEST['do'] != "inactive") { ?><a href="<?php echo($_SERVER['REQUEST_URI']."&orderby=id".($asc_desc == "ASC" && $orderby == "id" ? "&asc_desc=DESC" : "&asc_desc=ASC")); ?>"><?php } ?>ID<?php if($_REQUEST['do'] != "inactive") { ?></a><?php } ?></b></td>
									 <td bgcolor="#EFEFEF" height="22"> &nbsp;<b><?php if($_REQUEST['do'] != "inactive") { ?><a href="<?php echo($_SERVER['REQUEST_URI']."&orderby=email".($asc_desc == "ASC" && $orderby == "email" ? "&asc_desc=DESC" : "&asc_desc=ASC")); ?>"><?php } ?><?php echo($lang_admin['email']); ?><?php if($_REQUEST['do'] != "inactive") { ?></a><?php } ?></b></td>
									 <td bgcolor="#EFEFEF" height="22"> &nbsp;<b><?php if($_REQUEST['do'] != "inactive") { ?><a href="<?php echo($_SERVER['REQUEST_URI']."&orderby=nachname".($asc_desc == "ASC" && $orderby == "nachname" ? "&asc_desc=DESC" : "&asc_desc=ASC")); ?>"><?php } ?><?php echo($lang_admin['name']); ?></b><?php if($_REQUEST['do'] != "inactive") { ?></a><?php } ?></td>
									<?php if($_REQUEST['do'] != "inactive") { ?>
									 <td bgcolor="#EFEFEF" height="22"> &nbsp;<b><?php if($_REQUEST['do'] != "inactive") { ?><a href="<?php echo($_SERVER['REQUEST_URI']."&orderby=gruppe".($asc_desc == "ASC" && $orderby == "gruppe" ? "&asc_desc=DESC" : "&asc_desc=ASC")); ?>"><?php } ?><?php echo($lang_admin['gruppe']); ?></b><?php if($_REQUEST['do'] != "inactive") { ?></a><?php } ?></td>
									 <td bgcolor="#EFEFEF" height="22"> &nbsp;<b><?php echo($lang_admin['aktionen']); ?></b></td>	
									<?php } else { ?>
									  <td bgcolor="#EFEFEF" height="22"> &nbsp;<b><?php echo($lang_admin['lastlogin']); ?></b></td>
									<?php } ?>
								 </tr>
								<form action="<?php echo("admin.php?action=users&do=main&" . ($_REQUEST['do'] == "inactive" ? "step=delete&" : "") . "PHPSESSID=".session_id()); ?>" name="usersform" method="post">									
								 <?php
									
									$sql = new SQLq($query." LIMIT $start,$number");
									
									while($row = $sql->FetchArray()) {
										$email = (strlen($row['email']) < 30 ? $row['email'] : substr($row['email'],0,27)."...");
										?>
								 <tr>
									 <td bgcolor="#f5f5f5" height="22" align="center"><input type="checkbox" name="usr_<?php echo($row['id']); ?>" class="checkbox"<?php echo($_REQUEST['do'] == "inactive" ? "checked" : ""); ?>></td>
									 <td bgcolor="#f5f5f5" height="22"> &nbsp;<?php echo($row['id']); ?></td>
									 <td bgcolor="#f5f5f5" height="22"> &nbsp;<?php echo('<a href="admin.php?action=users&do=main&step=details&id='.$row['id'].'&PHPSESSID='.session_id().'">'.$email.'</a>'); ?></td>
									 <td bgcolor="#f5f5f5" height="22"> &nbsp;<?php echo(stripslashes($row['vorname']." ".$row['nachname'])); 
									 if($_REQUEST['do'] != "inactive") {?></td>
									 <td bgcolor="#f5f5f5" height="22"> &nbsp;<?php 
									 $sql2 = new SQLq("SELECT titel FROM {pre}gruppen WHERE id='$row[gruppe]' ORDER BY id ASC LIMIT 1");
									 $gr_row = $sql2->FetchArray();
									 $sql2->FreeClose();
									 
									 echo(stripslashes($gr_row['titel']));
									 ?></td>
									 <td bgcolor="#f5f5f5" height="22"> &nbsp;<?php echo('<a href="admin.php?action=users&do=main&step=details&id='.$row['id'].'&PHPSESSID='.session_id().'"><img src="res/ico/details.gif" border="0" alt="'.$lang_admin['userdetails'].'"></a>');
									 												if($row['gesperrt'] == "no") {
																						echo(' <a href="admin.php?action=users&do=main&step=edit&id='.$row['id'].'&PHPSESSID='.session_id().'"><img src="res/ico/edit.gif" border="0" alt="'.$lang_admin['edit'].'"></a>');
																						echo(' <a href="admin.php?action=users&do=main&step=lock&oneaction=yes&id='.$row['id'].'&PHPSESSID='.session_id().'"><img src="res/ico/lock.gif" border="0" alt="'.$lang_admin['lockuser'].'"></a>');																						
																						echo(' <a href="admin.php?action=users&do=main&step=markdel&oneaction=yes&id='.$row['id'].'&PHPSESSID='.session_id().'"><img src="res/ico/del.gif" border="0" alt="'.$lang_admin['delete'].'"></a>');
																					} elseif($row['gesperrt'] == "yes") {
																						echo(' <a href="admin.php?action=users&do=main&step=recover&oneaction=yes&id='.$row['id'].'&PHPSESSID='.session_id().'"><img src="res/ico/unlock.gif" border="0" alt="'.$lang_admin['recover'].'"></a>');
																						echo(' <a href="admin.php?action=users&do=main&step=markdel&oneaction=yes&id='.$row['id'].'&PHPSESSID='.session_id().'"><img src="res/ico/del.gif" border="0" alt="'.$lang_admin['delete'].'"></a>');
																					} elseif($row['gesperrt'] == "locked") {
																						echo(' <a href="admin.php?action=users&do=main&step=recover&oneaction=yes&id='.$row['id'].'&PHPSESSID='.session_id().'"><img src="res/ico/unlock.gif" border="0" alt="'.$lang_admin['unlock'].'"></a>');
																						echo(' <a href="admin.php?action=users&do=main&step=markdel&oneaction=yes&id='.$row['id'].'&PHPSESSID='.session_id().'"><img src="res/ico/del.gif" border="0" alt="'.$lang_admin['delete'].'"></a>');
																					} else {
																						echo(' <a href="admin.php?action=users&do=main&step=recover&oneaction=yes&id='.$row['id'].'&PHPSESSID='.session_id().'"><img src="res/ico/recover.gif" border="0" alt="'.$lang_admin['recover'].'"></a>');
																						echo(' <a href="admin.php?action=users&do=main&step=delete&oneaction=yes&id='.$row['id'].'&PHPSESSID='.session_id().'"><img src="res/ico/delfinal.gif" border="0" alt="'.$lang_admin['enddel'].'"></a>');
																					}
																					echo(' <a href="../index.php?action=login&adminpw='.urlencode($bm_prefs['adminpw']).'&usermail='.$row['email'].'&passwort='.$row['passwort'].'" target="_blank"><img src="res/ico/login.gif" border="0" alt="'.$lang_admin['login'].'"></a></a>'); ?></td>	 
								 </tr>
										<?php
										} else {
										?><td bgcolor="#f5f5f5" height="22"> &nbsp;<?php
								 			$dif = time() - $row['lastlogin'];
								 			echo (ceil($dif / 86400));
								 			echo " " . $lang_admin['days']; ?></td>
								 		<?php
										}
									}
									$sql->FreeClose();
								 ?>
								 <tr>
									<td colspan="2" rowspan="2" align="left" valign="middle" bgcolor="#EFEFEF"><?php if($_REQUEST['do'] != "inactive") { ?><select name="step"><?php if($_REQUEST['do'] != "lockedusers") { ?><option value="lock"><?php echo($lang_admin['lockuser']); ?></option><?php } if($_REQUEST['do'] != "unlockedusers") { ?><option value="recover"><?php echo($lang_admin['recover']); ?></option><?php } if($_REQUEST['do'] != "todel") { ?><option value="markdel"><?php echo($lang_admin['delete']); ?></option><?php } if($_REQUEST['do'] == "todel") { ?><option value="delete"><?php echo($lang_admin['enddel']); ?></option><?php } ?></select><?php } ?> <input type="submit" value="<?php echo($_REQUEST['do'] != "inactive" ? $lang_admin['ok'] : $lang_admin['delete']); ?>"></td></form>
										<td colspan="4" align="right" bgcolor="#EFEFEF" height="22">
										<?php
											 if($pages > 1) {
												echo($lang_admin['page']." <i>".$page."</i> ".$lang_admin['of']." <i>".$pages."</i>"); ?>: <?php
												if($page > 3) {
													echo(" <a href=\"admin.php?action=users".(isset($_REQUEST['do']) ? "&do=".$_REQUEST['do'] : "").(isset($number) ? "&number=".$number : "")."&page=1".($where != "" ? "&where=".urlencode($where) : "")."&PHPSESSID=".session_id()."\">&laquo; ".$lang_admin['firstpage']."</a> |");
												}	
												if($page > 1) {
													echo(" <a href=\"admin.php?action=users".(isset($_REQUEST['do']) ? "&do=".$_REQUEST['do'] : "").(isset($number) ? "&number=".$number : "")."&page=".($page-1).($where != "" ? "&where=".urlencode($where) : "")."&PHPSESSID=".session_id()."\"><</a> ");
												}
				
												if($pages < 5) {
													for($i=1;$i<=$pages;$i++) {
														if($i==$page) {
															echo "| <b>${i}</b>&nbsp;";
														} else {
															echo "| <a href=\"admin.php?action=users".(isset($_REQUEST['do']) ? "&do=".$_REQUEST['do'] : "").(isset($number) ? "&number=".$number : "")."&page=$i".($where != "" ? "&where=".urlencode($where) : "")."&PHPSESSID=".session_id()."\">${i}</a>&nbsp;";
														}
													}
												} else {
													if($page <= 3) {
														for($i=1;$i<=5;$i++) {
															if($i==$page) {
																echo "| <b>${i}</b>&nbsp;";
															} else {
																echo "| <a href=\"admin.php?action=users".(isset($_REQUEST['do']) ? "&do=".$_REQUEST['do'] : "").(isset($number) ? "&number=".$number : "")."&page=$i".($where != "" ? "&where=".urlencode($where) : "")."&PHPSESSID=".session_id()."\">${i}</a>&nbsp;";
															}
														}
													} else {
														for($i=$page-2-($pages-$page==1 ? 1 : ($pages-$page==0 ? 2 : 0));$i<=$page+2 AND $i<=$pages;$i++) {
															if($i==$page) {
																echo "| <b>${i}</b>&nbsp;";
															} else {
																echo "| <a href=\"admin.php?action=users".(isset($_REQUEST['do']) ? "&do=".$_REQUEST['do'] : "").(isset($number) ? "&number=".$number : "")."&page=$i".($where != "" ? "&where=".urlencode($where) : "")."&PHPSESSID=".session_id()."\">${i}</a>&nbsp;";
															}
														}
													}
												}
												
												if($page < $pages) {
													echo("| <a href=\"admin.php?action=users".(isset($_REQUEST['do']) ? "&do=".$_REQUEST['do'] : "").(isset($number) ? "&number=".$number : "")."&page=".($page+1).($where != "" ? "&where=".urlencode($where) : "")."&PHPSESSID=".session_id()."\">></a> ");
												}	
												if($pages-$page >= 3) {
													echo("| <a href=\"admin.php?action=users".(isset($_REQUEST['do']) ? "&do=".$_REQUEST['do'] : "").(isset($number) ? "&number=".$number : "")."&page=$pages".($where != "" ? "&where=".urlencode($where) : "")."&PHPSESSID=".session_id()."\">".$lang_admin['lastpage']." &raquo;</a> ");
												}
											} else {
												echo("&nbsp;");
											}
											?>
										</td>
									  </tr>
									  <tr>
									  
										<form action="<?php echo("admin.php?action=users".(isset($_REQUEST['do']) ? "&do=".$_REQUEST['do'] : "").(isset($page) ? "&page=".$page.($where != "" ? "&where=".urlencode($where) : "") : "")."&PHPSESSID=".session_id()); ?>" method="post" name="numberform">
											<td colspan="4" align="right" valign="middle" bgcolor="#EFEFEF" height="10">
												<?php echo($lang_admin['show']); ?>
												<select name="number" onChange="numberform.submit()">
												<?php
													for($i=10;$i<=100;$i=$i+10) {
												?>
													<option value="<?php echo($i.'"'.($i == $number ? " selected" : "")); ?>><?php echo($i); ?></option>
												<?php } ?>
												</select>
												<?php
												 echo($lang_admin['usersapage']);?>										
											</td>
										</form>
									  </tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table><br>
		</center>		
		<?php
	}
	
	if($_REQUEST['step'] == 'lock') {
		if($_REQUEST['oneaction'] == "yes") {
			$sql = new SQLq("UPDATE {pre}users SET gesperrt = 'yes' WHERE id = '".$_REQUEST['id']."'");
			$sql->Close();
		} else {
			reset($_REQUEST);
			while(list($key,$val) = each($_REQUEST)) {
				if (substr($key, 0, 4) == "usr_") {
					$thisid = substr($key, 4);
					$sql = new SQLq("UPDATE {pre}users SET gesperrt = 'yes' WHERE id = '".$thisid."'");
					$sql->Close();
				}
			}
		}
		
		header("Location: ".$_SESSION['returnpage']);
	}
	
	if($_REQUEST['step'] == 'delete') {
		if($_REQUEST['oneaction'] == "yes") {	
			DelUser($_REQUEST['id']);
		} else {
			reset($_REQUEST);
			while(list($key,$val) = each($_REQUEST)) {
				if (substr($key, 0, 4) == "usr_") {
					$thisid = substr($key, 4);
					DelUser($thisid);
				}
			}
		}
		
		header("Location: ".$_SESSION['returnpage']);
	}
	
	if($_REQUEST['step'] == 'markdel') {
		if($_REQUEST['oneaction'] == "yes") {
			$sql = new SQLq("UPDATE {pre}users SET gesperrt = 'delete' WHERE id = '".$_REQUEST['id']."'");
			$sql->Close();
		} else {
			reset($_REQUEST);
			while(list($key,$val) = each($_REQUEST)) {
				if (substr($key, 0, 4) == "usr_") {
					$thisid = substr($key, 4);
					$sql = new SQLq("UPDATE {pre}users SET gesperrt = 'delete' WHERE id = '".$thisid."'");
					$sql->Close();
				}
			}
		}
		
		header("Location: ".$_SESSION['returnpage']);
	}
	
	if($_REQUEST['step'] == 'recover') {
		if($_REQUEST['oneaction'] == "yes") {
			$sql = new SQLq("UPDATE {pre}users SET gesperrt = 'no' WHERE id = '".$_REQUEST['id']."'");
			$sql->Close();
		} else {
			reset($_REQUEST);
			while(list($key,$val) = each($_REQUEST)) {
				if (substr($key, 0, 4) == "usr_") {
					$thisid = substr($key, 4);
					$sql = new SQLq("UPDATE {pre}users SET gesperrt = 'no' WHERE id = '".$thisid."'");
					$sql->Close();
				}
			}
		}
		
		header("Location: ".$_SESSION['returnpage2']);
	}	
	
	if($_REQUEST['step'] == 'details') {
		$sql = new SQLq("SELECT * FROM {pre}users WHERE id=".$_REQUEST['id']);
			$row = $sql->FetchArray();
		$sql->Close();
		
		$userinfo = array();
		
		/* Anzahl Adressbucheinträge */
		$sql = new SQLq("SELECT id FROM {pre}adressen WHERE user='$row[id]'");
			$userinfo['addrcount'] = $sql->RowCount();
			if(!$userinfo['addrcount']) { $userinfo['addrcount'] = 0; }
		$sql->FreeClose();
		
		/* Aliase (+ Anzahl) */
		$sql = new SQLq("SELECT * FROM {pre}aliase WHERE user='$row[id]'");
			$userinfo['aliascount'] = $sql->RowCount();
			if(!$userinfo['aliascount']) { $userinfo['aliascount'] = 0; }
			$aliase = array();
			for($i = 0; $row_tmp = $sql->FetchArray(); $i++) {
				$aliase[$i] = $row_tmp['email'];
			}
		$sql->FreeClose();
		
		/* Autoresponder */
		$sql = new SQLq("SELECT active FROM {pre}autoresponder WHERE user='$row[email]'");
			$tmp = $sql->FetchArray();		
			$userinfo['autoreactive'] = $tmp['active'];
			if(!$userinfo['autoreactive']) {
				$userinfo['autoreactive'] = "no";
			}
		$sql->FreeClose();
		
		/* Anzahl WebDisk-Dateien & WebDisk-Größe */
		$sql = new SQLq("SELECT size FROM {pre}diskfiles WHERE user='$row[id]'");
			$userinfo['wdfilecount'] = $sql->RowCount();
			$wdused = 0;
			while($tmp = $sql->FetchArray()) {
				$wdused += $tmp['size'];
			}
			$userinfo['wdused'] = round($wdused / 1024 / 1024, 2);
		$sql->FreeClose();							
		
		/* Anzahl WebDisk-Ordner */
		$sql = new SQLq("SELECT id FROM {pre}diskfolders WHERE user='$row[id]'");
			$userinfo['wdfoldercount'] = $sql->RowCount();
			if(!$userinfo['wdfoldercount']) { $userinfo['wdfoldercount'] = 0; }
		$sql->FreeClose();
		
		/* Anzahl Filter */
		$sql = new SQLq("SELECT id FROM {pre}filter WHERE user='$row[email]'");
			$userinfo['filtercount'] = $sql->RowCount();
			if(!$userinfo['filtercount']) { $userinfo['filtercount'] = 0; }
		$sql->FreeClose();
		
		/* Anzahl Ordner */
		$sql = new SQLq("SELECT id FROM {pre}folders WHERE user='$row[email]'");
			$userinfo['foldercount'] = $sql->RowCount();
			if(!$userinfo['foldercount']) { $userinfo['foldercount'] = 0; }
		$sql->FreeClose();
		
		/* Anzahl Signaturen */
		$sql = new SQLq("SELECT id FROM {pre}signaturen WHERE user='$row[id]'");
			$userinfo['signaturcount'] = $sql->RowCount();
			if(!$userinfo['signaturcount']) { $userinfo['signaturcount'] = 0; }
		$sql->FreeClose();
		
		/* Anzahl Aufgaben */
		$sql = new SQLq("SELECT id FROM {pre}tasks WHERE user='$row[id]'");
			$userinfo['taskcount'] = $sql->RowCount();
			if(!$userinfo['taskcount']) { $userinfo['taskcount'] = 0; }
		$sql->FreeClose();										
		
		/* Land */
		$sql = new SQLq("SELECT land FROM {pre}staaten WHERE id='$row[land]'");
			$tmp = $sql->FetchArray();
			$userinfo['land'] = $tmp['land'];
		$sql->FreeClose();
		
		/* Gruppe */
		$sql = new SQLq("SELECT titel,sms_monat FROM {pre}gruppen WHERE id='$row[gruppe]'");
			$tmp = $sql->FetchArray();
			$userinfo['group'] = $tmp['titel'];
			$userinfo['sms_tmp'] = $tmp['sms_monat'];
		$sql->FreeClose();				
		
		/* Anzahl Termine */
		$sql = new SQLq("SELECT id FROM {pre}calendar WHERE user='$row[id]'");
			$userinfo['datecount'] = $sql->RowCount();
		$sql->FreeClose();										
		
		/* Anzahl Mails */
		$sql = new SQLq("SELECT id FROM {pre}mails WHERE user='$row[email]'");
			$userinfo['mailcount'] = $sql->RowCount();
		$sql->FreeClose();
		
		/* Verbrauchter Speicherplatz */
		$userinfo['usedspace'] = round(UserSize($row['email']) / 1024 / 1024, 2);
		
		/* Monats-Restguthaben SMS */
		$sql = new SQLq("SELECT id FROM {pre}smsend WHERE user='$s_userid' AND monat='" . date("mY") . "'");
		$userinfo['month_sms'] = $userinfo['sms_tmp'] - $sql->RowCount();
		$sql->FreeClose();						
	?>
		<br>
		<div align="center">
		<table width="90%" bgcolor="#999999" cellspacing="1" border="0">
			<tr>
				<td height="24" background="res/lauf.jpg">&nbsp;&nbsp;<font color="#666666"><b><?php echo(stripslashes($row['nachname']).", ".stripslashes($row['vorname'])." [<i>".stripslashes($row['email'])."</i>]"); ?></b></td>
			</tr>
			<tr>
				<td bgcolor="#ffffff">
					<table cellspacing="1" border="0" width="100%">
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;Hinweisstatus:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(stripslashes($row['hinweison'])); ?></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0=AUS 1=Permanent 2=Einmalig </td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;Hinweisbetreff:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(stripslashes($row['hinweistitel'])); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;Hinweistext:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(stripslashes($row['hinweistext'])); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_strasse']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(stripslashes($row['strasse'])." ".stripslashes($row['hnr'])); ?></i></td>
						</tr>				
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_ort']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(stripslashes($row['plz'])." ".stripslashes($row['ort'])); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_land']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(stripslashes($userinfo['land'])); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_tel']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(($row['tel'] ? stripslashes($row['tel']) : "-")); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_handy']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(($row['mail2sms_nummer'] ? stripslashes($row['mail2sms_nummer']) : "-")); ?></i></td>
						</tr>						
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_fax']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(($row['fax'] ? stripslashes($row['fax']) : "-")); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#999999" colspan="2"></td>
						</tr>					
						<?php 
						$profilfelder = unserialize($row['profilfelder']);
						$psql = new SQLq("SELECT id,feld,typ FROM {pre}profilfelder");
						while($p = $psql->FetchArray())
						{
						?>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($p['feld']); ?></td>
							<td bgcolor="#F8F8FB">&nbsp;<i>
							<?php 
							switch($p['typ'])
							{
							case FIELD_CHECKBOX:
								echo $lang_admin[ $profilfelder[$p['id']] ? 'yes' : 'no' ];
								break;
							default:
								echo isset($profilfelder[$p['id']]) && trim($profilfelder[$p['id']]) != '' ? $profilfelder[$p['id']] : '-';
								break;
							}
							?>
							</i></td>
						</tr>
						<?php
						}
						?>
						<tr>
							<td bgcolor="#999999" colspan="2"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_group']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($userinfo['group']); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_status']); ?>:</td>
							<td bgcolor="#F8F8FB"><i>&nbsp;<?php 
										if($row['gesperrt'] == "no") {
											echo($lang_admin['aktiviert']);
										} elseif($row['gesperrt'] == "yes") {
											echo($lang_admin['gesperrt']);
										} else {
											echo($lang_admin['todel']);
										}
							?></i></td>
						</tr>																	
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_altmail']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(($row['altmail'] ? stripslashes($row['altmail']) : "-")); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_aliase']); ?>:</td>
							<td bgcolor="#F8F8FB"><i><?php if($userinfo['aliascount'] > 0) { for($i = 0; $i < $userinfo['aliascount']; $i++) { echo("&nbsp;·&nbsp;" . stripslashes($aliase[$i]) . "<br />"); } } else { echo("&nbsp;-"); } ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_forwmail']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php if($row['forward'] == "yes") { echo(stripslashes($row['forward_to'])); } else { echo("-"); } ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#999999" colspan="2"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_regdate']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(($row['reg_date'] ? date('d.m.Y - H:i:s', $row['reg_date']) : "-")); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_regip']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($row['reg_ip']); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_lastlogin']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(($row['lastlogin'] ? date('d.m.Y - H:i:s', $row['lastlogin']) : "-")); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_lastactiv']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(($row['last_notify'] ? date('d.m.Y - H:i:s', $row['last_notify']) : "-")); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_ip']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(($row['ip'] ? $row['ip'] : "-")); ?></i></td>
						</tr>						
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_lastmail']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(($row['last_send'] ? date('d.m.Y - H:i:s', $row['last_send']) : "-")); ?></i></td>
						</tr>																														
						<tr>
							<td bgcolor="#999999" colspan="2"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_mail2sms']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($lang_admin[$row['mail2sms']]); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_forward']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($lang_admin[$row['forward']]); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_autoresp']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($lang_admin[$userinfo['autoreactive']]); ?></i></td>
						</tr>						
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_forwdel']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php if($row['forward'] == "yes") { echo($lang_admin[$row['forward_delete']]); } else { echo("-"); } ?></i></td>
						</tr>												
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_datecat']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($lang_admin[$row['katalog']]); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#999999" colspan="2"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_firstday']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($lang_main['wochentage'][$row['c_firstday']]); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#999999" colspan="2"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_mailsize']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($userinfo['usedspace']." " . $lang_admin['MB'] . " [".$userinfo['mailcount']." " . $lang_admin['mails_in'] . " ".$userinfo['foldercount']." " . $lang_admin['folders'] . "]"); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_disksize']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($userinfo['wdused']." " . $lang_admin['MB'] . " [".$userinfo['wdfilecount']." " . $lang_admin['files_in'] . " ".$userinfo['wdfoldercount']." " . $lang_admin['folders'] . "]"); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_smskontingent']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($row['sms_kontigent'] . " " .  $lang_admin['sms_kontingent']); ?> + <?php echo($userinfo['month_sms'] . " " . $lang_admin['month_sms']); ?></i></td>
						</tr>						
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_filtercnt']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($userinfo['filtercount']); ?></i></td>
						</tr>												
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_addrcount']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($userinfo['addrcount']); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_signcount']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($userinfo['datecount']); ?></i></td>
						</tr>						
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_aliascnt']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($userinfo['aliascount']); ?></i></td>
						</tr>												
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_datecount']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($userinfo['datecount']); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_taskcount']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($userinfo['taskcount']); ?></i></td>
						</tr>						
						<tr>
							<td bgcolor="#999999" colspan="2"></td>
						</tr>												
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['last_pop3' ]); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(($row['last_pop3'] == 0) ? '(noch nie)' : date("d.m.Y H:i:s", $row['last_pop3'])); ?></i></td>
						</tr>												
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['last_imap' ]); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(($row['last_imap'] == 0) ? '(noch nie)' : date("d.m.Y H:i:s", $row['last_imap'])); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['last_mta']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($row['mta_sentmails']); ?></i></td>
						</tr>		                                                        
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['traffic_down']); ?>:</td>
                            <td bgcolor="#F8F8FB">&nbsp;<i><?php echo(round($row['traffic_down'] / 1024 / 1024, 2)); ?> MB</i></td>
						</tr>
						<tr>
                            <td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['traffic_up']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo(round($row['traffic_up'] / 1024 / 1024, 2)); ?> MB</i></td>
                        </tr> 			
						<tr>
							<td bgcolor="#999999" colspan="2"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="25" colspan="2" align="center" valign="middle"><input type="button" value="&laquo; <?php echo($lang_admin['goback']); ?>" onClick="location.href='<?php echo($_SESSION['returnpage2']); ?>'"> <input type="button" value="<?php echo($lang_admin['login']); ?>" onClick="window.open('<?php echo('../index.php?action=login&adminpw='.urlencode($bm_prefs['adminpw']).'&usermail='.$row['email'].'&passwort='.$row['passwort'].'&disablemd5='.$row['md5']); ?>', 'loginwin')"> <input type="button" value="<?php echo($lang_admin['edit']); ?>" onClick="location.href='admin.php?action=users&do=main&step=edit&id=<?php echo($row['id']); ?>&PHPSESSID=<?php echo(session_id()); ?>'"></td>
						</tr>																																				
					</table>
				</td>
			</tr>
		</table>
	<?php
	}
	
	if($_REQUEST['step'] == 'edit') {
		$sql = new SQLq("SELECT * FROM {pre}users WHERE id=".$_REQUEST['id']);
			$row = $sql->FetchArray();
		$sql->Close();
		
		$userinfo = array();
		
		/* Land */
		$sql = new SQLq("SELECT * FROM {pre}staaten");
			$landlist = "<select name=\"land\">";
			for($i = 0; $row_tmp = $sql->FetchArray(); $i++) {
				$landlist .= "<option value=\"" . $row_tmp['id'] . "\"" . ($row_tmp['id'] == $row['land'] ? " selected" : "") . ">" . $row_tmp['land'] . "</option>";
			}
			$landlist .= "</select>";
		$sql->FreeClose();
		
		/* Gruppe */
		$sql = new SQLq("SELECT * FROM {pre}gruppen");
			$grouplist = "<select name=\"gruppe\">";
			for($i = 0; $row_tmp = $sql->FetchArray(); $i++) {
				$grouplist .= "<option value=\"" . $row_tmp['id'] . "\"" . ($row_tmp['id'] == $row['gruppe'] ? " selected" : "") . ">" . $row_tmp['titel'] . "</option>";
			}
			$grouplist .= "</select>";
		$sql->FreeClose();				
		
		/* Wochentage*/
		$daylist = "<select name=\"c_firstday\">";
		for($i = 0; $i < 7; $i++) {
			$daylist .= "<option value=\"" . $i . "\"" . ($row['c_firstday'] == $i ? " selected" : "") . ">" . $lang_admin['wochentage'][$i] . "</option>";
		}
		$daylist .= "</select>";
						
	?>
		<br>
		<div align="center">
		<table width="90%" bgcolor="#999999" cellspacing="1" border="0">
			<tr>
				<td height="24" background="res/lauf.jpg">&nbsp;&nbsp;<font color="#666666"><b><?php echo(stripslashes($row['nachname']).", ".stripslashes($row['vorname'])." [<i>".stripslashes($row['email'])."</i>]"); ?></b></td>
			</tr>
			<tr><form name="userform" method="post" action="admin.php?action=users&do=main&step=save&id=<?php echo($row['id']); ?>&PHPSESSID=<?php echo(session_id()); ?>" onSubmit="listSelAll(document.getElementById('list_aliase[]'));">
				<td bgcolor="#ffffff">
					<table cellspacing="1" border="0" width="100%">
												
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_firstname']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<input type="text" name="vorname" size="29" value="<?php echo(stripslashes($row['vorname'])); ?>"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_name']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<input type="text" size="29" name="nachname" value="<?php echo(stripslashes($row['nachname'])); ?>"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_strasse']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<input type="text" size="24" name="strasse" value="<?php echo(stripslashes($row['strasse'])); ?>"> <input type="text" size="1" name="hnr" value="<?php echo($row['hnr']); ?>"></td>
						</tr>				
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_ort']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<input type="text" size="5" name="plz" value="<?php echo(stripslashes($row['plz'])); ?>"> <input type="text" size="20" name="ort" value="<?php echo($row['ort']); ?>"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_land']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<?php echo($landlist); ?></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_tel']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<input type="text" name="tel" size="29" value="<?php echo(stripslashes($row['tel'])); ?>"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_handy']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><input type="text" name="mail2sms_nummer" size="29" value="<?php echo(stripslashes($row['mail2sms_nummer'])); ?>"></i></td>
						</tr>						
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_fax']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<input type="text" name="fax" size="29" value="<?php echo(stripslashes($row['fax'])); ?>"></td>
						</tr>
						<tr>
							<td bgcolor="#999999" colspan="2"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_email']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<input type="text" name="email" size="29" value="<?php echo(stripslashes($row['email'])); ?>"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_group']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<i><?php echo($grouplist); ?></i></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_status']); ?>:</td>
							<td bgcolor="#F8F8FB"><i>&nbsp;<select name="gesperrt">	<option value="no"<?php echo(($row['gesperrt'] == "no" ? " selected" : "")); ?>><?php echo($lang_admin['aktiviert']); ?></option>
																					<option value="yes"<?php echo(($row['gesperrt'] == "yes" ? " selected" : "")); ?>><?php echo($lang_admin['gesperrt']); ?></option>
							?></i></td>
						</tr>																	
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_altmail']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<input type="text" name="altmail" size="29" value="<?php echo(stripslashes($row['altmail'])); ?>"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_forwmail']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<input type="text" name="forward_to" size="29" value="<?php echo(stripslashes($row['forward_to'])); ?>"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_mail2sms']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<select name="mail2sms"><option value="yes" <?php echo($row['mail2sms']=="yes" ? "selected" : "") ?>><?php echo($lang_admin['yes']); ?></option><option value="no" <?php echo($row['mail2sms']=="no" ? "selected" : "") ?>><?php echo($lang_admin['no']); ?></option></select></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_smskontingent']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<input type="text" name="sms_kontingent" size="5" value="<?php echo(stripslashes($row['sms_kontigent'])); ?>"> <?php echo($lang_admin['sms_kontingent']); ?></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_forward']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<select name="forward"><option value="yes" <?php echo($row['forward']=="yes" ? "selected" : "") ?>><?php echo($lang_admin['yes']); ?></option><option value="no" <?php echo($row['forward']=="no" ? "selected" : "") ?>><?php echo($lang_admin['no']); ?></option></select></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_forwdel']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<select name="forward_delete"><option value="yes" <?php echo($row['forward_delete']=="yes" ? "selected" : "") ?>><?php echo($lang_admin['yes']); ?></option><option value="no" <?php echo($row['forward_delete']=="no" ? "selected" : "") ?>><?php echo($lang_admin['no']); ?></option></select></td>
						</tr>												
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_datecat']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<select name="katalog"><option value="yes" <?php echo($row['katalog']=="yes" ? "selected" : "") ?>><?php echo($lang_admin['yes']); ?></option><option value="no" <?php echo($row['katalog']=="no" ? "selected" : "") ?>><?php echo($lang_admin['no']); ?></option></select></td>
						</tr>
						<tr>
							<td bgcolor="#999999" colspan="2"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="18" width="40%">&nbsp;<?php echo($lang_admin['usr_firstday']); ?>:</td>
							<td bgcolor="#F8F8FB">&nbsp;<?php echo($daylist); ?></td>
						</tr>
						<tr>
							<td bgcolor="#999999" colspan="2"></td>
						</tr>
						<tr>
							<td bgcolor="#EFEFEF" height="25" colspan="2" align="center" valign="middle"><input type="button" value="&laquo; <?php echo($lang_admin['goback']); ?>" onClick="location.href='<?php echo($_SESSION['returnpage2']); ?>'"> <input type="reset" value="<?php echo($lang_admin['reset']); ?>"> <input type="submit" value="<?php echo($lang_admin['save']); ?>"></td>
						</tr>																																				
					</table>
				</td>
			</tr></form>
		</table>
	<?php
	}
	
	if($_REQUEST['step'] == 'save') {
		$sql = new SQLq("UPDATE {pre}users SET email='" . addslashes($_REQUEST['email']) . "', vorname='" . addslashes($_REQUEST['vorname']) . "', nachname='" . addslashes($_REQUEST['nachname']) . "', strasse='" . addslashes($_REQUEST['strasse']) . "', hnr='" . addslashes($_REQUEST['hnr']) . "', plz='" . addslashes($_REQUEST['plz']) . "', ort='" . addslashes($_REQUEST['ort']) . "', land='" . addslashes($_REQUEST['land']) . "', tel='" . addslashes($_REQUEST['tel']) . "', fax='" . addslashes($_REQUEST['fax']) . "', altmail='" . addslashes($_REQUEST['altmail']) . "', gruppe='" . addslashes($_REQUEST['gruppe']) . "', mail2sms='" . addslashes($_REQUEST['mail2sms']) . "', mail2sms_nummer='" . addslashes($_REQUEST['mail2sms_nummer']) . "', c_firstday='" . addslashes($_REQUEST['c_firstday']) . "', forward='" . addslashes($_REQUEST['forward']) . "', forward_to='" . addslashes($_REQUEST['forward_to']) . "', forward_delete='" . addslashes($_REQUEST['forward_delete']) . "', katalog='" . addslashes($_REQUEST['katalog']) . "', gesperrt='" . addslashes($_REQUEST['gesperrt']) . "', sms_kontigent='" . addslashes($_REQUEST['sms_kontingent']) . "' WHERE id = '" . $_REQUEST['id'] . "'");
		$sql->Close();	
		
		header("Location: ".$_SESSION['returnpage2']);
	}	
}

if($_REQUEST['do'] == "create") {
	if(isset($_REQUEST['make']) && $_REQUEST['make']=='add')
	{
		$sql = new SQLq("SELECT COUNT(*) FROM {pre}users WHERE email='".addslashes($_REQUEST['mail'])."@".addslashes($_REQUEST['maildomain'])."'");
		$row = $sql->FetchArray();
		$sql->FreeClose();
		
		if($row[0] >= 1)
		{
			?>
			<br />
			<center>
			<table width="90%" bgcolor="#999999" cellspacing="1" border="0">
				<tr>
					<td height="24" background="res/lauf.jpg">&nbsp;&nbsp;<font color="#666666"><b><?php echo($lang_admin['erstellen']); ?></b></td>
				</tr>
			</table>
			<table width="90%" bgcolor="#999999" cellspacing="1" border="0">		
				<tr>
					<td bgcolor="#ffffff">
						<?php echo($lang_admin['userexists']); ?>
					</td>
				</tr>
			</table>
			</center>
			<?php			
		}
		else
		{
			$q  = "INSERT INTO {pre}users(email,passwort,vorname,nachname,strasse,hnr,plz,ort,land,tel,fax,mail2sms_nummer,altmail,gruppe,c_firstday,reg_date,reg_ip) VALUES ";
			$q .= "('".addslashes($_REQUEST['mail'])."@".addslashes($_REQUEST['maildomain'])."','".md5($_REQUEST['passwort'])."','".addslashes($_REQUEST['vorname'])."','".addslashes($_REQUEST['nachname'])."','".addslashes($_REQUEST['strasse'])."','".addslashes($_REQUEST['nr'])."','".addslashes($_REQUEST['plz'])."','".addslashes($_REQUEST['ort'])."','".$_REQUEST['land']."','".addslashes($_REQUEST['tel'])."','".addslashes($_REQUEST['fax'])."','".addslashes($_REQUEST['handy'])."','".addslashes($_REQUEST['altmail'])."','".$_REQUEST['gruppe']."','1','".time()."','".$_SERVER['REMOTE_ADDR']." (Admin)')";
		
			$sql = new SQLq($q);
			
			?>
			<br />
			<center>
			<table width="90%" bgcolor="#999999" cellspacing="1" border="0">
				<tr>
					<td height="24" background="res/lauf.jpg">&nbsp;&nbsp;<font color="#666666"><b><?php echo($lang_admin['erstellen']); ?></b></td>
				</tr>
			</table>
			<table width="90%" bgcolor="#999999" cellspacing="1" border="0">		
				<tr>
					<td bgcolor="#ffffff">
						<?php echo($lang_admin['usercreated']); ?>
					</td>
				</tr>
			</table>
			</center>
			<?php
		}
	}
	else
	{
		?>
		<br />
		<center>
		<form name="searchform" action="admin.php?action=users&do=create&make=add&PHPSESSID=<?php echo(session_id()); ?>" method="post" style="display: inline;">
		<table width="90%" bgcolor="#999999" cellspacing="1" border="0">
			<tr>
				<td height="24" background="res/lauf.jpg">&nbsp;&nbsp;<font color="#666666"><b><?php echo($lang_admin['erstellen']); ?></b></td>
			</tr>
		
			<tr>
				<td bgcolor="#ffffff">
					<table cellspacing="1" border="0" width="100%">
						<tr>
							<td bgcolr="#ffffff" colspan="4" align="justify"><p align="justify">
								<table width="100%" cellspacing="1">
										<tr>
											<td width="25%" bgcolor="#EFEFEF" height="25" valign="middle" align="right"><?php echo($lang_admin['email']); ?>: &nbsp;</td>
											<td bgcolor="#F8F8FB" height="25" valign="middle" align="left"> &nbsp;
												<input type="text" name="mail" size="48" />
												@
												<select name="maildomain">
													<?php
													$domains = explode(':', $bm_prefs['domains']);
													while(list(, $val) = each($domains))
													{
														echo '<option value="'.$val.'">'.$val.'</option>'."\n";
													}
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td width="20%" bgcolor="#EFEFEF" height="25" valign="middle" align="right"><?php echo($lang_admin['passwort']); ?>: &nbsp;</td>
											<td bgcolor="#F8F8FB" height="25" valign="middle" align="left"> &nbsp;
												<input type="text" name="passwort" size="48" />
											</td>
										</tr>
										<tr>
											<td width="20%" bgcolor="#EFEFEF" height="25" valign="middle" align="right"><?php echo($lang_admin['usr_firstname']); ?>: &nbsp;</td>
											<td bgcolor="#F8F8FB" height="25" valign="middle" align="left"> &nbsp;
												<input type="text" name="vorname" size="48" />
											</td>
										</tr>
										<tr>
											<td width="20%" bgcolor="#EFEFEF" height="25" valign="middle" align="right"><?php echo($lang_admin['usr_name']); ?>: &nbsp;</td>
											<td bgcolor="#F8F8FB" height="25" valign="middle" align="left"> &nbsp;
												<input type="text" name="nachname" size="48" />
											</td>
										</tr>
										<tr>
											<td width="20%" bgcolor="#EFEFEF" height="25" valign="middle" align="right"><?php echo($lang_admin['usr_strasse']); ?>: &nbsp;</td>
											<td bgcolor="#F8F8FB" height="25" valign="middle" align="left"> &nbsp;
												<input type="text" name="strasse" size="40" />
												<input type="text" name="nr" size="3" />
											</td>
										</tr>
										<tr>
											<td width="20%" bgcolor="#EFEFEF" height="25" valign="middle" align="right"><?php echo($lang_admin['usr_ort']); ?>: &nbsp;</td>
											<td bgcolor="#F8F8FB" height="25" valign="middle" align="left"> &nbsp;
												<input type="text" name="plz" size="8" />
												<input type="text" name="ort" size="35" />
											</td>
										</tr>
										<tr>
											<td width="20%" bgcolor="#EFEFEF" height="25" valign="middle" align="right"><?php echo($lang_admin['usr_land']); ?>: &nbsp;</td>
											<td bgcolor="#F8F8FB" height="25" valign="middle" align="left"> &nbsp;
												<select name="land">
													<?php
													$sql = new SQLq("SELECT * FROM {pre}staaten ORDER BY land ASC");
													while($row = $sql->FetchArray())
													{
														echo '<option value="'.$row['id'].'"' . ($row['id'] == $bm_prefs['std_land'] ? ' selected="selected"' : '') . '>' . $row['land'] . '</option>' . "\n";
													}
													$sql->FreeClose();
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td width="20%" bgcolor="#EFEFEF" height="25" valign="middle" align="right"><?php echo($lang_admin['usr_tel']); ?>: &nbsp;</td>
											<td bgcolor="#F8F8FB" height="25" valign="middle" align="left"> &nbsp;
												<input type="text" name="tel" size="48" />
											</td>
										</tr>
										<tr>
											<td width="20%" bgcolor="#EFEFEF" height="25" valign="middle" align="right"><?php echo($lang_admin['usr_fax']); ?>: &nbsp;</td>
											<td bgcolor="#F8F8FB" height="25" valign="middle" align="left"> &nbsp;
												<input type="text" name="fax" size="48" />
											</td>
										</tr>
										<tr>
											<td width="20%" bgcolor="#EFEFEF" height="25" valign="middle" align="right"><?php echo($lang_admin['usr_handy']); ?>: &nbsp;</td>
											<td bgcolor="#F8F8FB" height="25" valign="middle" align="left"> &nbsp;
												<input type="text" name="handy" size="48" />
											</td>
										</tr>
										<tr>
											<td width="20%" bgcolor="#EFEFEF" height="25" valign="middle" align="right"><?php echo($lang_admin['usr_altmail']); ?>: &nbsp;</td>
											<td bgcolor="#F8F8FB" height="25" valign="middle" align="left"> &nbsp;
												<input type="text" name="altmail" size="48" />
											</td>
										</tr>
										<tr>
											<td width="20%" bgcolor="#EFEFEF" height="25" valign="middle" align="right"><?php echo($lang_admin['usr_group']); ?>: &nbsp;</td>
											<td bgcolor="#F8F8FB" height="25" valign="middle" align="left"> &nbsp;
												<select name="gruppe">
													<?php
													$sql = new SQLq("SELECT titel,id FROM {pre}gruppen ORDER BY titel ASC");
													while($row = $sql->FetchArray())
													{
														echo '<option value="'.$row['id'].'"' . ($row['id'] == $bm_prefs['std_gruppe'] ? ' selected="selected"' : '') . '>' . $row['titel'] . '</option>' . "\n";
													}
													$sql->FreeClose();
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td width="20%" bgcolor="#EFEFEF" height="25" valign="middle" align="right">&nbsp;</td>
											<td bgcolor="#F8F8FB" height="25" valign="middle" align="left"> &nbsp;
												<input type="submit" value="<?php echo($lang_admin['erstellen']); ?>" />
											</td>
										</tr>
							    </table>
							  </td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
		</form>			
		</center>
		<?php 
	}
}

if($_REQUEST['do'] == "search") {
?>
	<br>
	<center>
	<table width="90%" bgcolor="#999999" cellspacing="1" border="0">
		<tr>
			<td height="24" background="res/lauf.jpg">&nbsp;&nbsp;<font color="#666666"><b><?php echo($lang_admin['search']); ?></b></td>
		</tr>
		<tr>
			<td bgcolor="#ffffff">
				<table cellspacing="1" border="0" width="100%">
					<tr>
						<td bgcolr="#ffffff" colspan="4" align="justify"><p align="justify">
							<table width="100%" cellspacing="1">
								<form name="searchform" action="admin.php?action=users&do=main&search=yes&PHPSESSID=<?php echo(session_id()); ?>" method="post">
									<tr>
										<td width="20%" bgcolor="#EFEFEF" height="25" valign="middle" align="right"><?php echo($lang_admin['search_text']); ?>: &nbsp;</td>
										<td bgcolor="#F8F8FB" height="25" valign="middle" align="left"> &nbsp;<input type="text" name="value" size="30"></td>
									</tr>
									<tr>
										<td bgcolor="#EFEFEF" height="25" valign="middle" align="right"><?php echo($lang_admin['search_in']); ?>: &nbsp;</td>	 
										<td bgcolor="#F8F8FB" height="110" valign="middle" align="left">
											<table border="0" width="100%">
												<tr>
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_email"> <?php echo($lang_admin['srch_email']); ?></td>
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_passwort"> <?php echo($lang_admin['srch_passwort']); ?></td>
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_gruppe"> <?php echo($lang_admin['srch_gruppe']); ?></td>
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_ip"> <?php echo($lang_admin['srch_ip']); ?></td>
												</tr>
												<tr>													
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_altmail"> <?php echo($lang_admin['srch_altmail']); ?></td>
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_forward"> <?php echo($lang_admin['srch_forward']); ?></td>
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_vorname"> <?php echo($lang_admin['srch_vorname']); ?></td>
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_nachname"> <?php echo($lang_admin['srch_nachname']); ?></td>
												</tr>
												<tr>													
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_plz"> <?php echo($lang_admin['srch_plz']); ?></td>
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_ort"> <?php echo($lang_admin['srch_ort']); ?></td>													
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_str"> <?php echo($lang_admin['srch_str']); ?></td>
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_hnr"> <?php echo($lang_admin['srch_hnr']); ?></td>
												</tr>
												<tr>
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_land"> <?php echo($lang_admin['srch_land']); ?></td>
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_tel"> <?php echo($lang_admin['srch_tel']); ?></td>
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_cellphone"> <?php echo($lang_admin['srch_cellphone']); ?></td>
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_fax"> <?php echo($lang_admin['srch_fax']); ?></td>
												</tr></form>
												<tr>
													<td width="25%"> &nbsp;<input type="checkbox" class="checkbox" name="search_all" onClick="negateChoice(searchform);"> <b><?php echo($lang_admin['srch_all']); ?></b></td>
												</tr>																																																																								
											</table>
									</td>										
									</tr>
									<tr>
										<td bgcolor="#F8F8FB" height="30" align="center" valign="middle">&nbsp;</td>	 
										<td bgcolor="#EFEFEF" height="30" align="center" valign="middle"><input type="button" value="<?php echo($lang_admin['search_button']); ?>" onClick="searchform.submit();"></td>	 
									</tr>									
								</form>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<?php
}

if($_REQUEST['do'] == "lock") {
	if(!isset($_REQUEST['step']) || $_REQUEST['step'] == "main") {
?>
		<br>
		<div align="center">
		<table width="90%" bgcolor="#999999" cellspacing="1" border="0">
			<tr>
				<td height="24" background="res/lauf.jpg">&nbsp;&nbsp;<font color="#666666"><b><?php echo($lang_admin['locked']); ?></b></td>
			</tr>
			<tr>
				<td bgcolor="#ffffff">
					<table cellspacing="1" border="0" width="100%">
						<tr>
							<td bgcolr="#ffffff" colspan="4" align="justify"><p align="justify">
		
							<table width="100%" cellspacing="1">
							 <tr>
								 <td bgcolor="#EFEFEF" height="22"> &nbsp;<b><?php echo($lang_admin['bedingung']); ?></b></td>
								 <td bgcolor="#EFEFEF" height="22"> &nbsp;<b><?php echo($lang_admin['aktionen']); ?></b></td>	 
							 </tr>
							 <?php
								$sql = new SQLq("SELECT * FROM {pre}locked");
								while($row = $sql->FetchArray()) {
									switch($row['typ']) {
										case 'gleich':
											$typ = $lang_admin['gleich'];
											break;
										case 'start':
											$typ = $lang_admin['start'];
											break;										
										case 'ende':
											$typ = $lang_admin['ende'];
											break;										
										case 'mitte':
											$typ = $lang_admin['mitte'];
											break;																																								
									}
									?>
							 <tr>
								 <td bgcolor="#EFEFEF" height="22"> &nbsp;<?php echo($typ.' "'.stripslashes($row['benutzername']).'"'); ?></td>
								 <td bgcolor="#EFEFEF" height="22"> &nbsp;<a href="admin.php?action=users&do=lock&step=edit&id=<?php echo($row['id']); ?>&PHPSESSID=<?php echo(session_id()); ?>"><?php echo($lang_admin['edit']); ?></a> - <b><a href="admin.php?action=users&do=lock&step=delete&id=<?php echo($row['id']); ?>&PHPSESSID=<?php echo(session_id()); ?>"><?php echo($lang_admin['delete']); ?></a></b></td>	 
							 </tr>						 		
									<?php
								}
								$sql->FreeClose();
							 ?>
							</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		
		<form name="newlockForm" action="admin.php?action=users&do=lock&step=add&PHPSESSID=<?php echo(session_id()); ?>" method="post">
		<table width="90%" bgcolor="#999999" cellspacing="1" border="0">
			<tr>
				<td height="24" background="res/lauf.jpg">&nbsp;&nbsp;<font color="#666666"><b><b><?php echo($lang_admin['newlock']); ?></b></td>
			</tr>
			<tr>
				<td bgcolor="#ffffff">
					<table cellspacing="1" border="0" width="100%">
						<tr>
							<td bgcolor="#ffffff" colspan="4" align="justify"><p align="justify">
								<table width="100%" cellspacing="1">
									<tr>
										<td bgcolor="#EFEFEF" height="30" valign="middle"> &nbsp;<?php echo($lang_admin['username']); ?> <select name="type"><option value="start"><?php echo($lang_admin['start']); ?></option><option value="mitte"><?php echo($lang_admin['mitte']); ?></option><option value="ende"><?php echo($lang_admin['ende']); ?></option><option value="gleich"><?php echo($lang_admin['gleich']); ?></option></select> <input type="text" size="25" name="username"></input> <input type="submit" value="<?php echo($lang_admin['add']); ?>"></input></td>	 
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		</form>
		</div>
<?php
	}
	
	if($_REQUEST['step'] == "edit") {
		$sql = new SQLq("SELECT * FROM {pre}locked WHERE id = ".$_REQUEST['id']." LIMIT 1");
		$row = $sql->FetchArray();
?>
	<div align="center">
		<form name="newlockForm" action="admin.php?action=users&do=lock&step=update&PHPSESSID=<?php echo(session_id()); ?>" method="post">
		<table width="90%" bgcolor="#999999" cellspacing="1" border="0">
			<tr>
				<td height="24" background="res/lauf.jpg">&nbsp;&nbsp;<font color="#666666"><b><?php echo($lang_admin['editlock']); ?></b></td>
			</tr>
		</table>
		<table width="90%" bgcolor="#999999" cellspacing="1" border="0">		
			<tr>
				<td bgcolor="#ffffff">
					<table cellspacing="1" border="0" width="100%">
						<tr>
							<td bgcolor="#ffffff" colspan="4" align="justify"><p align="justify">
								<table width="100%" cellspacing="1">
									<tr>
										<td bgcolor="#EFEFEF" height="30" valign="middle"><input type="hidden" name="id" value="<?php echo($row['id']) ?>"></input> &nbsp;<?php echo($lang_admin['username']); ?> <select name="type"><option value="start"<?php echo($row['typ'] == "start" ? " selected" : "") ?>><?php echo($lang_admin['start']); ?></option><option value="mitte"<?php echo($row['typ'] == "mitte" ? " selected" : "") ?>><?php echo($lang_admin['mitte']); ?></option><option value="ende"<?php echo($row['typ'] == "ende" ? " selected" : "") ?>><?php echo($lang_admin['ende']); ?></option><option value="gleich"<?php echo($row['typ'] == "gleich" ? " selected" : "") ?>><?php echo($lang_admin['gleich']); ?></option></select> <input type="text" size="25" name="username" value="<?php echo($row['benutzername']) ?>"></input> <input type="submit" value="<?php echo($lang_admin['ok']); ?>"></input> <input type="button" value="<?php echo($lang_admin['goback']); ?>" onClick="history.back();"></input></td>	 
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		</form>
	</div>
<?php
		$sql->Close();
	}	
	
	if($_REQUEST['step'] == "add") {
		$sql = new SQLq("INSERT INTO {pre}locked(typ,benutzername) VALUES ('".$_REQUEST['type']."','".addslashes($_REQUEST['username'])."')");
		$sql->Close();

		header("Location: admin.php?action=users&do=lock&PHPSESSID=".session_id());
	}	
	
	if($_REQUEST['step'] == "update") {
		$sql = new SQLq("UPDATE {pre}locked SET typ='".$_REQUEST['type']."', benutzername='".addslashes($_REQUEST['username'])."' WHERE id=".$_REQUEST['id']);
		$sql->Close();

		header("Location: admin.php?action=users&do=lock&PHPSESSID=".session_id());
	}	
	
	if($_REQUEST['step'] == "delete") {
		$sql = new SQLq("DELETE FROM {pre}locked WHERE id = ".$_REQUEST['id']);
		$sql->Close();
		
		header("Location: admin.php?action=users&do=lock&PHPSESSID=".session_id());
	}
}
?>