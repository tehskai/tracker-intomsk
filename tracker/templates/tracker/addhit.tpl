<h1 class="pagetitle">{PAGE_TITLE}</h1>
<p class="nav"><a href="{U_INDEX}">{T_INDEX}</a></p>

<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr> 
		<th height="25" class="thCornerL" nowrap>Добавить новинку</th>
	</tr>
	<tr> 
		<td class="row1" align="center"><span class="gen">
	<Center><form method="post" action="?go=add"><br />
	<table id="fhit">
		<tr>
			<td>Ссылка на новинку:</td>
			<td><input width="300px" type="text" name="linkth" maxlength="255" /></td>
		</tr>
		<tr>
			<td colspan="2" style="color:#666; font-size:10px;">Пример: /forum/viewtopic.php?t=[id топика]</td>
		</tr>
		<tr>
			<td>Название (описание):</td>
			<td><input  name="descr" type="text" maxlength="500"></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;"><br />
			<input type="submit" name="submit" value="Добавить" /></td>
		</tr>
				
	</table>
	</form>
<table id="fhit">
<tr>
			<td>{HIT_UPDATE}{HIT_ADDED}</td>
		</tr>
</table>
</Center></span></td></tr>
</table>

<div class="bottom_info">
	<p style="float: left">{L_ONLINE_EXPLAIN}</p>
	<div id="timezone">
		<p>{LAST_VISIT_DATE}</p>
		<p>{CURRENT_TIME}</p>
		<p>{S_TIMEZONE}</p>
	</div>
	<div class="clear"></div>
</div><!--/bottom_info-->