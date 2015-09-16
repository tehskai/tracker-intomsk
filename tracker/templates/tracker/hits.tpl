<font color="#3377AA"><h1>{PAGE_TITLE}</h1></font>
<p class="nav"><a href="{U_INDEX}">{T_INDEX}</a></p>





<style type="text/css">
.h_link a{text-decoration:none;
}

</style>

<!-- IF IS_AM -->

<table>
	<tr>
		<td class="h_link"> <a href="/hits.php"><img src="/images/icontorhits/beginning.png"></a> </td>
		<td class="h_link"> <a href="?id=add"><img src="/images/icontorhits/add.png"></a> </td>
		<td class="h_link"> <a href="?id=all_hits"><img src="/images/icontorhits/all-new.png"></a> </td>
	<tr>
<table>



<!-- IF TPL_P20_HITS -->
<!--========================================================================-->



<table class="forumline">
<tr>
	<th colspan="6" class="thHead">{L_LAST_20_HITS}</th>
</tr>
<tr>
	<td class="catTitle">{L_HIT_NUM}</td>
	<td class="catTitle">{L_HIT_TOPIC_ID}</td>
	<td class="catTitle">{L_HIT_DESCR}</td>
	<td class="catTitle">{L_HIT_ID}</td>
	<td class="catTitle">{L_HIT_EDIT}</td>
	<td class="catTitle">{L_HIT_DEL}</td>

</tr>
<!-- BEGIN hit_row -->
	<tr>
	  <td class="row1" align="center">{hit_row.NUM}</td>
	  <td class="row1" align="center">{hit_row.HIT_TITLE}</td>
	  <td class="row1" align="center">{hit_row.HIT_IMG}</td>
	  <td class="row1" align="center">{hit_row.HIT_ID}</td>
	  <form method="post" action="?id=edit">
	  <td class="row1" align="center"><input name="ids" type="hidden" value="{hit_row.HIT_ID}"><input type="submit" name="submit" value="Редактировать" /></td>
	  </form>
	  <form method="post" action="?id=delete">
	  <td class="row1" align="center"><input name="ids" type="hidden" value="{hit_row.HIT_ID}"><input type="submit" name="submit" value="Удалить" /></td>
	  </form>
	</tr>
<!-- END hit_row -->
</table>
<!--========================================================================-->
<!-- ENDIF / TPL_P20_HITS -->


<!-- IF TPL_P_HITS -->
<!--========================================================================-->



<table class="forumline">
<tr>
	<th colspan="6" class="thHead">{L_ALL_HITS}</th>
</tr>
<tr>
	<td class="catTitle">{L_HIT_NUM}</td>
	<td class="catTitle">{L_HIT_TOPIC_ID}</td>
	<td class="catTitle">{L_HIT_DESCR}</td>
	<td class="catTitle">{L_HIT_ID}</td>
	<td class="catTitle">{L_HIT_EDIT}</td>
	<td class="catTitle">{L_HIT_DEL}</td>

</tr>
<!-- BEGIN hit_row -->
	<tr>
	  <td class="row1" align="center">{hit_row.NUM}</td>
	  <td class="row1" align="center">{hit_row.HIT_TITLE}</td>
	  <td class="row1" align="center">{hit_row.HIT_IMG}</td>
	  <td class="row1" align="center">{hit_row.HIT_ID}</td>
	  <form method="post" action="?id=edit">
	  <td class="row1" align="center"><input name="ids" type="hidden" value="{hit_row.HIT_ID}"><input type="submit" name="submit" value="Редактировать" /></td>
	  </form>
	  <form method="post" action="?id=delete">
	  <td class="row1" align="center"><input name="ids" type="hidden" value="{hit_row.HIT_ID}"><input type="submit" name="submit" value="Удалить" /></td>
	  </form>
	</tr>
<!-- END hit_row -->
</table>
<!--========================================================================-->
<!-- ENDIF / TPL_P_HITS -->


<!-- IF TPL_EDIT_HITS -->
<!--========================================================================-->
<font color="#3377AA"><h1>Инструкция</h1></font><br><br>
<font color="#333" size="12px">
1. В поле (Введите ID топика) вводится только ID топика! <br>1.2 Пример есть ссылка http://example.com/forum/viewtopic.php?t=<b>123</b> жирным выделен ID топика.<br><br>
2. В поле (Название(описание)) вводится название или описание релиза.
</font>
<br>
<br>
<br>

