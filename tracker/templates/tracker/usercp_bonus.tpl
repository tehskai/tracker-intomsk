
<h1>{PAGE_TITLE}</h1>

<form method="post" name="bonus_list" action="{S_MODE_ACTION}">
{S_USER_HIDDEN}
<table width="100%">
<tr>
	<td width="100%" class="nav vBottom"><a href="{U_INDEX}">{T_INDEX}</a> :: <a href="{U_USER_PROFILE}">{L_RETURN_PROFILE}</a> :: <a href="profile.php?mode=bonus">Управление бонусами</a> :: <a href="profile.php?mode=bonus&do=invites">Cписок приглашений</a></td>
</tr>
</table>
<!-- IF BONUS_PRESENT -->
<table class="forumline">
<col class="row2">
<col class="row1">
<col class="row1">
<thead>
<tr>
	<th>{L_BONUS_TYPE}</th>
	<th width="100">{L_SEED_POINTS_SHORT}</th>
	<th>{L_SELECT}</th>
</tr>
</thead>

<tbody>
<!-- BEGIN bonusrow -->
<tr>
	<td class="tLeft">{bonusrow.BONUS_DESC}<br><small><i>{bonusrow.BONUS_TIP}</i></small></td>
	<td class="tCenter"><span class="gen">{bonusrow.POINTS}</span></td>
	<td class="tCenter"><input type="radio" name="bonus_id" value="{bonusrow.BONUS_ID}" /></td>
</tr>
<!-- END bonusrow -->
</tbody>
<tr>
	<td class="catBottom tCenter" colspan="3">
		<input type="submit" name="submit" value="{L_SEED_POINTS_EXCHANGE}" class="lite" />
 </td>
</tr>
</table>
</form>
<!-- ENDIF -->
<br>
<!-- IF INVITE_PRESENT -->
<div class="category">
<div class="cat_title" style=" border:1px">
<center>
{L_YOUR_INVITES}
</center>
</div>
<table class="user_profile bordered w100" cellpadding="0" border=1>
<!-- IF INVITES_PRESENT -->
<tr>
	<th class="thHead">{L_INVITE_GET_DATE}</th>
	<th class="thHead">{L_INVITE_CODE}</th>
	<th class="thHead">{L_INVITE_ACTIVE}</th>
	<th class="thHead">{L_INVITE_INVITED_USER}</th>
	<th class="thHead">{L_INVITE_ACTIVATION_DATE}</th>
</tr>
<!-- BEGIN invite_row -->
	<tr">
	  <td class="row1" align="center">{invite_row.GENERATION_DATE}</td>
	  <td class="row2" align="center">{invite_row.INVITE_CODE}</td>
	  <td class="row1" align="center">{invite_row.ACTIVE}</td>
	  <td class="row1" align="center">{invite_row.NEW_USER}</td>
	  <td class="row1" align="center">{invite_row.ACTIVATION_DATE}</td>
	</tr>
<!-- END invite_row -->
<!-- ELSE -->
<tr>
	<td colspan="5" class="row1" align="center">{L_INVITE_NOT_GETED}</th>
</tr>
<!-- ENDIF -->
</table>
</div>
<!-- ENDIF -->

<!--bottom_info-->
<div class="bottom_info">

	<div class="spacer_4"></div>

	<div id="timezone">
		<p>{LAST_VISIT_DATE}</p>
		<p>{CURRENT_TIME}</p>
		<p>{S_TIMEZONE}</p>
	</div>
	<div class="clear"></div>

</div><!--/bottom_info-->
