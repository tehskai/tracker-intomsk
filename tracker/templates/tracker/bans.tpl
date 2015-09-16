<table cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
<tr>
  <th colspan="8">Список забаненых пользователей</th>
</tr>
<tr class="row7" align="center">
	 <td>ID</td><td>Пользователь</td><td>IP</td><td>Забанен</td><td>Бан до</td><td>Причина</td><td>Выдал бан</td><!-- IF AUTH_MOD --><td>Снять</td><!-- ENDIF / AUTH_MOD -->

</tr>
		<!-- BEGIN userban -->
			<tr class="row6" align="center">
				 <td>{userban.BAN_ID}</td>
				 <td>{userban.BAN_USERNAME}</td>
				 <td>{userban.BAN_IP}</td>
			 	 <td>{userban.BAN_TIME}</td>
			 	 <td>{userban.BAN_TIME_EXP}</td>
			 	 <td>{userban.BAN_THEME}</td>
			 	 <td>{userban.BAN_MODERNAME}</td>
			  	 <!-- IF AUTH_MOD -->
 			 	 <td>{userban.BAN_DELETE}</td>
 			 	 <!-- ENDIF / AUTH_MOD -->
			</tr>
		<!-- END userban -->

<th colspan="8"><br></th>

</table>