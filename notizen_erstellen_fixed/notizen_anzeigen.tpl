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
		<td class="box_head">Notizen - Anzeigen</td>
	</tr>
	<tr>
		<td class="box_main">
				<table width="100%" cellspacing="1" cellpadding="0" class="folder">
					<tr>
						<td class="td2" width="80" height="20" style="text-align:left;"><b>&nbsp;Betreff:</b></td>
						<td class="td2" height="20" style="text-align:left;">&nbsp;{$note.betreff}</td>
					</tr>
					<tr>
						<td class="td1" width="80" height="20" style="text-align:left;"><b>&nbsp;Erstellt:</b></td>
						<td class="td1" height="20" style="text-align:left;">&nbsp;{$note.uhrzeit} {$note.datum}</td>
					</tr>
					<tr>
						<td class="td2" width="80" height="20" style="text-align:left;" valign="top"><b>&nbsp;Notiz:</b></td>
						<td class="td2" height="20" style="text-align:left;">&nbsp;{$note.text}</td>
					</tr>
					<tr>
						<td class="td1" width="80" height="20" style="text-align:left;"><b>&nbsp;Aktionen:</b></td>
						<td class="td1" height="20" style="text-align:left;">&nbsp;<a href="main.php?action=notes&do=edit&id={$note.id}&bmsession={$sid}">Ändern</a> - <a href="main.php?action=notes&do=del&id={$note.id}&bmsession={$sid}">Löschen</a> - <a href="main.php?action=notes&bmsession={$sid}">Zurück</a></td>
					</tr>
				</table>
		</td>
	</tr>
</table>