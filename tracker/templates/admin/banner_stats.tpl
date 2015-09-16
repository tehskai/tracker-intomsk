 <link rel="stylesheet" href="{STYLESHEET}?v={$bb_cfg['css_ver']}" type="text/css">
  <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr> 
	  <td class="row1" align="center"><span class="gen">{L_BANNER_STATS}</span></td>
	</tr>
  </table>


  <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr> 
	  <td align="center">&nbsp;{BANNER_EXAMPLE}&nbsp;</td>
	</tr>
  </table>


  <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr> 
	  <th height="25">ID</th>
	  <th>Banner Name</th>
	  <th>User</th>
	  <th>IP</th>
	  <th>Data</th>
	  <th>&nbsp;</th>
	</tr>
	<!-- BEGIN bannerrow -->
	<tr> 
	  <td class="{bannerrow.ROW_CLASS}" align="center"><span class="gen">&nbsp;{bannerrow.BANNER_ID}&nbsp;</span></td>
	  <td class="{bannerrow.ROW_CLASS}" align="center"><span class="gen">{bannerrow.BANNER_NAME}</span></td>
	  <td class="{bannerrow.ROW_CLASS}" align="center"><span class="gen">{bannerrow.BANNER_USERNAME}</span></td>
	  <td class="{bannerrow.ROW_CLASS}" align="center"><span class="gensmall">{bannerrow.BANNER_IP}</span></td>
	  <td class="{bannerrow.ROW_CLASS}" align="center"><span class="gensmall">{bannerrow.DATE}</span></td>
	  <td class="{memberrow.ROW_CLASS}" align="center"><span class="gensmall"></span></td>
	</tr>
	<!-- END bannerrow -->
	<tr> 
	  <td class="cat" colspan="8" height="28" align="center">&nbsp;<b>{INFO_MESSAGE}</b>&nbsp;</td>
	</tr>
  </table>

<table width="100%" cellspacing="2" cellpadding="2" border="0">
  <tr> 
	<td><span class="nav">{PAGE_NUMBER}</span></td>
	<td align="right"><span class="nav">{PAGINATION}</span></td>
  </tr>
</table>

<br />
