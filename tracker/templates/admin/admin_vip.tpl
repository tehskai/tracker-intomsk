<h1>VIP {NAZV}</h1>
<p>{DESC}</p>
<br>
<!-- IF cp -->
<table class="forumline" width="100%">
<th colspan="9">Оформленные Пользователи</th>
<tr>
	<th>Пользователь</th>
	<th>Балланс</th>
	<th>Тариф</th>
	<th>Начальная дата</th>
	<th>Конечная дата</th>
	<th>Цена за тариф</th>
	<th>Статус</th>
	<th>Изменить</th>
	<th>Снять VIP</th>
</tr>
<!-- BEGIN viprow -->
<tr>
	<td class="row1" align="center"><a href="../profile.php?mode=viewprofile&u={viprow.VIP_ID}">{viprow.VIP_NAME}</a></td>
	<td class="row2" align="center">{viprow.VIP_BAL} IN</td>
	<td align="center" class="row1"><a href="admin_vip.php?mode=tedit&amp;t={viprow.VIP_TARIF_ID}"><strong>{viprow.VIP_TARIF}</strong></a></td>
	<td class="row2" align="center">{viprow.VIP_TARIF_SDATE}</td>
	<td class="row1" align="center">{viprow.VIP_TARIF_EDATE}</td>
	<td class="row2" align="center">{viprow.VIP_TARIF_PRICE} IN</td>
	<td class="row1" align="center">{viprow.VIP_STATUS}</td>
	<td class="row2" align="center"><a href="admin_vip.php?mode=edit&v={viprow.VIP_ID}">Перейти</a></td>
	<td class="row1" align="center"><a href="admin_vip.php?mode=del&v={viprow.VIP_ID}">OK</a></td>
</tr>
<!-- END viprow -->
</table>
<br />
<br />
<table class="forumline" width="100%">
	<tr>
		<th colspan="4">Не оформленные пользователи</th>
	</tr>
	<tr>
		<th>Пользователь</th>
		<th>Балланс</th>
		<th>Статус</th>
		<th>Изменить</th>
	</tr>
<!-- BEGIN nviprow -->
	<tr>
		<td class="row1" align="center"><a href="../profile.php?mode=viewprofile&u={nviprow.VIP_ID}" target="_blank">{nviprow.VIP_NAME}</a></td>
		<td class="row2" align="center">{nviprow.VIP_BAL} IN</td>
		<td class="row1" align="center">{nviprow.VIP_STATUS}</td>
		<td class="row2" align="center"><a href="admin_vip.php?mode=edit&v={nviprow.VIP_ID}">Перейти</a></td>
	</tr>	
<!-- END nviprow -->
</table>
<!-- ENDIF -->

<!-- IF edit -->
<form action="admin_vip.php" method="get">
<input type="hidden" name="mode" value="eu" />
<input type="hidden" name="v" value="{VIP_IDE}" />
<table class="forumline">
	<tr>
		<th colspan="2">Редатирование пользователя {USERNAME}</th>
	</tr>
	<tr>
		<td class="row1" width="50%" align="center"><strong>Балланс</strong></td>
		<td class="row3" width="50%"><input title="Балланс пользователя" type="text" maxlength="4" name="b" value="{BALLANCE}" /></td>
	</tr>
	<tr>
		<td class="row1" width="50%" align="center"><strong>Начальная дата</strong></td>
		<td class="row3" width="50%">{S_S} <strong>:</strong> {I_S} <strong>:</strong> {H_S}<strong> </strong>{D_S} <strong>-</strong> {M_S} <strong>-</strong> {Y_S} *		</td>
	</tr>
	<tr>
		<td class="row1" width="50%" align="center"><strong>Конечная дата</strong></td>
		<td class="row3" width="50%">{S_E} <strong>:</strong> {I_E} <strong>:</strong> {H_E}<strong> </strong>{D_E} <strong>-</strong> {M_E} <strong>-</strong> {Y_E} * </td>
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
		<!-- ENDIF -->		
		</td>
	</tr>
	
	<tr><td colspan="2" class="row6" align="center"><input type="reset" value="Сбросить"  />
	<input type="submit" value="Изменить" /></td></tr>
</table>
</form>
<br />
<p align="center">* Дата отображена в формате <u><strong class="topictitle">s:i:H d-m-Y </strong></u></p>
<!-- ENDIF -->
<!-- IF tarif -->
<p align="left">&nbsp;<a href="admin_vip.php?mode=tadd"><b>Добавить новый тариф</b></a></p>
<p align="left">&nbsp;</p>
<table class="forumline" width="100%">
	<tr>
		<th>ID</th>
		<th>Название тарифа</th>
		<th>Длительность</th>
		<th>Цена</th>
		<th colspan="2">Операции</th>
	</tr>
	<!-- BEGIN tarrow -->
	<tr>
		<td align="center" class="row1"><strong>{tarrow.ID}</strong></td>
		<td align="center" class="row3"><strong>{tarrow.NAME}</strong></td>
		<td align="center" class="row5"><strong>{tarrow.TIME}</strong></td>
		<td align="center" class="row3"><strong>{tarrow.PRICE}</strong></td>
		<td align="center" class="row5"><strong>{tarrow.EDIT}</strong></td>
		<td align="center" class="row5"><strong><a href="admin_vip.php?mode=delt&tar={tarrow.ID}">Удалить</a></strong></td>
	</tr>
	<!-- END tarrow -->
</table>
<!-- ENDIF -->
<!-- IF tadd -->
<br />
<form action="admin_vip.php" method="get">
<input type="hidden" name="mode" value="taddc" />
<table width="100%" class="forumline">
	<tr>
		<th colspan="2">Добавление Тарифа</th>
	</tr>
	<tr>
		<td width="50%" align="center" class="row1">Название</td>
		<td width="50%" class="row3"><input type="text" name="namet" size="20" maxlength="20" />
		не длинне 20 симв. </td>
	</tr>
	<tr>
		<td width="50%" align="center" class="row1">Длительность</td>
		<td width="50%" class="row3"><input type="text" name="time" size="20" maxlength="20" /> 
		в днях </td>
	</tr>
	<tr>
		<td width="50%" align="center" class="row1">Цена</td>
		<td width="50%" class="row3"><input type="text" name="price" size="20" maxlength="20" /> </td>
	</tr>
	<tr><th colspan="2"><input type="reset" value="Сбросить" /> <input type="submit" value="Создать" /></th></tr>
</table>
</form>
<!-- ENDIF -->

<br />
<br />
<p align="center">&copy; Create by <a href="mailto:Romanuy@sibmail.com">Romanuy</a></p>