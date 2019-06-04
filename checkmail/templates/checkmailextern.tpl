<html>
<head>
<meta http-equiv="refresh" content="{$in_refresh}" />
<title>{$notread} neue Nachricht(en) - CheckMail</title>
<link href="style.css.php?bmsession={$sid}" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
  <tr><td class="box_head_left"><center>CheckMail</center></td></tr>
  <tr>
	<td class="box_left">

				<center>{$zeitangabe}
<p />
<a target="_blank" href="index.php?bmsession={$sid}"><img border="0" src="{$self}icons/bm_logo.png" alt="" /></a>
<p />
<font color="#666666" face="Tahoma">{$s_usermail}</font>
<p />
<font face="Tahoma" size="2">{$willkommenstext}</font>
<p />
<font face="Tahoma" size="2">
<a href="main.php?action=checkmail&amp;bmsession={$sid}" onclick="window.location.reload()" style="text-decoration: none">Aktualisieren</a>
</font></center>
<p />{if $cgicore}<img src="{$cgiurl}" border="0" width="1" height="1" alt="" />{/if}
  <img src="fetch.mail.php?bmsession={$sid}" border="0" width="1" height="1" alt="" />
    </td>
  </tr>
</table>
</body>
</html>