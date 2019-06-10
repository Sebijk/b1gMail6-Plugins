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
		<td class="box_head">Notizen - Erstellen</td>
	</tr>
	<tr>
		<td class="box_main">
			<form method="post" action="main.php?action=notes&do=savenew&bmsession={$sid}">
				<table width="100%" cellspacing="1" cellpadding="0" class="folder">
					<tr>
						<td class="td1" width="80" height="20" style="text-align:left;"><b>&nbsp;Betreff:</b></td>
						<td class="td1" height="20" style="text-align:left;"><input type="text" name="betreff" style="width:100%;"></td>
					</tr>
					<tr>
						<td class="td2" width="80" height="20" style="text-align:left;" valign="top"><b>&nbsp;Notiz:</b></td>
						<td class="td2" height="20" style="text-align:left;">{$editor}</td>
					</tr>
					<tr>
						<td class="td1" width="80" height="20" style="text-align:left;"><b>&nbsp;</b></td>
						<td class="td1" height="20" style="text-align:left;">&nbsp;<input type="submit" value="Speichern"></td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
</table>