{*
 * b1gMail Template
 *
 * Template für Notizübersicht
 *
 * $Author: radada $
 * $Date: 2005/08/23 19:00:00 $
 * $Revision: 1.0 $
 * $RCSfile: notizen.tpl,v $
 *}
 <table class="box" cellspacing="1" cellpadding="0">
	<tr>
		<td class="box_head">Notizen - Übersicht</td>
	</tr>
	<tr>
		<td class="box_main">
				<table width="100%" cellspacing="1" cellpadding="0" class="folder">
					<tr>
						<td class="td2" height="20" style="text-align:left;"><b>&nbsp;Betreff</b></td>
						<td class="td1" width="60" height="20" style="text-align:left;"><b>&nbsp;Uhrzeit</b></td>
						<td class="td2" width="75" height="20" style="text-align:left;"><b>&nbsp;Datum</b></td>
						<td class="td1" width="115" height="20" style="text-align:left;"><b>&nbsp;Aktionen</b></td>
					</tr>
					{foreach from=$notes item=item}
					<tr>
						<td class="td2" height="20" style="text-align:left;">&nbsp;<a href="main.php?action=notes&do=show&id={$item.id}&bmsession={$sid}">{$item.betreff}</a></td>
						<td class="td1" width="60" height="20" style="text-align:left;">&nbsp;{$item.uhrzeit}</td>
						<td class="td2" width="75" height="20" style="text-align:left;">&nbsp;{$item.datum}</td>
						<td class="td1" width="115" height="20" style="text-align:left;">&nbsp;<a href="main.php?action=notes&do=edit&id={$item.id}&bmsession={$sid}">Ändern</a> - <a href="main.php?action=notes&do=del&id={$item.id}&bmsession={$sid}">Löschen</a></td>
					</tr>
					{/foreach}
				</table>
				<div align=left>>> <a href="main.php?action=notes&do=new&bmsession={$sid}">Notiz erstellen</a></div>
		</td>
	</tr>
</table>