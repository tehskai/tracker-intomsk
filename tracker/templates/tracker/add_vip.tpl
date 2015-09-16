<form action="add_vip.php" method="get">
<input type="hidden" name="mode" value="add" />
<input type="hidden" name="v" value="{VIP_IDE}" />
<table  width="510" align="center">
	<tr>
		<th colspan="2" class="thHead">Добавление пользователя {USERNAME}</th>
	</tr>
	<tr>
		<td class="row1" width="50%" align="center"><strong>Балланс</strong></td>
		<td class="row3" width="50%"><input title="Балланс пользователя" type="text" maxlength="4" name="b" value="{BALLANCE}" /></td>
	</tr>
	<tr>
		<td width="50%" align="center" class="row1"><strong>Тариф</strong></td>
		<td class="row3" width="50%">{TARIF}</td>
	</tr>
	<tr>
		<td class="row1" width="50%" align="center"><strong>Заблокирован</strong></td>
		<td class="row3" width="50%">
		<!-- IF LOCKED -->
		<select name="l" title="Заморозить VIP статус данного пользователя">
		<option value="1" selected="selected">Да</option>
		<option value="0">Нет</option>
		</select>
		<!-- ELSE -->
		<select name="l" title="Заморозить VIP статус данного пользователя">
		<option value="1">Да</option>
		<option value="0" selected="selected">Нет</option>
		</select>
		<!-- ENDIF -->		</td>
	</tr>
	
	<tr><td colspan="2" class="cat_title" align="center"><input type="reset" value="Сбросить"  />
	<input type="submit" value="Изменить" /></td></tr>
</table>
</form>