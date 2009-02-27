<?

class PieGenerator {
	
	static function edit() {
		?>
		<form action="" method="post">
		<input type="hidden" name="PAGEMODE" value="UPDATEINTERFACE" />
		<input type="hidden" name="SitePageID" value="<?=$SitePageID?>" />
		<input type="hidden" name="ID" value="<?=$ID?>" />
		<table>
		<tr>
			<td colspan="2">Table</td>
		</tr>
		<tr>
			<td colspan="2">
				<table>
				<tr>
					<td width="33%">
						<b>Table Name</b><span style="font-size:11px;"> &nbsp;
						<input type="checkbox" style="margin:0;width:14px;height:14px;vertical-align:top;" id="CopyData" name="CopyData" value="Y" /><label for="CopyData"> Copy Data</label> &nbsp;
						<input type="checkbox" style="margin:0;width:14px;height:14px;vertical-align:top;" id="Archive" name="Archive" value="Y" /><label for="Archive"> Archive</label></span>
						<input type="hidden" name="PreviousTableName" value="<?=htmlentities($TableName)?>" />
						<br /><input type="text" name="TableName" value="<?=htmlentities($TableName)?>" maxlength="64" style="width:100%;" />
					</td>
					<td>&nbsp;</td>
					<td style="width:38px;vertical-align:bottom;font-size:11px;">
						<input type="radio" name="AorAn" style="width:12px;height:12px;margin:0;" value="a"<? if ($AorAn == 'a') { ?> checked<? } ?> id="AorAn0" /><label for="AorAn0"> a&nbsp;&nbsp;</label>
						<br /><input type="radio" name="AorAn" style="width:12px;height:12px;margin:0;" value="an"<? if ($AorAn == 'an') { ?> checked<? } ?> id="AorAn1" /><label for="AorAn1"> an</label>
						<br /><img src="/spacer.gif" width="30" height="1" />
					</td>
					<td width="33%">
						<b>Singular</b>
						<br /><input type="text" name="Singular" value="<?=htmlentities($Singular)?>" maxlength="64" style="width:100%;" />
					</td>
					<td>&nbsp;</td>
					<td width="34%">
						<b>Plural</b>
						<br /><input type="text" name="Plural" value="<?=htmlentities($Plural)?>" maxlength="64" style="width:100%;" />
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">Page</td>
		</tr>
		<tr>
			<th>Title</th>
			<td><input type="text" name="PageTitle" style="width:100%;height:20px;" value="<?=htmlentities($Title)?>" class="w" /></td>
		</tr>
		<tr>
			<th>Description</th>
			<td><textarea name="PageDescription" style="width:100%;height:20px;" class="w" onfocus="this.style.height='80px'" onblur="this.style.height='20px'"><?=htmlentities($Description)?></textarea></td>
		</tr>
		<tr>
			<td colspan="2">Fields</td>
		</tr>
		<tr>
			<td colspan="2" style="padding:0px;">
			<div id="Fields"></div>
			<?
			/*
			$AddFields = $LastSitePageFieldID = '';
			$Fields = array();
			$FieldQuery = Query("SELECT f.ID, p.Param, p.Value FROM SitePageFields f, SitePageFieldParams p WHERE f.SitePageID = ".Number($ID)." AND p.SitePageFieldID = f.ID ORDER BY f.FieldIndex");
			while (list($SitePageFieldID, $Param, $Value) = mysql_fetch_row($FieldQuery)) {
				if ($SitePageFieldID != $LastSitePageFieldID) {
					$Fields[] = 'ID:'.$SitePageFieldID;
					$LastSitePageFieldID = $SitePageFieldID;
				}
				$Fields[count($Fields) - 1] .= ','.$Param.':'.JSQuote($Value);
			}
			if (count($Fields)) $AddFields = 'GenAddField({'.join("});GenAddField({", $Fields).'});';
			*/
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2">Security</td>
		</tr>
		<tr>
			<th width="10%">Allow</th>
			<td width="90%"><? /*Checkboxes("SELECT ID AS Allow, GroupName FROM UserGroups ORDER BY GroupName", 2)*/ ?></td>
		</tr>
		<tr>
			<th width="10%">Deny</th>
			<td width="90%"><? /*Checkboxes("SELECT ID AS Deny, GroupName FROM UserGroups ORDER BY GroupName", 2)*/ ?></td>
		</tr>
		<tr>
			<td colspan="2">Options</td>
		</tr>
		<tr>
			<th width="10%">Import SQL</th>
			<td width="90%"><textarea name="ImportSQL" style="width:100%;height:20px;" class="w" onfocus="this.style.height='360px'" onblur="/*this.style.height='20px'*/"><?=htmlentities($ImportSQL)?></textarea></td>
		</tr>
		<tr>
			<td><br /></td>
			<td>
				<table>
				<tr>
					<td><input type="submit" name="Generate" class="Button" value="Generate" /></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		</form>
		<?
	}
	
}
?>