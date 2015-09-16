<script type="text/javascript">
	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"expire",
			dateFormat:"%d.%m.%Y",
			cellColorScheme:"beige",
			imgPath:"templates/tracker/js/calend/img/"
			/*selectedDate:{				This is an example of what the full configuration offers.
				day:5,						For full documentation about these settings please see the full version of the code.
				month:9,
				year:2006
			},
			yearsRange:[1978,2020],
			limitToToday:false,
			cellColorScheme:"beige",
			dateFormat:"%m-%d-%Y",
			imgPath:"img/",
			weekStartDay:1*/
		});
	};
</script>
<!-- IF TPL_READONLY_SHOW -->
  
  <h1 class="pagetitle">Панель управления заглушками | <a href="readonly.php?action=log">Лог заглушек</a></h1>
  <p class="nav"><a href="{U_INDEX}">{T_INDEX}</a></p>
  <table class="user_profile bordered w100" cellpadding="2" border="1">
  <tr>
    <th colspan="5" class="thHead">Список заглушек</th>
  </tr>
  <!-- IF READONLY_PRESENT -->
    <tr>
      <td class="catTitle">Пользователь</td>
      <td class="catTitle">Модератор</td>
      <td class="catTitle">Нарушение</td>
      <td class="catTitle">Истекает</td>
      <td class="catTitle">Удалить</td>
    </tr>
  <!-- BEGIN readonly_row -->
      <tr>
          <td class="row1" align="center">{readonly_row.USERID}</td>
          <td class="row1" align="center">{readonly_row.MODID}</td>
          <td class="row1" align="center">{readonly_row.REASON}</td>
          <td class="row1" align="center">{readonly_row.EXPIRE}</td>
          <td class="row1" align="center">{readonly_row.DELETE}</td>
        </tr>
  <!-- END readonly_row -->
  <!-- ELSE / READONLY_PRESENT -->
    <tr>
      <td colspan="5" class="row1" align="center">Активные заглушки выставленные Вами отсутствуют.</th>
    </tr>
  <!-- ENDIF / READONLY_PRESENT -->
</table>
<!-- ENDIF / TPL_READONLY_SHOW --> 




<!-- IF TPL_READONLY_LOG -->
  <h1 class="pagetitle"><a href="readonly.php">Панель управления заглушками</a> | Лог заглушек</h1>
  <p class="nav"><a href="{U_INDEX}">{T_INDEX}</a></p>
  <table class="user_profile bordered w100" cellpadding="2" border="1">
  <tr>
    <th colspan="2" class="thHead">Лог заглушек</th>
  </tr>
  <!-- IF READONLY_LOG_PRESENT -->
        <tr>
        <td class="catTitle">Модератор</td>
        <td class="catTitle">Действие</td>
      </tr>
    <!-- BEGIN readonly_log_row -->
          <tr>
            <td class="row1" align="center">{readonly_log_row.MODID}</td>
            <td class="row1" align="center">{readonly_log_row.ENTRY}</td>
          </tr>
    <!-- END readonly_log_row -->
    <!-- ELSE -->
      <tr>
        <td colspan="2" class="row1" align="center">Записи отсутствуют.</th>
      </tr>
    <!-- ENDIF / READONLY_LOG_PRESENT -->
</table>
<!-- ENDIF / TPL_READONLY_LOG -->







<!-- IF TPL_READONLY_SIMPLE -->
  <table class="bordered search_username">
  <tr>
    <th class="thHead">Заглушка</th>
  </tr>
  <tr>
    <td class="row1 pad_12">
  <table>
  <!-- IF TPL_READONLY_ADD -->
    <!--========================================================================-->
    <form action="readonly.php" method="post">
      <input type="hidden" name="action" value="add">
      <tr><td>Пользователь:</td><td><input type="text" name="username" value="{READONLY_USERNAME}" readonly><input type="hidden" name="userid" value="{READONLY_USERID}"></td></tr>
      <tr><td>Дата снятия заглушки:</td><td><input type="text" name="expire" value="" id="expire" onclick="displayDatePicker('expire');"></td></tr><!--onclick="displayDatePicker('expire');"-->
      <tr><td>Описание нарушения:</td><td><textarea name="descr"></textarea></td></tr>
      <tr><td colspan="2" align="center"><input type="submit" value="Заглушить"></td></tr>
    </form>
  <!--========================================================================-->
  <!-- ENDIF / TPL_READONLY_ADD -->
  <!-- IF TPL_READONLY_NOTICE -->
    <!--========================================================================-->
    <tr align="center">
      <tr><td>{READONLY_NOTICE}</td>
    </tr>
    <!--========================================================================-->
  <!-- ENDIF / TPL_READONLY_NOTICE -->
  </table>
  </table>
<!-- ENDIF / TPL_READONLY_SIMPLE --> 