<table class="forumline">
<tr>
	<th colspan="6" class="thHead">{L_EDIT_HITS}</th>
</tr>
<tr>
	<td class="catTitle">{L_EDIT_HIT_TOPIC_ID}</td>
	<td class="catTitle">{L_EDIT_HIT_DESCR}</td>
	<td class="catTitle">{L_EDIT_HIT_SAVE}</td>
</tr>
<form method="post" action="?id=edit_news">
	<tr>
		
		<td class="row1" align="center"><!-- BEGIN edit_hit --><input type="text" name="linkth1" maxlength="255" value="{edit_hit.HIT_TITLE}"/><!-- END edit_hit --></td>
		<td class="row1" align="center"><!-- BEGIN edit_hit --><input name="descr1" type="text" maxlength="500" value="{edit_hit.HIT_IMG}"><!-- END edit_hit --></td>
		<td class="row1" align="center"><!-- BEGIN edit_hit --><input name="hid" type="hidden" value="{edit_hit.HIT_ID}"><!-- END edit_hit --><input type="submit" name="submit" value="Сохранить" /></td>
	</tr>
</form>
</table>
<!--========================================================================-->
<!-- ENDIF / TPL_EDIT_HITS -->


<!-- IF TPL_ADD_HITS -->
<!--========================================================================-->
<font color="#3377AA"><h1>Инструкция</h1></font><br><br>
<font color="#333" size="12px">
1. В поле (Введите ID топика) вводится только ID топика! <br>1.2 Пример есть ссылка http://example.com/forum/viewtopic.php?t=<b>123</b> жирным выделен ID топика.<br><br>
2. В поле (Название(описание)) вводится название или описание релиза.
</font>
<br>
<br>
<br>

<table class="forumline">
<tr>
	<th colspan="6" class="thHead">{L_ADD_HITS}</th>
</tr>
<tr>
	<td class="catTitle">{L_EDIT_HIT_TOPIC_ID}</td>
	<td class="catTitle">{L_EDIT_HIT_DESCR}</td>
	<td class="catTitle">{L_ADD_HIT_SAVE}</td>
</tr>
<form method="post" action="?id=add_news">
	<tr>
		
		<td class="row1" align="center"><input width="300px" type="text" name="linkth" maxlength="255" /></td>
		<td class="row1" align="center">
		<select name="platform">
		<option value="[PC]" selected>PC</option>
		<option value="[PS3]">PS3</option>
		<option value="[XBOX360]">XBOX360</option>
		<option value="[IOS]">IOS</option>
		<option value="[PSP]">PSP</option>
		<option value="[NINTENDO]">Nintendo</option>
		<option value="[XBOX]">XBOX</option>
		<option value="[V]">Видео</option>
		<option value="[PROGRAM]">Программа</option>
		<option value="[OTHER]">Другое</option>
      </select>
     <input name="descr" type="text" maxlength="500"></td>
		<td class="row1" align="center"><input type="submit" name="submit" value="Добавить" /></td>
	</tr>
</form>
</table>
<!--========================================================================-->
<!-- ENDIF / TPL_ADD_HITS -->

<!-- IF TPL_INFO -->
<!--========================================================================-->
<table class="forumline">
<tr>
	<th class="thHead">{L_TITLE}</th>
</tr>
	<tr>
	  <td class="row1" align="center">{L_INFO}</td>
	</tr>

</table>
<!--========================================================================-->
<!-- ENDIF / TPL_INFO -->
<!-- ELSE -->
<table class="forumline">
<tr>
	<th class="thHead">Недостаточно прав</th>
</tr>
	<tr>
	  <td class="row1" align="center">У вас недостаточно прав для управления новинками</td>
	</tr>

</table>
<!-- ENDIF -->
<div class="bottom_info">
	<p style="float: left">{L_ONLINE_EXPLAIN}</p>
	<div id="timezone">
		<p>{LAST_VISIT_DATE}</p>
		<p>{CURRENT_TIME}</p>
		<p>{S_TIMEZONE}</p>
	</div>
	<div class="clear"></div>
</div><!--/bottom_info-->

