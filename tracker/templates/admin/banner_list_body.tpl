 <link rel="stylesheet" href="{STYLESHEET}?v={$bb_cfg['css_ver']}" type="text/css">
<h1>{L_BANNER_TITLE}</h1>

<p>{L_BANNER_TEXT}</p>

<form method="post" action="{S_BANNER_ACTION}"><table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
		<th class="thCornerL">{L_BANNER_COMMENT}</th>
		<th class="thTop">{L_BANNER_ACTIVATED}</th>
		<th class="thTop">{L_TIME_TYPE}</th>
		<th class="thTop">{L_BANNER_CLICKS}</th>
		<th class="thTop">{L_BANNER_VIEW}</th>
		<th class="thTop">{L_BANNER_SPOT}</th>
		<th class="thTop">{L_EDIT}</th>
		<th class="thCornerR">{L_DELETE}</th>
	</tr>
	<!-- BEGIN banners -->
	<tr>
		<td class="{banners.ROW_CLASS}" align="center">{banners.BANNER_COMMENT}</td>
		<td class="{banners.ROW_CLASS}" align="center">{banners.BANNER_IS_ACTIVE}</td>
		<td class="{banners.ROW_CLASS}" align="center"><b>{banners.L_RULE_TYPE}</b>{banners.RULE_BEGIN}{banners.RULE_END}</td>
		<td class="{banners.ROW_CLASS}" align="center"><a href="{banners.U_BANNER_CLICKS}">{banners.BANNER_CLICKS}</a></td>
		<td class="{banners.ROW_CLASS}" align="center">{banners.BANNER_VIEW}</td>
		<td class="{banners.ROW_CLASS}" align="center">{banners.BANNER_SPOT}</td>
		<td class="{banners.ROW_CLASS}" align="center"><a href="{banners.U_BANNER_EDIT}">{L_EDIT}</a></td>
		<td class="{banners.ROW_CLASS}" align="center"><a href="{banners.U_BANNER_DELETE}">{L_DELETE}</a></td>
	</tr>
	<!-- END banners -->			
	<tr>
		<td class="cat" align="center" colspan="8">
		<input type="submit" class="mainoption" name="add" value="{L_ADD_BANNER}" /></td>
	</tr>
</table>


<br>
<h1>{L_BANNER_STATS_TITLE}</h1>
<p>{L_BANNER_STATS_EXPLANE}</p>

<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
		<th class="thCornerL" colspan="2">Add-ons</th>
	</tr>
	<tr>
		<td class="row1"><a href="{U_BANNER_STATS}">{L_BANNER_STATS}</a></td>
		<td class="row2"><a href="{U_BANNER_CLEAR}">{L_BANNER_CLEAR}</a></td>
	</tr>			
</table>



</form>








