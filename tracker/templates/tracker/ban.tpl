<table width="99%" class="forumline" cellpadding="4" cellspacing="1" border="0" align="center" >
<tr>
 <th colspan="1">Забанить пользователя: {USER_NAME}</th>
</tr>
<tr class="row6">
<td>
<form action="ban.php?mode=ban&u={USER}&p={POST_ID}" method=post name=post onsubmit="return checkForm(this);">
Устанвить наказание на: <select name=plasdate>
<option value=sel <!-- IF NAK_TIME == "sel" --> selected<!-- ENDIF -->> Выбрать</option>
<option value=1d <!-- IF NAK_TIME == "1d" --> selected<!-- ENDIF -->> Один день</option>
<option value=2d <!-- IF NAK_TIME == "2d" --> selected<!-- ENDIF -->> Два дня</option>
<option value=3d <!-- IF NAK_TIME == "3d" --> selected<!-- ENDIF -->> Три дня</option>
<option value=1w <!-- IF NAK_TIME == "1w" --> selected<!-- ENDIF -->> Одна неделя</option>
<option value=2w <!-- IF NAK_TIME == "2w" --> selected<!-- ENDIF -->> Две недели</option>
<option value=1m <!-- IF NAK_TIME == "1m" --> selected<!-- ENDIF -->> Месяц</option>
<option value=2m <!-- IF NAK_TIME == "2m" --> selected<!-- ENDIF -->> 2 Месяца</option>
<option value=3m <!-- IF NAK_TIME == "3m" --> selected<!-- ENDIF -->> 3 Месяца</option>
<option value=6m <!-- IF NAK_TIME == "6m" --> selected<!-- ENDIF -->> Пол года</option>
<option value=1y <!-- IF NAK_TIME == "1y" --> selected<!-- ENDIF -->> Год</option>
<option value=2y <!-- IF NAK_TIME == "2y" --> selected<!-- ENDIF -->> Два года</option>
<option value=3y <!-- IF NAK_TIME == "3y" --> selected<!-- ENDIF -->> Три года</option>
<option style="Font-weight: bold;" value=inf <!-- IF NAK_TIME == "inf" --> selected<!-- ENDIF -->>Вечный</option>
</select><br>
Причина: <input type=text name=warn_theme size=185 MAXLENGTH=250 value={THEM}>
<br>

</td></tr><tr align="center" class="row6" ><td>
<input type="submit" name="s_mode" value="Отправить" />
</td>
</tr>
<tr>
 <th><br></th>
</tr>

</table><br>




