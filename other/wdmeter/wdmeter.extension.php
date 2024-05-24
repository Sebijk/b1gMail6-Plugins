<?php
// +-------------------------------------------------------+
// | Copyright (c) 2005 - time() by Mario Hennenberger     |
// +-------------------------------------------------------+
// | $Author: altf4 $
// | $Date: 2005/08/24 16:45:12 $
// | $Revision: 0.23 $
// | $Source: http://src.0x8.net/Static/Projects/b1gmail/tools/wdmeter/wdmeter.extension.php,v $
// +-------------------------------------------------------+
// admin-modul Erweiterung
// zeigt dien "fuellstand" der user-websiscs an ;-)

$MODULE_CALL = 'modWDMeterExt';

class modWDMeterExt extends b1gMailModul
{
	// Informationen zum Modul
	function modWDMeterExt()
	{
		$reg = array();
		$this->titel		= 'Admin-Webdisk-fuellstand';
		$this->autor		= 'Mario [ALT][F4] Hennenberger';
		$this->web		= 'http://0x8.in.th/?1103467751';
		$this->mail		= 'mh@0x8.in.th';
		$this->version		= 'NON-Clean-Code BETA 0.23 ;-)';
		$this->designedfor	= '6.3.x';
		$this->admin_pages	= true;
		$this->admin_page_title = "WD-Meter";
	}
	// Administration
	function AdminHandler()
	{
		global $lang_admin;

		$sql = new SQLq("SELECT `dateiname`,`user`,`size` FROM `{pre}diskfiles` ORDER BY user");

		$tm_user = time();
		$tm_count = 0;
		while($row = $sql->FetchArray())
		{
		$count[$row['user']] = $count[$row['user']] + $row['size'];
		$icount[$row['user']]++;		
		
		}
		$sql->FreeClose();
		
		// output

?>

        <CENTER><BR>
        <table width="90%" cellspacing="1" bgcolor="#063781">
         <tr>
          <td height="19" colspan="3">&nbsp;&nbsp;<font color="#FFBE32"><b>Webdisk 'fuellstandsanzeiger' ;-)</b></font></td>
         </tr>
<?
		foreach($count as $Key => $Value) {
			$sql = new SQLq("SELECT gruppe,email,traffic_down,traffic_up FROM `{pre}users` WHERE id='".$Key."'");
			while($row = $sql->FetchArray())
			{
			$tm_group = $row['gruppe'];$tm_email = $row['email']; $tm_trafficin = $row['traffic_up']/1024/1024;$tm_trafficout = $row['traffic_down']/1024/1024; }

			$sql = new SQLq("SELECT webdisk FROM `{pre}gruppen` WHERE id='".$tm_group."'");
			while($row = $sql->FetchArray()) {
			$tm_dproz = $Value / $row['webdisk'] * 100;
			$tm_dproz = round($tm_dproz/10,0);
			}

//   echo "/nKey:" . $Key;
//   echo "Value:" . $Value;  

//			echo $tm_email .'Groesse: '.round($Value/1024/1024,3) .'MB  (Traffic: '.round($tm_trafficin,2).'/'.round($tm_trafficout,2).' (IN/OUT))';
echo "\n";


?>
          <tr>
                <td bgcolor="#EDEEF5" height="22" width="30%">&nbsp;<? echo $tm_email; ?></td>
                <td bgcolor="#EDEEF5" height="22" width="30%">&nbsp;<IMG SRC="res/load_<? echo $tm_dproz; ?>.gif" ALT="<? echo $icount[$Key];?> Files / <? echo round($Value,3); ?> Bytes">&nbsp; <ACRONYM TITLE="<? echo $icount[$Key];?> Files / <? echo round($Value,3); ?> Bytes"><? echo round($Value/1024/1024,3); ?> MB</ACRONYM></td>
                <td bgcolor="#EDEEF5" height="22" width="40%">&nbsp;User-Traffic: <? echo round($tm_trafficin,3).'/'.round($tm_trafficout,3).' MB (IN/OUT))'; ?></td>
          </tr>

<?
		}


//$tm_ac = count($count);
//for($y=0; $y<$tm_ac){
//print_r(current($count));
//}
/*echo "        </TABLE>
<H1> THIS IS A BETA VERSION !!!</H1>
please report bugs and requests to <A HREF=mailto:mh@0x8.in.th>mh@0x8.in.th</a><BR>
Krab kuhn ka ;-)</CENTER>";
echo "<PRE>"; readfile("http://src.0x8.in.th/Static/Projects/b1gmail/tools/wdmeter/README.txt"); echo "</PRE>";
*/}	

}
?>
